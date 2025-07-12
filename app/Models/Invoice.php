<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'pdf_path',
        'client_name',
        'client_address',
        'client_email',
        'products',
        'total',
        'total_ttc',
        'order_date',
        'payment_method',
    ];

    // Relations
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
