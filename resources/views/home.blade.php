@extends('layouts.beta_layout')
@section('head_script')
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
    <style>
           #advance-search-box{}
    </style>
@section('content')

    <div class="hero-banner-sec">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="banner-search-block home-select-box">
                        <h2 class="site-title">Explore Over 55,000+ Courses</h2>
                        <h4>Use Our Course Finder to Search</h4>
                        @php
                           $route = (Auth::check())? route('course-finder') : route('course-finder-guest');

                        @endphp
                        <form action="{{ $route }}" method="get">
                            <div class="row mt-2">
                                <div class="col-6">
                                 <div class="row align-items-center">
                                    <div class="col-12">
                                      {{-- <select name="program" class="form-control program-typehead" placeholder="What you want to study ?" data-url="{{ route('program-auto')}}">
                                      </select> --}}
                                      <input type="text" name="what" id="what" placeholder="What you want to study ?" class="form-control form-control-lg rounded-0">
                                    </div>
                                    {{-- <div class="col-1">
                                      <i class="fa fa-spin fa-spinner program-loader d-none"></i>
                                    </div> --}}
                                  </div>
                                </div>
                                <div class="col-6">
                                  <div class="row align-items-center">
                                     <div class="col-12">
                                      <input type="text" name="where" id="where" placeholder="Where do you want to study ?" class="form-control form-control-lg rounded-0">
                                       {{-- <select name="country_id" class="form-control country" placeholder="Where do yo want to study ?" data-url="{{ route('country-auto') }}"></select> --}}
                                     </div>
                                     {{-- <div class="col-1">
                                      <i class="fa fa-spin fa-spinner country-loader d-none"></i>
                                     </div> --}}
                                    </div>
                                  </div>
                            </div>
                            <div class="ygd-search-btn">
                                <div class="form-inline">
                                    <button class="search-btn" type="submit"><i class="search-icon"></i> Search</button>
                                </div>
                            </div>
                        </form>


                    </div>
                </div>
                <div class="col-md-6">
                    <div class="banner-img">
                        <img src="{{ asset('images/banner-graphic.png') }}" alt="Banner Graphic">
                    </div>
                </div>
            </div>

        </div>
    </div>

    <section class="work-sec">
        <div class="container">
            <div class="">
                <h2 class="site-title">How it Works</h2>
            </div>

            <div class="row">
                <div class="col">
                    <div class="work-block">
                        <i class="work-icon-1"></i>
                        <h4>Find the Right <strong>School & Program</strong></h4>
                    </div>
                </div>

                <div class="col">
                    <div class="work-block">
                        <i class="work-icon-2"></i>
                        <h4>Submit Your <strong>Application </strong></h4>
                    </div>
                </div>

                <div class="col">
                    <div class="work-block">
                        <i class="work-icon-3"></i>
                        <h4>Get Your Letter <strong>of Acceptance </strong></h4>
                    </div>
                </div>

                <div class="col">
                    <div class="work-block">
                        <i class="work-icon-4"></i>
                        <h4>Start the Visa <strong>Process</strong></h4>
                    </div>
                </div>

                <div class="col">
                    <div class="work-block">
                        <i class="work-icon-5"></i>
                        <h4>Book Your <strong>Flight</strong></h4>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="our-process-sec">
        <div class="container">

            <div class="row align-items-center">
                <div class="col-md-5">
                    <div class="our-process-heading">
                        <h2 class="site-title">Watch How Our <span>Process Works</span></h2>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="our-process-video">
                        <video width="320" height="240" poster={{ asset('images/thumbnail.png') }} controls>
                            <source src="{{ asset('videos/YounGrads-Explainer.mp4') }}" type="video/mp4">
                        </video>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('foot_script')
    <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>
    <script type="text/javascript">
        var path = "{{ route('autocompletecourse') }}";
        $('#what').typeahead({
            autoSelect: false,
            source:  function (query, process) {
            return $.get(path, { query: query }, function (data) {
                    return process(data);
                });
            }
        });
    </script>

    <script type="text/javascript">
        var path1 = "{{ route('autocompletecountry') }}";
        $('#where').typeahead({
            autoSelect: false,
            source:  function (query, process) {
            return $.get(path1, { query: query }, function (data) {
                    return process(data);
                });
            }
        });
    </script>
@endsection
