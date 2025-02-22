<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CryptoPrice extends Model
{
    protected $fillable = [
        'pair',
        'exchange',
        'average_price',
        'price_change',
        'change_direction',
    ];
}
