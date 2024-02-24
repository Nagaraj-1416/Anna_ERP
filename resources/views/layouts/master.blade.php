<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="author" content="ceymplon.lk">
    @if(env('APP_ENV') == 'production')
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon.png') }}">
    @endif
    <title>{{ env('APP_NAME') }} | @yield('title')</title>
    @include('layouts._inc.style')
</head>
<body class="fix-header card-no-border logo-center">
<div class="preloader">
    <svg class="circular" viewBox="25 25 50 50">
        <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/>
    </svg>
</div>
<div id="main-wrapper">
    @include('layouts._inc.top-bar')
    @include('layouts._inc.side-bar')
    <div class="page-wrapper">
        <div class="container-fluid" ng-app="app">
            @yield('breadcrumbs')
            @yield('content')
            @include('layouts._inc.login-as')
        </div>
    </div>
    @include('layouts._inc.footer')
</div>
@include('layouts._inc.script')
@include('layouts._inc.login-as-script')
</body>
</html>