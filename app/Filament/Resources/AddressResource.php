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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AddressResource extends Resource
{
    protected static ?string $model = Address::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Utilisateur')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required(),

                Forms\Components\TextInput::make('name')
                    ->label('Nom de l\'adresse')
                    ->maxLength(255),

                Forms\Components\TextInput::make('address_line1')
                    ->label('Adresse ligne 1')
                    ->required(),

                Forms\Components\TextInput::make('address_line2')
                    ->label('Adresse ligne 2'),

                Forms\Components\TextInput::make('city')
                    ->label('Ville')
                    ->required(),

                Forms\Components\TextInput::make('postal_code')
                    ->label('Code postal')
                    ->required(),

                Forms\Components\TextInput::make('phone')
                    ->label('Téléphone'),

                Forms\Components\Select::make('type')
                    ->label('Type')
                    ->options([
                        'livraison' => 'Adresse de livraison',
                        'facturation' => 'Adresse de facturation',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('address_line1')
                    ->searchable(),
                Tables\Columns\TextColumn::make('address_line2')
                    ->searchable(),
                Tables\Columns\TextColumn::make('city')
                    ->searchable(),
                Tables\Columns\TextColumn::make('postal_code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Type d\'adresse')
                    ->options([
                        'livraison' => 'Adresse de livraison',
                        'facturation' => 'Adresse de facturation',
                    ])
                    ->query(fn (Builder $query, array $data): Builder => $query->when($data['value'], fn (Builder $query, $value) => $query->where('type', $value))),
                Tables\Filters\Filter::make('city')
                    ->label('Ville')
                    ->form([
                        Forms\Components\TextInput::make('city')
                            ->label('Ville')
                            ->placeholder('Entrez le nom de la ville'),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => $query->when($data['city'], fn (Builder $query, $city) => $query->where('city', 'like', '%' . $city . '%'))),
                Tables\Filters\SelectFilter::make('created_at')
                    ->label('Date de création')
                    ->options([
                        'today' => 'Aujourd\'hui',
                        'yesterday' => 'Hier',
                        'this_week' => 'Cette semaine',
                        'this_month' => 'Ce mois-ci',
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
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('Voir'),
                Tables\Actions\EditAction::make()->label('Modifier'),
                Tables\Actions\DeleteAction::make()->label('Supprimer')->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
}
