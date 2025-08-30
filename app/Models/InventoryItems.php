<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryItems extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_id',
        'product_id',
        'r_qty',        // Retail quantity
        'w_qty',        // Wholesale quantity
        'r_capital',    // Retail capital
        'w_capital',    // Wholesale capital
        'r_subtotal',   // Retail subtotal
        'w_subtotal',   // Wholesale subtotal
        'price_type',   // 'retail' or 'wholesale'
        'status',
    ];
}
