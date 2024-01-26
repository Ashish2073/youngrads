<!DOCTYPE html>
<html class="body-full-height" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
{{-- @include('inc.pwa') --}}
{{-- @include('inc.variable') --}}
<!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Mr. Paradise') }}</title>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    <!-- Styles -->
    <link rel="stylesheet" type="text/css" id="theme" href="{{ asset('dashboard/css/theme-dark.css') }}"/>
    <link rel="stylesheet" type="text/css" id="theme" href="{{ asset('dashboard/css/style.css') }}"/>
    <link rel="icon" href="{{ asset('dashboard/img/favicon.png') }}" type="" sizes="32x32">
    {{-- @include('inc.sw-config') --}}
    @yield('head_script')
</head>
<body>
<div class="login-container">
    @yield('content')
</div>

<script type="text/javascript" src="{{ asset('dashboard/js/plugins/jquery/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('dashboard/js/plugins/jquery/jquery-ui.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('dashboard/js/plugins/bootstrap/bootstrap.min.js') }}"></script>
<script type='text/javascript' src="{{ asset('dashboard/js/plugins/icheck/icheck.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('dashboard/js/plugins.js') }}"></script>
<script type="text/javascript" src="{{ asset('dashboard/js/plugins/jquery-validation/jquery.validate.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.19.2/moment-with-locales.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.14/moment-timezone-with-data.min.js"></script>

@yield('foot_script')
</body>
</html>






