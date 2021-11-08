<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminController;
use App\User;
use App\UserDetails;
use App\Slab;
use App\UserWallet;
use Input;
use Hash;



class DealerController extends AdminController
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function dealerIndex(Request $request)
    {
      // dd($request->all());
        $title='Dealer Management';
       
          if($request->method() == 'POST'){
             if($request->district == null && $request->state == null)
               return redirect('/admin/dealer-management')->with('error', 'Invalid Data');

          }
             $dealers=User::join('user_details','users.id','=','user_details.user_id')
                         // ->join('slab','slab.id','=','users.slab')
                          ->where('users.user_type','dealer')
                          ->where(function ($query) use ($request) {
                             if ($request->method() == 'POST') {

                                if ($request->district != null) {
                                  $query->where('user_details.district', 'LIKE', '%'.$request->district.'%');
                                }

                                if ($request->state != null) {
                                  $query->where('user_details.state', 'LIKE', '%'.$request->state.'%');
                                }
                             }
                          })
                        ->select('users.id as useid','users.name','users.email','users.username','users.mobile','users.status','user_details.*')
                        ->orderByRaw('FIELD(users.status, "active", "suspend")')
                         ->orderBy('name', 'asc')
                        ->paginate(10)->onEachSide(1);
        
         
        
        return view('admin.dealers.dealerindex',compact('title','dealers'));
    }

    public function saveDealer(Request $request){

         $request->validate([
          'email' => 'required|unique:users',
          'mobile' => 'required|numeric|unique:users|digits:10',
        ]); 

        $user=User::create([
                'name' =>$request->name,
                'email' =>$request->email,
                'password' =>Hash::make('Blueford@123'),
                'username' =>$request->email,
                'mobile' =>$request->mobile,
                'sponsor' =>1,
                'user_type' =>'dealer',
             ]);

        UserDetails::create([
            'user_id' =>$user->id,
            'address' =>$request->address,
            'district' =>$request->district,
            'state' =>$request->state,
            'shop_name' =>$request->shop_name,
            'shop_address' =>$request->shop_address,
            'shop_district' =>$request->shop_district,
            'shop_state' =>$request->shop_state,
        ]);
       
       UserWallet::create([
            'user_id' =>$user->id,
            'balance' => 0,   
       ]); 
       return redirect('/admin/dealer-management')->with('success', 'Dealer Added Successfully');

    }

    public function suspendDealer($id){
      $status=User::where('id',$id)->value('status');
      if($status == 'suspend'){
        $new_status='active';
        $msg='activated';
      }        
      else{
        $new_status='suspend';
        $msg='suspended';
      }       

      User::where('id',$id)->update(['status' => $new_status]);
      $salesman=User::where('sponsor',$id)->pluck('id');
        foreach ($salesman as $key => $sales) {
           User::where('id',$sales)->update(['status' => $new_status]);          # code...
        }
      return redirect('/admin/dealer-management')->with('success', 'Dealer '.$msg.' successfully');

    }

    public function editDealer(Request $request){
  
       $request->validate([          
           'mobile'=>'required|digits:10|unique:users,mobile,'.$request->dealer.',id',
           'email'=>'required|unique:users,email,'.$request->dealer.',id',

        ]);  

       $dealer=User::find($request->dealer);
       $dealer->name =$request->name;
       $dealer->email =$request->email;
       $dealer->username =$request->email;
       $dealer->mobile=$request->mobile;
       $dealer->save();

       $dealer_details=UserDetails::where('user_id',$request->dealer)->first();
       $dealer_details->address =$request->address;
       $dealer_details->district =$request->district;
       $dealer_details->state =$request->state;
       $dealer_details->shop_name =$request->shop_name;
       $dealer_details->shop_address =$request->shop_address;
       $dealer_details->shop_district =$request->shop_district;
       $dealer_details->shop_state =$request->shop_state;
       $dealer_details->save();
       return redirect('/admin/dealer-management')->with('success', 'Dealer details edited Successfully');

    }


    public function registerSalesman(Request $request){

        $request->validate([
            'email' => 'required|email|unique:users',
            'mobile' => 'required|numeric|unique:users|digits:10',
            'name' =>'required',            
            'address' =>'required',
            'district' =>'required',
            'state' =>'required',
        ]);  

        $user=User::create([
                'name' =>$request->name,
                'email' =>$request->email,
                'password' =>Hash::make('Blueford@123'),
                'username' =>$request->email,
                'mobile' =>$request->mobile,
                'sponsor' =>$request->dealer,
                'user_type' =>'salesman',
             ]);

        UserDetails::create([
            'user_id' =>$user->id,
            'address' =>$request->address,
            'district' =>$request->district,
            'state' =>$request->state,
        ]); 

        UserWallet::create([
            'user_id' =>$user->id,
            'balance' => 0,   
            
        ]); 

       return redirect('/admin/dealer-management')->with('success', 'Salesperson Added Successfully');
      
    }

    public function editSalesman(Request $request){
       $request->validate([          
           'mobile'=>'required|digits:10|unique:users,mobile,'.$request->salesperson.',id',
           'email'=>'required|unique:users,email,'.$request->salesperson.',id',

        ]);  

       $salesperson=User::find($request->salesperson);
       $salesperson->name =$request->name;
       $salesperson->email =$request->email;
       $salesperson->username =$request->email;
       $salesperson->mobile=$request->mobile;
       $salesperson->sponsor=$request->sponsor;

       $salesperson->save();

       $salesperson_details=UserDetails::where('user_id',$request->salesperson)->first();
       $salesperson_details->address =$request->address;
       $salesperson_details->district =$request->district;
       $salesperson_details->state =$request->state;      
       $salesperson_details->save();
       return redirect('/admin/salesman-management')->with('success', 'Sales Person details edited Successfully');
    }


    public function validateEmail($email){

      $user_email = User::where('email', $email)->value('id');
      // dd($user_email);
      if(!$user_email)
        $data= 'available';
      else
        $data='na';

        return response()->json(
            [
                'status' => 200,
                'message' =>'success',
                'data' => $data,
            ]);
    }

    public function validateMobile($mobile){

      $user_email = User::where('mobile', $mobile)->value('id');
      // dd($user_email);
      if(!$user_email)
        $data= 'available';
      else
        $data='na';

        return response()->json(
            [
                'status' => 200,
                'message' =>'success',
                'data' => $data,
            ]);

    }

    
}
