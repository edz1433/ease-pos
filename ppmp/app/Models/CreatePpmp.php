<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreatePpmp extends Model
{
    use HasFactory;
    protected $fillable = ['year', 'start', 'end', 'status'];
}
