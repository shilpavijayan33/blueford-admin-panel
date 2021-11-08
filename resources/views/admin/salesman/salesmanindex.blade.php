@extends('layouts.admin')
@push('styles')
<style type="text/css">

.UrlInput { position: relative; }
.UrlInput label { position: absolute; left: 11px; top: 5px; color: #999; }
.UrlInput input {  left: 0; top: 0; padding-left:40px; }
 .nav-tabs.flex-column .nav-link.active {
   
    background-color: #d8e1ea;
}

b, strong {
    font-weight: bolder;
    font-family: math;
}

.table thead th{
    font-family: math;
  
}

.red{
  color: red;
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
    <section class="content">
     <div class="card card-primary card-tabs">
           
              <div class="card-body">
              
                    <div class="card card-primary">
                      <div class="card-body">


                          <div class="form-group">
                          <div class="row">
                             <div class="col-xs-3">
                                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-search">
                                <i class="fa fa-filter"></i>
                               </button>

                                     <!-- Modal -->
                                <div class="modal fade" id="modal-search" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                  <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Filter</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                        </button>
                                      </div>
                                      <form action="{{url('admin/salesman-management')}}" method="post">
                                        @csrf
                                      <div class="modal-body">
                                   
                                        <div class="form-group">
                                          <div class="row"> 
                                            <div class="col-sm-8 offset-2">
                                            <input type="text" name="dealer" placeholder="Dealer" class="form-control">
                                          </div>
                                          </div>
                                        </div>

                                            <div class="form-group">
                                          <div class="row"> 
                                            <div class="col-sm-8 offset-2">
                                            <input type="number" name="mobile" placeholder="Phone"  class="form-control">
                                          </div>
                                          </div>
                                        </div>
                              
                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-info">Search</button>
                                      </div>
                                      </form>
                                    </div>
                                  </div>
                                </div>
                              </div>
                                     <div class="col-xs-1">
                               <a class="btn btn-light" href="{{url('admin/salesman-management')}}" title="Reset"><i class="fa fa-undo" style="color: blue;"></i>
                               </a>
                               
                             </div>
                            </div>
                          </div>


                        <div class="row">
                         @if(count($salesmans) > 0)                       
                          <div class="col-sm-3">
                            <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
                              @foreach($salesmans as $salesman)
                                <a class="nav-link {{ $loop->first ? 'active' : ''}}" id="vert-tabs-{{$salesman->useid}}-tab" data-toggle="pill" href="#vert-tabs-{{$salesman->useid}}" role="tab" aria-controls="vert-tabs-{{$salesman->useid}}" aria-selected="{{ $loop->first ? 'true' : 'false'}}">{{$salesman->name}}</a>                         
                              @endforeach                            
                            </div>
                          </div>
                          <div class="col-sm-9">
                            <div class="tab-content" id="vert-tabs-tabContent">
                              @foreach($salesmans as $salesman)
                                <div class="tab-pane {{ $loop->first ? 'text-left fade active show' : 'fade'}}" id="vert-tabs-{{$salesman->useid}}" role="tabpanel" aria-labelledby="vert-tabs-{{$salesman->useid}}-tab"> 
                                   @php
                                  $then_date = date('Y-m-d H:i:s',strtotime("-30 day"));
                                  $total_points=App\Transactions::where('user_id',$salesman->useid)->sum('amount');
                                  $redeemable_points=App\Transactions::where('user_id',$salesman->useid)
                                                                      ->where('status','!=','returned')
                                                                      ->where('status','!=','redeemed')
                                                                      ->where('created_at','<',$then_date)
                                                                      ->sum('amount');
                               
                                  $redeem=App\RedeemList::where('user_id',$salesman->useid)
                                                        ->where('status','released')
                                                        ->get();
                                  @endphp
                                  <div class="form-group">
                                    <div class="row">
                                      <div class="col-sm-5">
                                       <strong> Total Points</strong>
                                        <br>
                                        <h3><b>{{$total_points}}</b></h3>
                                      </div> 
                                    <div class="col-sm-5">  
                                       <strong> Redeemable Points</strong>
                                       <br>
                                       <h3> <b>{{$redeemable_points}}</b></h3>
                                    </div>

                                     @if($total_points > 0)

                                     <div class="col-sm-2"> 
                                      <a href="{{url('admin/transaction-details/')}}/{{$salesman->useid}}" class="btn btn-light" target="_blank" style="float: right;"><i class="fas fa-money-bill"></i><strong> Transactions</a></strong>
                                     </div>
                                     @endif
                                   </div>
                                 </div>
                                <hr>

                                 <div class="form-group">
                                    <div class="row">
                                      <div class="col-md-4">
                                        <strong>Name</strong>
                                        <br>
                                      {{$salesman->name}}
                                      </div> 
                                   
                                   </div>
                                 </div>

                                   <div class="form-group">
                                    <div class="row">
                                      <div class="col-md-4">
                                        <strong>Address</strong>
                                        <br>
                                      {{$salesman->address}}
                                      </div> 
                                  
                                   </div>
                                 </div>

                                   <div class="form-group">
                                    <div class="row">
                                      <div class="col-md-4">
                                       <strong> District</strong>
                                        <br>
                                     {{$salesman->district}}
                                      </div> 
                                   
                                   </div>
                                 </div>

                                    <div class="form-group">
                                    <div class="row">
                                      <div class="col-md-4">
                                        <strong>State</strong>
                                        <br>
                                      {{$salesman->state}}
                                      </div> 
                                  
                                   </div>
                                 </div>

                               

                                 <div class="form-group">
                                    <div class="row">
                                      <div class="col-md-4">
                                        <strong>Mobile</strong>
                                        <br>
                                      +91 {{$salesman->mobile}}
                                      </div> 
                                
                                   </div>
                                 </div>

                              

                                 <div class="form-group">
                                    <div class="row">
                                      <div class="col-md-8">
                                       <strong> Email</strong>
                                        <br>
                                      {{$salesman->email}}
                                      </div> 

                                       <div class="col-md-4"> 
                                       @if($salesman->status == 'active') 
                                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal-delete{{$salesman->useid}}">
                                      Suspend
                                     </button>
                                     @else
                                     <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-activate{{$salesman->useid}}">
                                    Activate
                                     </button>
                                     @endif
                                      <!-- Modal -->
                                      <div class="modal fade" id="modal-delete{{$salesman->useid}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document" style="margin: 178px auto;">
                                          <div class="modal-content">
                                            <div class="modal-header">
                                              <h5 class="modal-title" id="exampleModalLabel">Suspend</h5>
                                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                              </button>
                                            </div>
                                            <div class="modal-body">
                                    Are you sure you want to Suspend this salesman {{$salesman->name}}??
                                            </div>
                                            <div class="modal-footer">
                                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                              <a href="{{url('admin/suspend-salesman/')}}/{{$salesman->useid}}" class="btn btn-danger">suspend</a>
                                            </div>
                                          </div>
                                        </div>
                                      </div>


                                               <!-- Modal -->
                                               <div class="modal fade" id="modal-activate{{$salesman->useid}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document" style="margin: 178px auto;">
                                          <div class="modal-content">
                                            <div class="modal-header">
                                              <h5 class="modal-title" id="exampleModalLabel">Activate</h5>
                                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                              </button>
                                            </div>
                                            <div class="modal-body">
                                    Are you sure you want to activate this salesman {{$salesman->name}}??
                                            </div>
                                            <div class="modal-footer">
                                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                              <a href="{{url('admin/suspend-salesman/')}}/{{$salesman->useid}}" class="btn btn-danger">Activate</a>
                                            </div>
                                          </div>
                                        </div>
                                      </div>


                                      <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-editsales{{$salesman->id}}" title="Edit"><i class="fa fa-pen"></i></button>


                                      <div class="modal fade" id="modal-editsales{{$salesman->id}}" role="dialog">
                                      <div class="modal-dialog">
                                        <div class="modal-content">
                                        <div class="modal-header">
                                          <h4 class="modal-title">Edit SalesPerson</h4>
                                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                  <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                          <div class="modal-body">
                                          <form method="post" action="{{url('admin/edit-salesman')}}" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="salesperson" value="{{$salesman->id}}">
                                          <div class="form-group">
                                            <div class="row">
                                              <div class="col-sm-6 offset-3">
                                                <strong>Name</strong>
                                                <br>
                                                <input type="text" name="name" placeholder="Name" value="{{$salesman->name}}" class="form-control" required>
                                              </div>

                                              
                                            </div>
                                          </div>

                                          <div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-6 offset-3">
                                                <strong>Mobile</strong>
                                                  <br>
                                                  <div class="UrlInput">
                                                <label>+91  </label>
                                                <input name="mobile" type="number" class="form-control" placeholder="Mobile" value="{{$salesman->mobile}}" id="mobile" required />
                                              </div>
                                              </div>
                                            
                                            </div>
                                          </div>

                                          <div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-6 offset-3">
                                                  <strong>Email</strong>
                                                <br>
                                                  <input name="email" type="email" value="{{$salesman->email}}" class="form-control" placeholder="Email" id="email" required />
                                                </div>
                                              </div>
                                          </div>


                                          <div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-6 offset-3">
                                                  <strong>Address</strong>
                                                  <br>
                                                  <textarea name="address" class="form-control" value="{{$salesman->address}}" placeholder="Address"required>{{$salesman->address}}</textarea>
                                              </div>
                                            </div>
                                          </div>

                                          <div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-6 offset-3">
                                                  <strong>District</strong>
                                                  <br>
                                                  <input type="text" name="district" placeholder="District" value="{{$salesman->district}}"  class="form-control" required>
                                                </div>

                                              
                                              </div>
                                          </div>

                                          <div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-6 offset-3">
                                                  <strong>State</strong>
                                                <br>
                                                  <input type="text" name="state" placeholder="State" value="{{$salesman->state}}" class="form-control" required>
                                                </div>

                                              
                                              </div>
                                          </div>


                                          <div class="form-group">
                                          <div class="row">
                                            <div class="col-sm-6 offset-3">
                                            <strong>Change Dealer</strong>
                                                      <br>
                                          <select class="form-control" id="sponsor" name="sponsor" required>
                                              <option value="{{$salesman->sponsor}}">{{App\User::find($salesman->sponsor)->name}}</option>
                                              @foreach($list_sponsor as $key=> $each)
                                              @if($salesman->sponsor != $key)
                                              <option value="{{$key}}">{{$each}}</option>
                                              @endif
                                              @endforeach    
                                              </select>        
                                            </div> 
                                      
                                        </div>
                                       </div>

                                          
                                          

                                          </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                          <button type="submit" class="btn btn-primary">Save</button>
                                      </div>
                                      </form>
                                      </div>
                                      <!-- /.modal-content -->
                                      </div>
                                      <!-- /.modal-dialog -->
                                      </div>
                                      <!-- /.modal --> 
           





                                      </div>
                                  
                                     </div>
                                   </div>
                           <br><br>
                                 
                                        @if(count($redeem) > 0)

                                        <strong>Redeemed History</strong>

                                        <table class="table table-bordered">
                                          <thead>                  
                                            <tr>
                                             <th>#</th>
                                             <th>Date</th>
                                             <th>Serial No.</th>
                                             <th>Redeem Amount</th>
                                            </tr>
                                         </thead>
                                         <tbody>
                                          @foreach($redeem as $key=>$redeem)
                                           <tr>
                                             <td>{{$key+1}}</td>
                                             <td>
                                               {{date('d-m-y', strtotime($redeem->updated_at))}}
                                              </td>
                                              <td>{{$redeem->serial_number}}</td>
                                              <td>{{$redeem->requested_amount}}</td>
                                           </tr>
                                           @endforeach
                  
                                          </tbody>
                                       </table>
                                      
                                       @endif
                              </div>                           
                              @endforeach                            
                            </div>
                          </div>
                          {{$salesmans->links()}}
                        @else
                        <p>No salesmans</p>
                        @endif
                        </div>  
                      </div>
                    </div>
 
                  </div>
                 

         
                </div>
             
    </section>
    <!-- /.content -->
@endsection
@push('head')
<script>

$(document).ready(function(){
    $( "#email" ).blur(function() {
       var email = $(this).val();
        $.get( "{{url('admin/email_validate')}}/"+email, function(response) {
          if (response.data == 'na') {
              $('#email').next(".red").remove();
              $('#email').after('<div class="red">Email already in use</div>');
          } 
          else {
              $('#email').next(".red").remove();
              return true;  
          }
        });
    });

     $( "#mobile" ).blur(function() {
       var mobile = $(this).val();
        if(mobile.length > 0){

          if(mobile.length != 10){
             $('#mobile').next(".red").remove();
                  $('#mobile').after('<div class="red">Mobile must be 10 digits</div>');
          }

          else{
              $.get( "{{url('admin/mobile_validate')}}/"+mobile, function(response) {
                if (response.data == 'na') {
                  $('#mobile').next(".red").remove();
                  $('#mobile').after('<div class="red">Mobile already in use</div>');
                } 
                  else {
              $('#mobile').next(".red").remove();
              return true;  
          }
              
              });
          }
          }
       
              else {
              $('#mobile').next(".red").remove();
              return true;  
          }
          
      
    });
});
</script>
@endpush
