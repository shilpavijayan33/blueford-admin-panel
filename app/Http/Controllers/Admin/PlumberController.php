<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminController;
use App\User;
use App\PendingPlumbers;
use App\UserDetails;
use App\Slab;
use App\UserWallet;
use Input;
use Hash;



class PlumberController extends AdminController
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function plumberIndex(Request $request)
    {
        $title='Plumber Management';
        if($request->method() == 'POST'){
             if($request->district == null && $request->state == null && $request->mobile == null)
               return redirect('/admin/plumber-management')->with('error', 'Invalid Data');
        }
        $plumbers=User::join('user_details','users.id','=','user_details.user_id')
                      ->where('users.user_type','plumber')
                       ->where(function ($query) use ($request) {
                             if ($request->method() == 'POST') {
                                if ($request->district != null) {
                                  $query->where('user_details.district', 'LIKE', '%'.$request->district.'%');
                                }
                                if ($request->state != null) {
                                  $query->where('user_details.state', 'LIKE', '%'.$request->state.'%');
                                }
                                if ($request->mobile != null) {
                                  $query->where('users.mobile', 'LIKE', '%'.$request->mobile.'%');
                                }
                             }
                          })
                      ->select('users.id as useid','users.name','users.email','users.username','users.mobile','users.status','user_details.*')
                       ->orderByRaw('FIELD(users.status, "active", "suspend")')
                       ->orderBy('name', 'asc')
                      ->paginate(10)->onEachSide(1);
        $pendingplumbers=PendingPlumbers::where('status','pending')->paginate(10)->onEachSide(1);
        return view('admin.plumbers.plumberindex',compact('title','plumbers','pendingplumbers'));
    }

    public function suspendPlumber($id){

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
       return redirect('/admin/plumber-management')->with('success', 'Plumber '.$msg.' Successfully');
    }

    public function acceptPlumber($id){

      $pending_plumber=PendingPlumbers::find($id);

      $exist_email=User::where('email',$pending_plumber->email)->value('id');
      $exist_phone=User::where('mobile',$pending_plumber->mobile)->value('id');

      if($exist_email <> null)
        return redirect('/admin/plumber-management')->with('error', 'Sorry,Email is already in use, Try another one');

      if($exist_phone <> null)
        return redirect('/admin/plumber-management')->with('error', 'Sorry,Phone number is already in use');


      $user=User::create([
        'name' =>$pending_plumber->name,
        'email' =>$pending_plumber->email,
        'password' =>Hash::make('Blueford@123'),
        'username' =>$pending_plumber->email,
        'mobile' =>$pending_plumber->mobile,
        'user_type' =>'plumber',
      ]);

      $user_data=json_decode($pending_plumber->request_data);

      UserDetails::create([
          'user_id' =>$user->id,
          'address' =>$user_data->address,
          'district' =>$user_data->district,
          'state' =>$user_data->state,
      ]); 

      UserWallet::create([
            'user_id' =>$user->id,
            'balance' => 0,   
      ]); 


      PendingPlumbers::where('id',$id)->update(['status' =>'approved']);

      return redirect('/admin/plumber-management')->with('success', 'Plumber Approved Successfully!!');

    }

    public function rejectPlumber($id){

      PendingPlumbers::where('id',$id)->update(['status' =>'rejected']);

      return redirect('/admin/plumber-management')->with('success', 'Plumber rejected Successfully!!');


    }

   
}
