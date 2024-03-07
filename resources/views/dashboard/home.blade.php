@extends('layouts/contentLayoutMaster')

@section('title', 'Dashboard Analytics')

@section('vendor-style')
    <!-- vendor css files -->
    <link rel="stylesheet" href="{{ asset('vendors/css/charts/apexcharts.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/css/extensions/tether-theme-arrows.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/css/extensions/tether.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/css/extensions/shepherd-theme-default.css') }}">
@endsection
@section('page-style')
    <!-- Page css files -->
    <link rel="stylesheet" href="{{ asset('css/pages/dashboard-analytics.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pages/card-analytics.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/tour/tour.css') }}">
@endsection

@section('content')
    {{-- Dashboard Analytics Start --}}
    @php $userrole=json_decode(auth('admin')->user()->getRoleNames(),true)?? []; @endphp
    <section id="statistics-card">
        <div class="row">
            <div class="col-xl-2 col-md-4 col-sm-6 d-none">
                <a href="{{ route('admin.users') }}" class="card text-center">
                    <div class="card text-center">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="avatar bg-rgba-primary p-50 m-0 mb-1">
                                    <div class="avatar-content">
                                        <i class="feather icon-check-circle text-primary font-medium-5"></i>
                                    </div>
                                </div>
                                <h2 class="text-bold-700">{{ $systemUsers }}</h2>
                                <p class="mb-0 line-ellipsis">System Users</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>








            @if (in_array('Admin', $userrole))
                <div class="col-xl-2 col-md-4 col-sm-6">
                    <a href="{{ route('admin.students') }}" class="card text-center">
                        <div class="card text-center">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="avatar bg-rgba-primary p-50 m-0 mb-1">
                                        <div class="avatar-content">
                                            <i class="feather icon-check-circle text-primary font-medium-5"></i>
                                        </div>
                                    </div>
                                    <h2 class="text-bold-700">{{ $students }}</h2>
                                    <p class="mb-0 line-ellipsis">Total Students</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>


                <div class="col-xl-2 col-md-4 col-sm-6">
                    <a href="{{ route('admin.students', ['scenario' => 'weeklydata']) }}" class="card text-center">
                        <div class="card text-center">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="avatar bg-rgba-primary p-50 m-0 mb-1">
                                        <div class="avatar-content">
                                            <i class="fas fa-calendar-week text-primary font-medium-5"></i>
                                        </div>
                                    </div>
                                    <h2 class="text-bold-700">{{ $weeklyenrolledstudents }}</h2>
                                    <p class="mb-0 line-ellipsis">Weekly Enrolled Student</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-xl-2 col-md-4 col-sm-6">
                    <a href="{{ route('admin.students', ['scenario' => 'monthlydata']) }}" class="card text-center">
                        <div class="card text-center">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="avatar bg-rgba-primary p-50 m-0 mb-1">
                                        <div class="avatar-content">
                                            <i class="fa fa-calendar text-primary font-medium-5"></i>
                                        </div>
                                    </div>
                                    <h2 class="text-bold-700">{{ $monthlyData }}</h2>
                                    <p class="mb-0 line-ellipsis">Monthly Enrolled Student</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-xl-2 col-md-4 col-sm-6">
                    <a href="{{ route('admin.students', ['scenario' => 'dailydata']) }}" class="card text-center">
                        <div class="card text-center">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="avatar bg-rgba-primary p-50 m-0 mb-1">
                                        <div class="avatar-content">
                                            <i class="fas fa-calendar-day text-primary font-medium-5"></i>
                                        </div>
                                    </div>
                                    <h2 class="text-bold-700">{{ $dailyData }}</h2>
                                    <p class="mb-0 line-ellipsis">Daily Enrolled Student</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>


                <div class="col-xl-2 col-md-4 col-sm-6">
                    <a href="{{ route('admin.applications-all') }}" class="card text-center">
                        <div class="card text-center">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="avatar bg-rgba-primary p-50 m-0 mb-1">
                                        <div class="avatar-content">
                                            <i class="feather icon-check-circle text-primary font-medium-5"></i>
                                        </div>
                                    </div>
                                    <h2 class="text-bold-700">{{ $applications }}</h2>
                                    <p class="mb-0 line-ellipsis">Applications</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>



                <div class="col-xl-2 col-md-4 col-sm-6">
                    <a href="{{ route('admin.applications-all', ['scenario' => 'weeklydata']) }}" class="card text-center">
                        <div class="card text-center">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="avatar bg-rgba-primary p-50 m-0 mb-1">
                                        <div class="avatar-content">
                                            <i class="fas fa-calendar-week text-primary font-medium-5"></i>
                                        </div>
                                    </div>
                                    <h2 class="text-bold-700">{{ $weeklyenrolledapplication }}</h2>
                                    <p class="mb-0 line-ellipsis">Weekly Application</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-xl-2 col-md-4 col-sm-6">
                    <a href="{{ route('admin.applications-all', ['scenario' => 'monthlydata']) }}"
                        class="card text-center">
                        <div class="card text-center">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="avatar bg-rgba-primary p-50 m-0 mb-1">
                                        <div class="avatar-content">
                                            <i class="fa fa-calendar text-primary font-medium-5"></i>
                                        </div>
                                    </div>
                                    <h2 class="text-bold-700">{{ $monthlyenrolledapplication }}</h2>
                                    <p class="mb-0 line-ellipsis">Monthly Enrolled Application</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-xl-2 col-md-4 col-sm-6">
                    <a href="{{ route('admin.applications-all', ['scenario' => 'dailydata']) }}"
                        class="card text-center">
                        <div class="card text-center">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="avatar bg-rgba-primary p-50 m-0 mb-1">
                                        <div class="avatar-content">
                                            <i class="fas fa-calendar-day text-primary font-medium-5"></i>
                                        </div>
                                    </div>
                                    <h2 class="text-bold-700">{{ $dailyenrolledapplication }}</h2>
                                    <p class="mb-0 line-ellipsis">Daily Enrolled Application</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endif


            @if (in_array('Admin', $userrole) || in_array('supermoderator', $userrole))
                <div class="col-xl-2 col-md-4 col-sm-6">
                    <a href="{{ route('admin.moderators') }}" class="card text-center">
                        <div class="card text-center">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="avatar bg-rgba-primary p-50 m-0 mb-1">
                                        <div class="avatar-content">
                                            <i class="fa-solid fa-people-arrows text-primary font-medium-5"></i>
                                        </div>
                                    </div>
                                    <h2 class="text-bold-700">{{ $moderators }}</h2>
                                    <p class="mb-0 line-ellipsis">Moderators</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endif


            @if (in_array('supermoderator', $userrole))
                <div class="col-xl-2 col-md-4 col-sm-6">
                    <a href="{{ route('admin.students') }}" class="card text-center">
                        <div class="card text-center">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="avatar bg-rgba-primary p-50 m-0 mb-1">
                                        <div class="avatar-content">
                                            <i class="feather icon-check-circle text-primary font-medium-5"></i>
                                        </div>
                                    </div>
                                    <h2 class="text-bold-700">{{ $students }}</h2>
                                    <p class="mb-0 line-ellipsis">Assign Student To Moderator</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endif



            @if (in_array('moderator', $userrole))
                <div class="col-xl-2 col-md-4 col-sm-6">
                    <a href="{{ route('admin.students') }}" class="card text-center">
                        <div class="card text-center">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="avatar bg-rgba-primary p-50 m-0 mb-1">
                                        <div class="avatar-content">
                                            <i class="feather icon-check-circle text-primary font-medium-5"></i>
                                        </div>
                                    </div>
                                    <h2 class="text-bold-700">{{ $students }}</h2>
                                    <p class="mb-0 line-ellipsis">Assign Student To You By SuperModerator</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endif






        </div>
        @if (in_array('Admin', $userrole))
            <h4>Manage</h4>

            <div class="row">
                <div class="col-lg-3 col-sm-6 col-12">
                    <a class="card" href="{{ route('admin.universities') }}">
                        <div class="card-header d-flex align-items-start pb-0">
                            <div>
                                <h2 class="text-bold-700 mb-0">{{ $unversities }}</h2>
                                <p>Universities</p>
                            </div>
                            <div class="avatar bg-rgba-primary p-50 m-0">
                                <div class="avatar-content">
                                    <i class="fa fa-building-o text-primary font-medium-5"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="card">
                        <a class="card-header d-flex align-items-start pb-0" href="{{ route('admin.campuses') }}">
                            <div>
                                <h2 class="text-bold-700 mb-0">{{ $campus }}</h2>
                                <p>Campus</p>
                            </div>
                            <div class="avatar bg-rgba-primary p-50 m-0">
                                <div class="avatar-content">
                                    <i class="fa fa-building text-primary font-medium-5"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                    <a class="card" href="{{ route('admin.campus-programs') }}">
                        <div class="card-header d-flex align-items-start pb-0">
                            <div>
                                <h2 class="text-bold-700 mb-0">{{ $campusPrograms }}</h2>
                                <p>Campus Programs</p>
                            </div>
                            <div class="avatar bg-rgba-warning p-50 m-0">
                                <div class="avatar-content">
                                    <i class="fa fa-columns text-warning font-medium-5"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        @endif
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
@endsection
