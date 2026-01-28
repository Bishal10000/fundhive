<!DOCTYPE html>
<html>
<head>
  <title>Admin | FundHive</title>
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
  <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
</head>

<body class="hold-transition sidebar-mini">
<div class="wrapper">

@include('admin.partials.navbar')
@include('admin.partials.sidebar')

<div class="content-wrapper">
    @yield('content')
</div>

</div>

<script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/chart.js/Chart.min.js') }}"></script>
<script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>
@stack('scripts')
</body>
</html>
