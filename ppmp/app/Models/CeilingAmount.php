<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CeilingAmount extends Model
{
    use HasFactory;

    protected $table = 'ceiling_amounts';

    protected $fillable = [
        'cppmt_id',
        'user_id',
        'amount',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];
}
