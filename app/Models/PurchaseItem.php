<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'purchase_id',
        'product_id',	
        'price',
        'selling_price',	
        'price_type',	
        'quantity',	
        'subtotal',
    ];
}
