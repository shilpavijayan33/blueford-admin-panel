<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\UserDetails;
use App\Transactions;
use App\ProductQRcodes;
use App\Slab;
use App\RedeemList;
use App\UserWallet;
use App\ScanCountHistory;
use App\Products;
use App\AppDetails;
use Carbon\Carbon;
use Auth;
use Hash;

class UserController extends Controller
{
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
                'sponsor' =>Auth::user()->id,
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

         return response()->json(
            [
                'status' => 201,
                'message' =>'registration success',
                'data' => $user,
            ]);

    }

  
    public function saveRewards(Request $request){

      $request->validate([
            'serial_number' =>'required|exists:product_qrcodes,qrcode_data',           
      ]);  
      $user=Auth::user();

      $returned=Transactions::where('serial_number',$request->serial_number)
                                            ->where('user_id',$user->id)
                                            ->where('status','returned')
                                            ->count();
        if($returned > 0){
          return response()->json(['status' => 401,'message' => 'Already Returned','data'=> []]);
        }

        if($user->user_type == 'dealer'){
           $another_dealer_exists=Transactions::where('serial_number',$request->serial_number)
                                              ->where('user_id','<>',$user->id)
                                              ->where('user_type','dealer')
                                              ->count();
             if($another_dealer_exists > 0){
               return response()->json(['status' => 401,'message' => 'Invalid code, Already in use by another dealer','data' => []]);
             }
             else{
               $current_dealer_exists=Transactions::where('serial_number',$request->serial_number)
                                                  ->where('user_id','=',$user->id)
                                                  ->where('user_type','dealer')
                                                  ->count();
               if($current_dealer_exists > 0){
                 return response()->json(['status' => 401,'message' => 'Please try another code','data' => []]);
               }
             }
        }

        if($user->user_type == 'salesman'){     
          $exists_another_sponsor=Transactions::where('serial_number',$request->serial_number)
                  ->where('user_type','dealer')
                  ->where('user_id','<>',Auth::user()->sponsor)
                  ->count();
            if($exists_another_sponsor > 0){
              return response()->json(['status' => 401,'message' => 'Invalid code, Already used by another dealer','data' => []]);
            }
            else{
              $exists_user_sponsor=Transactions::where('serial_number',$request->serial_number)      
                                                ->where('status','<>','returned')
                                               ->where('user_type','dealer')
                                               ->where('user_id','=',Auth::user()->sponsor)
                                               ->count();
                  if($exists_user_sponsor == 0){
                      return response()->json(['status' => 401,'message' => 'Invalid code,Not scanned by dealer or returned by dealer','data' => []]);
                  }
            }

          $exists_another_user=Transactions::where('serial_number',$request->serial_number)                
                  ->where('user_type','salesman')
                  ->where('user_id','<>',Auth::user()->id)
                  ->count();

            if($exists_another_user > 0){
              return response()->json(['status' => 401,'message' => 'Invalid code, Already used by another salesman','data' => []]);
            }else{
               $exists_current_user=Transactions::where('serial_number',$request->serial_number)                
                  ->where('user_type','salesman')
                  ->where('user_id','=',Auth::user()->id)
                  ->count();
                  if($exists_current_user > 0){
                      return response()->json(['status' => 401,'message' => 'Invalid code,Already Scanned','data' =>[]]);
                  }
            }

        }   

        if($user->user_type == 'plumber'){    

          $exists_other_dealer=Transactions::where('serial_number',$request->serial_number)      
                  ->where('status','<>','returned')        
                  ->where('user_type','dealer')                  
                  ->count();  
          if($exists_other_dealer == 0){
              return response()->json(['status' => 401,'message' => 'Invalid code, Not Scanned by dealer or returned by dealer','data' => []]);
          }

          $exists_other_salesman=Transactions::where('serial_number',$request->serial_number) 
                  ->where('status','<>','returned')          
                  ->where('user_type','salesman')                  
                  ->count();  
          if($exists_other_salesman == 0){
              return response()->json(['status' => 401,'message' => 'Invalid code, Not Scanned by salesman or returned by saleman','data' => []]);
          }

          $exists_cur_plumber=Transactions::where('serial_number',$request->serial_number)      

                  ->where('user_type','plumber')
                  ->where('user_id',Auth::user()->id)
                  ->count();  
           if($exists_cur_plumber > 0){
              return response()->json(['status' => 401,'message' => 'Invalid code, Already used','data' => []]);
            }
          $exists_other_plumber=Transactions::where('serial_number',$request->serial_number)                
                  ->where('user_type','plumber')
                  ->where('user_id','<>',Auth::user()->id)
                  ->count();  
           if($exists_other_plumber > 0){
              return response()->json(['status' => 401,'message' => 'Invalid code, Already used by other plumber','data' => []]);
            }
        }

      
          $product_id=ProductQRcodes::where('qrcode_data',$request->serial_number)->value('product_id');
          $slab=Slab::where('product_id',$product_id)->value('id');
          $scan_det=ScanCountHistory::where('user_id',Auth::user()->id)
                                      ->where('product_id',$product_id)
                                      ->first();
                                      // dd($scan_det);
          if($scan_det == null){
            $scan_det=ScanCountHistory::create([
              'user_id' =>Auth::user()->id,
              'product_id' =>$product_id,
            ]);
          }
                                   
          $scan=$scan_det->scan_count+1;
          $slab_range=Slab::slabCount($scan,$product_id);
          // dd($slab_range);
             // $amount=Slab::slabRange($scan,$slab);          

          if($user->user_type == 'dealer')
             $amount=Slab::slabRange($scan,$product_id);
          else{
            $am_det=AppDetails::find(1);
             if($user->user_type == 'plumber')
              $amount=Slab::where('product_id',$product_id)->where('type','plumber')->value('value');
             else
              $amount=Slab::where('product_id',$product_id)->where('type','salesman')->value('value');
          }  

          if($slab_range == null)
            $slab_range=1;
            $result=Transactions::create([
              'trans_id' =>mt_rand(10000000, 100000000),
              'serial_number' =>$request->serial_number,
              'user_id' =>$user->id,
              'product_id' =>$product_id,
              'amount' =>$amount,
              'slab_range' =>$slab_range,
              'user_type' =>Auth::user()->user_type,
            ]);
            UserWallet::where('user_id',$user->id)->increment('balance',$amount);          
            ScanCountHistory::where('id',$scan_det->id)->update(['scan_count' =>$scan]);
        
        return response()->json(['status' => 201,'message' => 'success','data' => $result]);
      }

    public function returnProduct(Request $request){

        $request->validate([
            'serial_number' =>'required|exists:product_qrcodes,qrcode_data',           
        ]); 
        $user=Auth::user();
        $returned=Transactions::where('serial_number',$request->serial_number)
                              ->where('status','returned')
                              ->where('user_id',$user->id)                                 
                              ->first();
        if($returned <> null)
          return response()->json(['status' => 401,'message' => 'Already returned this product','data' => []]);

        $transaction=Transactions::where('serial_number',$request->serial_number)
                            ->where('status','pending')
                            ->where('user_id',$user->id)->first();
        $product_id=ProductQRcodes::where('qrcode_data',$request->serial_number)->value('product_id');       

        if($transaction == null)
          return response()->json(['status' => 401,'message' => 'Invalid Data','data' => []]);
        else{

            $datetime1 = date_create($transaction->created_at); 
            $datetime2 = date_create(date('Y-m-d H:i:s')); 
            $dateDiff = date_diff($datetime1, $datetime2); 
            // dd($dateDiff->days,$transaction->created_at);
            if($dateDiff->days > 30)
               return response()->json(['status' => 401,'message' => 'Sorry, You cannot return this product','data' => []]);
            else{
              
                $all_transactions=Transactions::where('serial_number',$request->serial_number)
                                              ->where('status','pending')
                                              // ->where('user_type','<>','plumber') 
                                              ->get();
                foreach ($all_transactions as $key => $trans) {
                    UserWallet::where('user_id',$trans->user_id)->decrement('balance',$trans->amount);
                    // $each_count=ScanCountHistory::where('user_id',$trans->user_id)
                    //                 ->where('product_id',$trans->product_id)
                    //                 ->value('scan_count');
                    // $new_count=$each_count-1; 

                    // ScanCountHistory::where('user_id',$trans->user_id)
                    //                 ->where('product_id',$trans->product_id)
                    //                 ->update(['scan_count' =>$new_count]);
                    $change_trans=Transactions::find($trans->id);
                    $change_trans->amount =0;
                    $change_trans->status='returned';
                    $change_trans->save(); 

                    $then_date = $change_trans->created_at;
                    $now_date=date('Y-m-d H:i:s');
                    // if($trans->user_type == 'dealer'){
                    //     $userss_id=Transactions::where('user_id',Auth::user()->id)
                    //                         ->where('product_id',$trans->product_id)
                    //                         ->where('status','pending')
                    //                         ->where('created_at','>',$then_date)
                    //                         ->where('created_at','<=',$now_date)
                    //                         ->pluck('id');
                    //     $slb=Slab::where('product_id',$trans->product_id)->value('id');
                    //     $slbamount=Slab::slabRange($new_count,$trans->product_id);                        
                    //     $slbrnge=Slab::slabCount($new_count,$trans->product_id);


                    //       foreach ($userss_id as $key => $useid) {
                    //         $slb=Transactions::find($useid)->slab_range;
                    //         if($slb > $slbrnge)
                    //           Transactions::where('id',$useid)->update(['amount' => $slbamount,'slab_range' =>$slbrnge]);
                    //       }
                    // }

                               
                }
                return response()->json(['status' => 202,'message' => 'Successfully returned this product','data' => []]);
            }
        }


        

    }

    public function allTransactions(){

        $then_date = date('Y-m-d H:i:s',strtotime("-30 day"));  
        $valid_date = date('Y-m-d',strtotime("-120 day"));      
        $all_transactions=Transactions::join('users','users.id','=','transaction.user_id')
        ->join('products','products.id','=','transaction.product_id')
        ->where('user_id',Auth::user()->id)
        // ->where('transaction.status','<>','redeemed')
        // ->where('transaction.status','<>','to_redeem')        
        ->select('transaction.*','users.name','users.user_type','products.name as product')
        ->orderBy('transaction.created_at','desc')
        ->get();
        
        $result=[];
        foreach ($all_transactions as $transaction) {
            $temp['name'] = $transaction->name;
            $temp['transaction_id'] = $transaction->id;
            $temp['serial_no'] = $transaction->serial_number;
            $temp['user_type'] = $transaction->user_type;
            $temp['product_name'] = $transaction->product;
            $temp['reward'] = $transaction->amount;
            $creat_date=date('Y-m-d',strtotime($transaction->created_at));
            // if($creat_date < $then_date && $creat_date >= $valid_date   && $transaction->status == 'pending' ){
            if($creat_date < $then_date && $transaction->status == 'pending' ){

                $status = 'redeemable';
                // $valid=  date('d/m/Y',strtotime($creat_date. "+ 120 day")) ;
                $valid=  'no expiry' ;

            }
            // elseif($creat_date < $then_date && $creat_date < $valid_date   && $transaction->status == 'pending' ){
            //    $status = 'expired';
            //    $valid='expired';
            // }
            elseif($creat_date >= $then_date && $transaction->status == 'pending'){
                $status = 'waiting_period';
                $valid='waiting_period';
            }
            else{
                $status=$transaction->status;
                $valid='no_validity';
            }
            $temp['redeem_validity_date'] = $valid;
            $temp['status'] = $status;
            $temp['created_at'] = date('d/m/Y',strtotime($transaction->created_at));
            $temp['user_id'] = $transaction->user_id;
            $temp['product_id'] = $transaction->product_id;
            $temp['selected']=false;
            $result[] = $temp;
        }

        return response()->json(['status' => 200,'message' => 'success','data' => $result]);
    }


        public function redeemList(){

            $then_date = date('Y-m-d',strtotime("-30 day")); 
            $valid_date = date('Y-m-d',strtotime("-120 day"));  
          
             
            $all_transactions=Transactions::join('users','users.id','=','transaction.user_id')
                                          ->join('products','products.id','=','transaction.product_id')
                                          ->where('user_id',Auth::user()->id)
                                          ->where('transaction.status','<>','redeemed')
                                          ->where('transaction.status','<>','to_redeem')        
                                          ->select('transaction.*','users.name','users.user_type','products.name as product')
                                          ->orderBy('transaction.created_at','desc')
                                          ->get();

            $result=[];
            foreach ($all_transactions as $transaction) {
                $temp['name'] = $transaction->name;
                $temp['transaction_id'] = $transaction->id;
                $temp['serial_no'] = $transaction->serial_number;
                $temp['user_type'] = $transaction->user_type;
                $temp['product_name'] = $transaction->product;
                $temp['reward'] = $transaction->amount;
                $creat_date=date('Y-m-d',strtotime($transaction->created_at));
                // if($creat_date < $then_date && $creat_date >= $valid_date   && $transaction->status == 'pending' ){
                //     $status = 'redeemable';
                //     $valid=  date('d/m/Y',strtotime($creat_date. "+ 120 day")) ;
                // }
                if($creat_date < $then_date && $transaction->status == 'pending' ){

                  $status = 'redeemable';
                  // $valid=  date('d/m/Y',strtotime($creat_date. "+ 120 day")) ;
                  $valid=  'no expiry' ;
  
              }
                // elseif($creat_date < $then_date && $creat_date < $valid_date   && $transaction->status == 'pending' ){
                //    $status = 'expired';
                //    $valid='expired';
                // }
                elseif($creat_date >= $then_date && $transaction->status == 'pending'){
                    $status = 'waiting_period';
                    $valid='waiting_period';
                }
                else{
                    $status=$transaction->status;
                    $valid='no_validity';
                }
                $temp['redeem_validity_date'] = $valid;
                $temp['status'] = $status;
                $temp['created_at'] = date('d/m/Y',strtotime($transaction->created_at));
                $temp['user_id'] = $transaction->user_id;
                $temp['product_id'] = $transaction->product_id;
                $temp['selected']=false;
                
                $result[] = $temp;
            }

            return response()->json(['status' => 200,'message' => 'success','data' => $result]);
    }


    public function redeemRequest(Request $request){
      $request->validate([
            'data' =>'required', 
            'data.*'  => 'required',
          
        ]);

      $data=$request->data;
      $data=json_decode($data);
 
        if($data == null)
             return response()->json(['status' => 401,'message' => 'Check the values entered','data'=> []]);

      $total=0;        
        foreach ($data as $key => $trns) {
           $trns_details= Transactions::where('id',$trns)
                                      ->where('status','pending')
                                      ->where('user_id',Auth::user()->id)
                                      ->first();
          if($trns_details == null)
             return response()->json(['status' => 401,'message' => 'Ooopzzz!! Sorry Invalid Transaction','data'=> []]);
          else{
            $re_dat=RedeemList::where('user_id',Auth::user()->id)
                              ->where('serial_number',$trns_details->serial_number)
                              ->where('status','<>','rejected')
                              ->first();
                              
            if($re_dat <> null)
             return response()->json(['status' => 401,'message' => 'Ooopzzz!! Sorry already send for redeem','data'=> $re_dat]);

          }


           Transactions::where('id',$trns)->update(['status' =>'to_redeem']);
           $redeem=RedeemList::create([
            'user_id' => Auth::user()->id,
            'requested_amount' =>$trns_details->amount,
            'product_id' =>$trns_details->product_id,
            'serial_number' =>$trns_details->serial_number,
            'transaction_ids' =>$trns,
          ]);
          $redeem_ids[$key]=$redeem->id;
           $total+=$trns_details->amount;
        }

        // dd($redeem_ids);
        $redeem=RedeemList::join('products','redeem_list.product_id','=','products.id')
                           ->whereIn('redeem_list.id',$redeem_ids)
                           ->select('redeem_list.*','products.name as product_name')->get();  
        
        UserWallet::where('user_id',Auth::user()->id)->decrement('balance',$total);

        return response()->json(['status' => 201,'message' => 'success','data' => $redeem]);

    }

    public function redeemHistory(){
        $redeem_list=RedeemList::join('products','redeem_list.product_id','=','products.id')
                               ->where('redeem_list.user_id',Auth::user()->id)
                               // ->where('redeem_list.status','released')
                               ->select('redeem_list.*','products.name as product_name')
                               ->orderBy('redeem_list.created_at','desc')
                               ->get();
        return response()->json(['status' => 200,'message' => 'success','data' => $redeem_list]);

    }

    public function userDetails(){
       $tpoints=Transactions::where('user_id',Auth::user()->id)->sum('amount');
       $redeemed_points=Transactions::where('user_id',Auth::user()->id)                                      
                                    ->where('status','redeemed')                                     
                                    ->sum('amount');
       $total_points=$tpoints-$redeemed_points;
       $then_date = date('Y-m-d H:i:s',strtotime("-30 day"));
       $redeemable_points=Transactions::where('user_id',Auth::user()->id)
                                          ->where('status','!=','returned')
                                          ->where('status','!=','redeemed')
                                          ->where('created_at','<',$then_date)
                                          ->sum('amount');
       Carbon::setWeekStartsAt(Carbon::SUNDAY);
       Carbon::setWeekEndsAt(Carbon::SATURDAY);
       $this_week_scan=Transactions::where('user_id',Auth::user()->id)
                             ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                             ->where('status','!=','returned')
                             ->count();
        $this_week_return=Transactions::where('user_id',Auth::user()->id)
                             ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                             ->where('status','=','returned')
                             ->count();

        $this_month_scan= Transactions::where('user_id',Auth::user()->id)
                               ->where('status','!=','returned')
                               ->whereYear('created_at', Carbon::now()->year)
                               ->whereMonth('created_at', Carbon::now()->month)
                               ->count();
        $this_month_return= Transactions::where('user_id',Auth::user()->id)
                               ->where('status','=','returned')
                               ->whereYear('created_at', Carbon::now()->year)
                               ->whereMonth('created_at', Carbon::now()->month)
                               ->count();
        $total_week=$this_week_scan+$this_week_return;
        $total_month=$this_month_scan+$this_month_return;
        if($total_week > 0){
           $scan_percent_week=($this_week_scan/$total_week)*100;
           $return_percent_week=($this_week_return/$total_week)*100;
        }
        else{
           $scan_percent_week=0;
           $return_percent_week=0;
        }
        if($total_month > 0){
           $scan_percent_month=($this_month_scan/$total_month)*100;
           $return_percent_month=($this_month_return/$total_month)*100;
        }else{
           $scan_percent_month=0;
           $return_percent_month=0;
        }

        $data['username'] = Auth::user()->name;;
        $data['image'] = url(asset('dist/img/default-user.png'));
        $data['total_points'] = $total_points;
        $data['redeemable_points'] = $redeemable_points;      
        $data['scan_percent_week'] = round($scan_percent_week,2);
        $data['return_percent_week'] =round($return_percent_week,2);
        $data['scan_percent_month'] =round($scan_percent_month,2);
        $data['return_percent_month'] = round($return_percent_month,2);
        return response()->json(
            [
                'status' => 200,
                'message' =>'success',
                 'data' => [
                    'user_details' => $data,
                ],
            ]);
 
                              
    }

    public function userSignOut(Request $request){
     
       $request->user()->token()->revoke();
        return response()->json([
             'status' => 200,
                'message' =>'Successfully Logged Out !!',
                 'data' => [],
        ]);
    }

    public function redeemTotals(){

      $then_date = date('Y-m-d H:i:s',strtotime("-30 day"));
      $redeemable_points=Transactions::where('user_id',Auth::user()->id)
                                          ->where('status','!=','returned')
                                          ->where('status','!=','redeemed')
                                          ->where('created_at','<',$then_date)
                                          ->sum('amount');
      $redemmed=RedeemList::where('user_id',Auth::user()->id)
               ->where('status','released')
               ->sum('requested_amount');
      $data['redeemable_points'] = $redeemable_points;
      $data['redeemed'] = $redemmed;

        return response()->json(
            [
                'status' => 200,
                'message' =>'success',
                 'data' => [
                    'total_points' => $data,
                ],
            ]);

    }
}

