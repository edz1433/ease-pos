<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'barcode',
        'product_name',
        'category',
        'packaging',
        'retail_capital',
        'retail_price',
        'retail_unit',
        'whole_capital',
        'whole_price',
        'wholesale_unit',
    ];
}
