@extends('layouts.admin')
@push('styles')
<style type="text/css">
 .nav-tabs.flex-column .nav-link.active {
   
    background-color: #d8e1ea;
}

#load{
    width:100%;
    height:100%;
    position:fixed;
    z-index:9999;
    margin-top: -140px;

    background:url({{asset('assets/uploads/spiner-black.gif')}}) no-repeat center;


    .input-suggest ul {
  height: 10vh;
  overflow-y: scroll;
}
}
</style>
@endpush
@section('content')
@include('errors.success')
@include('errors.list')

  <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">{{$title}}</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">{{env('APP_NAME')}}</a></li>
              <li class="breadcrumb-item active">{{$title}}</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- <div class="col-md-10" id="load"></div> -->

    <!-- Main content -->
    <section class="content">
     <div class="card card-primary card-tabs">
              <div class="card-header p-0 pt-1">
                <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                  <li class="nav-item col-lg-6">
                    <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">New Product</a>
                  </li>
                  <li class="nav-item col-lg-6">
                    <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill" href="#custom-tabs-one-profile" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false">View All Products</a>
                  </li>
             
                </ul>
              </div>
              <div class="card-body">
                <div class="tab-content" id="custom-tabs-one-tabContent">
                  <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                    <!-- <div class="col-md-6 offset-3"> -->
                    <div class="card card-primary">
                       <!-- form start -->
                    <form method="post" action="{{url('admin/save-product')}}" id="myform" enctype="multipart/form-data">
                      @csrf
                      <div class="card-body">
                       <strong> Product Details</strong>
                          <div class="row">                       
                            <div class="col-md-4 offset-1">
                              <div class="form-group">  
                              <input type="text" class="form-control" id="name" name="name" placeholder="Name" required>
                                    @error('name')
                                    <span style="color: red;">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                   @enderror
                            </div>
                          </div>                       
                          <div class="col-md-4 offset-1">   
                              <div class="form-group">  
                            <input type="text" class="form-control" id="model" name="model" placeholder="Model Number" required>                       
                            </div>
                          </div>
                       </div>
                       <div class="row">                       
                            <div class="col-md-4 offset-1">
                              <div class="form-group">  
                             <input type="text" class="form-control" id="color" name="color" placeholder="Color" required>
                            </div>
                        </div>                                           
                        <div class="col-md-4 offset-1">
                          <div class="form-group">  
                             <input type="file" name="images[]" multiple class="form-control" style="opacity: 1;" required>
                                 @error('images')
                                   <span style="color: red;">
                                    <strong>{{ $message }}</strong>
                                   </span>
                                   @enderror
                                   <i style="font-size: 14px;">(Use CTRL to select multiple image files, Allowed files-jpeg,png,jpg,gif,svg, Max.File Size:2MB)</i>
                           </div>
                         </div> 
                      </div>
                        <div class="row"> 
                         <div class="col-md-4 offset-1">                        
                           <div class="form-group">  
                          <input type="text" class="form-control" id="size" name="size" placeholder="Size" required>
                        </div>
                      </div>
                      <div class="col-md-4 offset-1"> 
                        <div class="form-group">  
                          <textarea class="form-control" id="description" name="description" placeholder="Description" required></textarea>
                        </div>
                        </div>                     
                      </div>
                      <hr>
                      <strong>Slab Details</strong>
                      <p>Dealer Slab</p>
                      <br>
                     <div class="row"> 
                        <div class="col-md-3 offset-1">
                          <div class="form-group">  
                          <input type="number" class="form-control" id="Start" name="range[start][]" placeholder="Start" min="1" required>                                
                        </div>
                      </div>                     
                        <div class="col-md-3 offset-1">   
                            <div class="form-group">  
                          <input type="number" class="form-control" id="End" name="range[end][]" placeholder="End" min="1" required>                  
                          </div>
                        </div> 

                          <div class="col-md-2 offset-1">   
                            <div class="form-group">  
                          <input type="number" class="form-control" id="value" name="range[value][]" placeholder="Value" min="1" required>                  
                          </div>
                        </div> 
                    </div>
                     <div id="newRow"></div>
                     <div class="col-md-3 offset-1">   
                            <div class="form-group"> 
                     <button class="btn btn-transparent font-weight-bold  pl-0" type="button" id="addRow">
                      Add more range <i class="lnr-plus-circle icon-gradient bg-premium-dark mt-1"></i></button>
                    </div>
                  </div>

                    <div class="row"> 
                        <div class="col-md-3 offset-1">
                          <div class="form-group">  
                          <input type="number" class="form-control" id="plumber" name="plumber" placeholder="Plumber" min="1" required>                                
                        </div>
                      </div>                     
                        <div class="col-md-3 offset-1">   
                            <div class="form-group">  
                          <input type="number" class="form-control" id="salesman" name="salesman" placeholder="Salesman" min="1" required>                  
                          </div>
                        </div> 
                    </div>

                    
                    


                </div>
                      <!-- /.card-body -->
                     <div class="form-group">  
                        <div class="row"> 
                          <div class="col-md-6 offset-1">  
                           <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                          </div>
                        </div>
                      </div>
                    </div>
                    </form>
                  </div>
                <!-- </div> -->
                  </div>
                  <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">
                    <div class="card card-primary">
                      <div class="card-body">

                        <form action="{{url('admin/product-management')}}" method="post">
                          @csrf
                        <div class="form-group">
                          <div class="row">
                             <div class="col-xs-2">
                               <input type="text" name="search" id="searchprod" placeholder="Search" @if($term != 'no') 
                               value="{{$term}}" @endif class="form-control" autocomplete="off" required>
                               <div id="product_search_list" class="input-suggest"></div> 
                             </div>
                             <div class="col-xs-1">
                               <button class="btn btn-light" type="submit" title="Search"><i class="fa fa-search" style="color: green;"></i>
                               </button>
                               
                             </div>
                               <div class="col-xs-1">
                               <a class="btn btn-light" href="{{url('admin/product-management')}}" title="Reset"><i class="fa fa-undo" style="color: blue;"></i>
                               </a>
                               
                             </div>

                           </div>
                         </div>
                         </form>
                        <div class="row">
                         
                         @if(count($products) > 0)                       
                          <div class="col-sm-3">
                     
                            <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
                              @foreach($products as $product)
                                <a class="nav-link {{ $loop->first ? 'active' : ''}}" id="vert-tabs-{{$product->id}}-tab" data-toggle="pill" href="#vert-tabs-{{$product->id}}" role="tab" aria-controls="vert-tabs-{{$product->id}}" aria-selected="{{ $loop->first ? 'true' : 'false'}}">{{$product->name}}</a>                         
                              @endforeach                            
                            </div>
                          </div>
                          <div class="col-sm-9">
                            <div class="tab-content" id="vert-tabs-tabContent">
                              @foreach($products as $product)
                                <div class="tab-pane {{ $loop->first ? 'text-left fade active show' : 'fade'}}" id="vert-tabs-{{$product->id}}" role="tabpanel" aria-labelledby="vert-tabs-{{$product->id}}-tab"> 

                              <div class="row">
                                <div class="col-md-4">

                                 <div class="form-group">
                                    <div class="row">
                                      <div class="col-md-12">
                                          <strong> Name</strong>                                   
                                          <p class="text-muted">
                                            {{$product->name}}
                                          </p>
                                      </div>
                                    
                                    </div>
                                 </div>

                                 <div class="form-group">
                                    <div class="row">
                                      <div class="col-md-6">
                                         <strong> Model Number</strong>
                                          <p class="text-muted"> {{$product->model}}</p>
                                      </div>
                                   
                                    </div>
                                 </div>

                                  <div class="form-group">
                                    <div class="row">
                                      <div class="col-md-4">
                                          <strong> Color</strong>
                                          <p class="text-muted"> {{$product->colour}}</p>
                                      </div>                                   
                                    </div>
                                  </div> 

                                  <div class="form-group">
                                    <div class="row">
                                      <div class="col-md-4">
                                          <strong> Size</strong>
                                          <p class="text-muted"> {{$product->size}}</p>
                                      </div>                                   
                                    </div>
                                  </div>  

                                                               

                        
                              </div>
                              <div class="col-md-6">
                                <div class="row">
                                  <strong>Images</strong>
                                </div>

                               <div class="row">
                                  @php $prod_images=json_decode($product->images); @endphp
                                  @foreach($prod_images as $pimages)
                                  <img src="{{asset('assets/uploads/products/')}}/{{$pimages}}" style="width:  100px;height: 100px;object-fit: cover;margin-top: 10px;margin-right: 10px;">
                                  @endforeach
                                </div>
                             
                               
                                     
                              </div>
                              <!-- //col -->
                            </div>
                           
                                 <div class="form-group">
                                    <div class="row">
                                      <div class="col-md-12">
                                          <strong> Description</strong>
                                         <p class="text-muted"> {{$product->description}}</p> 
                                      </div>                                   
                                    </div>
                                  </div> 
                           
                            <!-- //roe -->
                            <div class="row">

                              <div class="col-md-6 offset-6">
                                    <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-edit{{$product->id}}">
                                      Edit
                                     </button>

                                      <div class="modal fade" id="modal-edit{{$product->id}}" role="dialog">
                                      <div class="modal-dialog">
                                        <div class="modal-content">
                                         <div class="modal-header">
                                           <h4 class="modal-title">Edit Product</h4>
                                               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                  <span aria-hidden="true">&times;</span>
                                                </button>
                                             </div>
                                          <div class="modal-body">
                                          <form method="post" action="{{url('admin/edit-product')}}" enctype="multipart/form-data" id="myform">
                                            @csrf
                                            <input type="hidden" name="product" value="{{$product->id}}">
                                          <div class="form-group">
                                             <div class="row">
                                               <div class="col-sm-8 offset-2">
                                                <strong>Name</strong>
                                                <br>
                                                <input type="text" name="name" placeholder="Name" value="{{$product->name}}" class="form-control" required>
                                               </div>
                                             </div>
                                          </div>

                                          <div class="form-group">
                                             <div class="row">
                                                <div class="col-sm-8 offset-2">
                                                  <strong>Model</strong>
                                                  <br>
                                                  <input type="text" name="model" placeholder="Model Number" value="{{$product->model}}" class="form-control" required>
                                                </div>
                                              </div>
                                          </div>

                                           <div class="form-group">
                                             <div class="row">
                                                <div class="col-sm-8 offset-2">
                                                  <strong>Color</strong>
                                                   <br>
                                                  <input type="text" name="color" placeholder="Color" value="{{$product->colour}}" class="form-control" required>
                                                </div>
                                              </div>
                                          </div>

                                           <div class="form-group">
                                             <div class="row">
                                                <div class="col-sm-8 offset-2">
                                                  <strong>Size</strong>
                                                <br>
                                                  <input type="text" name="size" placeholder="Size" value="{{$product->size}}" class="form-control" required>
                                                </div>
                                              </div>
                                          </div>

                                           <div class="form-group">
                                             <div class="row">
                                                <div class="col-sm-8 offset-2">
                                                  <strong>Description</strong>
                                                <br>
                                                  <textarea name="description" placeholder="Description" class="form-control" required>{{$product->description}}</textarea> 
                                                </div>
                                              </div>
                                          </div>

                                          <div class="form-group">
                                             <div class="row">
                                                <div class="col-sm-8 offset-2">
                                                  <strong>Image</strong>
                                                <br>
                                                  <input type="file" name="images[]" multiple class="form-control">
                                                </div>
                                              </div>
                                          </div>

                                           </div>
                                         <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                           <button type="submit" class="btn btn-primary">Save changes</button>
                                      </div>
                                      </form>
                                    </div>
                                   <!-- /.modal-content -->
                                 </div>
                                  <!-- /.modal-dialog -->
                               </div>
                              <!-- /.modal -->                            

                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal-delete{{$product->id}}">
                                Delete
                               </button>

                                     <!-- Modal -->
                                <div class="modal fade" id="modal-delete{{$product->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                  <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Delete</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                        </button>
                                      </div>
                                      <div class="modal-body">
                              Are you sure you want to delete this Product {{$product->name}}??
                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <a href="{{url('admin/delete-product/')}}/{{$product->id}}" class="btn btn-danger">Delete</a>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                                  
                              </div>                           
                              @endforeach                            
                            </div>
                          </div>
                          {{$products->links()}}
                        @else
                        <p>No Products</p>
                        @endif
                        </div>  
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- /.card -->
            </div>
    </section>
    <!-- /.content -->
@endsection
@push('head')
<script type="text/javascript">


$(document).ready(function () {
      
      $('#searchprod').on('keyup',function() {
       
          var query = $(this).val(); 
          $.ajax({
            
              url:'get-list-products',        
              type:"GET",
            
            data:{'search':query},
            
            success:function (data) {
              
                $('#product_search_list').html(data);
            }
          })
          // end of ajax call
      });

             
      $(document).on('click', '#list-search', function(){
        
        
          var value = $(this).text();
          var id=$(this).attr("data-searchid")
          if(id != 0){
          $('#searchprod').val(value);
          $('#searched_qr_id').val(id);
          }
          
          $('#product_search_list').html("");
      });
});
$(document).ready(function(){
    $('a[data-toggle="pill"]').on('show.bs.tab', function(e) {
        localStorage.setItem('activeTab', $(e.target).attr('href'));
    });
    var activeTab = localStorage.getItem('activeTab');
    if(activeTab){
        $('#custom-tabs-one-tab a[href="' + activeTab + '"]').tab('show');
    }
});

$(document).ready(function(){
     $("#addRow").click(function () {
        var html = '';
        html += '<div class="row" id="inputFormRow">';
        html += '<div class="col-md-3 offset-1">';
        html += '<div class="form-group">';
        html += '<input type="number" class="form-control" id="start" name="range[start][]" placeholder="Start" min="1" required>';
        html += '</div>';
        html += '</div>';

        html += '<div class="col-md-3 offset-1">';
        html += '<div class="form-group">';
        html += '<input type="number" class="form-control" id="end" name="range[end][]" placeholder="End" min="1" required>';
        html += '</div>';
        html += '</div>';

          html += '<div class="col-md-2 offset-1">';
        html += '<div class="form-group">';
        html += '<input type="number" class="form-control" id="value" name="range[value][]" placeholder="Value" min="1" required>';
        html += '</div>';
        html += '</div>';


        html += '<div class="input-group-append">';
        html += '<i id="removeRow" class="fa fa-trash" style="cursor:pointer;color:red;"></i>';
        html += '</div>';
        html += '</div>';

        $('#newRow').append(html);
    });
  });

  $(document).on('click', '#removeRow', function () {
      $(this).closest('#inputFormRow').remove();
  });


$(document).ready(function(){
       document.getElementById('load').style.visibility="hidden";
});


$(document).ready(function(){

  $("#myform").on("submit", function(){
         document.getElementById('load').style.visibility="visible";
   
  });//submit
});//d







</script>
@endpush
