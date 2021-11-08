@extends('layouts.printdefault')
@push('styles')
<style type="text/css">



  @page { 
     size: auto;   /* auto is the initial value */
       
    margin: 50px;
     

     }
    body { border: 5px black; }



 
</style>

<style media="print">
 @page {
  size: auto;
  margin: 0;
       }
</style>
@endpush

@section('content')
@include('errors.success')
@include('errors.list')

    <!-- Main content -->
    <section class="content">

      <div class="invoice p-3 mb-3">
              <!-- title row -->
              <div class="row" style="float: right;">
                <div class="col-12">
                  <h4>
                    <img src="{{ asset('dist/img/')}}/{{$app->logo}}" alt="Logo" class="brand-image img-circle elevation-3" style="height: 50px;"><br> <h5 style="margin-left: -7px;">{{$app->name}}</h5>
                    
                    <h6 style="margin-left: -1px;">{{date('d/m/y')}}</h6>
                  </h4>
                </div>
                <!-- /.col -->
              </div>
              <!-- info row -->
              <br>
              <div class="row invoice-info">
                <div class="col-sm-8 invoice-col">
                  From
                  <br>
                   <address style="margin-left: 30px;">
                    <strong>Admin</strong><br>
                    {{$app->address}}<br>
                    
                   Phone: +91 {{$app->mobile}}<br>
                   Email: {{$app->email}}
                  </address>
                 
                </div>
                <!-- /.col -->
                
                <!-- /.col -->
              
                <!-- /.col -->
              </div>
              <!-- /.row -->

              <div class="row invoice-info">
                <div class="col-sm-4 invoice-col">
                  To<br>
                 

                  
                  <address style="margin-left: 30px;">
                    <strong>{{$user->name}}</strong><br>
                   {{$user_details->address}}<br>
                   Phone: +91 {{$user->mobile}}<br>
                    Email: {{$user->email}}<br>
                   <strong>User Type:{{ucfirst($user->user_type)}}<br>
                   User Status: @if($user->status == 'active') <span class="badge bg-success">{{ucfirst($user->status)}}</span>@else <span class="badge bg-warning">{{ucfirst($user->status)}}</span> @endif</strong>
                  </address>
                </div>

              </div>
              <br>
             
              <!-- Table row -->
                    @if(count($total_transactions))
              <div class="row">
                <strong>Transaction Details</strong>
                <div class="col-12 table-responsive">
                  <table class="table table-striped">
                    <thead>
                    <tr>
                      <th>#</th>
                      <th>Serial No.</th>
                      <th>Product</th>
                      <th>Amount</th>
                      <th>Status</th>
                      <th>Date</th>

                    </tr>
                    </thead>
                    <tbody>
                      @foreach($total_transactions as $key=>$transaction)
                    <tr>
                      <td>{{$key+1}}</td>
                      <td>{{$transaction->serial_number}}</td>
                      <td>{{$transaction->product}}</td>
                      <td>{{$transaction->amount}}</td>
                      @if($transaction->status == 'returned')
                      <td style="color: red;">
                        {{ucfirst($transaction->status)}}
                      </td>
                      @elseif($transaction->created_at < $then_date &&  $transaction->status == 'pending')
                      <td style="color: purple;">                        
                        Redeemable
                      </td>
                      @elseif($transaction->created_at >= $then_date &&  $transaction->status == 'pending')
                      <td style="color: black;">
                        Waiting period
                      </td>
                        @elseif($transaction->status == 'to_redeem')
                      <td style="color: orange;">
                        Pending
                      </td>
                        @else
                      <td style="color: green;">
                     
                        {{ucfirst(str_replace("_"," ",$transaction->status))}}
                      </td>
                        @endif

                   
                      <td>{{date("d/m/y", strtotime($transaction->created_at))}}</td>
                    </tr>
                    @endforeach
                
                    </tbody>

                  </table>
                </div>

                
                <!-- /.col -->
              </div>

    
              @endif
              <br>


                    @if(count($redeem) > 0)
                <div class="row">
                <strong>Redeemed History</strong>
                <div class="col-12 table-responsive">
                  <table class="table table-striped">
                    <thead>
                    <tr>
                      <th>#</th>
                      <th>Date</th>
                      <th>Serial No.</th>

                      <th>Amount</th>
                     

                    </tr>
                    </thead>
                    <tbody>
                      @foreach($redeem as $key=>$redeem)
                    <tr>
                      <td>{{$key+1}}</td>
                      <td>{{date("d/m/y", strtotime($redeem->created_at))}}</td>
                      <td>{{$redeem->serial_number}}</td>
                      <td>{{$redeem->requested_amount}}</td>
                    </tr>
                    @endforeach
                
                    </tbody>
                    
                  </table>
                </div>

               
                <!-- /.col -->
              </div>
                    @endif

                    <br><br>

                    @if(count($total_transactions) > 0)

                              <div class="row" style="page-break-inside: avoid">
 
                <div class="col-6 offset-6">
                 <strong>GRAND TOTAL</strong>

                  <div class="table-responsive">
                    <table class="table">
                      <tbody>
                      <tr>
                        <th>Redeemable </th>
                        <td>{{$redeemable_points}}</td>
                      </tr>
                      <tr>
                        <th>Redeemed:</th>
                        <td>{{$redeemed}}</td>
                      </tr>

                      <tr>
                        <th>Waiting Period Amount:</th>
                        <td>{{$waiting_points}}</td>
                      </tr>
                      <tr>
                        <th>Total:</th>
                        <td>{{$total_points}}</td>
                      </tr>
                    </tbody></table>
                  </div>
                </div>
                <!-- /.col -->
              </div>
                    @endif
              <!-- /.row -->

  
              <!-- /.row -->

              <!-- this row will not appear when printing -->
              <div class="row no-print">
                <div class="col-12">
                 
                
                </div>
              </div>
            </div>
    </section>
    <!-- /.content -->
@endsection
@push('head')
<script type="text/javascript">

</script>


@endpush
