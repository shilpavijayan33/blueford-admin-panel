@extends('layouts.printdefault')
@push('styles')
<style type="text/css">
.UrlInput { position: relative; }
.UrlInput label { position: absolute; left: 11px; top: 5px; color: #999; }
.UrlInput input {  left: 0; top: 0; padding-left:40px; }
 .nav-tabs.flex-column .nav-link.active {
   
    background-color: #d8e1ea;
}


    @page 
    {
        size: auto;   /* auto is the initial value */
        margin: 0mm;  /* this affects the margin in the printer settings */
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

      <div class="invoice p-3 mb-3">
              <!-- title row -->

             
              <!-- Table row -->
                    @if(count($total_transactions))
              <div class="row">
                <strong >Transaction Details \ {{$user->name}}</strong>
                <br><br>
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


                    @if(count($redeem) > 0)
                <div class="row">
                <strong>Redeemed History</strong>
                <br><br>
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
                              <div class="row">
 
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
                
                    <iframe src="{{url('/admin/trans-print')}}/{{$user->id}}" name="frame{{$user->id}}" style="display: none;"> </iframe>

                       <button class="btn btn-default" onclick="frames['frame{{$user->id}}'].print()" value="printletter">Print  <i class="fa fa-print"></i></button> 
                  
                
                </div>
              </div>
            </div>
    </section>
    <!-- /.content -->
@endsection
@push('head')



@endpush
