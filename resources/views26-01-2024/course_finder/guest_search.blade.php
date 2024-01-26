@extends('layouts.beta_layout')
@section('head_script')
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
    {{-- <link rel="stylesheet" href="//cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css"> --}}
    <link rel="stylesheet" href="{{ asset(mix('css/pages/data-list-view.css')) }}">
    {{-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/datatables.min.css"/> --}}
    <style>
        #advance-search-box {}

        #result-search_wrapper {
            margin-bottom: 2%;
        }

        .btn-primary {
            color: white !important;
        }

        .select2-selection__choice__remove {
            color: white !important;
        }

        .select2-selection__choice {
            background: #ff8510 !important;
            color: white;
            margin: 1% 0%;
        }
    </style>

@section('content')
    <div class="container">
        <h1 class="text-center m-y-2">Course Finder</h1>
        <div class="row justify-content-center">
            <div class="col-md-4 col-12">
                <label for="">What you want to study?</label>
                {{-- <select name="program" class="select-2 form-control" id="program" multiple>
            @foreach ($programs as $program)
                <option {{ ($program->id == request()->get('program')) ? "selected" : "" }} value="{{ $program->id }}">{{ $program->name }}</option>
            @endforeach
        </select> --}}
                <input placeholder="Search for Courses/Keyword" type="text" name="what" class="form-control"
                    value="{{ request()->get('what') }}" id="what">
            </div>
            <div class="col-md-4 col-12 mt-1 mt-md-0">
                <label for="">Where do you want to study?</label>
                {{-- <select class="form-control select-2" name="country" id="country" multiple>
              @foreach ($countries as $country)
                  <option {{ ($country->id == request()->get('country_id')) ? "selected" : "" }} value="{{ $country->id }}">{{ $country->name }}</option>
              @endforeach
          </select> --}}
                <input placeholder="Search for Country/Keyword" type="text" class="form-control"
                    value="{{ request()->get('where') }}" id="where">
            </div>
        </div>
        <div class="row text-center mt-2">
            <div class="col-md-12">
                <button type="button" class="btn btn-primary" id="guest-search" style="">Search</button>
                <button type="button" class="btn btn-primary" id="guest-reset" style="">Reset</button>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h4 class="mt-2 text-center">Search Results</h4>
                    <section id="data-thumb-view" class="data-thumb-view-header">
                        <div class="action-btns d-none">
                            <div class="btn-dropdown mr-1 mb-1">
                                <div class="btn-group dropdown actions-dropodown">
                                    <button type="button"
                                        class="btn btn-white px-1 py-1 dropdown-toggle waves-effect waves-light"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Actions
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="#"><i class="feather icon-trash"></i>Delete</a>
                                        <a class="dropdown-item" href="#"><i
                                                class="feather icon-archive"></i>Archive</a>
                                        <a class="dropdown-item" href="#"><i class="feather icon-file"></i>Print</a>
                                        <a class="dropdown-item" href="#"><i class="feather icon-save"></i>Another
                                            Action</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- dataTable starts --}}
                        <div class="table-responsive">
                            <table id="result-search" class="table data-thumb-view w-100" style="width:100%">
                                <thead class="d-none">
                                    <th>Logo</th>
                                    <th>Search Results</th>
                                    {{-- <th>Admission Fee</th> --}}
                                    {{-- <th>Tution Fee</th> --}}
                                </thead>
                            </table>
                        </div>
                </div>
                </section>
            </div>
        </div>
    </div>
    </div>
@endsection
@section('foot_script')
    {{-- <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script> --}}
    {{-- <script src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>
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
