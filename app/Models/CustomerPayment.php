<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'customer_id',
        'amount',
        'payment_date',
        'payment_method',
        'reference',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
