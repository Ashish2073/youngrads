<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Youngrads') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@100;200;300;400;500;600;700;800;900&display=swap"
          rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css"
          integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}" type="text/css" media="screen"/>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}" type="text/css" media="screen"/>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css"/>
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="" sizes="32x32">
    @routes
</head>

<body>
<header class="header">
    @include('inc.nav')
</header>

@yield('content')

@include('inc.footer')
{{--<script type="text/javascript" src="{{ asset('js/core/app.js') }}"></script>--}}
{{--<script type="text/javascript" src="{{ asset('js/core/app-menu.js') }}"></script>--}}

<script src="{{ asset(mix('vendors/js/vendors.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/ui/prism.min.js')) }}"></script>

<script type="text/javascript" src="{{ asset('js/scripts/main.js') }}"></script>
<script src="{{ asset('js/scripts/select_ajax.js') }}"></script>

@yield('foot_script')

</body>

</html>
