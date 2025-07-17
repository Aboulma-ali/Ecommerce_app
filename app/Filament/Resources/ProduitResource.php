<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProduitResource\Pages;
use App\Filament\Resources\ProduitResource\RelationManagers;
use App\Models\Product;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

use Filament\Support\Enums\FontWeight;
use Filament\Tables\Enums\FiltersLayout;

class ProduitResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?string $navigationLabel = 'Produits';

    protected static ?string $modelLabel = 'Produit';

    protected static ?string $pluralModelLabel = 'Produits';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informations générales')
                    ->description('Renseignez les informations de base du produit')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nom du produit')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Ex: MacBook Pro 14"')
                                    ->columnSpan(1),

                                Forms\Components\Select::make('category_id')
                                    ->label('Catégorie')
                                    ->relationship('category', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->placeholder('Sélectionnez une catégorie')
                                    ->columnSpan(1),
                            ]),

                        Forms\Components\Textarea::make('description')
                            ->label('Description')
                            ->required()
                            ->maxLength(1000)
                            ->rows(4)
                            ->placeholder('Décrivez votre produit en détail...')
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->columns(2),

                Forms\Components\Section::make('Prix et stock')
                    ->description('Définissez le prix et gérez le stock')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('price')
                                    ->label('Prix (XOF)')
                                    ->numeric()
                                    ->required()
                                    ->prefix('XOF')
                                    ->placeholder('0.00')
                                    ->step(0.01)
                                    ->minValue(0)
                                    ->columnSpan(1),

                                Forms\Components\TextInput::make('stock')
                                    ->label('Quantité en stock')
                                    ->numeric()
                                    ->required()
                                    ->placeholder('0')
                                    ->minValue(0)
                                    ->suffix('unités')
                                    ->columnSpan(1),
                            ]),
                    ])
                    ->collapsible()
                    ->columns(2),

                Forms\Components\Section::make('Média')
                    ->description('Ajoutez des images pour votre produit')
                    ->schema([
                        Forms\Components\FileUpload::make('image')
                            ->label('Image principale')
                            ->image()
                            ->directory('produits')
                            ->maxSize(2048)
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '16:9',
                                '4:3',
                                '1:1',
                            ])
                            ->helperText('Cette image sera utilisée comme image principale du produit')
                            ->columnSpanFull(),

                        Forms\Components\Repeater::make('images')
                            ->label('Images supplémentaires')
                            ->relationship('images')
                            ->schema([
                                Forms\Components\FileUpload::make('image_path')
                                    ->label('Image')
                                    ->image()
                                    ->directory('produits/gallery')
                                    ->maxSize(2048)
                                    ->imageEditor()
                                    ->imageEditorAspectRatios([
                                        '16:9',
                                        '4:3',
                                        '1:1',
                                    ])
                                    ->required()
                                    ->columnSpan(2),

                                Forms\Components\TextInput::make('alt')
                                    ->label('Texte alternatif')
                                    ->placeholder('Description de l\'image')
                                    ->maxLength(255)
                                    ->columnSpan(1),
                            ])
                            ->columns(3)
                            ->addActionLabel('Ajouter une image')
                            ->collapsible()
                            ->collapsed()
                            ->itemLabel(fn (array $state): ?string => $state['alt'] ?? 'Image sans description')
                            ->maxItems(10)
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Image')
                    ->circular()
                    ->size(60)
                    ->defaultImageUrl(url('/images/placeholder-product.png')),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nom')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Medium)
                    ->wrap(),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Catégorie')
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('price')
                    ->label('Prix')
                    ->money('XOF')
                    ->sortable()
                    ->weight(FontWeight::SemiBold)
                    ->color('success'),

                Tables\Columns\TextColumn::make('stock')
                    ->label('Stock')
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match (true) {
                        $state == 0 => 'danger',
                        $state < 10 => 'warning',
                        default => 'success',
                    })
                    ->formatStateUsing(fn (string $state): string => $state . ' unités'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Modifié le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Catégorie')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->multiple(),

                Filter::make('price_range')
                    ->label('Gamme de prix')
                    ->form([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('price_from')
                                    ->label('Prix minimum')
                                    ->numeric()
                                    ->placeholder('0')
                                    ->prefix('XOF'),
                                Forms\Components\TextInput::make('price_to')
                                    ->label('Prix maximum')
                                    ->numeric()
                                    ->placeholder('1000000')
                                    ->prefix('XOF'),
                            ]),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['price_from'],
                                fn (Builder $query, $price): Builder => $query->where('price', '>=', $price),
                            )
                            ->when(
                                $data['price_to'],
                                fn (Builder $query, $price): Builder => $query->where('price', '<=', $price),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['price_from'] ?? null) {
                            $indicators['price_from'] = 'Prix min: ' . number_format($data['price_from']) . ' XOF';
                        }
                        if ($data['price_to'] ?? null) {
                            $indicators['price_to'] = 'Prix max: ' . number_format($data['price_to']) . ' XOF';
                        }
                        return $indicators;
                    }),

                Filter::make('stock_status')
                    ->label('État du stock')
                    ->form([
                        Forms\Components\Select::make('stock_status')
                            ->label('Statut')
                            ->options([
                                'available' => 'En stock',
                                'low' => 'Stock faible (< 10)',
                                'out_of_stock' => 'Rupture de stock',
                            ])
                            ->placeholder('Tous les statuts'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['stock_status'],
                            function (Builder $query, $status): Builder {
                                return match ($status) {
                                    'available' => $query->where('stock', '>', 0),
                                    'low' => $query->where('stock', '>', 0)->where('stock', '<', 10),
                                    'out_of_stock' => $query->where('stock', 0),
                                };
                            }
                        );
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if ($data['stock_status'] ?? null) {
                            return match ($data['stock_status']) {
                                'available' => 'Produits en stock',
                                'low' => 'Stock faible',
                                'out_of_stock' => 'Rupture de stock',
                            };
                        }
                        return null;
                    }),


            ])
            ->filtersLayout(FiltersLayout::AboveContentCollapsible)
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->label('Voir')
                        ->icon('heroicon-m-eye'),
                    Tables\Actions\EditAction::make()
                        ->label('Modifier')
                        ->icon('heroicon-m-pencil-square'),
                    Tables\Actions\DeleteAction::make()
                        ->label('Supprimer')
                        ->icon('heroicon-m-trash'),
                ])
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->size('sm')
                    ->color('gray')
                    ->button()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Supprimer sélection'),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Créer un produit'),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->paginated([10, 25, 50, 100])
            ->poll('60s')
            ->deferLoading()
            ->persistFiltersInSession()
            ->persistSortInSession()
            ->persistSearchInSession();
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
            'index' => Pages\ListProduits::route('/'),
            'create' => Pages\CreateProduit::route('/create'),
            'edit' => Pages\EditProduit::route('/{record}/edit'),
        ];
    }



    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::count() > 100 ? 'warning' : 'primary';
    }
}
