@extends('layouts.admin')
@push('styles')
<style type="text/css">
 .nav-tabs.flex-column .nav-link.active {
   
    background-color: #d8e1ea;
}

.alert-warning {
    color: #856404;
    background: #fff3cd;
    border-color: #ffeeba;
}



#load{
    width:100%;
    height:100%;
    position:fixed;
    z-index:9999;
    margin-top: -140px;

    background:url({{asset('assets/uploads/spiner-black.gif')}}) no-repeat center;

}

#from_date{
  z-index: 10000 !important;
}

#enddate{
  z-index: 10000 !important;
}


.qr-view-div {
  max-height: 100vh;
  overflow-y: auto;
}

.input-suggest ul {
  height: 10vh;
  overflow-y: scroll;
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

    <!-- Main content -->
    <div class="col-md-10" id="load"></div>

    <section class="content" id="contents">
     <div class="card card-primary card-tabs">
              <div class="card-header p-0 pt-1">
                <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                  <li class="nav-item col-lg-6">
                    <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">Generate QR Code</a>
                  </li>
                  <li class="nav-item col-lg-6">
                    <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill" href="#custom-tabs-one-profile" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false">View All QR codes</a>
                  </li>
             
                </ul>
              </div>
              <div class="card-body">
              
                <div class="tab-content" id="custom-tabs-one-tabContent">
                  <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                   
                    <div class="card card-primary">
                       <!-- form start -->
                    <form id="myform" method="post" action="{{url('admin/save-qrcodes')}}" enctype="multipart/form-data">
                    <input type="hidden" name="searched_product" id="searched_product" value="">
                      @csrf
                      <div class="card-body">
                        <div class="form-group"> 
                           <div class="row">                       
                            <div class="col-md-6 offset-3">                        
                              <!-- <select class="form-control" id="product-search" name="product" required>
                                <option value="">Select Product</option>
                                @foreach($list_produts as $each)
                                <option value="{{$each->id}}">{{$each->name}}</option>
                                @endforeach    
                                </select>                       -->
                                <input type="text" name="product" id="product-search" placeholder="Enter product name" class="form-control" autocomplete="off" required>
                                <div id="product_list" class="input-suggest"></div>      
                            </div>
                          </div>
                        </div>
                       <div class="form-group"> 
                          <div class="row">                       
                            <div class="col-md-6 offset-3">                          
                              <input type="number" class="form-control" id="count" name="count" placeholder="Enter number of QR code needed" required>
                            </div>
                          </div>
                        </div>

                        <div class="form-group"> 
                          <div class="row">                       
                            <div class="col-md-6 offset-3">                          
                         
                             <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <strong>Note!</strong>You can only create 126 QR codes for a product for a day
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                            </div>
                          </div>
                        </div>
                      
                      </div>
                      <!-- /.card-body -->
                       <div class="row">                       
                          <div class="col-md-6 offset-3"> 
                            <div class="card-footer" style="background-color: white;">
                              <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                          </div>
                        </div>
                    </form>
                  </div>
               
                  </div>
                  <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">
                    <div class="card card-primary">
                      <div class="card-body">


                        <form action="{{url('admin/qrcode-management')}}" method="post">
                          @csrf
                        <div class="form-group">
                          <div class="row">
                             <div class="col-xs-2">
                               <input type="text" name="search" id="search-qr" placeholder="Search" @if($term != 'no') 
                               value="{{$term}}" @endif class="form-control" autocomplete="off" required>
                               <div id="search_list" class="input-suggest"></div> 
                             </div>
                             <div class="col-xs-1">
                               <button class="btn btn-light" type="submit" title="Search"><i class="fa fa-search" style="color: green;"></i>
                               </button>
                               
                             </div>
                               <div class="col-xs-1">
                               <a class="btn btn-light" href="{{url('admin/qrcode-management')}}" title="Reset"><i class="fa fa-undo" style="color: blue;"></i>
                               </a>
                               
                             </div>

                           </div>
                         </div>
                         </form>
                  
                         
                        <div class="row">
                         @if(count($products) > 0)                       
                          <div class="col-sm-3">
                            <div class="nav flex-column nav-tabs qr-tabssss h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
                              @foreach($products as $product)
                              
                                <a class="nav-link {{ $loop->first ? 'active' : ''}}" data="{{$product->id}}" id="vert-tabs-{{$product->id}}-tab" onClick="getQrcodes({{$product->id}})" data-toggle="pill" href="#vert-tabs-{{$product->id}}" role="tab" aria-controls="vert-tabs-{{$product->id}}" aria-selected="{{ $loop->first ? 'true' : 'false'}}">{{$product->name}}
                                  <p>Date Created:{{ date('d-m-Y', strtotime($product->created_at)) }}</p> 
                                  No : {{$product->qrcode_count}}
                                </a>                         
                              @endforeach                            
                            </div>
                          </div>
                          <div class="col-sm-9 qr-view-div">
                            <div class="tab-content" id="vert-tabs-tabContent">
                              @foreach($products as $product)
                                <div class="tab-pane {{ $loop->first ? 'text-left fade active show' : 'fade'}}" id="vert-tabs-{{$product->id}}" role="tabpanel" aria-labelledby="vert-tabs-{{$product->id}}-tab"> 
                                @php
                                $exist=App\ProductQRcodes::where('product_id',$product->id)->first();
                               
                                @endphp
                                @if($exist != null)
                                   
                                  
                                <iframe src="{{url('/admin/qrcode-print')}}/{{$product->id}}" name="frame{{$product->id}}" style="display: none;"> </iframe>

<button class="btn btn-info" onclick="frames['frame{{$product->id}}'].print()" value="printletter" style="float: right;">Print  <i class="fa fa-print"></i></button> 
                                   <div class="form-group">
                                  <div class="row">
                                    <div class="col-md-2 offset-10">  
                                     <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal-delete{{$product->id}}" style="float: right;">
                                     Delete
                                    </button>
                                  </div>
                             </div>
                             </div>

                                         <!-- Modal -->
                     
                      
                                          



                                 <!-- Modal -->
                                  <div class="modal fade" id="modal-delete{{$product->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                  <div class="modal-dialog" role="document" style="margin: 178px auto;">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Delete</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                        </button>
                                      </div>
                                      <div class="modal-body">
                                      Are you sure you want to delete these QR codes for product {{$product->name}}??
                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <a href="{{url('admin/delete-qrcode/')}}/{{$product->id}}" class="btn btn-danger">Delete</a>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                    
                                
                                @endif 
                                <div class="qr-data row" id="qr-data">
                              
                                </div>
                                <div class="row">
                               <div class="more-loading" id="more-loading">
                                <div class="ajax-loading"><img src="{{ asset('assets/uploads/loader.gif') }}" style="width:100px;" /></div>                             
                                   
                                  <button type="button" class="btn btn-primary btn-sm"  onclick="loadMorepages({{$product->id}})"><i class="fa fa-refresh"></i> Load More.. </button>
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

    <div class="modal fade" id="modal-inddelete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document" style="margin: 178px auto;">
        <form method="post"  action="{{url('admin/product/delete-qr')}}">
        <input type="hidden" id="qr_code_del" name="qr_code_del" value="">
            @csrf
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            Are you sure you want to delete this QR code <span id="qrcode_name" name="qrcode_name" ></span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-danger" >Delete</button>

                
            </div>
            </div>
            </form>
    </div>
    </div>


            <!-- Filter Modal -->
            <div class="modal fade filterModal" id="modal-print" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel" aria-hidden="true">
                          <div class="modal-dialog" role="document">
                              <div class="modal-content">
                                  <div class="modal-header">
                                      <h5 class="modal-title" id="filterModalLabel">Filter</h5>
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                      </button>
                                  </div>
                                  <div class="modal-body">
                                      <form action="{{url('/admin/filter-project')}}" method="post" id="filter-form">
                                          @csrf
                                          <input type="hidden" name="filter_project" id="filter_project" value="">
                                      <div class="row">
                                          <div class="input-group col-md-6 date-group mt-3">
                                              <input type="text" value="" name="from_date" class="form-control border-right-0" id="from_date" data-toggle="datepicker" placeholder="From Date" autocomplete="off" required>
                                              <div class="input-group-prepend datepicker-trigger">
                                                  <div class="input-group-text bg-white border-left-0">
                                                      <i class="fa fa-calendar-alt"></i>
                                                  </div>
                                              </div>
                                          </div>
                                          <div class="input-group col-md-6 date-group mt-3">
                                              <input type="text" value="" name="to_date" class="form-control border-right-0" id="enddate" data-toggle="datepicker" placeholder="To Date" autocomplete="off" required>
                                              <div class="input-group-prepend datepicker-trigger">
                                                  <div class="input-group-text bg-white border-left-0">
                                                      <i class="fa fa-calendar-alt"></i>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                                  <div class="modal-footer">
                                    
                                      <button class="btn btn-secondary btn-shadow btn-reset">Reset</button>
                                    
                                      <button type="submit" class="btn btn-primary btn-shadow">Filter</button>
                                  </div>
                              </form>
                              </div>
                          </div>
                      </div>
@endsection
@push('head')
<script type="text/javascript">
 var recordCount;
$(document).ready(function(){
 
  var ref_this = $(".qr-tabssss  a.active").attr("data");
  recordCount=0;
  $.ajax(
        {
            url: 'get-qr-code/'+ref_this,
            type: "get",
            success:function(data)
              {
              
                $('.qr-data').empty();
             
                $('.qr-data').html(data.data);
                if(data.count == 0)
                      $('.more-loading').hide();
                else
                      $('.more-loading').show();
                
              }

  });
});

function getQrcodes(product){
  recordCount=0;
 
  $.ajax(
        {
            url: 'get-qr-code/'+product,
            type: "get",
            success:function(data)
              {
                $('.qr-data').empty();
             

                $('.qr-data').html(data.data);
                if(data.count == 0)
                      $('.more-loading').hide();
                else
                $('.more-loading').show();
              }

  });
}

   
    function loadMorepages(product) {           
        recordCount = recordCount + 1;
       
        $.ajax({
                    type: "GET",
                    url: 'get-more-pages/'+recordCount+'/'+product,
                                 
                    success: function(data) {                        
                       
                      $('.qr-data').append(data.data);
                      if(data.count == 0)
                       $('.more-loading').hide();
                      else
                       $('.more-loading').show();
                    }
            });  
    }        


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
       document.getElementById('load').style.visibility="hidden";
});


$(document).ready(function(){

  $("#myform").on("submit", function(){
         document.getElementById('load').style.visibility="visible";
   
  });//submit
});//d


// document.onreadystatechange = function () {
//   var state = document.readyState
//   if (state == 'interactive') {
//        document.getElementById('contents').style.visibility="hidden";
//   } else if (state == 'complete') {
//       setTimeout(function(){
//          document.getElementById('interactive');
//          document.getElementById('load').style.visibility="hidden";
//          document.getElementById('contents').style.visibility="visible";
//       },1000);
//   }
// }


function functionDeleteQr(qrcode){

  $('#qrcode_name').html(qrcode);
  $("#qr_code_del").val(qrcode);  
}


$(function(){
    $("#from_date").datepicker({
      dateFormat: "dd/mm/yy"
    })

    $("#enddate").datepicker({
        dateFormat: "dd/mm/yy"
    })
})

  function getProjectName(project){
    $("#filter_project").val(project);  
  }

  $(document).ready(function () {
             
      $('#product-search').on('keyup',function() {
          var query = $(this).val(); 
          $.ajax({
            
              url:'get-searched-products',
        
              type:"GET",
            
              data:{'product':query},
            
              success:function (data) {
                
                  $('#product_list').html(data);
              }
          })
          // end of ajax call
      });

      
      $(document).on('click', '#list-prodcut', function(){       
        
          var value = $(this).text();
          var id=$(this).attr("data-id")
          if(id != 0){
          $('#product-search').val(value);
          $('#searched_product').val(id);
          }
          
          $('#product_list').html("");
      });
  });



  $(document).ready(function () {
      
      $('#search-qr').on('keyup',function() {
          var query = $(this).val(); 
          $.ajax({
            
              url:'get-list-products',        
              type:"GET",
            
            data:{'search':query},
            
            success:function (data) {
              
                $('#search_list').html(data);
            }
          })
          // end of ajax call
      });

             
      $(document).on('click', '#list-search', function(){
        
        
          var value = $(this).text();
          var id=$(this).attr("data-searchid")
          if(id != 0){
          $('#search-qr').val(value);
          $('#searched_qr_id').val(id);
          }
          
          $('#search_list').html("");
      });
});

</script>

@endpush
