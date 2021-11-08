@extends('layouts.admin')
@push('styles')
<style type="text/css">
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
                    <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">View All Plumbers</a>
                  </li>
                
                    <li class="nav-item col-lg-6">
                    <a class="nav-link" id="custom-tabs-one-reg-tab" data-toggle="pill" href="#custom-tabs-one-reg" role="tab" aria-controls="custom-tabs-one-reg" aria-selected="false">Plumber Onboard Request</a>
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
                                      <form action="{{url('admin/plumber-management')}}" method="post">
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

                                            <div class="form-group">
                                          <div class="row"> 
                                            <div class="col-sm-8 offset-2">
                                            <input type="number" name="mobile" placeholder="Phone" class="form-control">
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
                               <a class="btn btn-light" href="{{url('admin/plumber-management')}}" title="Reset"><i class="fa fa-undo" style="color: blue;"></i>
                               </a>
                               
                             </div>
                             
                            </div>
                          </div>


                        <div class="row">
                         @if(count($plumbers) > 0)                       
                          <div class="col-sm-3">
                            <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
                              @foreach($plumbers as $plumber)
                                <a class="nav-link {{ $loop->first ? 'active' : ''}}" id="vert-tabs-{{$plumber->useid}}-tab" data-toggle="pill" href="#vert-tabs-{{$plumber->useid}}" role="tab" aria-controls="vert-tabs-{{$plumber->useid}}" aria-selected="{{ $loop->first ? 'true' : 'false'}}">{{$plumber->name}}</a>                         
                              @endforeach                            
                            </div>
                          </div>
                          <div class="col-sm-9">
                            <div class="tab-content" id="vert-tabs-tabContent">
                              @foreach($plumbers as $plumber)
                                <div class="tab-pane {{ $loop->first ? 'text-left fade active show' : 'fade'}}" id="vert-tabs-{{$plumber->useid}}" role="tabpanel" aria-labelledby="vert-tabs-{{$plumber->useid}}-tab"> 
                                  @php
                                  $then_date = date('Y-m-d H:i:s',strtotime("-30 day"));
                                  $total_points=App\Transactions::where('user_id',$plumber->useid)->sum('amount');
                                  $redeemable_points=App\Transactions::where('user_id',$plumber->useid)
                                                                      ->where('status','!=','returned')
                                                                      ->where('status','!=','redeemed')
                                                                      ->where('created_at','<',$then_date)
                                                                      ->sum('amount');
                               
                                  $redeem=App\RedeemList::where('user_id',$plumber->useid)
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
                                      <a href="{{url('admin/transaction-details/')}}/{{$plumber->useid}}" class="btn btn-light" target="_blank" style="float: right;"><i class="fas fa-money-bill"></i><br><strong> Transactions</strong></a>
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
                                      {{$plumber->name}}
                                      </div> 
                                   
                                   </div>
                                 </div>

                                   <div class="form-group">
                                    <div class="row">
                                      <div class="col-md-4">
                                       <strong>Address</strong>
                                        <br>
                                      {{$plumber->address}}
                                      </div> 
                                   
                                   </div>
                                 </div>

                                   <div class="form-group">
                                    <div class="row">
                                      <div class="col-md-4">
                                        <strong>District</strong>
                                        <br>
                                       {{$plumber->district}}
                                      </div> 
                                  
                                   </div>
                                 </div>

                                    <div class="form-group">
                                    <div class="row">
                                      <div class="col-md-4">
                                        <strong>State</strong>
                                        <br>
                                      {{$plumber->state}}
                                      </div> 
                                  
                                   </div>
                                 </div>

                                
                                 <div class="form-group">
                                    <div class="row">
                                      <div class="col-md-4">
                                       <strong> Mobile</strong>
                                        <br>
                                       +91 {{$plumber->mobile}}
                                      </div> 
                                
                                   </div>
                                 </div>

                                 <div class="form-group">
                                    <div class="row">
                                      <div class="col-md-8">
                                       <strong> Email</strong>
                                        <br>
                                      {{$plumber->email}}
                                      </div> 

                                       <div class="col-md-4">  
                                        @if($plumber->status == 'active')
                                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal-delete{{$plumber->useid}}">
                                         Suspend
                                        </button>
                                        @else
                                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-active{{$plumber->useid}}">
                                         Activate
                                        </button>
                                        @endif

                                      <!-- Modal -->
                                      <div class="modal fade" id="modal-delete{{$plumber->useid}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document" style="margin: 178px auto;">
                                          <div class="modal-content">
                                            <div class="modal-header">
                                              <h5 class="modal-title" id="exampleModalLabel">Suspend</h5>
                                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                              </button>
                                            </div>
                                            <div class="modal-body">
                                    Are you sure you want to Suspend this plumber {{$plumber->name}}??
                                            </div>
                                            <div class="modal-footer">
                                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                              <a href="{{url('admin/suspend-plumber/')}}/{{$plumber->useid}}" class="btn btn-danger">suspend</a>
                                            </div>
                                          </div>
                                        </div>
                                      </div>


                                            <!-- Modal -->
                                            <div class="modal fade" id="modal-active{{$plumber->useid}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document" style="margin: 178px auto;">
                                          <div class="modal-content">
                                            <div class="modal-header">
                                              <h5 class="modal-title" id="exampleModalLabel">Activate</h5>
                                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                              </button>
                                            </div>
                                            <div class="modal-body">
                                    Are you sure you want to activate this plumber {{$plumber->name}}??
                                            </div>
                                            <div class="modal-footer">
                                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                              <a href="{{url('admin/suspend-plumber/')}}/{{$plumber->useid}}" class="btn btn-danger">Activate</a>
                                            </div>
                                          </div>
                                        </div>
                                      </div>




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
                          {{$plumbers->links()}}
                        @else
                        <p>No plumbers</p>
                        @endif
                        </div>  
                      </div>
                    </div>
 
                  </div>
                 

                  <div class="tab-pane fade" id="custom-tabs-one-reg" role="tabpanel" aria-labelledby="custom-tabs-one-reg-tab">
                   <div class="row">
                    <div class="col-12">
                      <div class="card">
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                        @if(count($pendingplumbers) > 0)
                          <table class="table table-hover text-nowrap">
                           @foreach($pendingplumbers as $pendingplumber)
                            <tbody>
                              <tr>
                                <td>{{$pendingplumber->name}}</td>
                                <td>{{$pendingplumber->email}}</td>
                                <td>+91 {{$pendingplumber->mobile}}</td>
                                <td>
                                  <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-accept{{$pendingplumber->id}}">
                                                Accept
                                  </button>
                                      <!-- Modal -->
                                      <div class="modal fade" id="modal-accept{{$pendingplumber->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document" style="margin: 178px auto;">
                                          <div class="modal-content">
                                            <div class="modal-header">
                                              <h5 class="modal-title" id="exampleModalLabel">Accept</h5>
                                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                              </button>
                                            </div>
                                            <div class="modal-body">
                                            Are you sure you want to accept this plumber {{$pendingplumber->name}}??
                                            </div>
                                            <div class="modal-footer">
                                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                              <a href="{{url('admin/accept-plumber/')}}/{{$pendingplumber->id}}" class="btn btn-success">Accept</a>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                  <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal-reject{{$pendingplumber->id}}">
                                  Reject
                                  </button>                               

                                   <div class="modal fade" id="modal-reject{{$pendingplumber->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document" style="margin: 178px auto;">
                                          <div class="modal-content">
                                            <div class="modal-header">
                                              <h5 class="modal-title" id="exampleModalLabel">Reject</h5>
                                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                              </button>
                                            </div>
                                            <div class="modal-body">
                                    Are you sure you want to reject this plumber {{$pendingplumber->name}}??
                                            </div>
                                            <div class="modal-footer">
                                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                              <a href="{{url('admin/reject-plumber/')}}/{{$pendingplumber->id}}" class="btn btn-success">Reject</a>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                   </td>
                                  </tr>                   
                                </tbody>
                                @endforeach
                              </table>

                              {{$pendingplumbers->links()}}
                               @else
                            No Pending Requests
                            @endif
                            </div>                           
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
    $('a[data-toggle="pill"]').on('show.bs.tab', function(e) {
        localStorage.setItem('activeTab', $(e.target).attr('href'));
    });
    var activeTab = localStorage.getItem('activeTab');
    if(activeTab){
        $('#custom-tabs-one-tab a[href="' + activeTab + '"]').tab('show');
    }
});
</script>
@endpush
