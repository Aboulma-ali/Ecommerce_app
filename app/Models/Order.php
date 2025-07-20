<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'shipping_address_id',
        'total',
        'status',
        'payment_status',
        'payment_method',
        'ordered_at',
    ];

    protected $casts = [
        'ordered_at' => 'datetime',
        'total' => 'decimal:2',
    ];

    // Méthode pour calculer et sauvegarder le total
    public function calculateTotal()
    {
        $total = $this->orderItems()->sum('total');

        // Utilisation de updateQuietly pour éviter de déclencher les events
        $this->updateQuietly(['total' => $total]);

        return $total;
    }

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shippingAddress()
    {
        return $this->belongsTo(Address::class, 'shipping_address_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
