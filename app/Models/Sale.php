<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
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
        'payment_method',
        'status',
        'user_id'
    ];

    public function salesorder()
    {
        return $this->hasMany(SalesOrder::class, 'sales_id');
    }
}
