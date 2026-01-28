<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin Dashboard')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="{{ asset('assets/backend/css/adminlte.min.css') }}">

    <!-- Font Awesome (AdminLTE needs it) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @stack('styles')
</head>

<body class="hold-transition sidebar-mini">
<div class="wrapper">

    {{-- Navbar --}}
    @include('admin.partials.navbar')

    {{-- Sidebar --}}
    @include('admin.partials.sidebar')

    {{-- Content Wrapper --}}
    <div class="content-wrapper p-4">
        @yield('content')
    </div>

    {{-- Footer --}}
    <footer class="main-footer text-sm">
        <strong>FundHive © {{ date('Y') }}</strong>
    </footer>

</div>

<!-- AdminLTE JS -->
<script src="{{ asset('assets/backend/js/adminlte.min.js') }}"></script>

@stack('scripts')
</body>
</html>
