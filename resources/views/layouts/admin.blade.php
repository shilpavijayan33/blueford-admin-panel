<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{env('APP_NAME')}} | {{$title}}</title>
  <link rel="icon" href="{{ URL::asset('dist/img/blueford.jpg') }}" type="image/x-icon"/>


  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bbootstrap 4 -->
  <link rel="stylesheet" href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <!-- JQVMap -->
  <link rel="stylesheet" href="{{ asset('plugins/jqvmap/jqvmap.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
  <!-- summernote -->
  <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.css') }}">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

  <!-- datatables css -->  
  <!-- <link href="{{ asset('css/jquery.dataTables.min.css') }}" rel="stylesheet"> -->
  <link href="{{ asset('css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
  <meta name="csrf-token" content="{{ csrf_token() }}" />

 
  <!-- datatables css -->

    <!-- @stack('styles') -->

  <script src="{{ asset('plugins/jquery/jquery.min.js')}}"></script>

  <!-- datatables script -->
  <script src="{{ asset('js/jquery.js')}}"></script>  
  <script src="{{ asset('js/jquery.validate.js')}}"></script>
  <script src="{{ asset('js/jquery.dataTables.min.js')}}"></script>
  <script src="{{ asset('js/popper.js')}}"></script>  
  <!-- <script src="{{ asset('js/bootstrap.min.js')}}"></script> -->
  <script src="{{ asset('js/dataTables.bootstrap4.min.js')}}"></script>
  <!-- <script src="{{ asset('js/responsive.bootstrap4.min.js')}}"></script> -->
  
  <!-- datatables script -->

@stack('styles')
  
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    @stack('head')
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="{{url('admin/product-management')}}" class="nav-link">Home</a>
      </li>
     <!--  <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Contact</a>
      </li> -->
    </ul>

    <!-- SEARCH FORM -->
   <!--  <form class="form-inline ml-3">
      <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-navbar" type="submit">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </form> -->

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Messages Dropdown Menu -->

      <!-- Notifications Dropdown Menu -->



 

          <li class="nav-item dropdown">
            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                {{ Auth::user()->name }} <span class="caret"></span>
            </a>

            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="{{ route('logout') }}"
                   onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </li>
    </ul>
  </nav>
  <!-- /.navbar -->
@php
$app=App\AppDetails::find(1);
@endphp
  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{url('admin/product-management')}}" class="brand-link">
      <img src="{{ asset('dist/img/')}}/{{$app->logo}}" alt="Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">{{$app->name}}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ asset('dist/img/user2-160x160.jpg')}}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{Auth::user()->name}}</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
            <?php
            $segment =Request::segment(2);
            ?>
        <!--   <li class="nav-item">
            <a href="{{url('admin/dashboard')}}" class="nav-link 
              @if($segment == 'dashboard')
              active
              @endif">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>    -->       

          <li class="nav-item">
            <a href="{{url('admin/product-management')}}" class="nav-link 
              @if($segment=='product-management')
              active
              @endif">
              <i class="fa fa-shopping-cart"></i>
              <p>
                Product Management
                <!-- <span class="right badge badge-danger">New</span> -->
              </p>
            </a>
          </li>

            <li class="nav-item">
            <a href="{{url('admin/qrcode-management')}}" class="nav-link 
              @if($segment=='qrcode-management')
              active
              @endif">
              <i class="nav-icon fas fa-th"></i>
              <p>
                QR Code
                <!-- <span class="right badge badge-danger">New</span> -->
              </p>
            </a>
          </li>
     
          <!-- <li class="nav-header">EXAMPLES</li> -->
          <li class="nav-item">
            <a href="{{url('admin/slab-management')}}" class="nav-link 
              @if($segment=='slab-management')
              active
              @endif">
              <i class="nav-icon fa fa-adjust"></i>
              <p>
                Slab Management
                <!-- <span class="badge badge-info right">2</span> -->
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{url('admin/dealer-management')}}" class="nav-link
              @if($segment=='dealer-management')
              active
              @endif">
              <i class="nav-icon fa fa-address-book"></i>
              <p>
                Dealer Management
              </p>
            </a>
          </li>
     <!--      <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon far fa-envelope"></i>
              <p>
                Mailbox
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="pages/mailbox/mailbox.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Inbox</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/mailbox/compose.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Compose</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/mailbox/read-mail.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Read</p>
                </a>
              </li>
            </ul>
          </li> -->
      
        
         
          <li class="nav-item">
            <a href="{{url('admin/transactions')}}" class="nav-link
              @if($segment=='transactions')
              active
              @endif">
              <i class="nav-icon fas fa-file"></i>
              <p>Transactions</p>
            </a>
          </li>
         
          <li class="nav-item">
            <a href="{{url('admin/plumber-management')}}" class="nav-link
              @if($segment=='plumber-management')
              active
              @endif">
              <i class="nav-icon fa fa-address-card"></i>
              <p>Plumbers</p>
            </a>
          </li>
      
          <li class="nav-item">
            <a href="{{url('admin/salesman-management')}}" class="nav-link
              @if($segment=='salesman-management')
              active
              @endif">
              <i class="nav-icon fas fa-user-circle "></i>
              <p>Salesman</p>
            </a>
          </li>
          
          <li class="nav-item">
            <a href="{{url('admin/redeem-management')}}" class="nav-link
              @if($segment=='redeem-management')
              active
              @endif">
              <i class="nav-icon fas fa-money-bill"></i>
              <p class="text">Redeem</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{url('admin/staff-management')}}" class="nav-link
              @if($segment=='staff-management')
              active
              @endif">
              <i class="nav-icon fas fa-portrait"></i>
              <p>Staff Management</p>
            </a>
          </li>
         
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    @yield('content')
  
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <strong>Copyright &copy; 2014-2019 <a href="#">{{env('APP_NAME')}}</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 3.0.5
    </div>
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<!-- <script src="{{ asset('plugins/jquery/jquery.min.js')}}"></script> -->
<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- ChartJS -->
<script src="{{ asset('plugins/chart.js/Chart.min.js')}}"></script>
<!-- Sparkline -->
<script src="{{ asset('plugins/sparklines/sparkline.js')}}"></script>
<!-- JQVMap -->
<script src="{{ asset('plugins/jqvmap/jquery.vmap.min.js')}}"></script>
<script src="{{ asset('plugins/jqvmap/maps/jquery.vmap.usa.js')}}"></script>
<!-- jQuery Knob Chart -->
<script src="{{ asset('plugins/jquery-knob/jquery.knob.min.js')}}"></script>
<!-- daterangepicker -->
<script src="{{ asset('plugins/moment/moment.min.js')}}"></script>
<script src="{{ asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{ asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
<!-- Summernote -->
<script src="{{ asset('plugins/summernote/summernote-bs4.min.js')}}"></script>
<!-- overlayScrollbars -->
<script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('dist/js/adminlte.js')}}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{ asset('dist/js/pages/dashboard.js')}}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ asset('dist/js/demo.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.all.min.js"></script>

</body>
</html>
