<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;
    protected $fillable = [
        'branch_id',
        'supplier_name',
        'contact_person',
        'contact_number',
        'email',
        'address',
        'amount_payable',
    ];
}
