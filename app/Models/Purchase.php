<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_no',
        'supplier_id',
        'po_number', 
        'payment_mode',
        'total_amount',
        'purchase_date',
        'payment_status',
        'status',
    ];
}
