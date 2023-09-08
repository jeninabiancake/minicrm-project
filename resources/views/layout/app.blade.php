<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <title>CRM @yield('title')</title>
 <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Satisfy">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">
  <script src="https://kit.fontawesome.com/f3d62e51db.js" crossorigin="anonymous"></script>
 
    <meta name="csrf_token" content="{{ csrf_token() }}" />

    @include('layout.styles')

    <script>
        var BASE_URL = '{{ url("/") }}';
    </script>

</head>
<body class="hold-transition skin-white sidebar-mini">

    <div class="wrapper">
        @include('layout.header')
        @include('layout.sidebar')

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            @yield('content')
        </div>
    </div>

    @include('layout.footer')
    @yield('scripts')
</body>
</html>