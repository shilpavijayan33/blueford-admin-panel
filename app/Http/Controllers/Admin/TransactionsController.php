<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminController;
use App\User;
use App\Transactions;
use App\UserDetails;
use App\RedeemList;
use App\AppDetails;
use Input;
use DataTables;



class TransactionsController extends AdminController
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function transactionsIndex()
    {
        $title='Transactions';
        return view('admin.transactions.index',compact('title'));
    }

    public function transactionsData(){
         $data = Transactions::join('users','users.id','=','transaction.user_id')
         ->join('products','products.id','=','transaction.product_id')
         ->select('users.user_type','users.name','products.name as product','transaction.serial_number','transaction.amount','transaction.created_at as date','transaction.created_at as time','transaction.status as realstatus')
         ->orderBy('transaction.created_at','desc')
         ->get();            

        return Datatables::of($data)
                        ->addIndexColumn()
                         ->editColumn('amount', ' @if ($amount == 0 && $realstatus == "returned") Returned @else {!!$amount!!}@endif')
                         ->editColumn('user_type', function ($data){
                             return ucfirst($data->user_type);
                         })
                         ->editColumn('date', function ($data){
                             return date('d-m-y', strtotime($data->date));
                         })  
                         ->editColumn('time', function ($data){
                             return date('h:i A', strtotime($data->time));
                         })                         
                         ->make(true);
    }

    public function transactionDetails($id){
        $title="Transaction Details";
        $user=User::find($id);
        $user_details=UserDetails::where('user_id',$id)->first();
        $then_date = date('Y-m-d H:i:s',strtotime("-30 day"));
        $total_points=Transactions::where('user_id',$id)->sum('amount');
        $redeemable_points=Transactions::where('user_id',$id)
                                           ->where('status','!=','returned')
                                           ->where('status','!=','redeemed')
                                           ->where('created_at','<',$then_date)
                                           ->sum('amount');
        $waiting_points=Transactions::where('user_id',$id)
                                           ->where('status','!=','returned')
                                           ->where('status','!=','redeemed')
                                           ->where('created_at','>=',$then_date)
                                           ->sum('amount');
        $total_transactions=Transactions::join('products','products.id','=','transaction.product_id')
                                        ->where('user_id',$id)
                                        ->select('transaction.*','products.name as product')
                                        ->get();
        $redeem=RedeemList::where('user_id',$id)
                          ->where('status','released')
                          ->get();
        $redeemed=RedeemList::where('user_id',$id)
                          ->where('status','released')
                          ->sum('requested_amount');
        $app=AppDetails::find(1);
        return view('admin.transactions.transactiondetails',compact('title','user','user_details','total_points','redeemable_points','redeem','app','total_transactions','then_date','redeemed','waiting_points'));

    }


    public function transPrint($id){

       $title="Transaction Details";
        $user=User::find($id);
        $user_details=UserDetails::where('user_id',$id)->first();
        $then_date = date('Y-m-d H:i:s',strtotime("-30 day"));
        $total_points=Transactions::where('user_id',$id)->sum('amount');
        $redeemable_points=Transactions::where('user_id',$id)
                                           ->where('status','!=','returned')
                                           ->where('status','!=','redeemed')
                                           ->where('created_at','<',$then_date)
                                           ->sum('amount');
        $waiting_points=Transactions::where('user_id',$id)
                                           ->where('status','!=','returned')
                                           ->where('status','!=','redeemed')
                                           ->where('created_at','>=',$then_date)
                                           ->sum('amount');
        $total_transactions=Transactions::join('products','products.id','=','transaction.product_id')
                                        ->where('user_id',$id)
                                        ->select('transaction.*','products.name as product')
                                        ->get();
        $redeem=RedeemList::where('user_id',$id)
                          ->where('status','released')
                          ->get();
        $redeemed=RedeemList::where('user_id',$id)
                          ->where('status','released')
                          ->sum('requested_amount');
        $app=AppDetails::find(1);
        return view('admin.transactions.transactionprint',compact('title','user','user_details','total_points','redeemable_points','redeem','app','total_transactions','then_date','redeemed','waiting_points'));

    }

  
}
