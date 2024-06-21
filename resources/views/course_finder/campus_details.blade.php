@inject('shortList', 'App\Http\Controllers\UserShortlistProgramController')
@extends(Auth::check() ? 'layouts.contentLayoutMaster' : 'layouts.beta_layout')
@section('title', $campus->name)
@section('page-style')
    <link rel="stylesheet" href="{{ asset(mix('css/pages/data-list-view.css')) }}">
@endsection
@section('content')
    <section id="basic-datatable" class="@if (!Auth::check()) px-1 @endif">
        <div class="row w-100">
            <div class="col-12 @if (!Auth::check()) pr-0 @endif ">
                <div class="card ">
                    <div class="card-body card-dashboard">
                        <div class="row">
                            <div class="col-md-3 col-12">
                                @if (!empty($campus->getLogo()))
                                    <div class="text-center">
                                        <img src="{{ $campus->getLogo() }}" alt="Campus Logo" class="img-fluid">
                                    </div>
                                @endif
                                <div>
                                    <h4>{{ $campus->name }}</h4>
                                    <p>
                                        <strong><i class="fa fa-university" aria-hidden="true"></i></strong>
                                        {{ $campus->getUniversity->name }}
                                    </p>
                                    <strong>Address</strong>
                                    @php
                                        $address = $campus->address;
                                    @endphp
                                    @if (isset($address) || !is_null($address))
                                        <p>
                                            <strong>
                                                <i class="fa fa-map-marker" aria-hidden="true"></i>
                                            </strong>
                                            {{ $address->address ?? '' }}, {{ $address->country->name ?? '' }},
                                            {{ $address->state->name ?? '' }},
                                            {{ $address->city->name ?? '' }}
                                        </p>
                                    @else
                                        <div>
                                            <strong>N/A</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-9 col-12">
                                @if (!empty($campus->about_us))
                                    <div class="row">
                                        <div class="col-md-9 col-12">
                                            <h4>About</h4>
                                            @if (empty($campus->about_us))
                                                <strong class="">N/A</strong>
                                            @else
                                                {!! $campus->about_us !!}
                                            @endif
                                        </div>
                                    </div>
                                    <hr>
                                @endif

                                @if (!empty($campus->feature))
                                    <div class="row">
                                        <div class="col-12">
                                            <h4>Features</h4>
                                            @if (empty($campus->feature))
                                                <strong class="">N/A</strong>
                                            @else
                                                {!! $campus->feature !!}
                                            @endif
                                        </div>
                                    </div>
                                    <hr>
                                @endif
                                <div class="row">
                                    <div class="col-12">
                                        <h4>Programs</h4>
                                        <div class="table-responsive">
                                            <table class="table data-thumb-view w-100 border-0 pl-25 w-100"
                                                id="program-campus-table">
                                                <thead class="d-none">
                                                    <th>Action</th>
                                                    <th>Duration</th>
                                                    <th>Website</th>
                                                    <th>Study Area</th>
                                                    <th>Program Level</th>
                                                    <th>Action</th>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('page-script')
    <!-- Page js files -->
    <script>
        $(document).ready(function() {
            let id = "{{ $id }}";
            campusTable = $("#program-campus-table").DataTable({
                "processing": true,
                "serverSide": true,
                ajax: {
                    url: "{{ url('campus-search') }}" + "/" + id,
                    data: function(d) {

                    }
                },
                dom: "tp",
                //"order": [[1, "desc" ]],
                columns: [{
                    name: 'action',
                    data: 'action',
                    orderable: false,
                    searching: false

                }, ],
                responsive: false,
                "language": {
                    "emptyTable": "No Programs Yet"
                },
                drawCallback: function(setting, data) {
                    // $(".table-img").each(function() {
                    //     $(this).parent().addClass('product-img');
                    //     $(this).parent().addClass('text-center');
                    // });
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

                bInfo: false,
                pageLength: 10,
                initComplete: function(settings, json) {
                    $(".dt-buttons .btn").removeClass("btn-secondary");
                    $(".table-img").each(function() {
                        $(this).parent().addClass('product-img');
                    });

                    // setInterval(()=>{
                    //   messgeTable.ajax.reload(null,false);
                    //   },5000);
                }
            });
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
                        campusTable.draw('page');
                    }
                });

            });

            $(document).on('click', '.apply', function() {
                id = $(this).data('id');

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

            $(document).on('click', '.remove', function() {
                let that = $(this);

                if (confirm('Are you sure  you want to remove this from shortlist ?')) {
                    $.ajax({
                        url: "{{ route('shortlist-programs-remove') }}",
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
                            campusTable.draw('page');
                            //that.removeAttr('disabled').html('Remove Program');


                        }
                    });
                }
            });
        });
    </script>
@endsection
