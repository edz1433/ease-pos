<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'product_id',
        'w_capital',
        'w_price',
        'wqty',
        'w_unit',
        'r_capital',
        'r_price',
        'r_unit',
        'rqty',
        'wqty',
        'r_stock_alert',
        'w_stock_alert',
    ];
}
