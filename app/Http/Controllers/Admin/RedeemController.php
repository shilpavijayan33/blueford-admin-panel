<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminController;
use App\Products;
use App\User;
use App\RedeemList;
use App\Transactions;
use App\UserWallet;
use DataTables;
use Input;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;



class RedeemController extends AdminController
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function redeemIndex(Request $request)
    {

        // dd($request->all());
        $title='Redeem List';
        $then_date = date('Y-m-d H:i:s',strtotime("-30 day"));
        if($request->method() == 'POST'){
             if($request->user == null && $request->mobile == null && $request->date == null)
               return redirect('/admin/redeem-management')->with('error', 'Invalid Data');

          }
        $datas = RedeemList::join('users','users.id','=','redeem_list.user_id')
                           ->where(function ($query) use ($request) {
                             if ($request->method() == 'POST') {

                                if ($request->user != null) {
                                  $query->where('users.user_type',$request->user);
                                }

                                if ($request->mobile != null) {
                                  $query->where('users.mobile', 'LIKE', '%'.$request->mobile.'%');
                                }

                                if ($request->date != null) {
                                  $query->where('redeem_list.created_at', '>', date('Y-m-d 00:00:00', strtotime($request->date)))
                                       ->where('redeem_list.created_at', '<', date('Y-m-d 23:59:59', strtotime($request->date)));
                                }
                             }
                          })
                          ->select('users.name','users.user_type','redeem_list.*')
                          ->orderBy('redeem_list.created_at','desc')
                          // ->orderBy('redeem_list.status','desc')
                          ->get();
          // $result=[];               
        // foreach ($datas as $key => $data) {
        //     $temp['id']=$data->id;
        //     $temp['name']=$data->name;
        //     $temp['user_type']=$data->user_type;
        //     $temp['requested_amount']=$data->requested_amount;
        //     $temp['redeemable_amount']=Transactions::where('user_id',$data->user_id)
        //                         ->where('status','<>','returned')
        //                         ->where('status','<>','redeemed')
        //                         ->where('created_at','<',$then_date)
        //                         ->sum('amount');
        //     $temp['status']=$data->status;
        //     $temp['date']=$data->created_at;
        //     $result[] = $temp;
        // }

        // $data = array();
        // $currentPage = LengthAwarePaginator::resolveCurrentPage();
        // $collection = new Collection($result);
        // $per_page = 1;
        // $currentPageResults = $collection->slice(($currentPage-1) * $per_page, $per_page)->values();
        // $data['results'] = new LengthAwarePaginator($currentPageResults, count($collection), $per_page);
        // $data['results']->setPath($request->url());
      
        return view('admin.redeem.redeemindex',compact('title','datas'));
    }   

    public function acceptRedeem(Request $request){
        $redeem=RedeemList::find($request->redeem_id);
        $trans=Transactions::where('id',$redeem->transaction_ids)->first();
        Transactions::where('id',$redeem->transaction_ids)->update(['status' => 'redeemed']);
        $redeem->status='released';
        $redeem->save();

        $then_date = date('Y-m-d H:i:s',strtotime("-30 day"));
        $am=Transactions::where('user_id',$trans->user_id)
                              ->where('status','!=','returned')
                              ->where('status','!=','redeemed')
                              ->where('created_at','<',$then_date)
                              ->sum('amount');
        return response()->json([
          'status' => 200,
          'message' => 'success',
          'amount' =>$am,
          'user' =>$trans->user_id,
          'data' => []
        ]);

    }

    public function rejectRedeem(Request $request){
        $redeem=RedeemList::find($request->redeem_id);
        Transactions::where('id',$redeem->transaction_ids)->update(['status' => 'pending']);
        UserWallet::where('user_id',$redeem->user_id)->increment('balance',$redeem->requested_amount);
        $redeem->status='rejected';
        $redeem->save();
        
        return response()->json([
          'status' => 200,
          'message' => 'success',
          'data' => []
        ]);
         
    }

    public function acceptAllredem(Request $request){

      foreach($request->redeem_ids as $redeem_id){
        $redeem=RedeemList::find($redeem_id);     
        Transactions::where('id',$redeem->transaction_ids)->update(['status' => 'redeemed']);
        $redeem->status='released';
        $redeem->save();
      }     
      return response()->json([
        'status' => 200,
        'message' => 'success',
        'data' => []
      ]);

  }


    public function rejectAllRedeem(Request $request){
      foreach($request->redeem_ids as $redeem_id){
        $redeem=RedeemList::find($redeem_id);
        Transactions::where('id',$redeem->transaction_ids)->update(['status' => 'pending']);
        UserWallet::where('user_id',$redeem->user_id)->increment('balance',$redeem->requested_amount);
        $redeem->status='rejected';
        $redeem->save();
      }
    
      
      return response()->json([
        'status' => 200,
        'message' => 'success',
        'data' => []
      ]);
       
  }
      

   
}
