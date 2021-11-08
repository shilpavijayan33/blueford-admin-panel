<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Transactions extends Model
{
     use SoftDeletes;

    protected $table = 'transaction';

    protected $fillable = ['trans_id', 'serial_number','user_id','product_id','amount','status','user_type','slab_range'];
}
