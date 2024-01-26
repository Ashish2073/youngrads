<meta name="mobile-web-app-capable" content="yes">
<meta name="application-name" content="Mr. Paradise">
{{-- <meta name="apple-mobile-web-app-title" content="Mr. Paradise"> --}}
<meta name="theme-color" content="#000">
<meta name="msapplication-navbutton-color" content="#000">
{{-- <meta name="apple-mobile-web-app-status-bar-style" content="#000"> --}}
{{-- <meta name="apple-mobile-web-app-capable" content="yes"> --}}
<meta name="msapplication-starturl" content="/">
{{-- <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"> --}}
<link rel="icon" sizes="128x128" href="{{ asset('img/touch/icon-128x128.png') }}">
<link rel="apple-touch-icon" sizes="128x128" href="{{ asset('img/touch/icon-128x128.png') }}">
<link rel="icon" sizes="192x192" href="{{ asset('icon-192x192.png') }}">
<link rel="apple-touch-icon" sizes="192x192" href="{{ asset('img/touch/icon-192x192.png') }}">
<link rel="icon" sizes="256x256" href="{{ asset('img/touch/icon-256x256.png') }}">
<link rel="apple-touch-icon" sizes="256x256" href="{{ asset('img/touch/icon-256x256.png') }}">
<link rel="icon" sizes="384x384" href="{{ asset('img/touch/icon-384x384.png') }}">
<link rel="apple-touch-icon" sizes="384x384" href="{{ asset('img/touch/icon-384x384.png') }}">
<link rel="icon" sizes="512x512" href="{{ asset('img/touch/icon-512x512.png') }}">
<link rel="apple-touch-icon" sizes="512x512" href="{{ asset('img/touch/icon-512x512.png') }}">
{{-- Manifest --}}
<link rel="manifest" href="{{ asset('manifest.json') }}">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="apple-mobile-web-app-title" content="Mr. Paradise">
<script>
    window.Laravel = {!! json_encode([
        'user' => Auth::user(),
        'csrfToken' => csrf_token(),
        'vapidPublicKey' => config('webpush.vapid.public_key'),
        'pusher' => [
            'key' => config('broadcasting.connections.pusher.key'),
            'cluster' => config('broadcasting.connections.pusher.options.cluster'),
        ],
    ]) !!};
</script>
