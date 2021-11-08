<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\UserDetails;
use App\PendingPlumbers;
use Hash;
use App\UserWallet;

class RegisterController extends Controller
{
   

    public function registerPlumber(Request $request){
    	
    	$request->validate([
    		'email' => 'required|email|unique:users',
            'mobile' => 'required|numeric|unique:users|digits:10',
            'name' =>'required',
            'address' =>'required',
            'district' =>'required',
            'state' =>'required',
    	]);  

    	$data=PendingPlumbers::create([
    		'name' =>$request->name,
    		'email' =>$request->email,
    		'mobile' =>$request->mobile,
    		'request_data' =>json_encode($request->all())
    	]);

    	 return response()->json(
            [
                'status' => 201,
                'message' =>'User Details saved, Waiting for Admin approval',
                'data' =>$data,
            ]);

    }
}