<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;



class Products extends Model
{
    use SoftDeletes;
    
    protected $table = 'products';

    protected $fillable = ['name', 'model','colour','size','description','images','price','qrcode_count'];

    public function qrcodes()
    {
        return $this->hasMany('App\ProductQRcodes','product_id','id')->orderBy('id','desc');;
    }
}
//