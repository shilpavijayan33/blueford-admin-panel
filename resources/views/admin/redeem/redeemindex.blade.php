@extends('layouts.admin')
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
    <div class="card">
              
              <!-- /.card-header -->
              <div class="card-body">
                <div class="form-group">
                          <div class="row">
                             <div class="col-sm-9">
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
                                      <form action="{{url('admin/redeem-management')}}" method="post">
                                        @csrf
                                      <div class="modal-body">
                                        <div class="form-group">
                                          <div class="row">
                                            <div class="col-sm-8 offset-2">
                                              <select name="user" class="form-control">
                                             
                                              <option value="dealer">Dealer</option>
                                              <option value="salesman">Salesman</option>
                                              <option value="plumber">Plumber</option>

                                            </select>
                                            </div>
                                            
                                          </div>
                                          
                                        </div>
                                   
                                        <div class="form-group">
                                          <div class="row"> 
                                            <div class="col-sm-8 offset-2">
                                            <input type="number" name="mobile" placeholder="Mobile" class="form-control">
                                          </div>
                                          </div>
                                        </div>

                                            <div class="form-group">
                                          <div class="row"> 
                                            <div class="col-sm-8 offset-2">
                                            <input type="date" name="date" placeholder="Date" class="form-control">
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
                              <div class="col-sm-3">
                                <button class="btn btn-success" id="redeemAll" onclick="redeemAll()">Redeem All</button>
  
                                <button class="btn btn-danger" id="rejectAll" onclick="rejectAll()">Reject All</button>
                             
                            </div>

                            
                          </div>
                          <p>
                 
                <table class="table table-bordered data-table" id="myTable">
                    <thead>
                     <tr>
                      <!-- <th>Sl.no</th> -->
<th></th>
                      <th>Username</th>
                      <th>Type of user</th>
                      <th>Requested Amount</th>
                      <th>Redeemable Amount</th>
                      <th>Date</th>
                      <th>Accept</th>
                      <th>Reject</th>

                    </tr>
                  </thead>
                  
                  <tbody>
                    @foreach($datas as $key=> $dataus)
                    @php
                    $then_date = date('Y-m-d H:i:s',strtotime("-30 day"));
                    $am=App\Transactions::where('user_id',$dataus->user_id)
                              ->where('status','!=','returned')
                               ->where('status','!=','redeemed')
                                ->where('created_at','<',$then_date)
                               ->sum('amount');

                    @endphp
                    <tr id="{{$dataus->id}}">
                      <td class="checkbx">
                        @if($dataus->status == 'pending')
                        <input type="checkbox" name="redeem" value="{{$dataus->id}}">
                        @else
                        <input type="checkbox" disabled>

                        @endif
                      </td>
                      <!-- <td>{{$loop->iteration}}</td> -->
                  
                    <td>{{$dataus->name}}</td>
                    <td>{{ucfirst($dataus->user_type)}}</td>
                    <td>{{$dataus->requested_amount}}</td>
                    <td class="amount{{$dataus->user_id}}">{{$am}}</td>
                    <td>{{date('d/m/y',strtotime($dataus->created_at))}}</td>
                   
                    <td class="redeem">
                      @if($dataus->status == 'pending')
                      <button type="button" class="btn btn-success" onclick="acceptRedeem({{$dataus->id}})">
                                <i class="fa fa-check"></i>
                      </button> 
                 
                      @elseif($dataus->status == 'released')
                      <span class="badge badge-success">Redeemed</span>
                      @else
                      <span class="badge badge-danger">Rejected</span>

                      @endif
                    </td> 

                    <td class="reject">
                    @if($dataus->status == 'pending')
                    <button type="button" class="btn btn-danger" onclick="rejectRedeem({{$dataus->id}})">
                              <i class="fa fa-times"></i>
                    </button>
                            
                    @elseif($dataus->status == 'released')
                    <span class="badge badge-success">Redeemed</span>
                    @else
                   
                    <span class="badge badge-danger">Rejected</span>
                    @endif
                    </td>
                    </tr>
                    @endforeach
                 </tbody>
                </table>
                <br>
       
              <!-- /.card-body -->
            </div>
    </section>
    <!-- /.content -->
@endsection
@push('head')
<script type="text/javascript">

  $.fn.DataTable.ext.pager.numbers_length = 5;
  
   $(document).ready( function () {
    $('#myTable').DataTable({

             aaSorting: [[5, 'desc']],

      pagingType: 'full_numbers',
     
    });
} );

function acceptRedeem(id){ 
        

        swal({
        title: "Accept?",
        text: "Are you sure you want to accept this redeem?",
        type: "success",
        showCancelButton: !0,
        confirmButtonText: "Yes, accept the redeem!",
        cancelButtonText: "No, cancel!",
        reverseButtons: !0
    }).then(function (e) {

    if (e.value === true) {
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            type: 'POST',
            url: "/admin/accept-redeem",
            data: {_token: CSRF_TOKEN,redeem_id:id},
            dataType: 'JSON',
            success: function (results) {     

            $('#myTable #'+id+' .redeem').html('<span class="badge badge-success">Redeemed</span>');
            $('#myTable #'+id+' .reject').html('<span class="badge badge-success">Redeemed</span>');
            $('#myTable  .amount'+results.user).html(results.amount);
            $('#myTable #'+id+' .checkbx').html('<input type="checkbox" disabled>');


            
                 
                    swal({
                            title: "Done!",
                            text: "Reedem accepted successfully !!",
                            type: "success",
                            // allowOutsideClick: false
                          
                        }).then(function (e) {                            
                            
                            
                        })              
            }
        });

    } else {
        e.dismiss;            
        swal("Cancelled", "Redeem request cancelled :)", "error");
                
    }

}, function (dismiss) {
    return false;
})

}


function rejectRedeem(id){ 
        

        swal({
        title: "Reject?",
        text: "Are you sure you want to reject this redeem?",
        type: "error",
        showCancelButton: !0,
        confirmButtonText: "Yes, reject the redeem!",
        cancelButtonText: "No, cancel!",
        reverseButtons: !0
    }).then(function (e) {

    if (e.value === true) {
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            type: 'POST',
            url: "/admin/reject-redeem",
            data: {_token: CSRF_TOKEN,redeem_id:id},
            dataType: 'JSON',
            success: function (results) {     

            $('#myTable #'+id+' .redeem').html('<span class="badge badge-danger">Rejected</span>');
            $('#myTable #'+id+' .reject').html('<span class="badge badge-danger">Rejected</span>');
            $('#myTable #'+id+' .checkbx').html('<input type="checkbox" disabled>');

            
                 
                    swal({
                            title: "Done!",
                            text: "Reedem rejected successfully !!",
                            type: "success",
                            // allowOutsideClick: false
                          
                        }).then(function (e) {                            
                            
                            
                        })              
            }
        });

    } else {
        e.dismiss;            
        swal("Cancelled", "Redeem rejection cancelled :)", "error");
                
    }

}, function (dismiss) {
    return false;
})

}

function redeemAll(){
      var favorite = [];
      var table = $('#myTable').DataTable();
      table.rows().nodes().to$().find('input[name="redeem"]:checked').each(function(){     
          favorite.push($(this).val());
      });
      
      if(favorite.length == 0){
        swal("Warning", "Please select atleast one checkbox", "error");

      }
      else{


            swal({
            title: "Accept?",
            text: "Are you sure you want to accept this redeem?",
            type: "success",
            showCancelButton: !0,
            confirmButtonText: "Yes, accept the redeem!",
            cancelButtonText: "No, cancel!",
            reverseButtons: !0
        }).then(function (e) {

        if (e.value === true) {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                type: 'POST',
                url: "/admin/accept-allredeem",
                data: {_token: CSRF_TOKEN,redeem_ids:favorite},
                dataType: 'JSON',
                success: function (results) {     
                    
                        swal({
                                title: "Done!",
                                text: "Reedem accepted successfully !!",
                                type: "success",
                                // allowOutsideClick: false
                              
                            }).then(function (e) {                            
                                
                                location.reload();
                            })              
                }
            });

        } else {
            e.dismiss;            
            swal("Cancelled", "Redeem request cancelled :)", "error");
                    
        }

    }, function (dismiss) {
        return false;
    })


      }
     
}

function rejectAll(){
      var favorite = [];
      var table = $('#myTable').DataTable();
      table.rows().nodes().to$().find('input[name="redeem"]:checked').each(function(){     
          favorite.push($(this).val());
      });
      
      if(favorite.length == 0){
        swal("Warning", "Please select atleast one checkbox", "error");

      }

      else{


                    swal({
                    title: "Reject?",
                    text: "Are you sure you want to reject this redeem?",
                    type: "error",
                    showCancelButton: !0,
                    confirmButtonText: "Yes, reject the redeem!",
                    cancelButtonText: "No, cancel!",
                    reverseButtons: !0
                }).then(function (e) {

                if (e.value === true) {
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        type: 'POST',
                        url: "/admin/reject-allredeem",
                        data: {_token: CSRF_TOKEN,redeem_ids:favorite},
                        dataType: 'JSON',
                        success: function (results) {    
                       
                                swal({
                                        title: "Done!",
                                        text: "Reedem rejected successfully !!",
                                        type: "success",
                                        // allowOutsideClick: false
                                      
                                }).then(function (e) {                            
                                        
                                        location.reload();
                                })              
                        }
                    });

                } else {
                    e.dismiss;            
                    swal("Cancelled", "Redeem rejection cancelled :)", "error");
                    location.reload();

                            
                }

            }, function (dismiss) {
                return false;
            })


      }
     
}

</script>
@endpush
