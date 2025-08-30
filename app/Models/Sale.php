<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_number',
        'date',
        'total',
        'vat',
        'total_wvat',
        'discount',
        'amt_tendered',
        'amount_change',
        'table_no',
        'customer',
        'status'
    ];
}
