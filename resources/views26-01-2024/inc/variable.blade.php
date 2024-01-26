<script>
    window.Laravel = {!! json_encode([
        'user' => Auth::user(),
        'csrfToken' => csrf_token(),
        'vapidPublicKey' => config('webpush.vapid.public_key'),
        'pusher' => [
            'key' => config('broadcasting.connections.pusher.key'),
            'cluster' => config('broadcasting.connections.pusher.options.cluster'),
        ],
        'appUrl' => url('/')
    ]) !!};
</script>
