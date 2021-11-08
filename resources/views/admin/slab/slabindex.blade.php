
@extends('layouts.admin')
@push('styles')
<style type="text/css">

#load{
    width:100%;
    height:100%;
    position:fixed;
    z-index:9999;
    margin-top: -140px;

    background:url({{asset('assets/uploads/spiner-black.gif')}}) no-repeat center;

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
    <div class="col-md-10" id="load"></div>

    <!-- Main content -->
    <section class="content">
     <div class="card card-primary">
              <div class="card-header">
               Slab
              </div>
              <div class="card-body">
                <div class="tab-content" id="custom-tabs-one-tabContent">
                  <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                   
                    <div class="card card-primary">
                       <!-- form start -->
                    <div class="row">
                      <div class="col-12">
                        
                          <!-- /.card-header -->
                          
                          <div class="card-body" style="overflow: auto;">
                            <form method="post" action="{{url('admin/edit-slab')}}">
                              @csrf
                           <div class="form-group">
                            <div class="row">
                              <div class="col-sm-4">
                                Choose Product
                              </div>
                              <div class="col-sm-4">
                                <select name="product" id="product" class="form-control selectPrd">
                                 
                                  @foreach($products as $key=> $product)
                                  <option value="{{$key}}" {{$key==request()->route()->id ? 'selected' : ''}}>{{$product}}</option>
                                  @endforeach
                                </select>                              
                            </div>                             
                           </div>                          
                          </div>
                          @foreach($dealer_slabs as $key=> $dealer_slab)
                            <div class="form-group" id="AlreadyRow">
                            <div class="row">
                              <div class="col-sm-4">
                                Range {{$key+1}}
                              </div>
                              <div class="col-sm-2">
                               <input type="number" name="range[start][]" value="{{$dealer_slab->start}}" class="form-control" required>
                               </div>  - 
                              <div class="col-sm-2">
                               <input type="number" name="range[end][]" value="{{$dealer_slab->end}}" class="form-control" required>
                               </div>  
                            
                            <div class="col-sm-2">
                                  <input type="number" name="range[value][]" value="{{$dealer_slab->value}}" class="form-control" required>                    
                            </div> 
                            @if($key > 0) 
                          <div class="input-group-append">
                           <i id="AlreadyremovRow" class="fa fa-trash" style="cursor:pointer;color:red;"></i>
                           </div>
                           @endif
      
                                  
                           </div>                          
                          </div>
                          @endforeach

                      <div id="newRow"></div>
                     <div class="col-md-3 offset-9">   
                    <div class="form-group"> 
                     <button class="btn btn-transparent font-weight-bold  pl-0" type="button" id="addRow">
                      Add more range <i class="lnr-plus-circle icon-gradient bg-premium-dark mt-1"></i></button>
                    </div>
                  </div>

                           <div class="form-group">
                            <div class="row">
                              <div class="col-sm-4">
                                Salesman
                              </div>
                             
                            <div class="col-sm-4">
                                <input type="number" name="salesman" value="{{$salesman_slabs->value}}" class="form-control" required>
                                                      
                            </div>                              
                           </div>                          
                          </div>

                                <div class="form-group">
                            <div class="row">
                              <div class="col-sm-4">
                                Plumber
                              </div>
                             
                            <div class="col-sm-4">
                                                    
                                <input type="number" name="plumber" value="{{$plumber_slabs->value}}" class="form-control" required>
                            </div>                              
                           </div>                          
                          </div>

                          <div class="form-group">
                            <div class="row">
                              <div class="col-sm-4">
                                <button type="submit" class="btn btn-primary">Update</button>
                              </div>

                            </div>
                          </div>
                          
                          </form>
                          <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
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


$(document).ready(function(){
       document.getElementById('load').style.visibility="hidden";
});
$(document).ready(function(){

$(".selectPrd").on("change",function(){

    location.href= $(this).val();
    
});
});

$(document).ready(function(){
     $("#addRow").click(function () {
        var html = '';
        html += '<div class="row" id="inputFormRow">';
        html += '<div class="col-md-2 offset-4">';
        html += '<div class="form-group">';
        html += '<input type="number" class="form-control" id="start" name="range[start][]" placeholder="Start" min="1" required>';
        html += '</div>';
        html += '</div>';

        html += '<div class="col-md-2">';
        html += '<div class="form-group">';
        html += '<input type="number" class="form-control" id="end" name="range[end][]" placeholder="End" min="1" required>';
        html += '</div>';
        html += '</div>';

          html += '<div class="col-md-2">';
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

    $(document).on('click', '#AlreadyremovRow', function () {
      $(this).closest('#AlreadyRow').remove();
  });


  


$(document).ready(function(){

  $("#myform").on("submit", function(){
         document.getElementById('load').style.visibility="visible";
   
  });//submit
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


$.fn.DataTable.ext.pager.numbers_length = 5;
  
   $(document).ready( function () {
    $('#myTable').DataTable({

      pagingType: 'full_numbers',
     
    });
} );
</script>

@endpush
