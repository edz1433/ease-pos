<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
	use HasFactory;
	
	protected $table = 'type';

	protected $dates = ['deleted_at'];

	public $timestamps = false;

	public function itemss()
    {
        return $this->hasMany('Category', 'type_id');
    }
}