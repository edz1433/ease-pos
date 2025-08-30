<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'sales_id',
        'product_id',
        'capital',
        'price',
        'price_type',
        'quantity',
        'subtotal',
    ];
}
