<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
	use HasFactory;
	
	protected $table = 'category';

	protected $fillable = array('type_id','title', 'partno');

	protected $dates = ['deleted_at'];

	public $timestamps = false;

	public function type()
    {
        return $this->belongsTo('Type');
    }
}