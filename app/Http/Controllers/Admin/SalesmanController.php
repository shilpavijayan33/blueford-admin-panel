<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminController;
use App\Products;
use App\User;
use Input;



class SalesmanController extends AdminController
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function salesmanIndex(Request $request)
    {
        $title='Salesman Management';
        if($request->method() == 'POST'){
             if($request->dealer == null && $request->mobile == null)
               return redirect('/admin/salesman-management')->with('error', 'Invalid Data');

          }

        $salesmans=User::join('user_details','users.id','=','user_details.user_id')
                      ->where('user_type','salesman')
                       ->where(function ($query) use ($request) {
                             if ($request->method() == 'POST') {
                                if ($request->dealer != null) {
                                  $dealer=User::where('name','LIKE', '%'.$request->dealer.'%')->value('id');
                                  $query->where('users.sponsor', $dealer);
                                }
                                if ($request->mobile != null) {
                                  $query->where('users.mobile', 'LIKE', '%'.$request->mobile.'%');
                                }
                             }
                          })
                      ->select('users.id as useid','users.name','users.email','users.username','users.mobile','users.status','user_details.*','users.sponsor')
                       ->orderByRaw('FIELD(users.status, "active", "suspend")')
                       ->orderBy('name', 'asc')
                      ->paginate(10)->onEachSide(1);
          $list_sponsor=User::where('status','active')->where('user_type','dealer')->pluck('name','id');
                      // dd($salesmans);
        return view('admin.salesman.salesmanindex',compact('title','salesmans','list_sponsor'));
    }

    public function suspendSalesman($id){

      $status=User::where('id',$id)->value('status');
      if($status == 'suspend'){
        $new_status='active';
        $msg='activated';
        $sponsor=User::where('id',$id)->value('sponsor');
        $sponsor_detail=User::find($sponsor);
        if($sponsor_detail->status == 'suspend')
        return redirect('/admin/salesman-management')->with('error', 'You cannot activate this salesman since the dealer '.$sponsor_detail->name.' is not active. Please change the dealer or activate the dealer');

      }        
      else{
        $new_status='suspend';
        $msg='suspended';
      } 
      User::where('id',$id)->update(['status' => $new_status]);
      return redirect('/admin/salesman-management')->with('success', 'Salesman '.$msg.' Successfully');
    }

   
}
