<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Slab extends Model
{
   
    protected $table = 'slab';

    protected $fillable = ['product_id', 'start','end','value','type'];


    public static function slabRange($scan,$product){

       $slab_value= self::where('product_id',$product)->where('start','<=',$scan)->where('type','dealer')->max('id');
       // dd($slab_value);
       if($slab_value <> null)     
         return self::find($slab_value)->value;
       else
         return 0;

       // return $slab_value;


    }



    public static function slabCount($scan,$product){
        $slab_value= self::where('product_id',$product)->where('start','<=',$scan)->where('type','dealer')->max('id');
        // dd($slab_value);

        $all_deals=self::where('product_id',$product)->where('type','dealer')->get();
        $count=0;
        foreach ($all_deals as $key => $eachdeals) {
            if($eachdeals->id == $slab_value ){
                $count++;

                return $count;
            }
            else
            {
                $count++;
            }
        }
      
    }
}
