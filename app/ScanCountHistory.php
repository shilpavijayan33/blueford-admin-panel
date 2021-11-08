<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ScanCountHistory extends Model
{
    protected $table = 'scan_count_history';

    protected $fillable = ['user_id', 'product_id','scan_count','slab'];
}
