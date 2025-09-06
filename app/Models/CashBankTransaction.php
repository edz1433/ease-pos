<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashBankTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_date',
        'account_type',
        'account_name',
        'transaction_type',
        'amount',
        'description',
    ];

    protected $casts = [
        'transaction_date' => 'datetime',
        'amount' => 'decimal:2',
    ];

}
