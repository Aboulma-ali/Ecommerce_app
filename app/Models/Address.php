<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'address_line1',
        'address_line2',
        'city',
        'postal_code',
        'phone',
        'type',
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
