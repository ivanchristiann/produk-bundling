<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>
    Paper Dashboard 2 by Creative Tim
  </title>
  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">

  <!-- CSS Files -->
  <link href="{{ asset('../assets/css/bootstrap.min.css')}}" rel="stylesheet" />
  <link href="{{ asset('../assets/css/paper-dashboard.css?v=2.0.1')}}" rel="stylesheet" />

  <!-- CSS Just for demo purpose, don't include it in your project -->
  <link href="{{ asset('../assets/demo/demo.css')}}" rel="stylesheet" />

  <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('../assets/DataTables/datatables.css') }}">

  <!-- Select2 -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

  <!-- BoxIcon -->
  <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />

</head>

<body >
  <div class="wrapper" style="height: auto;" >
    <div class="sidebar" data-color="white" data-active-color="danger">
      <div class="logo">
        <a href="#" class="simple-text logo-mini">
          <div class="logo-image-small">
            @php
              $user = Auth::user();
            @endphp
            <img src="{{ asset('../assets/images/employees/'. $user->foto) }}">
          </div>
        </a>
        <a href="{{'http://127.0.0.1:8000/employee/' . $user->id .'/edit'}}" class="simple-text logo-normal">
            {{$user->nama}}
        </a>
      </div>
      @include('layout.sidebar')
    </div>
    <div class="main-panel">
      <!-- CONTENT -->
      <div class="container-xxl flex-grow-1 container-p-y" >
        <div class="card" style="padding: 25px;">
              @yield('content')
        </div>
    </div>
      <!-- END CONTENT -->
    </div>
  </div>
  <!--   Core JS Files   -->
  <script src="{{ asset('../assets/js/core/jquery.min.js')}}"></script>
  <script src="{{ asset('../assets/js/core/popper.min.js')}}"></script>
  <script src="{{ asset('../assets/js/core/bootstrap.min.js')}}"></script>
  <script src="{{ asset('../assets/js/plugins/perfect-scrollbar.jquery.min.js')}}"></script>
  <!--  Google Maps Plugin    -->
  <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>
  <!-- Chart JS -->
  <script src="{{ asset('../assets/js/plugins/chartjs.min.js')}}"></script>
  <!--  Notifications Plugin    -->
  <script src="{{ asset('../assets/js/plugins/bootstrap-notify.js')}}"></script>
  <!-- Control Center for Now Ui Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="{{ asset('../assets/js/paper-dashboard.min.js?v=2.0.1')}}" type="text/javascript"></script><!-- Paper Dashboard DEMO methods, don't include it in your project! -->
  <script src="{{ asset('../assets/demo/demo.js')}}"></script>

  <!-- DataTables -->
  <script src="{{ asset('../assets/DataTables/datatables.js') }}"></script>

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  
  <script src="{{ asset('../assets/js/main.js')}}"></script>

  <!-- Select2 -->
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script>
    $(document).ready(function() {
      // Javascript method's body can be found in assets/assets-for-demo/js/demo.js
      demo.initChartsPages();
    });
  </script>
   @yield('script')
</body>
</html>
