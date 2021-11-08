<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminController;
use App\Products;
use App\Slab;
use App\AppDetails;
use Input;



class SlabController extends AdminController
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function slabIndex()
    {
        $title='Slab Management';
        $slab=Products::orderBy('name','asc')                   
                    ->first();
        return redirect()->to('admin/slab-management/' . $slab->id);
    }

    public function slabDetails($id){
        $title='Slab Management';
   
        $dealer_slabs=Slab::where('product_id',$id)->where('type','dealer')->get();
        $plumber_slabs=Slab::where('product_id',$id)->where('type','plumber')->first();
        $salesman_slabs=Slab::where('product_id',$id)->where('type','salesman')->first();

        $products=Products::orderBy('name','asc')->pluck('name','id');

        return view('admin.slab.slabindex',compact('title','products','dealer_slabs','plumber_slabs','salesman_slabs'));  
    }

    public function saveSlab(Request $request){   

        $request->validate([
          'name' => 'required|string|unique:slab',
        ]);         

        Slab::create([
            'name' =>$request->name,
            'first' =>$request->first,
            'second' =>$request->second,
            'third' =>$request->third,
            'fourth' =>$request->fourth,
            'fifth' =>$request->fifth,
            'sixth' =>$request->sixth,
            'seventh' =>$request->seventh,
            'eighth' =>$request->eighth,
            'nineth' =>$request->nineth,
            'tenth' =>$request->tenth,

        ]);

        return redirect('/admin/slab-management')->with('success', 'Slab added successfully');
    }

    public function editSlab(Request $request){     

        $start=0;
        $end=0;

        for ($i=0; $i < count($request->range['start']) ; $i++) {  
            // echo "start".$start."<br>";
            // echo "end".$end."<br>";

            // echo "startinloop".$request->range['start'][$i]."<br>";
            // echo "endinloop".$request->range['end'][$i]."<br>";

            if($request->range['start'][$i] >= $request->range['end'][$i])
                 return redirect()->back()->with('error', 'Slab starting value must less than end value');

            // if($i == 0){
                // if($request->range['start'][$i] <> 1)
                //      return redirect()->back()->with('error', 'Slab starting range must begin with 1');
            // }
            // else{

             if($request->range['start'][$i] <= $start)
                return redirect()->back()->with('error', 'Start must be greater than previous Start');

             if($request->range['end'][$i] <= $end)
                return redirect()->back()->with('error', 'End must be greater than previous End');
             if($i <> 0){
             
               if($request->range['start'][$i] <> ($end+1))
                return redirect()->back()->with('error', 'Start must be greater than previous End');
             }

             $start=$request->range['start'][$i];
             $end=$request->range['end'][$i];

            //  echo "__________________________<br>";
            //   echo "startafterassign".$start."<br>";
            // echo "endafterassign".$end."<br>";
            //  echo "...............................<br>";

        }
 

        $dealerslabs=Slab::where('product_id',$request->product)->where('type','dealer')->get();    
        foreach ($dealerslabs as $key => $dslab) {
             Slab::find($dslab->id)->delete(); 
        }  
        for ($i=0; $i < count($request->range['start']) ; $i++) {    

               Slab::create([       
                'product_id' =>$request->product,
                'start' =>$request->range['start'][$i],
                'end' =>$request->range['end'][$i],
                'value' =>$request->range['value'][$i],
               ]);
        } 
        Slab::where('product_id',$request->product)->where('type','salesman')->update(['value' => $request->salesman]);  
        Slab::where('product_id',$request->product)->where('type','plumber')->update(['value' => $request->plumber]);      
        return redirect()->back()->with('success', 'Slab edited successfully');

    }

    public function deleteSlab($id){

        $slab=Slab::find($id);
        $slab->delete();
        return redirect('/admin/slab-management')->with('success', 'Slab deleted successfully');

    }

    public function saveOtherSlabs(Request $request){

        AppDetails::where('id',1)->update(['salesman_slab' =>$request->salesman,'plumber_slab' =>$request->plumber]);
        return redirect('/admin/slab-management')->with('success', 'Slab edited successfully');

    }
}
