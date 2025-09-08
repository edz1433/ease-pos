<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashBankTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_date',
        'category',
        'transaction_type',
        'amount',
        'description',
        'user_id',
    ];

    protected $casts = [
        'transaction_date' => 'datetime',
        'amount' => 'decimal:2',
    ];

}
