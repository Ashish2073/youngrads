@extends('layouts/contentLayoutMaster')

@section('title', 'Dashboard')

@section('vendor-style')
    <!-- vendor css files -->
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/charts/apexcharts.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/tether-theme-arrows.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/tether.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/shepherd-theme-default.css')) }}">
@endsection
@section('page-style')
    <!-- Page css files -->
    <link rel="stylesheet" href="{{ asset(mix('css/pages/dashboard-analytics.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/pages/card-analytics.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/plugins/tour/tour.css')) }}">
@endsection

@section('content')
    {{-- Dashboard Analytics Start --}}
    <section id="dashboard-analytics" class="d-flex flex-column">

        @if (session('verified'))

            <div class="alert alert-success fade container show" id="success-alert">
                <strong>Congratulations!</strong> Email address verified successfully!
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true"><i class="feather icon-x-circle"></i></span>
                </button>
            </div>
        @endif

        <div class="item flex-fill">
            <div class=" row justify-content-center">
                <div class="col-xl-2 col-md-4 col-sm-6">
                    <div class="card h-100 text-center" data-url="{{ route('shortlist-programs') }}" style="cursor: pointer;">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="avatar bg-rgba-primary p-50 m-0 mb-1">
                                    <div class="avatar-content">
                                        <i class="feather icon-check-circle text-primary font-medium-5"></i>
                                    </div>
                                </div>
                                <h2 class="text-bold-700">{{ $shortList }}</h2>
                                <p class="mb-0 line-ellipsis">Shortlisted Programs</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4 col-sm-6 mt-1 mt-md-0">
                    <div class="card h-100 text-center" data-url="{{ route('applications') }}" style="cursor: pointer;">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="avatar bg-rgba-primary p-50 m-0 mb-1">
                                    <div class="avatar-content">
                                        <i class="fa fa-file-o text-primary font-medium-5"></i>
                                    </div>
                                </div>
                                <h2 class="text-bold-700">{{ $application }}</h2>
                                <p class="mb-0 line-ellipsis">Applications</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4 col-sm-6 mt-1 mt-md-0">
                    <div class="card h-100 text-center" data-url="{{ route('course-finder') }}" style="cursor: pointer;">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="avatar bg-rgba-primary p-50 m-0 mb-1">
                                    <div class="avatar-content">
                                        <i class="fa fa-search text-primary font-medium-5"></i>
                                    </div>
                                </div>
                                <h2 style="" class="text-bold-700">10K+</h2>
                                <p class="mb-0 line-ellipsis">Find a Program</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4 col-sm-6 mt-1 mt-md-0">
                    <div class="card h-100 text-center" data-url="{{ route('student.edit-profile') }}" style="cursor: pointer;">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="avatar bg-rgba-primary p-50 m-0 mb-1">
                                    <div class="avatar-content">
                                        <i class="fa fa-user-circle text-primary font-medium-5"></i>
                                    </div>
                                </div>
                                <p class="text-bold-700">
                                    @if(!auth()->user()->isCompleted())
                                        <i data-toggle='tooltip' title='Please complete your profile' class="fa fa-warning text-primary"></i>
                                    @endif
                                    Complete your profile
                                </p>
                                <p class="mb-0 line-ellipsis">Edit Profile</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('course-finder') }}">
            <div class="container  mt-2">
                <div class="row justify-content-center align-items-end">
                    <div class="col-md-4 col-sm-12">
                        <label class="h5 text-primary font-weight-bold" for="what">What you want to study?</label>
                        <input placeholder="Search for Courses/Keyword" type="text" name="what" class="form-control"
                            value="{{ request()->get('program') }}" id="what">
                    </div>
                    <div class="col-md-4 col-sm-12 mt-1 mt-md-0">
                        <label class="h5 text-primary font-weight-bold" for="">Where do you want to study?</label>
                        <input name="where" placeholder="Search for Country/Keyword" type="text" class="form-control"
                            value="{{ request()->get('where') }}" id="where">
                    </div>
                    <div class="col-md-4 col-sm-12 mt-1 mt-md-0">
                        <label class="h5 text-primary font-weight-bold">Year</label>
                        <select name="year[]" data-style="bg-white border-light" class="form-control select" id="ygrad_year"
                            name="ygrad_year" multiple>
                            @for ($i = date('Y'); $i < date('Y') + 3; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    {{-- <div
                        class="col-md-3 top-search-options col-sm-12 text-right  mt-sm-2">
                        <button type="button" class="btn btn-outline-primary reset-search">Reset
                        </button>
                    </div> --}}
                </div>
                <div class="row mt-2 justify-content-center">
                    <div class="col-md-4 text-center">
                        <button type="submit" class="btn btn-primary search-submit">Search
                        </button>
                        <button type="button" class="btn btn-outline-primary reset-search">Reset
                        </button>
                    </div>
                </div>
            </div>
        </form>

    </section>
    <!-- Dashboard Analytics end -->
@endsection

@section('vendor-script')
    <!-- vendor files -->
    <script src="{{ asset(mix('vendors/js/charts/apexcharts.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/extensions/tether.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/extensions/shepherd.min.js')) }}"></script>
@endsection
@section('page-script')
    <!-- Page js files -->
    <script src="{{ asset(mix('js/scripts/pages/dashboard-analytics.js')) }}"></script>
    <script>
        // $("#success-alert").fadeTo(2000, 500).slideUp(500, function() {
        //     $(this).slideUp(500);
        // });
        $(document).ready(function() {
            $("#ygrad_year").selectpicker();
            $(".reset-search").on('click', function() {
                $("#what, #where").val("");
                $("#ygrad_year").val("");
                $("#ygrad_year").selectpicker('refresh');

            });
            $(".card").css('cursor', 'pointer');
            $(".card").on("click", function() {
                let url = $(this).data('url');
                if (url.length > 0) {
                    window.location = url;
                }
            })
        })

    </script>
@endsection
