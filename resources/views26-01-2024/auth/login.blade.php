@extends('layouts/fullLayoutMaster')

@section('title', 'Login Page')

@section('page-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('css/pages/authentication.css')) }}">
@endsection

@section('content')
    <section class="row flexbox-container">
        <div class="col-xl-8 col-11 d-flex justify-content-center">
            <div class="card bg-authentication rounded-0 mb-0">
                <div class="row m-0">
                    <div class="col-lg-6 d-lg-block d-none text-center align-self-center px-1 py-0">
                        <img src="{{ asset('images/pages/login.png') }}" alt="branding logo">
                    </div>
                    <div class="col-lg-6 col-12 p-0">
                        <div class="card rounded-0 mb-0 px-2">
                            <div class="card-header pb-1">
                                <div class="card-title">
                                    <img class="mb-1 img-fluid" src="{{ asset('images/site-logo.png') }}"
                                        alt="{{ config('app.name') }}" />
                                    <h4 class="mb-0">Login</h4>
                                </div>
                            </div>
                            <p class="px-2">Welcome back, please login to your accout.</p>
                            <div class="card-content pb-1">

                                <div class="card-body ">
                                    @if (session('resent'))
                                        <div class="alert alert-success" role="alert">
                                            {{ __('A fresh verification link has been sent to your email address.') }}
                                        </div>
                                    @endif
                                    @if (session()->has('code'))
                                        <p class="text-{{ session()->pull('code') }} font-weight-bold   ">
                                            {{ session()->pull('message') }}
                                        </p>
                                    @endif
                                    @if (session('unverified'))
                                        <p class=" font-weight-bold mb-1">
                                            {{ __('If you did not receive the email') }},
                                            <a onclick="event.preventDefault();
                                            document.getElementById('resend-form').submit();"
                                                href="">{{ __('click here to request another') }}</a>.
                                        </p>
                                        {{-- {{ dd(session()->pull('verification_link')) }} --}}

                                        <form id="resend-form" action="{{ session()->pull('verification_link') }}"
                                            method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('login') }}">
                                        @csrf
                                        <fieldset class="form-label-group form-group position-relative has-icon-left">

                                            <input id="email" type="email"
                                                class="form-control @error('email') is-invalid @enderror" name="email"
                                                placeholder="E-Mail Address" value="{{ old('email') }}" required
                                                autocomplete="email" autofocus>

                                            <div class="form-control-position">
                                                <i class="feather icon-user"></i>
                                            </div>
                                            <label for="email">E-Mail Address</label>
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </fieldset>

                                        <fieldset class="form-label-group position-relative has-icon-left">

                                            <input id="password" type="password"
                                                class="form-control @error('password') is-invalid @enderror" name="password"
                                                placeholder="Password" required autocomplete="current-password">

                                            <div class="form-control-position">
                                                <i class="feather icon-lock"></i>
                                            </div>
                                            <label for="password">Password</label>
                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </fieldset>
                                        <div class="form-group d-flex justify-content-between align-items-center">
                                            <div class="text-left">
                                                <fieldset class="checkbox">
                                                    <div class="vs-checkbox-con vs-checkbox-primary">
                                                        <input type="checkbox" {{ old('remember') ? 'checked' : '' }}>
                                                        <span class="vs-checkbox">
                                                            <span class="vs-checkbox--check">
                                                                <i class="vs-icon feather icon-check"></i>
                                                            </span>
                                                        </span>
                                                        <span class="">Remember me</span>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            @if (Route::has('password.request'))
                                                <div class="text-right"><a class="card-link"
                                                        href="{{ route('password.request') }}">
                                                        Forgot Password?
                                                    </a></div>
                                            @endif

                                        </div>
                                        <a href="{{ route('register') }}"
                                            class="btn btn-outline-primary float-left btn-inline">Register</a>
                                        <button type="submit" class="btn btn-primary float-right btn-inline">Login
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @include('auth.social_login')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
