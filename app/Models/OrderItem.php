<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'total',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        // Calculer le total avant la sauvegarde
        static::saving(function ($orderItem) {
            $orderItem->total = $orderItem->price * $orderItem->quantity;
        });

        // Recalculer le total de la commande après sauvegarde/suppression
        static::saved(function ($orderItem) {
            if ($orderItem->order) {
                $orderItem->order->calculateTotal();
            }
        });

        static::deleted(function ($orderItem) {
            if ($orderItem->order) {
                $orderItem->order->calculateTotal();
            }
        });
    }

    // Relations
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
