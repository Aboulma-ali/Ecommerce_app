<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AddressResource\Pages;
use App\Filament\Resources\AddressResource\RelationManagers;
use App\Models\Address;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Support\Enums\FontWeight;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AddressResource extends Resource
{
    protected static ?string $model = Address::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    protected static ?string $navigationLabel = 'Adresses';

    protected static ?string $modelLabel = 'Adresse';

    protected static ?string $pluralModelLabel = 'Adresses';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informations d\'adresse')
                    ->description('Gérez les détails de l\'adresse')
                    ->icon('heroicon-o-map-pin')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('user_id')
                                    ->label('Utilisateur')
                                    ->relationship('user', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->prefixIcon('heroicon-o-user')
                                    ->placeholder('Sélectionnez un utilisateur'),

                                Forms\Components\TextInput::make('name')
                                    ->label('Nom de l\'adresse')
                                    ->maxLength(255)
                                    ->prefixIcon('heroicon-o-tag')
                                    ->placeholder('Ex: Domicile, Bureau...'),
                            ]),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('address_line1')
                                    ->label('Adresse ligne 1')
                                    ->required()
                                    ->prefixIcon('heroicon-o-home')
                                    ->placeholder('Numéro et nom de rue'),

                                Forms\Components\TextInput::make('address_line2')
                                    ->label('Adresse ligne 2')
                                    ->prefixIcon('heroicon-o-building-office')
                                    ->placeholder('Appartement, étage...'),
                            ]),

                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('city')
                                    ->label('Ville')
                                    ->required()
                                    ->prefixIcon('heroicon-o-building-office-2')
                                    ->placeholder('Ville'),

                                Forms\Components\TextInput::make('postal_code')
                                    ->label('Code postal')
                                    ->required()
                                    ->prefixIcon('heroicon-o-map')
                                    ->placeholder('Code postal'),

                                Forms\Components\TextInput::make('phone')
                                    ->label('Téléphone')
                                    ->prefixIcon('heroicon-o-phone')
                                    ->placeholder('Numéro de téléphone'),
                            ]),

                        Forms\Components\Select::make('type')
                            ->label('Type d\'adresse')
                            ->options([
                                'livraison' => '📦 Adresse de livraison',
                                'facturation' => '💳 Adresse de facturation',
                            ])
                            ->required()
                            ->prefixIcon('heroicon-o-clipboard-document-list')
                            ->placeholder('Sélectionnez le type'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Utilisateur')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::SemiBold)
                    ->icon('heroicon-o-user')
                    ->tooltip('Propriétaire de l\'adresse'),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nom')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-tag')
                    ->placeholder('Sans nom')
                    ->tooltip('Nom de l\'adresse'),

                Tables\Columns\TextColumn::make('address_line1')
                    ->label('Adresse')
                    ->searchable()
                    ->limit(30)
                    ->icon('heroicon-o-home')
                    ->tooltip(fn ($record) => $record->address_line1),

                Tables\Columns\TextColumn::make('city')
                    ->label('Ville')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-building-office-2')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('postal_code')
                    ->label('Code postal')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-map')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Téléphone')
                    ->searchable()
                    ->icon('heroicon-o-phone')
                    ->placeholder('Non renseigné')
                    ->copyable()
                    ->tooltip('Cliquez pour copier'),

                Tables\Columns\TextColumn::make('type')
                    ->label('Type')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'livraison' => 'success',
                        'facturation' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'livraison' => '📦 Livraison',
                        'facturation' => '💳 Facturation',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime('d/m/Y à H:i')
                    ->sortable()
                    ->toggleable()
                    ->tooltip('Date de création'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Modifié le')
                    ->dateTime('d/m/Y à H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->tooltip('Dernière modification'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Type d\'adresse')
                    ->options([
                        'livraison' => '📦 Livraison',
                        'facturation' => '💳 Facturation',
                    ])
                    ->native(false)
                    ->placeholder('Tous les types'),

                Tables\Filters\SelectFilter::make('city')
                    ->label('Ville')
                    ->options(fn (): array => Address::distinct()->pluck('city', 'city')->toArray())
                    ->searchable()
                    ->native(false)
                    ->placeholder('Toutes les villes'),

                Tables\Filters\SelectFilter::make('created_at')
                    ->label('Période de création')
                    ->options([
                        'today' => '📅 Aujourd\'hui',
                        'yesterday' => '📅 Hier',
                        'this_week' => '📅 Cette semaine',
                        'this_month' => '📅 Ce mois-ci',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['value'] === 'today',
                                fn (Builder $query) => $query->whereDate('created_at', today())
                            )
                            ->when(
                                $data['value'] === 'yesterday',
                                fn (Builder $query) => $query->whereDate('created_at', today()->subDay())
                            )
                            ->when(
                                $data['value'] === 'this_week',
                                fn (Builder $query) => $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                            )
                            ->when(
                                $data['value'] === 'this_month',
                                fn (Builder $query) => $query->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
                            );
                    })
                    ->native(false)
                    ->placeholder('Toutes les périodes'),
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->label('Voir')
                        ->color('info'),
                    Tables\Actions\EditAction::make()
                        ->label('Modifier')
                        ->color('warning'),
                    Tables\Actions\DeleteAction::make()
                        ->label('Supprimer')
                        ->requiresConfirmation()
                        ->color('danger'),
                ])
                    ->tooltip('Actions')
                    ->icon('heroicon-o-ellipsis-vertical'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->paginated([10, 25, 50, 100])
            ->emptyStateHeading('Aucune adresse trouvée')
            ->emptyStateDescription('Commencez par créer une nouvelle adresse.')
            ->emptyStateIcon('heroicon-o-map-pin')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Créer une adresse')
                    ->icon('heroicon-o-plus'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAddresses::route('/'),
            'create' => Pages\CreateAddress::route('/create'),
            'edit' => Pages\EditAddress::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::count() > 20 ? 'success' : 'primary';
    }
}
