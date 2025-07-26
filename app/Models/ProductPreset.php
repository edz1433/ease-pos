<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPreset extends Model
{
    use HasFactory;
    protected $fillable = [
        'barcode',
        'product_name',
    ];
}
