@extends('layouts.contentLayoutMaster')

@section('title', 'Course Finder')
@section('vendor-style')
    <!-- vendor css files -->
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/nouislider.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/pages/app-chat.css')) }}" />
@endsection
@section('page-style')
    <!-- Page css files -->
    <link rel="stylesheet" href="{{ asset(mix('css/plugins/extensions/noui-slider.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/core/colors/palette-noui.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/pages/data-list-view.css')) }}">
    <style>
        /* .select2-results__option .wrap:before{
                                                          font-family:fontAwesome;
                                                          color:#999;
                                                          content:"\f096";
                                                          width:25px;
                                                          height:25px;
                                                          padding-right: 10px;

                                                      }
                                                      .select2-results__option[aria-selected=true] .wrap:before{
                                                          content:"\f14a";
                                                      } */
        tr {
            box-shadow: 0 0.5rem 1rem rgba(34, 41, 47, 0.15) !important;
        }

        /* .bs-select-all, .bs-select-all:hover,.bs-deselect-all, .bs-deselect-all:hover  {
                                                        background: white!important;
                                                        color: black!important;
                                                        border: 1px solid #babfc7 !important;
                                                      }
                                                      .dropdown-header {
                                                          font-weight: bold!important;
                                                          color: #626262;
                                                          cursor: pointer;
                                                      }
                                                      .dropdown .dropdown-menu::before {
                                                          content: unset!important;
                                                      }
                                                      .dropdown-toggle:focus {
                                                        outline-color: white!important;
                                                      } */
        ist-view.dataTable tbody tr,
        table.data-thumb-view.dataTable tbody tr {
            cursor: default;
        }

        .sticky {
            position: fixed !important;
            top: 0;
            z-index: 999999;
        }

        .card-header {
            position: relative !important;
        }
    </style>

    <style>
        @media(max-width: 1100px) {
            .chat-app-window .univ-box.user-chats .vs-checkbox-con {
                display: block;
            }

            .chat-app-window .univ-box.user-chats span.vs-checkbox {
                display: inline-block;
                width: 20px;
                vertical-align: top;
            }

            .chat-app-window .univ-box.user-chats span.font-weight-bold {
                display: inline-block;
                width: 75%;
            }
        }

        @media(max-width: 767px) {
            .univ-box.user-chats span.font-weight-bold {
                display: inline-block;
                width: 90%;
            }
        }

        @media(max-width: 480px) {
            .univ-box.user-chats span.font-weight-bold {
                display: inline-block;
                width: 80%;
            }
        }
    </style>
@endsection


@section('content')
    <div class="upper-section">
        {{-- Top Search Section --}}
        <section class="">
            <div class="row justify-content-center align-items-end">
                <div class="col-md-3 col-sm-12">
                    <label class="h5 text-primary font-weight-bold" for="what">What you want to study?</label>
                    <input placeholder="Search for Courses/Keyword" type="text" name="what" class="form-control"
                        value="{{ request()->get('what') }}" id="what">
                </div>
                <div class="col-md-3 col-sm-12 mt-2 mt-md-0">
                    <label class="h5 text-primary font-weight-bold" for="">Where do you want to study?</label>
                    <input placeholder="Search for Country/Keyword" type="text" class="form-control"
                        value="{{ request()->get('where') }}" id="where">
                </div>
                <div class="col-md-3 col-sm-12 mt-2 mt-md-0 course-finder-year">
                    <label class="h5 text-primary font-weight-bold ">Year</label>
                    <select data-style="bg-white border-light" class="form-control select" id="ygrad_year" name="ygrad_year"
                        multiple>
                        @php $g_year = !is_null(request()->get('year')) ? request()->get('year') : []; @endphp
                        @for ($i = date('Y'); $i < date('Y') + 3; $i++)
                            <option {{ in_array($i, $g_year) ? 'selected' : '' }} value="{{ $i }}">
                                {{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-3 top-search-options col-sm-12 text-md-right text-sm-center text-center  mt-2 mt-md-0">
                    <button type="button" class="btn btn-icon btn-outline-primary reset-search">Reset
                    </button>
                    <button type="button" class="btn btn-icon btn-primary search-submit">Search
                    </button>
                </div>
            </div>
            <div class="row justify-content-center mt-2 text-center">
                <div class="col-12">
                    <button class="btn btn-icon btn-primary toggle-advance"><span class="fa fa-search"></span> Advance
                        Search</button>
                </div>
            </div>
        </section>

        {{-- Advance Search box --}}
        <section class=" mt-2 d-none advance-search-box">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <div id="advance-search-box">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="h5 text-primary font-weight-bold" for="program-level">Program
                                            Level</label>
                                        <select data-actions-box="true" data-style="bg-white border-light"
                                            class="form-control select" id="program-level" name="programlevel[]" multiple>
                                            @foreach ($programLevels as $programLevel)
                                                <option value="{{ $programLevel->id }}">{{ $programLevel->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="h5 text-primary font-weight-bold" for="">Intakes</label>
                                        <select data-actions-box="true" data-style="bg-white border-light"
                                            class="form-control select" id="intakes" name="intakes" multiple>
                                            <optgroup label="Jan - April">
                                                @foreach ($intakes as $intake)
                                                    @if ($intake->group_name == 'Jan')
                                                        <option @if ($selectedIntake == $intake->id) selected @endif
                                                            value="{{ $intake->id }}">{{ $intake->name }}</option>
                                                    @endif
                                                @endforeach
                                            </optgroup>
                                            <optgroup label="May - Aug">
                                                @foreach ($intakes as $intake)
                                                    @if ($intake->group_name == 'May')
                                                        <option @if ($selectedIntake == $intake->id) selected @endif
                                                            value="{{ $intake->id }}">{{ $intake->name }}</option>
                                                    @endif
                                                @endforeach
                                            </optgroup>
                                            <optgroup label="Sep - Dec">
                                                @foreach ($intakes as $intake)
                                                    @if ($intake->group_name == 'Sep')
                                                        <option @if ($selectedIntake == $intake->id) selected @endif
                                                            value="{{ $intake->id }}">{{ $intake->name }}</option>
                                                    @endif
                                                @endforeach
                                            </optgroup>
                                            <optgroup label="Season">
                                                @foreach ($intakes as $intake)
                                                    @if ($intake->type == 'Season')
                                                        <option @if ($selectedIntake == $intake->id) selected @endif
                                                            value="{{ $intake->id }}">{{ $intake->name }}</option>
                                                    @endif
                                                @endforeach
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="h5 text-primary font-weight-bold">Duration</label>
                                        <select data-style="bg-white border-light" class="form-control select"
                                            id="duration" name="duration" multiple>
                                            {{-- @for ($duration = 2; $duration <= 5; $duration++)
                                                <option value="{{ $duration }}">{{ $duration }} Years</option>
                                            @endfor --}}
                                            @foreach ([
            '0 - 1 Years' => [0, 1],
            '1 - 2 Years' => [1, 2],
            '2 - 3 Years' => [2, 3],
            '3 - 4 Years' => [3, 4],
            '4 and above Years' => [4, 10],
        ] as $duration => $value)
                                                <option value="{{ json_encode($value) }}">{{ $duration }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="h5 text-primary font-weight-bold"
                                            for="special-test">Requirements</label>
                                        <select data-actions-box="true" data-style="bg-white border-light"
                                            class="form-control select" id="special-test" name="special_test[]" multiple>
                                            @foreach ($special_tests as $special_test)
                                                <option value="{{ $special_test->id }}">{{ $special_test->test_name }}
                                                </option>
                                            @endforeach
                                        </select>

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="h5 text-primary font-weight-bold">Country</label>
                                        <select data-live-search="true" data-style="bg-white border-light"
                                            class="form-control select" id="country" name="countries[]" id="country"
                                            multiple>
                                            @foreach ($countries as $country)
                                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="h5 text-primary font-weight-bold">Study Area</label>
                                        <select data-live-search="true" data-style="bg-white border-light"
                                            class="form-control select" id="study-area" name="study_area[]"
                                            id="study-area" multiple>
                                            @foreach ($studyAreas as $studyArea)
                                                <option value="{{ $studyArea->id }}">{{ $studyArea->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    @php
                                        $selectedDiscipline = DB::table('programs')
                                            ->where('id', request()->get('program'))
                                            ->first();
                                    @endphp
                                    <div class="form-group">
                                        <label class="h5 text-primary font-weight-bold">Discipline Area</label>
                                        <select data-live-search="true" data-style="border-light bg-white"
                                            class="form-control select" id="discipline-area" name="discipline[]"
                                            multiple>
                                            @if (isset($selectedDiscipline))
                                                <option value="{{ $selectedDiscipline->id }}" selected>
                                                    {{ $selectedDiscipline->name }}</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row align-items-end">
                                {{-- <div class="col-md-3">
                                    <label class="h5 text-primary font-weight-bold">Sort</label>
                                    @php $f=2;@endphp
                                    <select data-style="bg-white border-light"  id="col-sorting" class="form-control select">
                                        <option value="">--Order By--</option>
                                        <optgroup label="Program Fees">
                                            @foreach ($feeTypes as $feeType)
                                                @if ($feeType->name == 'Admission Fees') @php $f++; @endphp @continue @endif
                                                <option value="{{ $f }}" >{{ $feeType->name }}</option>
                                                @php $f++;@endphp
                                            @endforeach
                                        </optgroup>
                                        <optgroup label="Other">
                                            <option value="{{ $f }}">Country</option> @php $f++; @endphp
                                            <option value="{{ $f }}">Duration</option>@php $f++; @endphp
                                            <option value="{{ $f }}">University</option>
                                        </optgroup>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select data-style="bg-white border-light" id="sort-order" class="form-control select">
                                        <option value="">--Order--</option>
                                        <option value="ASC">Ascending</option>
                                        <option value="DESC">Descending</option>
                                    </select>
                                </div> --}}
                                <div class="col-md-3"></div>
                                <div class="col-md-3 text-right">
                                    <button type="button"
                                        class="btn btn-icon btn-outline-primary reset-search">Reset</button>
                                    <button type="button" class="btn btn-icon btn-primary search-submit">Search</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="card-footer text-right border-0 bg-white">
                    </div> --}}
                </div>
            </div>

        </section>
    </div>



    <section id="search-result-section" class="mt-2">
        <div class="card">
            <div class="card-header">
                <div class="row w-100 align-items-center">
                    <div class="col-md-3 col-12">
                        <h2 class="filter-title"><span class="fa fa-filter"></span> Filters</h2>
                    </div>
                    <div class="col-md-6 col-12 mt-1 mt-md-0">
                        <h2 class='d-inline-block'>Search Results</h2> <span
                            class="d-inline-block dx-1 result-count text-primary font-weight-bold"></span>
                    </div>
                    <div class="col-md-3 mt-1 mt-md-0 col-12 pr-0 text-md-right">
                        @php $f=2;@endphp
                        <div class="d-inline-block w-50">
                            <select data-style="bg-white border-light" id="col-sorting" class="form-control select">
                                <option value="">Sort by</option>
                                <optgroup label="Program Fees">
                                    @foreach ($feeTypes as $feeType)
                                        @if ($feeType->name == 'Admission Fees')
                                            @php $f++; @endphp @continue
                                        @endif
                                        <option value="{{ $f }}">{{ $feeType->name }}</option>
                                        @php $f++;@endphp
                                    @endforeach
                                </optgroup>
                                <optgroup label="Other">
                                    <option value="{{ $f }}">Country</option> @php $f++; @endphp
                                    <option value="{{ $f }}">Duration</option>@php $f++; @endphp
                                    <option value="{{ $f }}">University</option>
                                </optgroup>
                            </select>
                        </div>
                        <div class="d-inline-block">
                            <button id="sort-order" data-val="ASC" class='btn btn-outline-light btn-icon'>
                                <i class="fa fa-sort-amount-asc text-dark"></i>
                            </button>
                            {{-- <select data-style="bg-white border-light" id="sort-order" class="form-control select">
                                <option value="">--Order--</option>
                                <option value="ASC">Ascending</option>
                                <option value="DESC">Descending</option>
                            </select> --}}
                        </div>

                    </div>
                </div>
            </div>
            <div class="card-content">
                <div class="card-body pt-0">
                    <div class="row">
                        <div class="col-md-3 col-12 pt-2">
                            <div class="card chat-widget">
                                <div class="card-header bg-primary  rounded" style="padding: 8px;">
                                    <h4 class="card-title text-white">Universities</h4>
                                </div>
                                <div class="chat-app-window">
                                    <div class="univ-box user-chats text-left px-0 pl-1 py-0"
                                        style="height: auto; max-height: 200px;">
                                        @forelse (config('universities') as $univ)
                                            <div data-toggle='tooltip' title="{{ html_friendly($univ->name) }}"
                                                class="vs-checkbox-con vs-checkbox-primary mt-1">
                                                <input name='univs[]' id="univ_{{ $univ->id }}"
                                                    value="{{ $univ->id }}" type="checkbox" />
                                                <span class="vs-checkbox">
                                                    <span class="vs-checkbox--check">
                                                        <i class="vs-icon feather icon-check"></i>
                                                    </span>
                                                </span>
                                                <span class="font-weight-bold">
                                                    {!! \Str::limit($univ->name, 30) !!}
                                                </span>
                                            </div>
                                        @empty
                                            <div class="p-1"><strong>No universities found</strong></div>
                                        @endforelse
                                    </div>
                                    <div class="chat-footer d-none">
                                        <div class="card-body d-flex justify-content-around pt-0">
                                            <input type="text" class="form-control mr-50"
                                                placeholder="Type your Message">
                                            <button type="button" class="btn btn-icon btn-primary"><i
                                                    class="feather icon-navigation"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card chat-widget d-none">
                                <div class="card-header bg-primary  rounded" style="padding: 8px;">
                                    <h4 class="card-title text-white">Program Levels</h4>
                                </div>
                                <div class="chat-app-window">
                                    <div class="program-level-box user-chats text-left px-0 pl-1 py-0 pt-1"
                                        style="height: auto; max-height: 200px;">
                                        @foreach (config('program_levels') as $level)
                                            <div>
                                                <label class="h5 text-left " for="level_{{ $level->id }}">
                                                    <input id="level_{{ $level->id }}" type="checkbox" />
                                                    {{ $level->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="chat-footer d-none">
                                        <div class="card-body d-flex justify-content-around pt-0">
                                            <input type="text" class="form-control mr-50"
                                                placeholder="Type your Message">
                                            <button type="button" class="btn btn-icon btn-primary"><i
                                                    class="feather icon-navigation"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>



                            @foreach ($feeTypes as $feeType)
                                <div class="form-group {{ $feeType->name == 'Admission Fees' ? 'd-none' : '' }}">
                                    <h4 class="my-1">{{ $feeType->name }}</h4>
                                    <div id="slider-{{ $feeType->id }}" class="my-1 fee-slider"
                                        data-fee ="{{ Str::slug($feeType->name, '_') }}"></div>
                                    <div class="">
                                        <span class="{{ Str::slug($feeType->name, '_') }}-min">left</span> <span
                                            class="float-right {{ Str::slug($feeType->name, '_') }}-max">right</span>
                                    </div>
                                </div>
                            @endforeach


                        </div>

                        <div class="col-md-9 col-12 px-0">
                            <section id="data-thumb-view" class="data-thumb-view-header">
                                {{-- dataTable starts --}}
                                <div class="table-responsive">
                                    <table id="result-search" class="table data-thumb-view w-100  border-0"
                                        style="width:100%">
                                        <thead class="d-none">
                                            <th>Logo</th>
                                            <th>Search Results</th>
                                            @foreach ($feeTypes as $feeType)
                                                <th>{{ Str::slug($feeType->name, '_') }}</th>
                                            @endforeach
                                            <th>Country</th>
                                            <th>Duration</th>
                                            <th>University</th>
                                        </thead>
                                    </table>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('vendor-script')
    <!-- vendor files -->
    <script src="{{ asset(mix('vendors/js/extensions/wNumb.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/extensions/nouislider.min.js')) }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>
    <script src="{{ asset('js/scripts/select2.multi-checkboxes.js') }}"></script>













    <script>
        // Chat Application
        (function($) {
            "use strict";
            // Chat area
            if ($('.program-level-box').length > 0) {
                var chat_user = new PerfectScrollbar(".program-level-box", {
                    wheelPropagation: false
                });
            }
            if ($('.univ-box').length > 0) {
                var chat_user = new PerfectScrollbar(".univ-box", {
                    wheelPropagation: false
                });
            }

        })(jQuery);
    </script>
@endsection
@section('page-script')
    <script>
        var fee = {};
        var feePrice = {};

        $(document).ready(function() {
            initPlugins();
            initResultTable();

            // Toggle advance search
            $(".toggle-advance").on("click", function() {
                $(".advance-search-box").toggleClass('d-none', 1000);
                $(".top-search-options").toggleClass('d-none', 1000);
            });

            // Handle Sorting event
            $('#col-sorting').on('change', function() {
                sortColumn();
            });
            $('#sort-order').on('click', function() {
                if ($(this).attr('data-val') == "ASC") {
                    $(this).attr('data-val', "DESC");
                    $(this).attr('title', "Descending");

                    $(this).find("i").removeClass("fa-sort-amount-asc");
                    $(this).find("i").addClass("fa-sort-amount-desc");
                } else {
                    $(this).attr('data-val', "ASC");
                    $(this).attr('title', "Ascending");
                    $(this).find("i").removeClass("fa-sort-amount-desc");
                    $(this).find("i").addClass("fa-sort-amount-asc");
                }
                sortColumn();
            });

            // Shortlist program
            $(document).on('click', '.shortlist-add', function() {
                let that = $(this);

                $.ajax({
                    url: "{{ route('shortlist-programs-add') }}",
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        campus_program_id: $(this).data('id')
                    },
                    beforeSend: function() {
                        that.attr('disabled', true).prepend(
                            "<i class='fa fa-spinner fa-spin'></i> ");
                    },
                    success: function(data) {
                        that.removeAttr('disabled').html('ShortList');
                        setAlert(data);
                        dataTable.draw('page');
                    }
                });
            });

            // Remove program for shortlist
            $(document).on('click', '.remove', function() {
                let that = $(this);

                if (confirm('Are you sure  you want to remove this from shortlist ?')) {
                    $.ajax({
                        url: route('shortlist-programs-remove'),
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: $(this).data('id')
                        },
                        beforeSend: function() {
                            // shortlist-programs
                            that.attr('disabled', true).prepend(
                                "<i class='fa fa-spinner fa-spin'></i> ");
                        },
                        success: function(data) {
                            setAlert(data);
                            dataTable.draw('page');
                            that.removeAttr('disabled').html('Remove');
                        }
                    });
                }
            });

            // Apply for Program
            $(document).on('click', '.apply', function() {
                id = $(this).data('id')
                $('.apply-title').text('Apply Now');
                $.ajax({
                    url: "{{ url('apply-application') }}" + "/" + id,
                    beforeSend: function() {
                        $('.dynamic-apply').html("Loading");
                    },
                    success: function(data) {
                        $('.dynamic-apply').html(data);
                    }
                })
            });

            // Filter by Universities
            $(document).on("change", "input[name='univs[]']", function() {
                dataTable.draw();
            });

            // Show subareas based on Study Area
            $('#study-area').change(function() {
                let programOption = '';
                // let id = $(this).val();
                let arr = [];
                $("#study-area").find("option:selected").each(function(value, index) {
                    arr.push($(this).val());
                })
                // if(id != ''){
                $.ajax({
                    url: "{{ route('get-sub-areas') }}",
                    type: 'post',
                    data: {
                        _token: "{{ csrf_token() }}",
                        ids: arr
                    },
                    beforeSend: function() {
                        $('#discipline-area').html("");
                        $('#discipline-area').selectpicker("refresh");

                    },
                    success: function(data) {
                        // data.forEach(program => {
                        //     programOption += `<option value='${program.id}'>${program.name}</option>`;
                        // });
                        programOption = "";
                        data.forEach(function(program) {
                            name = program.name;
                            if (program.name.length > 50) {
                                //name = program.name.substr(0, 50) + "...";
                                name = program.name;
                            }
                            return programOption +=
                                `<option value='${program.id}'>${name}</option>`;
                        })
                        // $('#discipline-area').find('option').remove().end();
                        $('#discipline-area').html(programOption);
                        $("#discipline-area").selectpicker('refresh');
                    }
                })
                // }

            });

            // Submit Search
            $(".search-submit").on("click", function() {
                $('html, body').animate({
                    scrollTop: $("#search-result-section").offset().top
                }, 1000);
                dataTable.draw();
            });

            // Toggle Advance Search
            $('#advance-search').on('click', function() {
                $('#advance-search-box').toggleClass('d-none');
                if ($("#advance-search-box").hasClass('d-none')) {
                    $(this).html("Show Advance Search");
                } else {
                    $(this).html("Hide Advance Search");
                }
                $(".search-submit-no-auth").toggleClass('d-none');
            });

            // Reset Search
            $(document).on('click', '.reset-search', function() {
                resetSearch();
            });
        });
    </script>
    <script>
        function enableBoostrapSelectOptgroup() {
            let that = $(this).data('selectpicker'),
                inner = that.$menu.children('.inner');

            // remove default event
            inner.off('click', '.divider, .dropdown-header');
            // add new event
            inner.on('click', '.divider, .dropdown-header', function(e) {
                // original functionality
                e.preventDefault();
                e.stopPropagation();
                if (that.options.liveSearch) {
                    that.$searchbox.trigger('focus');
                } else {
                    that.$button.trigger('focus');
                }

                // extended functionality
                let position0 = that.isVirtual() ? that.selectpicker.view.position0 : 0,
                    clickedData = that.selectpicker.current.data[$(this).index() + position0];

                // copied parts from changeAll function
                let selected = null;
                for (let i = 0, data = that.selectpicker.current.data, len = data.length; i < len; i++) {
                    let element = data[i];
                    if (element.type === 'option' && element.optID === clickedData.optID) {
                        if (selected === null) {
                            selected = !element.selected;
                        }
                        element.option.selected = selected;
                    }
                }
                that.setOptionStatus();
                that.$element.triggerNative('change');
            });
        }

        function initPlugins() {
            $(".select").selectpicker();
            $('.select').selectpicker().on('loaded.bs.select', enableBoostrapSelectOptgroup);
            $('#col-sorting').selectpicker();
            $('#sort-order').selectpicker();
            $('#col-sorting').selectpicker();
            $('#sort-order').selectpicker();
            $('.select-2').select2({
                placeholder: '--Select--',
                width: '100%'
            });
            $(".select-2").selectpicker();
            $('#discipline-area').selectpicker();

            fees = {};
            feePrice = {};

            $(".fee-slider").each(function() {
                let min = 0;
                let max = 99999;
                let fee_key = $(this).data('fee');
                fees[fee_key] = $(this)[0];
                feePrice[fee_key] = ["", ""];
                noUiSlider.create(fees[fee_key], {
                    start: [min, max],
                    connect: true,
                    decimals: 0,
                    step: 500,
                    range: {
                        'min': min,
                        'max': max
                    }
                });

                fees[fee_key].noUiSlider.on('update', function(values, handle) {
                    key = this.target.dataset.fee;
                    $('.' + key + '-min').text(values[0]);
                    $('.' + key + '-max').text(values[1]);
                    feePrice[key] = values;
                });
                fees[fee_key].noUiSlider.on('change', function(values, handle) {
                    dataTable.draw();
                });
            });
        }

        function initResultTable() {
            dataTable = $("#result-search").DataTable({
                "processing": true,
                "serverSide": true,
                "pageLength": 100,
                ajax: {
                    url: "{{ route('getprogram') }}",
                    data: function(d) {
                        d.what = $('#what').val();
                        d.where = $('#where').val();

                        d.program_levels = $("#program-level").val();
                        d.intakes = $('#intakes').val();
                        d.duration = $('#duration').val();
                        d.special_tests = $("#special-test").val();
                        d.country_id = $("#country").val();
                        d.study_area = $('#study-area').val();
                        d.discipline = $('#discipline-area').val();

                        let univs = [];
                        $('input[name="univs[]"]:checked').each(function(i) {
                            univs.push($(this).val());
                        });
                        d.univs = univs;
                        d.fee = feePrice;

                        if ($("#col-sorting").val() !== "" && $("#sort-order").attr('data-val') !== "") {
                            d.order[0].column = $("#col-sorting").val();
                            d.order[0].dir = $("#sort-order").attr('data-val');
                        }


                    },
                    beforeSend: function() {
                        $('.filter-title').append("   <i class='fa fa-spin fa-spinner'></i>");
                    },
                    complete: function(data) {
                        $('.filter-title').html("<span class='fa fa-filter'></span> Filters");
                        $(".result-count").html("<u>" + data.responseJSON.recordsTotal + " Programs found</u>");
                        $('html, body').animate({
                            scrollTop: $("#search-result-section").offset().top
                        }, 1000);
                        // try {
                        //     let univs = data.responseJSON.universities;
                        //     renderUnivs(univs);
                        // } catch(e) {
                        //     console.log("Error", e);
                        // }
                    }
                },
                columns: [{
                        data: 'logo',
                        "visible": false
                    },
                    {
                        data: 'row',
                    },
                    @foreach ($slugs as $slug)
                        {
                            data: "{{ $slug }}",
                            "visible": false
                        },
                    @endforeach {
                        data: 'country',
                        name: 'countries.name',
                        visible: false
                    },
                    {
                        data: 'duration',
                        name: 'campus_programs.campus_program_duration',
                        visible: false
                    },
                    {
                        data: 'universtiy',
                        name: 'universities.name',
                        visible: false
                    },

                ],
                responsive: false,
                // columnDefs: [
                //     {
                //         orderable: true,
                //         targets: 0,
                //         checkboxes: { selectRow: true }
                //     }
                // ],
                drawCallback: function(setting, data) {
                    $(".table-img").each(function() {
                        $(this).parent().addClass('product-img');
                        $(this).parent().addClass('text-center');
                    });
                },
                // dom:'<"top"<"actions action-btns"><"action-filters">><"clear">rt<"bottom"<"actions">p>',
                dom: '<"clear">rt<"bottom"<"actions">p>',
                oLanguage: {
                    sLengthMenu: "_MENU_",
                    sSearch: "",
                    sEmptyTable: "No results found"
                },
                aLengthMenu: [
                    [10, 15, 20],
                    [10, 15, 20]
                ],
                // select: {
                //     style: "multi"
                // },
                // order: [[1, "asc"]],
                bInfo: false,
                pageLength: 10,
                buttons: [{
                    text: "<i class='feather icon-plus'></i> Add New",
                    action: function() {
                        $(this).removeClass("btn-secondary")
                        $(".add-new-data").addClass("show")
                        $(".overlay-bg").addClass("show")
                        $("#data-name, #data-price").val("")
                        $("#data-category, #data-status").prop("selectedIndex", 0)
                    },
                    className: "btn-outline-primary"
                }],
                initComplete: function(settings, json) {
                    $(".dt-buttons .btn").removeClass("btn-secondary");
                    $(".table-img").each(function() {
                        $(this).parent().addClass('product-img');
                    });
                }
            });
        }

        function resetSearch() {
            // Input box
            $('#what').val("");
            $("#where").val("");

            // Select pickers
            $(".select").selectpicker('deselectAll');
            $(".select").val("");
            $(".select").selectpicker('refresh');

            // Checkboxes
            $('input[name="univs[]"]').prop("checked", false);

            // Program Fees
            for (fee_key in fees) {
                fees[fee_key].noUiSlider.reset();
            }
            dataTable.draw();
        }

        function sortColumn() {
            let column = $('#col-sorting').val();
            let order = $("#sort-order").attr('data-val');
            if (column != "" && order != "") {
                dataTable.order([column, order]).draw();
            }
        }



        function renderUnivs(univs) {
            let html = '';
            for (id in univs) {
                let checked = univs[id].checked ? 'checked' : '';
                html += `
                    <div class="vs-checkbox-con vs-checkbox-primary mt-1">
                        <input ${checked} name='univs[]' id="univ_${id}" value="${id}" type="checkbox" />
                        <span class="vs-checkbox">
                            <span class="vs-checkbox--check">
                                <i class="vs-icon feather icon-check"></i>
                            </span>
                        </span>
                        <span class="font-weight-bold">
                            ${univs[id].name}
                        </span>
                    </div>
                `;
            }
            $(".univ-box").html(html);
        }
    </script>


    <script>
        $(document).ready(function() {

            // $('.select-2').select2();

            dataTable = $("#result-search").DataTable({
                "processing": true,
                "serverSide": true,
                "pageLength": 100,
                ajax: {
                    url: `{{ route('getprogram') }}`,
                    type: "GET",
                    data: function(d) {
                        // d.program = $('#program').val();
                        // d.country_id = $("#country").val();
                        console.log(d);
                        d.what = $('#what').val();
                        d.where = $('#where').val();
                    }
                },
                columns: [{
                        data: 'logo',
                        "visible": false
                    },
                    {
                        data: 'row',
                    },
                ],
                responsive: false,
                // columnDefs: [
                //     {
                //         orderable: true,
                //         targets: 0,
                //         checkboxes: { selectRow: true }
                //     }
                // ],
                drawCallback: function(setting, data) {
                    $(".table-img").each(function() {
                        $(this).parent().addClass('product-img');
                        $(this).parent().addClass('text-center');
                    });

                    $('body,html').animate({
                        scrollTop: 290
                    }, 1000);
                },
                dom: '<<"actions action-btns"><"action-filters">><"clear">rt<"bottom"<"actions">p>',
                oLanguage: {
                    sLengthMenu: "_MENU_",
                    sSearch: "",
                    sEmptyTable: "No results found"
                },
                aLengthMenu: [
                    [10, 15, 20],
                    [10, 15, 20]
                ],
                // select: {
                //     style: "multi"
                // },
                // order: [[1, "asc"]],
                bInfo: false,
                pageLength: 10,
                buttons: [{
                    text: "<i class='feather icon-plus'></i> Add New",
                    action: function() {
                        $(this).removeClass("btn-secondary")
                        $(".add-new-data").addClass("show")
                        $(".overlay-bg").addClass("show")
                        $("#data-name, #data-price").val("")
                        $("#data-category, #data-status").prop("selectedIndex", 0)
                    },
                    className: "btn-outline-primary"
                }],
                initComplete: function(settings, json) {
                    $(".dt-buttons .btn").removeClass("btn-secondary");
                    $(".table-img").each(function() {
                        $(this).parent().addClass('product-img');
                    });
                }
            });

            $('#guest-search').click(function() {
                dataTable.draw("page");
            });

            $('#guest-reset').click(function() {
                $('#what').val("");
                $('#where').val("");
                dataTable.draw("page");
            });
        });

        var path = "{{ route('autocompletecourse') }}";
        $('#what').typeahead({
            autoSelect: false,
            source: function(query, process) {
                return $.get(path, {
                    query: query
                }, function(data) {
                    return process(data);
                });
            }
        });

        var path1 = "{{ route('autocompletecountry') }}";
        $('#where').typeahead({
            autoSelect: false,
            source: function(query, process) {
                return $.get(path1, {
                    query: query
                }, function(data) {
                    return process(data);
                });
            }
        });
    </script>






@endsection
