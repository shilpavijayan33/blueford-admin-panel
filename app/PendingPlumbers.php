<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class PendingPlumbers extends Model
{
    use SoftDeletes;

    protected $table = 'pending_plumbers';

    protected $fillable = ['name', 'mobile','email','request_data','status'];
}
