<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
	use HasFactory;
	
	protected $table = 'item';

	protected $fillable = array('item_code','item_spec', 'amount', 'category','measure','partno');

	protected $dates = ['deleted_at'];

	public $timestamps = false;

	public static function search($keyword)
	 {
	 	return static::where('item_code', 'LIKE', '%'.$keyword.'%')->paginate(10);
	 }
}