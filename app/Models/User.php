<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    protected $primaryKey = 'id';

    public $timestamps = true; // keep true since table has timestamps

    protected $fillable = [
        'fname',
        'lname',
        'mname',
        'image',
        'ceiling_amount',
        'position',
        'office',
        'email',
        'username',
        'password',
        'isAdmin',
        'status',
        'remember_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'ceiling_amount' => 'array', // cast JSON/text to array automatically (Laravel will try to json_decode)
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        // Add isAdmin as integer or enum as string if you want
    ];
}
