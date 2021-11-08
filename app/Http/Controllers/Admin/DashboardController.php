<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminController;
use App\RedeemList;
use App\Products;
use App\ProductQRcodes;



class DashboardController extends AdminController
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function dashboardAdmin()
    {
        $title='views';
        return view('admin.dashboard',compact('title'));
    }

    public function addDummy(){

        for ($i=1; $i <100 ; $i++) { 
            RedeemList::create([
          'user_id' =>2,
            'requested_amount' =>100,
          'status' => 'pending',   
          'transaction_ids' =>1,   
          'product_id' => 1,     
          'serial_number' =>121424324,  
        ]);
        }

        dd("ss");

    }


    public function saveCurrentQr(){
      $products=Products::pluck('id');
      foreach($products as $product){
        $qr=ProductQRcodes::where('product_id',$product)->count();
        Products::find($product)->update(['qrcode_count' => $qr]);

      }
      dd("done");
    }
}
