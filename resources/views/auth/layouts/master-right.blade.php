<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="ceymplon.lk">
    @if(env('APP_ENV') == 'production')
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon.png') }}">
    @endif
    <title>{{ env('APP_NAME') }} | @yield('title')</title>
    <link href="{{ asset('css/vendor/basic.css') }}" rel="stylesheet">
    <link href="{{ asset('css/theme/horizontal/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/theme/colors/green.css') }}" rel="stylesheet">
    @yield('style')
</head>
<body>
<div class="preloader">
    <svg class="circular" viewBox="25 25 50 50">
        <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
    </svg>
</div>
<section id="wrapper" class="login-register login-sidebar"  style="background-image:url('{{ asset('images/background/login-register.jpg') }}');">
    <div class="login-box card">
        <div class="card-body">
            @yield('content')
        </div>
        <footer class="footer">
            © {{ date('Y') }} AnnA Industry, All rights reserved.
        </footer>
    </div>
</section>
<script src="{{ asset('js/vendor/basic.js') }}"></script>
<script src="{{ asset('js/theme/script.js') }}"></script>
@yield('script')
</body>
</html>
