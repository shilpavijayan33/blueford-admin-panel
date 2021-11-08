<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminController;
use App\Products;
use App\ProductQRcodes;
use Input;
use QrCode;
use Barryvdh\DomPDF\Facade as PDF;



class QRCodeController extends AdminController
{

        /**
         * Show the application dashboard.
         *
         * @return \Illuminate\Contracts\Support\Renderable
         */
        public function qrIndex(Request $request)
        {
            $title='QR Code';
            $products=Products::orderBy('name')->paginate(10)->onEachSide(1);
            $list_produts=Products::select('id','name')->orderBy('name','asc')->get(); 
            $first_product=$products[0]->id;   
            $term='no';
            if($request->method() == 'POST'){

                $term=$request->search;
                $products=Products::whereHas('qrcodes', function($q) use($term){
                                $q->where('qrcode_data', 'LIKE', '%'.$term.'%');
                                })
                                ->OrWhere('name', 'LIKE', '%'.$term.'%')
                                ->paginate(10)->onEachSide(1);

                                
            }

            return view('admin.qrcode.qrindex',compact('title','products','list_produts','term'));
        }

        public function saveQRcode(Request $request){ 
            
            $request->validate([
                'searched_product' => 'required',
              
            ]);  

            $today_count=ProductQRcodes::where('product_id',$request->searched_product)->whereDate('created_at',date('Y-m-d'))->count();
            $max=126;
            $rem=$max-$today_count; 
        
            if($request->count > $rem)
            return redirect()->back()->with('error','Ooopz..!! Sorry, you cannot make more qr code for this product today');

            if($request->count > 126 || $today_count >= 126)
            return redirect()->back()->with('error','Ooopz..!! Sorry, Maximum QR code count must be 126 for a day');

            $curnt_count=Products::find($request->searched_product)->qrcode_count;
            for($i = 1;$i <= $request->count;$i++) {             
                ProductQRcodes::create([
                'product_id' =>$request->searched_product,
                'qrcode_data' =>mt_rand(1000000000, 10000000000),
                ]);
            } 
            Products::find($request->searched_product)->update(['qrcode_count' =>$curnt_count+$request->count]);
        
            return redirect('/admin/qrcode-management')->with('success', 'QR codes added successfully');
        }

        
        public function deleteQRcode($id){

            $qr_codes=ProductQRcodes::where('product_id',$id)->get();
            foreach ($qr_codes as $key => $qr_code) {
                $qr_code->delete();
            }
            Products::find($id)->update(['qrcode_count' =>0]);
            return redirect('/admin/qrcode-management')->with('success', 'QR Codes deleted successfully');

        }

        public function deleteSpecificQr(Request $request){
          
            
            $qr_code=ProductQRcodes::where('qrcode_data',$request->qr_code_del)->first();
            $curnt_count=Products::find($qr_code->product_id)->qrcode_count;
            Products::find($qr_code->product_id)->update(['qrcode_count' =>$curnt_count-1]);
            $qr_code->delete();
            
            return redirect('/admin/qrcode-management')->with('success', 'QR Code deleted successfully');

        }

        public function printQRcode($id){

            $qr_codes=ProductQRcodes::where('product_id',$id)->orderBy('id','desc')->take(42)->get();
         
            $product=Products::find($id);
            // $pdf=PDF::loadView('admin.qrcode.printqr',array('qr_codes' => $qr_codes,'product'=>$product))->setOptions(['defaultFont' => 'sans-serif']);

            // //    $pdf = PDF::loadView('projectmanagement::pdf_views.test');
            //    return $pdf->stream("printqr.pdf");
             
            return view('admin.qrcode.printqr',compact('qr_codes','product'));

        }
        //ajax

        public function getFirstQRCode(Request $request,$productid)
        {        
            $qr_codes=ProductQRcodes::orderBy('id','desc')->where('product_id',$productid)->take(30)->get();
            $out = '';
            if(count($qr_codes) > 0){
                foreach($qr_codes as $row){
                    $qr= QrCode::size(70)->generate($row->qrcode_data);
                    $prod=Products::find($row->product_id)->name;
                    $deleteurl=url('admin/product/delete-qr/').'/'.$row->qrcode_data;
                   
                  
                    $out.='<div class="col-md-4">'.$qr.'
                            <p class="pt-1">'.$row->qrcode_data.' </p>
                            <p class="pt-1"> 
                            <button type="button" class="btn btn-light" onclick="functionDeleteQr('.$row->qrcode_data.')" data-toggle="modal" data-target="#modal-inddelete">
                            <i class="fa fa-trash" style="color: black;"></i>
                            </button>
                            </p>                              
                            </div> ';                
                } 
                $count=1;     
            }
            else{
              $out='
              <p> No QR codes found</p>';  
              $count=0;  
            }
            return response()->json([
                'data' => $out,'count' => $count
             
            ]);
            
        }

        public function getMoreQRCodes($page,$productid){
            
            $pge=$page*30;

            $qr_codes=ProductQRcodes::orderBy('id','desc')->where('product_id',$productid)->skip($pge)->take(30)->get();
            $out = '';
            if(count($qr_codes) > 0){
                foreach($qr_codes as $row){
                    $qr= QrCode::size(70)->generate($row->qrcode_data);
                    $prod=Products::find($row->product_id)->name;
                    $deleteurl=url('admin/product/delete-qr/').'/'.$row->qrcode_data;
                   
                  
                    $out.='<div class="col-md-4">'.$qr.'
                            <p class="pt-1">'.$row->qrcode_data.' </p>
                            <p class="pt-1"> 
                            <button type="button" class="btn btn-light" onclick="functionDeleteQr('.$row->qrcode_data.')" data-toggle="modal" data-target="#modal-inddelete">
                            <i class="fa fa-trash" style="color: black;"></i>
                            </button>
                            </p>                                
                            </div>';                  
                }  
                $count=1;  
            }
            else{
              $out='<p> No QR codes found</p>';  
              $count=0;  
            }
            return response()->json([
                'data' => $out,'count' => $count
             
            ]);
           
        }

        public function index()
        {
            $title="pagination";
            $data = ProductQRcodes::where('product_id',42)->paginate(10);
            return view('admin.qrcode.pagination', compact('data','title'));
        }

        public function fetch_data(Request $request)
        {
            if($request->ajax()){
                $title="pagination";
                $data = ProductQRcodes::where('product_id',42)->paginate(10);
                return view('admin.qrcode.pagination_data', compact('data','title'))->render();
            }
        }


        public function getPrintQr(Request $request){

            if($request->from_date > $request->to_date)
            return redirect()->back()->with('error','Start date must be a date lesser than end date');

           
               $qr_codes=ProductQRcodes::where('product_id',$request->filter_project)->where('created_at','>=',\Carbon\Carbon::parse(implode("-", array_reverse(explode("/",$request->from_date)))))->where('created_at','<=',\Carbon\Carbon::parse(implode("-", array_reverse(explode("/",$request->to_date)))))->orderBy('created_at')->get();
              
               $product=Products::find($request->filter_project);
            
             
               $pdf=PDF::loadView('admin.qrcode.printqr',array('qr_codes' => $qr_codes,'product'=>$product))->setOptions(['defaultFont' => 'sans-serif']);
              

            //    $pdf = PDF::loadView('projectmanagement::pdf_views.test');
               return $pdf->stream("printqr.pdf");
             
            //    return $pdf->download('qrcode_'.$product->name.'_'.time().'pdf');

        }


        public function getSearchedProducts(Request $request){
         
            $data = Products::where('name', 'LIKE', '%'.$request->product.'%')
                ->take(5)->get();
           
            $output = '';
           
            if (count($data)>0) {
              
                $output = '<ul class="list-group" style="display: block; position: relative; z-index: 1">';
              
                foreach ($data as $row){
                   
                    $output .= '<li class="list-group-item" id="list-prodcut" data-id="'.$row->id.'">'.$row->name.'</li>';
                }
              
                $output .= '</ul>';
            }
            else {
             
                $output .= '<li class="list-group-item" id="list-prodcut" data-id="0">'.'No results'.'</li>';
            }
           
            return $output;
        }


        public function getlistProducts(Request $request){

            $data = Products::where('name', 'LIKE', '%'.$request->search.'%')->take(3)
            ->get();
       
            $output = '';
        
            if (count($data)>0) {
            
                $output = '<ul class="list-group" style="display: block; position: relative; z-index: 1">';
            
                foreach ($data as $row){
                
                    $output .= '<li class="list-group-item" id="list-search" data-searchid="'.$row->id.'">'.$row->name.'</li>';
                }
            
                $output .= '</ul>';
            }
            else {
            
                $output .= '<li class="list-group-item" id="list-search" data-searchid="0">'.'No results'.'</li>';
            }
        
            return $output;

        }
}
