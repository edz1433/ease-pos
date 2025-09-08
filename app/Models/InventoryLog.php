<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'quantity',
        'adjustment_type', // 'sale', 'return', 'restock', 'adjustment', 'cancellation_restock'
        'price_type',      // 'retail', 'wholesale', etc.
        'previous_quantity',
        'new_quantity',
        'reason',
        'sale_id',
        'user_id',
        'price'
    ];

}
