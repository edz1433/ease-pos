<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BundleItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'bundle_id',
        'product_id',
        'quantity',
    ];
}
