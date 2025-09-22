<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashCount extends Model
{
    use HasFactory;

    protected $fillable = [
        'qty_0_25',
        'qty_0_50',
        'qty_1',
        'qty_5',
        'qty_10',
        'qty_20',
        'qty_50',
        'qty_100',
        'qty_500',
        'qty_1000',
        'gcash',
        'bank',
        'total_inflow',
        'total_outflow',
        'total_purchases',
        'total_sales_today',
        'variance',
    ];
}
