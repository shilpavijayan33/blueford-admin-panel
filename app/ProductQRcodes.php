<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class ProductQRcodes extends Model
{
   
    protected $table = 'product_qrcodes';

    protected $fillable = ['product_id', 'qrcode_data','status'];
}
