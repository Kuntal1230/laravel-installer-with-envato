<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@hasSection('title')@yield('title')@elseif(isset($title)){{ $title }}@endif - {{ config('installer.company.name') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Urbanist:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('vendor/installer/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/installer/css/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/installer/css/nice-select.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/installer/css/jquery-confirm.min.css') }}">

    <!-- Main Style -->
    <link rel="stylesheet" href="{{ asset('vendor/installer/style.css') }}">
</head>
<body>
@include('installer::layouts.steps')

<div class="container">
    <div class="content">
        <h5 class="page-title">@hasSection('title')@yield('title')@elseif(isset($title)){{ $title }}@endif</h5>

        @yield('content')
    </div>
</div>

@include('installer::layouts.footer')

<!-- All JavaScript Files -->
<script src="{{ asset('vendor/installer/js/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/installer/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('vendor/installer/js/nice-select.min.js') }}"></script>
<script src="{{ asset('vendor/installer/js/jquery.form.min.js') }}"></script>
<script src="{{ asset('vendor/installer/js/jquery.validate.js') }}"></script>
<script src="{{ asset('vendor/installer/js/flasher.min.js') }}"></script>
<script src="{{ asset('vendor/installer/js/jquery-confirm.min.js') }}"></script>
<script src="{{ asset('vendor/installer/js/active.js') }}"></script>

@stack('pageScripts')
</body>
</html>
