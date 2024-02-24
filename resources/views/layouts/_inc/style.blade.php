<link href="{{ asset('css/vendor/basic.css') }}" rel="stylesheet">
<link href="{{ asset('css/theme/horizontal/style.css') }}" rel="stylesheet">
@if(env('APP_ENV') == 'production')
    <link href="{{ asset('css/theme/colors/megna-dark.css') }}" rel="stylesheet">
@else
    <link href="{{ asset('css/theme/colors/red-dark.css') }}" rel="stylesheet">
@endif
@yield('style')