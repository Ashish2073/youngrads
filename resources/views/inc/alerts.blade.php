@if(session('code'))
    <div class="alert alert-{{ session('code') }}">
        {{ session('message') }}
    </div>
@endif