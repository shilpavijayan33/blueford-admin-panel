<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminController;
use App\Products;
use App\Slab;
use Input;



class ProductController extends AdminController
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function productIndex(Request $request)
    {
        // dd($request->method());
        $title='Product Management';
        $products=Products::orderBy('name')->paginate(10)->onEachSide(1);        
        $term='no';
         if($request->method() == 'POST'){

             $term=$request->search;
             $products=Products::where('name', 'LIKE', '%'.$term.'%')
                                ->OrWhere('model', 'LIKE', '%'.$term.'%')
                                ->OrWhere('colour', 'LIKE', '%'.$term.'%')
                                ->OrWhere('size', 'LIKE', '%'.$term.'%')
                                ->OrWhere('description', 'LIKE', '%'.$term.'%')
                                ->OrWhere('price', 'LIKE', '%'.$term.'%')
                                ->orderBy('id','desc')
                                ->paginate(10)->onEachSide(1);
          }

            
        return view('admin.products.productindex',compact('title','products','term'));
    }

    public function saveProduct(Request $request){  
 
        $request->validate([
          'name' => 'required|string|unique:products',
          'model' => 'required|string|unique:products',
          'images.*' => 'mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);   

        

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
            //     if($request->range['start'][$i] <> 1)
            //          return redirect()->back()->with('error', 'Slab starting range must begin with 1');
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

        // dd("aaa");

        $uploaded_images=array(); 

        foreach ($request->images as $key => $images) {            
           
            $destinationPath = public_path() . '/assets/uploads/products';
            $extension       = $images->getClientOriginalExtension();
            $fileName        = rand(000011111, 99999999999) . '.' . $extension;
            $images->move($destinationPath, $fileName);
            $uploaded_images[]=$fileName;
        }        

        $product=Products::create([
            'name' =>$request->name,
            'model' =>$request->model,
            'colour' =>$request->color,
            'size' =>$request->size,
            'description' =>$request->description,
            'images' =>json_encode($uploaded_images),
        ]);

      
        for ($i=0; $i < count($request->range['start']) ; $i++) {  
       

               Slab::create([       
                'product_id' =>$product->id,
                'start' =>$request->range['start'][$i],
                'end' =>$request->range['end'][$i],
                'value' =>$request->range['value'][$i],
               ]);
            }
        

        Slab::create([       
            'product_id' =>$product->id,
            'value' =>$request->plumber, 
            'type' =>'plumber',          
        ]);

        Slab::create([       
            'product_id' =>$product->id,
            'value' =>$request->salesman,
            'type' =>'salesman',  
        ]);        

        return redirect('/admin/product-management')->with('success', 'Product added successfully');
    }

    public function editProduct(Request $request){

        $request->validate([
          'images.*' => 'mimes:jpeg,png,jpg,gif,svg|max:2048',
           'name'=>'required|unique:products,name,'.$request->product.',id',
           'model'=>'required|unique:products,model,'.$request->product.',id',

        ]);  

        $product=Products::find($request->product);
        if (Input::hasFile('images')) {
           $uploaded_images=array(); 
          
        foreach ($request->images as $key => $images) {            
           
            $destinationPath = public_path() . '/assets/uploads/products';
            $extension       = $images->getClientOriginalExtension();
            $fileName        = rand(000011111, 99999999999) . '.' . $extension;
            $images->move($destinationPath, $fileName);
            $uploaded_images[]=$fileName;
            $product->images =json_encode($uploaded_images);
        }  
       }
        $product->name=$request->name;
        $product->colour=$request->color;
        $product->model=$request->model;
        $product->size=$request->size;
        $product->description=$request->description;
        $product->save();
        // Slab::where('product_id',$request->product)->update(['name' =>$request->name]);
        return redirect('/admin/product-management')->with('success', 'Product edited successfully');

    }

    public function deleteProduct($id){
        $product=Products::find($id);
        $da=Slab::where('product_id',$id)->get();
        foreach ($da as $key => $d) {
            $da->delete();
        }
        $product->delete();
        return redirect('/admin/product-management')->with('success', 'Product deleted successfully');


    }
}
