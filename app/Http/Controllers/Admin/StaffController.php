<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminController;
use App\User;
use Input;
use Hash;
use App\UserDetails;
use App\UserWallet;



class StaffController extends AdminController
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function staffIndex()
    {
        $title='Staff Management';
        $staffs=User::join('user_details','users.id','=','user_details.user_id')
                   ->where('users.admin','=',1)
                   ->where('users.id','>',1)
                    ->select('users.id as useid','users.name','users.email','users.username','users.mobile','users.status','user_details.*')
                     ->orderByRaw('FIELD(status, "active", "suspend")')
                     ->orderBy('name', 'asc')
                    ->paginate(10)->onEachSide(1);
                   
        return view('admin.staffs.staffindex',compact('title','staffs'));
    }

    public function staffAdd(Request $request){   

        $request->validate([
          // 'username' => 'required|alpha_num|unique:users',
          'email' => 'required|string|unique:users',
          'mobile' => 'required|numeric|unique:users|digits:10',
          'password' => 'required|string|min:8|confirmed',


        ]);         

        $user=User::create([
                'name' =>$request->name,
                'email' =>$request->email,
                'password' =>Hash::make($request->password),
                'username' =>$request->name,
                'mobile' =>$request->mobile,
                'sponsor' =>1,
                'user_type' =>'admin',
                'admin' =>1,
             ]);

        UserDetails::create([
            'user_id' =>$user->id,
            'address' =>$request->address,
        ]);

        UserWallet::create([
            'user_id' =>$user->id,
            'balance' => 0,   
            
        ]); 

       
       return redirect('/admin/staff-management')->with('success', 'Staff Added Successfully');
    }

    public function staffEdit(Request $request){ 

        // dd($request->all());
        $request->validate([
          // 'username' => 'required|string|unique:users,username,'.$request->staff_id.',id',
          'email' => 'required|string|unique:users,email,'.$request->staff_id.',id',
          'mobile' => 'required|string|unique:users,mobile,'.$request->staff_id.',id',


        ]);       

        $user=User::find($request->staff_id);        
        $user->name=$request->name;
        $user->username=$request->name;
        $user->email=$request->email;
        $user->mobile=$request->mobile;        
        $user->save();
        $user_det=UserDetails::where('user_id',$request->staff_id)->first();
        $user_det->address=$request->address;
        $user_det->save();

        return redirect('/admin/staff-management')->with('success', 'Staff edited successfully');

    }

    public function staffSuspend($id){
        User::where('id',$id)->update(['status' => 'suspend']);
        return redirect('/admin/staff-management')->with('success', 'Staff suspended successfully');


    }
}
