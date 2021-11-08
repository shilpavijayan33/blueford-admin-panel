<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
use App\UserDetails;


class LoginController extends Controller
{
    public function login(Request $request){
       
    	$request->validate([
    		'mobile' => 'required|numeric|exists:users',
    	]);   	

    	
        $user = User::where('mobile', $request->mobile)->first();
        if($user->status == 'suspend')
          return response()->json(['status' => 401,'message' => 'Oopzz..Sorry!! This user is suspended by admin','data'=> []]);

        if($user->admin == 1)
          return response()->json(['status' => 401,'message' => 'Unauthorized','data'=> []]);
            

        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        $token->expires_at = Carbon::now()->addWeeks(1);
        $data = $user;
        $user_details=UserDetails::where('user_id',$user->id)->first();
        $data['user_details'] = $user_details;
        $data['token'] = $tokenResult->accessToken;
        $data['token_type'] = 'Bearer';
        $data['expires_at'] = Carbon::parse(
            $tokenResult->token->expires_at
        )->toDateTimeString();

        return response()->json(
            [
                'status' => 200,
                'message' =>'login success',
                'data' => $data,
            ]);

    	

    }

    public function checkPhoneNumber(Request $request){
        $request->validate([
            'mobile' => 'required|numeric',
        ]);

        $user = User::where('mobile', $request->mobile)->first();
        if($user <> null)
             return response()->json([
                'status' => false,
                'message' => 'User already exists',
                'error_code' => 400,
                'error' => [],
            ]);
        
        else
            return response()->json([
                'status' => 200,
                'message' =>'Mobile Number doesnt exists',
                'exists' => false,
            ]);



    }
}
