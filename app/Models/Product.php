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
        'warranty',
        'rep_duration',
        'image',
        'vatable',
        'is_bundle',
    ];

}
