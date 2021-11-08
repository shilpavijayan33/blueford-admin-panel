<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class RedeemList extends Model
{
   

    protected $table = 'redeem_list';

    protected $fillable = ['user_id', 'requested_amount','status','transaction_ids','product_id','serial_number'];
}
