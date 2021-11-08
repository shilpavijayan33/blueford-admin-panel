@extends('layouts.admin')
@push('styles')
<style type="text/css">
  .redClass{
    color: red; 

  }

  .greenClass{
    color: black; 

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
    <div class="card" style="overflow: auto;">
    

    
              <!-- /.card-header -->
              <div class="card-body">
                <table class="table table-bordered data-table" id="transaction-table">
                    <thead>
                     <tr>
                      <th width="10px;">#</th>
                      <th width="80px;">User Type</th>
                      <th>Name</th>
                      <th width="69px;">Serial No.</th>

                      <th>Product</th>
                      <th width="50px;">Reward</th>
                      <th width="50px;">Date</th>
                      <th width="50px;">Time</th>
                    

                    </tr>
                  </thead>
                  <tbody>
                 </tbody>
                </table>
              <!-- /.card-body -->
            </div>
    </section>
    <!-- /.content -->
@endsection
@push('head')
<script type="text/javascript">
  $.fn.DataTable.ext.pager.numbers_length = 5;

   $(document).ready(function() {
        var table = $('#transaction-table').DataTable({
        processing: true,
        serverSide: true,
        pagingType: 'full_numbers',
        numbers_length : 3,


        ajax: "transactions/data",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'user_type', name: 'user_type'},
            {data: 'name', name: 'name'},
            {data: 'serial_number', name: 'serial_number'},
            {data: 'product', name: 'product'},
            {data: 'amount', name: 'amount'},
            {data: 'date', name: 'date'},
            {data:'time',name:'time'},      

        ],
        createdRow: function( row, data, dataIndex){
          console.log(data['amount']);
                if(data['amount'] > 0){
                 
                   $(row).addClass('greenClass');
                }
                else{
                 
                    $(row).addClass('redClass');
                }
            }
    });
    });
</script>
@endpush
