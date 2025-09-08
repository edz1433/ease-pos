<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'barcode',
        'w_barcode',
        'product_name',
        'model',
        'more_details',
        'product_type',
        'category',
        'packaging',
        'w_capital',
        'w_price',
        'wqty',
        'w_unit',
        'r_capital',
        'r_price',
        'r_unit',
        'rqty',
        'image',
        'vatable',
        'r_stock_alert',
        'w_stock_alert',
    ];

}
