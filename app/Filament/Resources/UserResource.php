<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Support\Enums\FontWeight;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Utilisateurs';

    protected static ?string $modelLabel = 'Utilisateur';

    protected static ?string $pluralModelLabel = 'Utilisateurs';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informations Utilisateur')
                    ->description('Gérez les informations de base de l\'utilisateur')
                    ->icon('heroicon-o-user')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nom complet')
                                    ->required()
                                    ->maxLength(255)
                                    ->prefixIcon('heroicon-o-user')
                                    ->placeholder('Nom et prénom'),

                                Forms\Components\TextInput::make('email')
                                    ->label('Adresse email')
                                    ->email()
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->prefixIcon('heroicon-o-envelope')
                                    ->placeholder('exemple@email.com'),
                            ]),

                        Forms\Components\TextInput::make('password')
                            ->label('Mot de passe')
                            ->password()
                            ->required(fn ($livewire) => $livewire instanceof Pages\CreateRecord)
                            ->dehydrated(fn ($state) => filled($state))
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->prefixIcon('heroicon-o-lock-closed')
                            ->placeholder('Minimum 8 caractères')
                            ->minLength(8)
                            ->revealable()
                            ->helperText('Laissez vide pour conserver le mot de passe actuel (modification uniquement)'),

                        Forms\Components\DateTimePicker::make('email_verified_at')
                            ->label('Email vérifié le')
                            ->nullable()
                            ->prefixIcon('heroicon-o-check-badge')
                            ->helperText('Laissez vide si l\'email n\'est pas vérifié'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nom')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::SemiBold)
                    ->icon('heroicon-o-user')
                    ->copyable()
                    ->tooltip('Cliquez pour copier'),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-envelope')
                    ->copyable()
                    ->tooltip('Cliquez pour copier'),

                Tables\Columns\IconColumn::make('email_verified_at')
                    ->label('Email vérifié')
                    ->boolean()
                    ->sortable()
                    ->tooltip(fn ($record) => $record->email_verified_at
                        ? 'Vérifié le ' . $record->email_verified_at->format('d/m/Y à H:i')
                        : 'Email non vérifié'
                    ),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime('d/m/Y à H:i')
                    ->sortable()
                    ->toggleable()
                    ->tooltip('Date de création du compte'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Modifié le')
                    ->dateTime('d/m/Y à H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->tooltip('Dernière modification'),
            ])
            ->filters([
                Tables\Filters\Filter::make('name')
                    ->form([
                        Forms\Components\TextInput::make('name')
                            ->label('Nom')
                            ->placeholder('Rechercher par nom...')
                            ->prefixIcon('heroicon-o-user'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['name'],
                                fn (Builder $query, $name): Builder => $query->where('name', 'like', "%{$name}%"),
                            );
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if (! $data['name']) {
                            return null;
                        }
                        return 'Nom: ' . $data['name'];
                    }),

                Tables\Filters\Filter::make('email')
                    ->form([
                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->placeholder('Rechercher par email...')
                            ->prefixIcon('heroicon-o-envelope'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['email'],
                                fn (Builder $query, $email): Builder => $query->where('email', 'like', "%{$email}%"),
                            );
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if (! $data['email']) {
                            return null;
                        }
                        return 'Email: ' . $data['email'];
                    }),

                Tables\Filters\SelectFilter::make('email_verified_at')
                    ->label('Statut de vérification')
                    ->options([
                        'verified' => 'Email vérifié',
                        'unverified' => 'Email non vérifié',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['value'] === 'verified',
                                fn (Builder $query, $date): Builder => $query->whereNotNull('email_verified_at'),
                            )
                            ->when(
                                $data['value'] === 'unverified',
                                fn (Builder $query, $date): Builder => $query->whereNull('email_verified_at'),
                            );
                    })
                    ->indicator('Statut')
                    ->multiple(false)
                    ->searchable(false)
                    ->native(false)
                    ->placeholder('Tous les utilisateurs'),
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->color('info'),
                    Tables\Actions\EditAction::make()
                        ->color('warning'),
                    Tables\Actions\Action::make('verify_email')
                        ->label('Vérifier l\'email')
                        ->icon('heroicon-o-check-badge')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Vérifier l\'email')
                        ->modalDescription('Êtes-vous sûr de vouloir marquer cet email comme vérifié ?')
                        ->action(fn (User $record) => $record->update(['email_verified_at' => now()]))
                        ->visible(fn (User $record) => $record->email_verified_at === null),
                    Tables\Actions\DeleteAction::make()
                        ->color('danger'),
                ])
                    ->tooltip('Actions')
                    ->icon('heroicon-o-ellipsis-vertical'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('verify_emails')
                        ->label('Vérifier les emails')
                        ->icon('heroicon-o-check-badge')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Vérifier les emails sélectionnés')
                        ->modalDescription('Êtes-vous sûr de vouloir marquer tous les emails sélectionnés comme vérifiés ?')
                        ->action(fn ($records) => $records->each(fn ($record) => $record->update(['email_verified_at' => now()]))),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->paginated([10, 25, 50, 100])
            ->poll('30s')
            ->emptyStateHeading('Aucun utilisateur trouvé')
            ->emptyStateDescription('Commencez par créer un nouvel utilisateur.')
            ->emptyStateIcon('heroicon-o-users')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Créer un utilisateur')
                    ->icon('heroicon-o-plus'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Vous pouvez ajouter ici les RelationManagers pour addresses et orders
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::count() > 10 ? 'success' : 'primary';
    }
}
