@extends('layouts.admin')
@push('styles')
<style type="text/css">
.UrlInput { position: relative; }
.UrlInput label { position: absolute; left: 11px; top: 7px; color: #999; }
.UrlInput input {  left: 0; top: 0; padding-left:40px; }
.nav-tabs.flex-column .nav-link.active {
   
    background-color: #d8e1ea;
}

#load{
    width:100%;
    height:100%;
    position:fixed;
    z-index:9999;
    margin-top: -140px;
    margin-left: -64px;

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
     <div class="card card-primary card-tabs">
              <div class="card-header p-0 pt-1">
                <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                  <li class="nav-item col-lg-6">
                    <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">View All Staffs</a>
                  </li>
                
                    <li class="nav-item col-lg-6">
                    <a class="nav-link" id="custom-tabs-one-reg-tab" data-toggle="pill" href="#custom-tabs-one-reg" role="tab" aria-controls="custom-tabs-one-reg" aria-selected="false">New Staffs</a>
                  </li>
             
                </ul>
              </div>
              <div class="card-body">
                <div class="tab-content" id="custom-tabs-one-tabContent">
                  <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                             <div class="card card-primary">
                      <div class="card-body">
                        <div class="row">
                         @if(count($staffs) > 0)                       
                          <div class="col-sm-3">
                            <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
                              @foreach($staffs as $staff)
                                <a class="nav-link {{ $loop->first ? 'active' : ''}}" id="vert-tabs-{{$staff->useid}}-tab" data-toggle="pill" href="#vert-tabs-{{$staff->useid}}" role="tab" aria-controls="vert-tabs-{{$staff->useid}}" aria-selected="{{ $loop->first ? 'true' : 'false'}}">{{$staff->name}}</a>                         
                              @endforeach                            
                            </div>
                          </div>
                          <div class="col-sm-9">
                            <div class="tab-content" id="vert-tabs-tabContent">
                              @foreach($staffs as $staff)
                                <div class="tab-pane {{ $loop->first ? 'text-left fade active show' : 'fade'}}" id="vert-tabs-{{$staff->useid}}" role="tabpanel" aria-labelledby="vert-tabs-{{$staff->useid}}-tab"> 
                                 

                                 <div class="form-group">
                                    <div class="row">
                                      <div class="col-md-4">
                                        Name
                                        <br>
                                      {{$staff->name}}
                                      </div> 
                                   
                                   </div>
                                 </div>

                                

                                   <div class="form-group">
                                    <div class="row">
                                      <div class="col-md-4">
                                        Address
                                        <br>
                                      {{$staff->address}}
                                      </div> 
                                   
                                   </div>
                                 </div>

                               
                                  

                                
                                 <div class="form-group">
                                    <div class="row">
                                      <div class="col-md-4">
                                        Mobile
                                        <br>
                                       +91 {{$staff->mobile}}
                                      </div> 
                                
                                   </div>
                                 </div>

                                 <div class="form-group">
                                    <div class="row">
                                      <div class="col-md-8">
                                        Email
                                        <br>
                                      {{$staff->email}}
                                      </div> 
                                    </div>
                                  </div>
                                   <div class="form-group">
                                    <div class="row">
                                      <div class="col-md-1">


                                       <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-edit{{$staff->useid}}">
                                      Edit
                                     </button>

                                      <div class="modal fade" id="modal-edit{{$staff->useid}}" role="dialog">
                                      <div class="modal-dialog">
                                        <div class="modal-content" style="    margin-left: 50px;">
                                         <div class="modal-header">
                                           <h4 class="modal-title">Edit Staff</h4>
                                               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                  <span aria-hidden="true">&times;</span>
                                                </button>
                                             </div>
                                          <div class="modal-body">
                                          <form method="post" action="{{url('admin/edit-staff')}}" id="myform">
                                            @csrf
                                            <input type="hidden" name="staff_id" value="{{$staff->useid}}">
                                          <div class="form-group">
                                             <div class="row">
                                               <div class="col-sm-8 offset-2">
                                                <strong>Name</strong>
                                                <br>
                                                <input type="text" name="name" placeholder="Name" value="{{$staff->name}}" class="form-control" required>
                                               </div>
                                             </div>
                                          </div>

                                         
                                           <div class="form-group">
                                             <div class="row">
                                                <div class="col-sm-8 offset-2">
                                                  <strong>Email</strong>
                                                <br>
                                                  <input type="email" name="email" placeholder="Email" value="{{$staff->email}}" class="form-control" required>
                                                </div>
                                              </div>
                                          </div>

                                          <div class="form-group">
                                             <div class="row">
                                                <div class="col-sm-8 offset-2">
                                                  <strong>Mobile</strong>
                                                <br>
                                                <div class="form-group UrlInput">
                                                  <label>+91  </label>
                                                  <input type="number" name="mobile" placeholder="Phone Number" value="{{$staff->mobile}}" class="form-control" required>
                                                </div>
                                              </div>
                                          </div>
                                        </div>

                                           <div class="form-group">
                                             <div class="row">
                                                <div class="col-sm-8 offset-2">
                                                  <strong>Address</strong>
                                                <br>
                                                  <textarea name="address" placeholder="address" class="form-control" required>{{$staff->address}}</textarea> 
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
                                       <div class="col-md-4">  
                                        @if($staff->status == 'active')
                                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal-delete{{$staff->useid}}">
                                         Suspend staff
                                        </button>
                                        @else
                                        <button class="btn btn-secondary" inactive>Suspended</button>
                                        @endif

                                      <!-- Modal -->
                                      <div class="modal fade" id="modal-delete{{$staff->useid}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document" style="margin: 178px auto;">
                                          <div class="modal-content">
                                            <div class="modal-header">
                                              <h5 class="modal-title" id="exampleModalLabel">Suspend</h5>
                                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                              </button>
                                            </div>
                                            <div class="modal-body">
                                    Are you sure you want to Suspend this staff {{$staff->name}}??
                                            </div>
                                            <div class="modal-footer">
                                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                              <a href="{{url('admin/suspend-staff/')}}/{{$staff->useid}}" class="btn btn-danger">suspend</a>
                                            </div>
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
                          {{$staffs->links()}}
                        @else
                        <p>No staffs</p>
                        @endif
                        </div>  
                      </div>
                    </div>
 
                  </div>
                 

                  <div class="tab-pane fade" id="custom-tabs-one-reg" role="tabpanel" aria-labelledby="custom-tabs-one-reg-tab">
                   <div class="row">
                      <div class="col-12">
                        <div class="card">
                            <div class="card card-primary">
                                   <!-- form start -->
                                <form method="post" action="{{url('admin/add-staff')}}" >
                                  @csrf
                                  <div class="card-body">
                                    <div class="form-group">  
                                      <div class="row">                       
                                        <div class="col-md-6 offset-3">
                                          <input type="text" class="form-control" id="name" name="name" placeholder="Name" required>
                                                @error('name')
                                                <span style="color: red;">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                               @enderror
                                        </div>
                                      </div>
                                   </div>
                                 

                                      <div class="form-group">  
                                    <div class="row"> 
                                      <div class="col-md-6 offset-3">                                             
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>                       
                                        </div>
                                      </div>
                                   </div>

                                      <div class="form-group">  
                                    <div class="row"> 
                                      <div class="col-md-6 offset-3">                                             
                                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" required>                       
                                        </div>
                                      </div>
                                   </div>
                                  <div class="form-group">  
                                    <div class="row"> 
                                        <div class="col-md-6 offset-3">
                                         <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                                        </div>
                                    </div>
                                   </div>

                              
                               <div class="form-group">  
                                    <div class="row"> 
                                     <div class="col-md-6 offset-3">   
                                       <div class="form-group UrlInput">
                                        <label>+91  </label>                     
                                         <input type="number" class="form-control" id="mobile" name="mobile" placeholder="Phone Number" required>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                 <div class="form-group">  
                                    <div class="row"> 
                                      <div class="col-md-6 offset-3">                        
                                      <textarea class="form-control" id="address" name="address" placeholder="Address" required></textarea>
                                    </div>                     
                                  </div>
                                </div>
                              </div>
                                  <!-- /.card-body -->
                                 <div class="form-group">  
                                    <div class="row"> 
                                      <div class="col-md-6 offset-3">  
                                       <div class="card-footer">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                </form>
                              </div>
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
