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
              <div class="card-header p-0 pt-1">
                <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                  <li class="nav-item col-lg-6">
                    <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">View All Dealers</a>
                  </li>
                
                    <li class="nav-item col-lg-6">
                    <a class="nav-link" id="custom-tabs-one-reg-tab" data-toggle="pill" href="#custom-tabs-one-reg" role="tab" aria-controls="custom-tabs-one-reg" aria-selected="false">Dealer Registration</a>
                  </li>
             
                </ul>
              </div>
              <div class="card-body">
                <div class="tab-content" id="custom-tabs-one-tabContent">
                  <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
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
                                      <form action="{{url('admin/dealer-management')}}" method="post">
                                        @csrf
                                      <div class="modal-body">
                                        <div class="form-group">
                                          <div class="row"> 
                                          <div class="col-sm-8 offset-2">
                                            <input type="text" name="district" placeholder="District" class="form-control">
                                          </div>
                                          </div>
                                        </div>
                                        <div class="form-group">
                                          <div class="row"> 
                                            <div class="col-sm-8 offset-2">
                                            <input type="text" name="state" placeholder="State" class="form-control">
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
                               <a class="btn btn-light" href="{{url('admin/dealer-management')}}" title="Reset"><i class="fa fa-undo" style="color: blue;"></i>
                               </a>
                               
                             </div>
                            </div>
                          </div>



                        <div class="row">
                         @if(count($dealers) > 0)                       
                          <div class="col-sm-3">
                            <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
                              @foreach($dealers as $dealer)
                                <a class="nav-link {{ $loop->first ? 'active' : ''}}" id="vert-tabs-{{$dealer->useid}}-tab" data-toggle="pill" href="#vert-tabs-{{$dealer->useid}}" role="tab" aria-controls="vert-tabs-{{$dealer->useid}}" aria-selected="{{ $loop->first ? 'true' : 'false'}}">{{$dealer->name}}</a>                         
                              @endforeach                            
                            </div>
                          </div>
                          <div class="col-sm-9">
                            <div class="tab-content" id="vert-tabs-tabContent">
                              @foreach($dealers as $dealer)
                                <div class="tab-pane {{ $loop->first ? 'text-left fade active show' : 'fade'}}" id="vert-tabs-{{$dealer->useid}}" role="tabpanel" aria-labelledby="vert-tabs-{{$dealer->useid}}-tab"> 
                                  @php
                                  $then_date = date('Y-m-d H:i:s',strtotime("-30 day"));
                                  $total_points=App\Transactions::where('user_id',$dealer->useid)->sum('amount');
                                  $redeemable_points=App\Transactions::where('user_id',$dealer->useid)
                                                                      ->where('status','!=','returned')
                                                                      ->where('status','!=','redeemed')
                                                                      ->where('created_at','<',$then_date)
                                                                      ->sum('amount');
                                  $sales_persons=App\User::join('user_details','users.id','=','user_details.user_id')->where('users.sponsor',$dealer->useid)
                                  ->select('users.*','user_details.address','user_details.state','user_details.district')
                                  ->take(5)->get();
                                  $redeem=App\RedeemList::where('user_id',$dealer->useid)
                                                        ->where('status','released')
                                                        ->get();
                                  @endphp
                                  <div class="form-group">
                                    <div class="row">
                                      <div class="col-sm-6">
                                        <strong>Total Points</strong>
                                        <br>
                                        <h3><b>{{$total_points}}</b></h3>
                                      </div> 
                                    <div class="col-sm-4">  
                                       <strong> Redeemable Points</strong>
                                       <br>
                                       <h3> <b>{{$redeemable_points}}</b></h3>
                                    </div>

                                    <!-- @if($total_points > 0) -->

                                     <div class="col-sm-2"> 
                                      <a href="{{url('admin/transaction-details/')}}/{{$dealer->useid}}" class="btn btn-light" target="_blank" style="float: right;"><i class="fas fa-money-bill"></i><br><strong> Transactions</strong></a>
                                     </div>
                                     <!-- @endif -->
                                   </div>
                                 </div>
                                <hr>

                                 <div class="form-group">
                                    <div class="row">
                                      <div class="col-md-6">
                                        <strong>Name</strong>
                                        <br>
                                      {{$dealer->name}}
                                      </div> 
                                    <div class="col-md-6">  
                                        <strong>Shop Name</strong>
                                       <br>
                                       {{$dealer->shop_name}}
                                    </div>
                                   </div>
                                 </div>

                                   <div class="form-group">
                                    <div class="row">
                                      <div class="col-md-6">
                                        <strong>Address</strong>
                                        <br>
                                      {{$dealer->address}}
                                      </div> 
                                    <div class="col-md-6">  
                                        <strong>Shop Address</strong>
                                       <br>
                                       {{$dealer->shop_address}}
                                    </div>
                                   </div>
                                 </div>

                                   <div class="form-group">
                                    <div class="row">
                                      <div class="col-md-6">
                                       <strong> District</strong>
                                        <br>
                                      {{$dealer->district}}
                                      </div> 
                                    <div class="col-md-6">  
                                        <strong>Shop District</strong>
                                       <br>
                                        {{$dealer->shop_district}}
                                    </div>
                                   </div>
                                 </div>

                                    <div class="form-group">
                                    <div class="row">
                                      <div class="col-md-6">
                                       <strong> State</strong>
                                        <br>
                                      {{$dealer->state}}
                                      </div> 
                                    <div class="col-md-6">  
                                       <strong> Shop State</strong>
                                       <br>
                                       {{$dealer->shop_state}}
                                    </div>
                                   </div>
                                 </div>

                                 

                                 <div class="form-group">
                                    <div class="row">
                                      <div class="col-md-6">
                                       <strong> Mobile</strong>
                                        <br>
                                     +91 {{$dealer->mobile}}
                                      </div>

                                       <div class="col-md-4">
                                        <strong> Email</strong>
                                        <br>
                                      {{$dealer->email}}
                                      </div>  

                                      
                                
                                   </div>
                                 </div>

                                 <div class="form-group">
                                    <div class="row">
                                     

                                      <div class="col-sm-2 offset-4">
                                         <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#modal-edit{{$dealer->useid}}">Edit</button>


                                      <div class="modal fade" id="modal-edit{{$dealer->useid}}" role="dialog">
                                      <div class="modal-dialog">
                                        <div class="modal-content">
                                         <div class="modal-header">
                                           <h4 class="modal-title">Edit Dealer</h4>
                                               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                  <span aria-hidden="true">&times;</span>
                                                </button>
                                             </div>
                                          <div class="modal-body">
                                          <form method="post" action="{{url('admin/edit-dealer')}}" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="dealer" value="{{$dealer->useid}}">
                                          <div class="form-group">
                                             <div class="row">
                                               <div class="col-sm-6">
                                                <strong>Name</strong>
                                                <br>
                                                <input type="text" name="name" placeholder="Name" value="{{$dealer->name}}" class="form-control" required>
                                               </div>

                                                <div class="col-sm-6">
                                                <strong>Shop Name</strong>
                                                <br>
                                                <input type="text" name="shop_name" placeholder="Shop Name" value="{{$dealer->shop_name}}" class="form-control" required>
                                               </div>
                                             </div>
                                          </div>

                                          <div class="form-group">
                                             <div class="row">
                                                <div class="col-sm-6">
                                                  <strong>Address</strong>
                                                  <br>
                                                  <input type="text" name="address" placeholder="Address" value="{{$dealer->address}}" class="form-control" required>
                                                </div>

                                                <div class="col-sm-6">
                                                  <strong>Shop Address</strong>
                                                  <br>
                                                  <input type="text" name="shop_address" placeholder="Shop Address" value="{{$dealer->shop_address}}" class="form-control" required>
                                                </div>
                                              </div>
                                          </div>

                                           <div class="form-group">
                                             <div class="row">
                                                <div class="col-sm-6">
                                                  <strong>District</strong>
                                                   <br>
                                                  <input type="text" name="district" placeholder="District" value="{{$dealer->district}}" class="form-control" required>
                                                </div>

                                                 <div class="col-sm-6">
                                                  <strong>Shop District</strong>
                                                   <br>
                                                  <input type="text" name="shop_district" placeholder="Shop District" value="{{$dealer->shop_district}}" class="form-control" required>
                                                </div>
                                              </div>
                                          </div>

                                           <div class="form-group">
                                             <div class="row">
                                                <div class="col-sm-6">
                                                  <strong>State</strong>
                                                <br>
                                                  <input type="text" name="state" placeholder="State" value="{{$dealer->state}}" class="form-control" required>
                                                </div>

                                                 <div class="col-sm-6">
                                                  <strong>Shop State</strong>
                                                <br>
                                                  <input type="text" name="shop_state" placeholder="Shop State" value="{{$dealer->shop_state}}" class="form-control" required>
                                                </div>
                                              </div>
                                          </div>

                                           <div class="form-group">
                                             <div class="row">
                                                <div class="col-sm-6">
                                                 <strong>Mobile</strong>
                                                  <br>
                                                  <div class="UrlInput">
                                                 <label>+91  </label>
                                                 <input name="mobile" type="number" class="form-control" value="{{$dealer->mobile}}"placeholder="Mobile" id="mobile" required />
                                               </div>
                                               </div>
                                            
                                             </div>
                                           </div>

                                           <div class="form-group">
                                             <div class="row">
                                                <div class="col-sm-6">
                                                  <strong>Email</strong>
                                                <br>
                                                  <input name="email" type="email" value="{{$dealer->email}}" class="form-control" placeholder="Email" id="email" required />
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
                                      </div>

                                       <div class="col-sm-3">  
                                        @if($dealer->status == 'active')
                                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal-delete{{$dealer->useid}}">
                                      Suspend
                                     </button>
                                     @else
                                     <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-activate{{$dealer->useid}}">
                                      Activate
                                     </button>
                                     @endif
                                      <!-- Modal -->
                                      <div class="modal fade" id="modal-delete{{$dealer->useid}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document" style="margin: 178px auto;">
                                          <div class="modal-content">
                                            <div class="modal-header">
                                              <h5 class="modal-title" id="exampleModalLabel">Suspend</h5>
                                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                              </button>
                                            </div>
                                            <div class="modal-body">
                                    Are you sure you want to Suspend this dealer {{$dealer->name}}??
                                            </div>
                                            <div class="modal-footer">
                                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                              <a href="{{url('admin/suspend-dealer/')}}/{{$dealer->useid}}" class="btn btn-danger">Suspend</a>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    


                                               <!-- Modal -->
                                               <div class="modal fade" id="modal-activate{{$dealer->useid}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document" style="margin: 178px auto;">
                                          <div class="modal-content">
                                            <div class="modal-header">
                                              <h5 class="modal-title" id="exampleModalLabel">Activate</h5>
                                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                              </button>
                                            </div>
                                            <div class="modal-body">
                                    Are you sure you want to activate this dealer {{$dealer->name}}??
                                            </div>
                                            <div class="modal-footer">
                                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                              <a href="{{url('admin/suspend-dealer/')}}/{{$dealer->useid}}" class="btn btn-danger">Activate</a>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                      </div>



                                      <div class="col-sm-1">
                                       

                                     <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-add{{$dealer->useid}}" title="Add Sales Person"><i class="fa fa-plus"></i></button>


                                      <div class="modal fade" id="modal-add{{$dealer->useid}}" role="dialog">
                                      <div class="modal-dialog">
                                        <div class="modal-content">
                                         <div class="modal-header">
                                           <h4 class="modal-title">Add SalesPerson</h4>
                                               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                  <span aria-hidden="true">&times;</span>
                                                </button>
                                             </div>
                                          <div class="modal-body">
                                          <form method="post" action="{{url('admin/register-salesman')}}" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="dealer" value="{{$dealer->useid}}">
                                          <div class="form-group">
                                             <div class="row">
                                               <div class="col-sm-6 offset-3">
                                                <strong>Name</strong>
                                                <br>
                                                <input type="text" name="name" placeholder="Name" class="form-control" required>
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
                                                 <input name="mobile" type="number" class="form-control" placeholder="Mobile" id="mobile" required />
                                               </div>
                                               </div>
                                            
                                             </div>
                                           </div>

                                           <div class="form-group">
                                             <div class="row">
                                                <div class="col-sm-6 offset-3">
                                                  <strong>Email</strong>
                                                <br>
                                                  <input name="email" type="email" class="form-control" placeholder="Email" id="email" required />
                                                </div>
                                              </div>
                                          </div>


                                          <div class="form-group">
                                             <div class="row">
                                                <div class="col-sm-6 offset-3">
                                                  <strong>Address</strong>
                                                  <br>
                                                  <textarea name="address" class="form-control" placeholder="Address"required></textarea>
                                              </div>
                                            </div>
                                          </div>

                                           <div class="form-group">
                                             <div class="row">
                                                <div class="col-sm-6 offset-3">
                                                  <strong>District</strong>
                                                   <br>
                                                  <input type="text" name="district" placeholder="District"  class="form-control" required>
                                                </div>

                                               
                                              </div>
                                          </div>

                                           <div class="form-group">
                                             <div class="row">
                                                <div class="col-sm-6 offset-3">
                                                  <strong>State</strong>
                                                <br>
                                                  <input type="text" name="state" placeholder="State" class="form-control" required>
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

                                         @if(count($sales_persons) > 0)
                                      <strong>Sales People</strong>
                                       <table class="table table-bordered">
                                          <thead>                  
                                            <tr>
                                              <th>#</th>
                                              <th>Name</th>
                                              <th>Address</th>
                                              <th>Mobile No.</th>
                                              <!-- <th>Action</th> -->

                                            </tr>
                                          </thead>
                                          <tbody>
                                              @foreach($sales_persons as $key=>$sales)
                                            <tr>
                                               <td>{{$key+1}}</td>
                                               <td>{{$sales->name}}</td>
                                               <td>
                                                  {{$sales->address}}
                                               </td>
                                               <td>+91 {{$sales->mobile}}</td>
                                               <!-- <td>


                                    

                                               </td> -->
                                             </tr>
                                             @endforeach
                   
                                           </tbody>
                                       </table>
                                      
                                       @endif
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
                          {{$dealers->links()}}
                        @else
                        <p>No Dealers Available</p>
                        @endif
                        </div>  
                      </div>
                    </div>
 
                  </div>
                 

                  <div class="tab-pane fade" id="custom-tabs-one-reg" role="tabpanel" aria-labelledby="custom-tabs-one-reg-tab">
                    <form method="post" action="{{url('admin/save-dealer')}}" id="saveDealer" class="saveDealer">
                      @csrf
                    
                      <div class="row">
                        <div class="col-md-4 offset-1">
                          <div class="form-group">
                          <input type="text" name="name" class="form-control" placeholder="Name" required>
                        </div>
                        </div>
                        <div class="col-md-4 offset-1">
                          <div class="form-group">
                          <input type="text" name="shop_name" class="form-control" placeholder="Shop Name" required>
                        </div>
                        </div>
                        
                      </div>

                               <div class="row"> 
                          <div class="col-md-4 offset-1">
                               <div class="form-group UrlInput">
                              <label>+91  </label>
                              <input name="mobile" type="number" class="form-control" id="mobile" placeholder="Mobile" required />
                              </div>
                        </div>
                       
                
                    

                        <div class="col-md-4 offset-1">
                          <div class="form-group">
                          <input type="email" name="email" class="form-control" id="email" placeholder="Email" required>
                        </div>
                        </div>
                      </div>
                   
                    
                      <div class="row">
                        <div class="col-md-4 offset-1">
                          <div class="form-group">
                          <input type="text" name="address" class="form-control" placeholder="Address" required>
                        </div>
                        </div>
                        <div class="col-md-4 offset-1">
                          <div class="form-group">
                          <input type="text" name="shop_address" class="form-control" placeholder="Shop Address" required>
                        </div>
                        </div>
                        
                      </div>
                  

                   
                      <div class="row">
                        <div class="col-md-4 offset-1">
                          <div class="form-group">
                          <input type="text" name="district" class="form-control" placeholder="District" required>
                        </div>
                        </div>
                        <div class="col-md-4 offset-1">
                          <div class="form-group">
                          <input type="text" name="shop_district" class="form-control" placeholder="Shop District" required>
                        </div>
                        </div>
                        
                      </div>
                   

                      
                      <div class="row">
                        <div class="col-md-4 offset-1">
                          <div class="form-group">
                          <input type="text" name="state" class="form-control" placeholder="State" required>
                        </div>
                        </div>
                        <div class="col-md-4 offset-1">
                          <div class="form-group">
                          <input type="text" name="shop_state" class="form-control" placeholder="Shop State" required>
                        </div>
                        </div>
                        
                      </div>
                    

                     
             
                   

                   
                      <div class="row">
                        <div class="col-md-4 offset-6">
                          <div class="form-group">
                           <button class="btn btn-primary" type="submit" id="dealerbutton">Save</button>
                         </div>
                        </div>
                      </div>
                    </div>

                  </form>
                 
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
    $('a[data-toggle="pill"]').on('show.bs.tab', function(e) {
        localStorage.setItem('activeTab', $(e.target).attr('href'));
    });
    var activeTab = localStorage.getItem('activeTab');
    if(activeTab){
        $('#custom-tabs-one-tab a[href="' + activeTab + '"]').tab('show');
    }
});

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
