@extends('layouts.app')

@section('title', 'Contact Entries')
@section('content')

    <section class="work-sec">
        <div class="container">
                <h2 class="site-title">Contact Us</h2>
                <div class="row justify-content-center">
                    <div class="col-6">
                        @if(session()->has('code'))
                            <div class="alert alert-{{ session('code') }}">{{ session('message') }}</div>
                        @endif
                        <form method="POST" action="{{ route('contact_store') }}" id="contact-form">
                            @csrf
                            <div class="form-group">
                                <label for="name">Name <span class="required">*</span></label>
                                <input required type="text" class="form-control @error('name') {{ errCls() }} @enderror" name="name"
                                       id="name" value="{{ old('name') }}">
                                @error('name')
                                <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="email">Email <span class="required">*</span></label>
                                <input required type="text" class="form-control @error('email') {{ errCls() }} @enderror" name="email"
                                       value="{{ old('email') }}">
                                @error('email')
                                <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="message">Message <span class="required">*</span></label>
                                <textarea required class="form-control @error('message') {{ errCls() }} @enderror" name="message"
                                          rows="5" id="message" value="{{ old('message') }}"></textarea>
                                @error('message')
                                <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="form-group text-center">

                                <button type="submit" class="btn btn-warning">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>


        </div>
    </section>
@endsection

@section('foot_script')
    <script>
        $(document).ready(function () {

            validateForm($('#contact-form'), {
                rules: {
                    name: {
                        required: true,
                    },
                    email: {
                        required: true,
                        email: true,
                    },
                    messsage: {
                        required: true,
                    }
                },
                messages: {}
            });
        });
    </script>
@endsection
