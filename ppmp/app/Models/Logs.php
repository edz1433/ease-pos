<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logs extends Model
{
	use HasFactory;
	
	protected $table = 'logs';

	protected $fillable = array('user_id','fname', 'lname','level','status');

	protected $dates = ['deleted_at'];

	public $timestamps = false;

}