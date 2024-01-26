@if($errors->any() || session()->has('error'))
    <div class="alert alert-danger alert-dismissible">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        @if($errors->has('form_error'))
            {{ $errors->first('form_error') }}
        @else
            {{ session()->get('error') }}
        @endif
    </div>
@endif

@if(session()->has('success'))
    <div class="alert alert-success alert-dismissible">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        {{ session('success') }}
    </div>
@endif

@if(isset($success))
    <div class="alert alert-success alert-dismissible">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>

        {{ $success  }}


    </div>
@endif
