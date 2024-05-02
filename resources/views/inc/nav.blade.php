<nav class="navbar navbar-expand-lg">
    <div class="col-md-10">
        <a class="navbar-brand" href="{{ route('/') }}"><img class="img-fluid" src="{{ asset('images/site-logo.png') }}"
                alt="{{ config('app.name') }}"></a>
    </div>
    <div class="col-md-2">
        <a href="#access_form" class="btn btn-info p-2 btn btn-warning" style="font-size: 25px;"><strong> Contact
                Us</strong></a>
    </div>



    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav m-auto">
            {{-- <li class="nav-item active">
                <p>Enroll</p>
            </li>
            <li class="nav-item">
                <p>Educate</p>
            </li>
            <li class="nav-item">
                <p>Empower</p>
            </li> --}}
        </ul>

        @if (!Auth::check())
            {{-- <div class="login-btn ml-auto">
                <a class="nav-link" href="{{ route('login') }}"><i class="fa fa-user-circle" aria-hidden="true"></i> Login/Signup</a>
        </div> --}}
        @else
            <div class="login-btn ml-auto">
                <a class="nav-link" href="{{ route('my-account') }}"><i class="fa fa-user-circle"
                        aria-hidden="true"></i> My Account</a>
            </div>
        @endif


    </div>
</nav>
