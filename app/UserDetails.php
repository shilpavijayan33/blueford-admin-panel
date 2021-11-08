<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class UserDetails extends Model
{
    use SoftDeletes;

    protected $table = 'user_details';

    protected $fillable = ['user_id', 'address','district','state','shop_name','shop_address','shop_district','shop_state'];
}
