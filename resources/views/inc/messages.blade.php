@if($errors->any() || session()->has('error'))
    <div class="alert alert-danger">
        @if(empty(session()->get('error')) && empty(session()->get('error')))
            Error! Please check below.
        @else
            {{ session()->get('error') }}
        @endif
    </div>
@endif

@if(isset($success))
    <div class="alert alert-succcess">
        {{ $success }}
    </div>
@endif
