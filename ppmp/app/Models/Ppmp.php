<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ppmp extends Model
{
    use HasFactory;

    protected $table = 'ppmp';

	protected $fillable = array('user_id', 'cppmt_id', 'item_id', 'item_code','jan','feb','mar','q1','q1_amount','apr','may','jun','q2','q2_amount','jul','aug','sep','q3','q3_amount','oct','nov','decem','q4','q4_amount','total_qty','cataloque','total_amot', 'price', 'tier');

	protected $dates = ['deleted_at'];

	public $timestamps = false;

	public function type()
    {
        return $this->belongsTo('Type');
    }
}
