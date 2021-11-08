<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppDetails extends Model
{
     protected $table = 'app_details';

    protected $fillable = ['name', 'mobile','email','address','logo','salesman_slab','plumber_slab'];
}

