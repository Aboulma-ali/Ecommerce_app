<?php

namespace App\Filament\Resources;

use App\Events\OrderEvent;
use App\Models\Order;
use App\Models\Product;
use App\Models\Address;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Support\Colors\Color;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationLabel = 'Commandes';
    protected static ?string $modelLabel = 'Commande';
    protected static ?string $pluralModelLabel = 'Commandes';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationGroup = 'Ventes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informations Client')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('user_id')
                                    ->label('Client')
                                    ->relationship('user', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->createOptionForm([
                                        TextInput::make('name')
                                            ->label('Nom')
                                            ->required()
                                            ->maxLength(255),
                                        TextInput::make('email')
                                            ->label('Email')
                                            ->email()
                                            ->required()
                                            ->maxLength(255),
                                    ])
                                    ->columnSpan(1),

                                Select::make('shipping_address_id')
                                    ->label('Adresse de livraison')
                                    ->relationship('shippingAddress', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->getOptionLabelFromRecordUsing(function (Address $record) {
                                        return "{$record->name} - {$record->address_line1}, {$record->city}";
                                    })
                                    ->createOptionForm([
                                        TextInput::make('name')
                                            ->label('Nom de l\'adresse')
                                            ->required()
                                            ->maxLength(255),
                                        TextInput::make('address_line1')
                                            ->label('Adresse ligne 1')
                                            ->required()
                                            ->maxLength(255),
                                        TextInput::make('address_line2')
                                            ->label('Adresse ligne 2 (optionnel)')
                                            ->maxLength(255),
                                        TextInput::make('city')
                                            ->label('Ville')
                                            ->required()
                                            ->maxLength(255),
                                        TextInput::make('postal_code')
                                            ->label('Code postal')
                                            ->required()
                                            ->maxLength(10),
                                        TextInput::make('phone')
                                            ->label('Téléphone')
                                            ->tel()
                                            ->maxLength(20),
                                        Select::make('type')
                                            ->label('Type d\'adresse')
                                            ->options([
                                                'livraison' => 'Livraison',
                                                'facturation' => 'Facturation',
                                            ])
                                            ->default('livraison')
                                            ->nullable(),
                                    ])
                                    ->columnSpan(1),
                            ])
                    ])
                    ->collapsible()
                    ->icon('heroicon-m-user'),

                Section::make('Détails de la Commande')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Select::make('status')
                                    ->label('Statut')
                                    ->options([
                                        'en_attente' => 'En attente',
                                        'expédiée' => 'Expédiée',
                                        'livrée' => 'Livrée',
                                        'annulée' => 'Annulée',
                                    ])
                                    ->default('en_attente')
                                    ->required()
                                    ->native(false),

                                Select::make('payment_status')
                                    ->label('Statut de paiement')
                                    ->options([
                                        'non_payé' => 'Non payé',
                                        'payé' => 'Payé',
                                    ])
                                    ->default('non_payé')
                                    ->required()
                                    ->native(false),

                                Select::make('payment_method')
                                    ->label('Méthode de paiement')
                                    ->options([
                                        'en_ligne' => 'En ligne',
                                        'à_la_livraison' => 'À la livraison',
                                    ])
                                    ->required()
                                    ->native(false),
                            ]),

                        DateTimePicker::make('ordered_at')
                            ->label('Date de commande')
                            ->default(now())
                            ->required()
                            ->native(false),
                    ])
                    ->collapsible()
                    ->icon('heroicon-m-clipboard-document-list'),

                Section::make('Produits')
                    ->schema([
                        Repeater::make('orderItems')
                            ->relationship()
                            ->schema([
                                Grid::make(4)
                                    ->schema([
                                        Select::make('product_id')
                                            ->label('Produit')
                                            ->relationship('product', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->required()
                                            ->live()
                                            ->afterStateUpdated(function ($state, Set $set) {
                                                if ($state) {
                                                    $product = Product::find($state);
                                                    if ($product) {
                                                        $set('price', $product->price);
                                                        $set('total', $product->price);
                                                    }
                                                }
                                            })
                                            ->columnSpan(2),

                                        TextInput::make('quantity')
                                            ->label('Quantité')
                                            ->numeric()
                                            ->default(1)
                                            ->minValue(1)
                                            ->required()
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                                $price = $get('price') ?? 0;
                                                $quantity = $state ?? 1;
                                                $set('total', $price * $quantity);
                                            })
                                            ->columnSpan(1),

                                        TextInput::make('price')
                                            ->label('Prix unitaire')
                                            ->numeric()
                                            ->prefix('€')
                                            ->required()
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                                $quantity = $get('quantity') ?? 1;
                                                $price = $state ?? 0;
                                                $set('total', $price * $quantity);
                                            })
                                            ->columnSpan(1),

                                        TextInput::make('total')
                                            ->label('Total')
                                            ->numeric()
                                            ->prefix('€')
                                            ->required()
                                            ->readOnly()
                                            ->columnSpan(1),
                                    ])
                            ])
                            ->columns(1)
                            ->addActionLabel('Ajouter un produit')
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['product_id'] ? Product::find($state['product_id'])?->name : 'Nouveau produit')
                            ->defaultItems(1)
                            ->minItems(1)
                            ->live(),

                        Placeholder::make('total_calculation')
                            ->label('Total de la commande')
                            ->content(function (Get $get) {
                                $items = $get('orderItems') ?? [];
                                $total = 0;

                                foreach ($items as $item) {
                                    if (isset($item['total']) && is_numeric($item['total'])) {
                                        $total += floatval($item['total']);
                                    }
                                }

                                return '€ ' . number_format($total, 2, ',', ' ');
                            })
                            ->extraAttributes(['class' => 'text-lg font-bold text-primary-600']),
                    ])
                    ->collapsible()
                    ->icon('heroicon-m-shopping-bag'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('user.name')
                    ->label('Client')
                    ->sortable()
                    ->searchable()
                    ->weight(FontWeight::Medium)
                    ->description(function (Order $record): string {
                        if ($record->user) {
                            $count = Order::where('user_id', $record->user_id)->count();
                            return $count . ' commande(s)';
                        }
                        return '';
                    }),

                TextColumn::make('total')
                    ->label('Total')
                    ->money('EUR')
                    ->sortable()
                    ->summarize(Sum::make()->money('EUR'))
                    ->weight(FontWeight::Bold),

                BadgeColumn::make('status')
                    ->label('Statut')
                    ->colors([
                        'warning' => 'en_attente',
                        'info' => 'expédiée',
                        'success' => 'livrée',
                        'danger' => 'annulée',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'en_attente' => 'En attente',
                        'expédiée' => 'Expédiée',
                        'livrée' => 'Livrée',
                        'annulée' => 'Annulée',
                    }),

                BadgeColumn::make('payment_status')
                    ->label('Paiement')
                    ->colors([
                        'danger' => 'non_payé',
                        'success' => 'payé',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'non_payé' => 'Non payé',
                        'payé' => 'Payé',
                    }),

                TextColumn::make('payment_method')
                    ->label('Méthode')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'en_ligne' => 'En ligne',
                        'à_la_livraison' => 'À la livraison',
                    })
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'en_ligne' => 'info',
                        'à_la_livraison' => 'warning',
                    }),

                TextColumn::make('ordered_at')
                    ->label('Date')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('status')
                    ->label('Statut de la commande')
                    ->placeholder('Tous les statuts')
                    ->trueLabel('Commandes livrées')
                    ->falseLabel('Commandes non livrées')
                    ->queries(
                        true: fn (Builder $query) => $query->where('status', 'livrée'),
                        false: fn (Builder $query) => $query->whereIn('status', ['en_attente', 'expédiée', 'annulée']),
                    ),

                Tables\Filters\TernaryFilter::make('payment_status')
                    ->label('Statut de paiement')
                    ->placeholder('Tous les paiements')
                    ->trueLabel('Payées')
                    ->falseLabel('Non payées')
                    ->queries(
                        true: fn (Builder $query) => $query->where('payment_status', 'payé'),
                        false: fn (Builder $query) => $query->where('payment_status', 'non_payé'),
                    ),

                SelectFilter::make('payment_method')
                    ->label('Méthode de paiement')
                    ->options([
                        'en_ligne' => 'En ligne',
                        'à_la_livraison' => 'À la livraison',
                    ])
                    ->multiple(),

                Filter::make('date_range')
                    ->label('Période')
                    ->form([
                        DateTimePicker::make('created_from')
                            ->label('Du')
                            ->placeholder('Date de début'),
                        DateTimePicker::make('created_until')
                            ->label('Au')
                            ->placeholder('Date de fin'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['created_from'] ?? null) {
                            $indicators['created_from'] = 'Du: ' . \Carbon\Carbon::parse($data['created_from'])->format('d/m/Y');
                        }
                        if ($data['created_until'] ?? null) {
                            $indicators['created_until'] = 'Au: ' . \Carbon\Carbon::parse($data['created_until'])->format('d/m/Y');
                        }
                        return $indicators;
                    }),
            ], layout: FiltersLayout::AboveContentCollapsible)
            ->actions([
                // Actions principales toujours visibles
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),

                // Groupe d'actions secondaires
                Tables\Actions\ActionGroup::make([
                    Action::make('download_invoice')
                        ->label('Télécharger la facture')
                        ->icon('heroicon-m-document-arrow-down')
                        ->color('primary')
                        ->url(fn (Order $record) => route('orders.invoice', $record->id))
                        ->openUrlInNewTab(),


                    Action::make('mark_as_paid')
                        ->label('Marquer comme payé')
                        ->icon('heroicon-m-check-circle')
                        ->color('success')
                        ->visible(fn (Order $record): bool => $record->payment_status === 'non_payé')
                        ->action(function (Order $record) {
                            $record->update(['payment_status' => 'payé']);
                        })
                        ->requiresConfirmation(),

                    Action::make('mark_as_shipped')
                        ->label('Marquer comme expédiée')
                        ->icon('heroicon-m-truck')
                        ->color('info')
                        ->visible(fn (Order $record): bool => $record->status === 'en_attente')
                        ->action(function (Order $record) {
                            $record->update(['status' => 'expédiée']);
                        })
                        ->requiresConfirmation(),

                    Action::make('mark_as_delivered')
                        ->label('Marquer comme livrée')
                        ->icon('heroicon-m-check-badge')
                        ->color('success')
                        ->visible(fn (Order $record): bool => $record->status === 'expédiée')
                        ->action(function (Order $record) {
                            $record->update(['status' => 'livrée']);
                        })
                        ->requiresConfirmation(),

                    Action::make('cancel_order')
                        ->label('Annuler la commande')
                        ->icon('heroicon-m-x-circle')
                        ->color('danger')
                        ->visible(fn (Order $record): bool => !in_array($record->status, ['livrée', 'annulée']))
                        ->action(function (Order $record) {
                            $record->update(['status' => 'annulée']);
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Annuler la commande')
                        ->modalDescription('Êtes-vous sûr de vouloir annuler cette commande ?')
                        ->modalSubmitActionLabel('Oui, annuler'),
                ])
                    ->label('Actions')
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->color('gray')
                    ->size('sm'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('mark_as_paid')
                        ->label('Marquer comme payées')
                        ->icon('heroicon-m-check-circle')
                        ->color('success')
                        ->action(function ($records) {
                            $records->each(function ($record) {
                                $record->update(['payment_status' => 'payé']);
                            });
                        })
                        ->requiresConfirmation(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->poll('30s');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Informations Client')
                    ->schema([
                        Infolists\Components\TextEntry::make('user.name')
                            ->label('Client')
                            ->weight(FontWeight::Bold),
                        Infolists\Components\TextEntry::make('user.email')
                            ->label('Email')
                            ->copyable(),
                        Infolists\Components\TextEntry::make('shippingAddress.name')
                            ->label('Nom de l\'adresse')
                            ->weight(FontWeight::Medium),
                        Infolists\Components\TextEntry::make('shippingAddress.address_line1')
                            ->label('Adresse de livraison')
                            ->formatStateUsing(function (Order $record) {
                                $address = $record->shippingAddress;
                                if (!$address) return 'Non définie';

                                $fullAddress = $address->address_line1;
                                if ($address->address_line2) {
                                    $fullAddress .= ', ' . $address->address_line2;
                                }
                                $fullAddress .= ', ' . $address->city . ' ' . $address->postal_code;

                                return $fullAddress;
                            }),
                        Infolists\Components\TextEntry::make('shippingAddress.phone')
                            ->label('Téléphone')
                            ->copyable(),
                        Infolists\Components\TextEntry::make('shippingAddress.type')
                            ->label('Type d\'adresse')
                            ->badge()
                            ->formatStateUsing(fn (?string $state): string => match ($state) {
                                'livraison' => 'Livraison',
                                'facturation' => 'Facturation',
                                null => 'Non défini',
                                default => $state,
                            }),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Détails de la Commande')
                    ->schema([
                        Infolists\Components\TextEntry::make('status')
                            ->label('Statut')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'en_attente' => 'warning',
                                'expédiée' => 'info',
                                'livrée' => 'success',
                                'annulée' => 'danger',
                            }),
                        Infolists\Components\TextEntry::make('payment_status')
                            ->label('Statut de paiement')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'non_payé' => 'danger',
                                'payé' => 'success',
                            }),
                        Infolists\Components\TextEntry::make('payment_method')
                            ->label('Méthode de paiement')
                            ->badge(),
                        Infolists\Components\TextEntry::make('ordered_at')
                            ->label('Date de commande')
                            ->dateTime('d/m/Y H:i'),
                        Infolists\Components\TextEntry::make('total')
                            ->label('Total')
                            ->money('EUR')
                            ->weight(FontWeight::Bold),
                    ])
                    ->columns(3),

                Infolists\Components\Section::make('Produits Commandés')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('orderItems')
                            ->schema([
                                Infolists\Components\TextEntry::make('product.name')
                                    ->label('Produit')
                                    ->weight(FontWeight::Medium),
                                Infolists\Components\TextEntry::make('quantity')
                                    ->label('Quantité')
                                    ->badge(),
                                Infolists\Components\TextEntry::make('price')
                                    ->label('Prix unitaire')
                                    ->money('EUR'),
                                Infolists\Components\TextEntry::make('total')
                                    ->label('Total')
                                    ->money('EUR')
                                    ->weight(FontWeight::Bold),
                            ])
                            ->columns(4),
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

}
