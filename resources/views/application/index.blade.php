@extends('layouts/contentLayoutMaster')

@section('title', 'Applications')

@section('vendor-style')

@endsection
@section('page-style')
    <link rel="stylesheet" href="{{ asset(mix('css/pages/app-chat.css')) }}" />
    <style>
        .modal-dialog-aside {
            width: 44% !important;
        }
    </style>
@endsection

@section('content')
    <section id="basic-datatable">
        <div class="row align-items-center mb-2">
            <div class="col-md-3 col-12">
                <div class="form-group">
                    <label for="intake">Program Name</label>
                    <select data-style="bg-white border-light" class="select form-control" name="program" id="campus-program">
                        <option value="">All Programs</option>
                        @foreach ($programs as $program)
                            <option {{ request()->get('id') == $program->campus_program_id ? 'selected' : '' }}
                                value="{{ $program->campus_program_id }}">{{ \Str::limit($program->program, 40, '...') }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3 col-12">
                <div class="form-group">
                    <label for="intake">Intake</label>
                    <select data-style="bg-white border-light" class="select form-control" name="intake" id="intake">
                        <option value="">All Intakes</option>
                        @foreach ($intakes as $intake)
                            <option {{ request()->get('intake') == $intake->intake_id ? 'selected' : '' }}
                                value="{{ $intake->intake_id }}">{{ $intake->name }}</option>
                        @endforeach
                    </select>
                </div>

            </div>
            <div class="col-md-3 col-12">
                <div class="form-group">
                    <label for="year">Year</label>
                    <select data-style="bg-white border-light" class="select form-control" name="year" id="year">
                        <option value="">All Years</option>
                        @foreach ($years as $year)
                            <option {{ request()->get('year') == $year->year ? 'selected' : '' }}
                                value="{{ $year->year }}">
                                {{ $year->year }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3 col-12">
                <div class="form-group">
                    <label for="application_id">Application Id</label>
                    <select data-style="bg-white border-light" class="select form-control" name="application_id[]"
                        data-live-search="true" name="application_id" multiple id="application_id">

                        @if (session()->has('application_id_message'))
                            @php $application_id_message=session()->get('application_id_message'); @endphp
                        @else
                            @php $application_id_message = [] ;@endphp
                        @endif

                        @foreach ($application_numbers as $application_number)
                            <option
                                {{ request()->get('application_id') == $application_number->id || in_array($application_number->id, $application_id_message) ? 'selected' : '' }}
                                value="{{ $application_number->id }}">
                                {{ $application_number->application_number }}</option>
                        @endforeach
                    </select>
                </div>
            </div>


            <div class="col-md-3 col-12 text-right">
                <button class="btn  btn-outline-primary clear-filter">Clear</button>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    {{-- <div class="card-header"> application_number

                    </div> --}}
                    <div class="card-content">
                        <div class="card-body card-dashboard">
                            <div class="table-responsive">
                                <table id="application-table" class="table table-hover w-100 zero-configuration">
                                    <thead>
                                        <tr>
                                            <th>Application ID</th>
                                            <th>University Name</th>
                                            <th>Program Name</th>
                                            <th>Intake</th>
                                            <th>Status</th>
                                            <th>Applied Date</th>
                                            <th>Action</th>
                                            <th>Application</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('vendor-script')

@endsection
@section('page-script')
    <!-- Page js files -->



    <script src="{{ asset(mix('js/scripts/pages/app-chat.js')) }}"></script>
    <script>
        $(document).ready(function() {




            $("#showlatestmessage").click(function() {


                let applictionIdLatestMessage = "";
                console.log(typeof($(this).data('id')));
                if ((typeof(($(this).data('id'))) == 'number')) {
                    applictionIdLatestMessage = [parseInt($(this).data('id'))];
                } else {

                    applictionIdLatestMessage = $(this).data('id').split(',');
                }





                $("#application_id").val(applictionIdLatestMessage);
                $(".select").selectpicker('refresh');
                dataTable.draw();



            });



            $(".select").selectpicker();
            $(".clear-filter").click(function() {
                $("select[name='intake'], select[name='year'],select[name='program'],select[name='application_id[]']")
                    .val("");
                $(".select").selectpicker('refresh');
                dataTable.draw();
            })
            let dataTable;
            dataTable = $("#application-table").DataTable({
                "processing": true,
                "serverSide": true,
                ajax: {
                    url: "{{ route('applications') }}",
                    data: function(d) {
                        d.id = $('#campus-program').val();
                        d.intake = $("select[name='intake']").val();
                        d.year = $("select[name='year']").val();
                        d.application_id = $("#application_id").val();
                    }
                },
                //dom: "tps",
                "order": [
                    [6, "desc"]
                ],
                columns: [
                    // {
                    //       name: 'campus.name',
                    //       data: 'campus'
                    //   },
                    {
                        name: 'users_applications.application_number',
                        data: 'application_number'
                    },
                    {
                        name: 'universities.name',
                        data: 'university'
                    },
                    {
                        name: 'programs.name',
                        data: 'program'
                    },
                    {
                        name: 'users_applications.year',
                        data: 'year'
                    },
                    {
                        name: 'status',
                        data: 'status'
                    },
                    {
                        name: 'users_applications.created_at',
                        data: 'apply_date'
                    },
                    {
                        name: 'count',
                        data: 'count',
                        orderable: false,
                        //searching: false
                        searchable: false
                        //"visible":false,
                    },
                    {
                        name: 'id',
                        data: 'id',
                    }
                ],
                responsive: false,

                drawCallback: function(setting, data) {

                    let setTime;
                    clearTimeout(setTime);
                    setTime = setTimeout(() => {
                        // messgeTable.draw('page');
                    }, 10000);

                },
                bInfo: true,
                pageLength: 100,
                initComplete: function(settings, json) {

                    // setInterval(()=>{
                    //   dataTable.ajax.reload(null,false);
                    // },5000);
                }
            });

            $(document).on('click', '.action-application', function() {
                let that = $(this)
                if (confirm($(this).data('msg'))) {
                    $.ajax({
                        // url: route('application-remove'),
                        url: $(this).data('url'),
                        type: 'GET',
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: $(this).data('id')
                        },
                        beforeSend: function() {
                            that.attr('disabled', true).prepend(
                                "<i class='fa fa-spinner fa-spin'></i> ");
                        },
                        success: function(data) {
                            setAlert(data);
                            dataTable.draw('page');
                            that.removeAttr('disabled').html();
                        }
                    });
                }
            });

            $(document).on('click', '.user-message', function() {

                $('.dynamic-title').text('Chat with us');
                message_scenario = $(this).attr("data-custom");
                id = $(this).data('id');
                $.ajax({
                    url: "{{ url('application') }}" + "/" + id + "/" + "message",
                    data: {
                        _token: "{{ csrf_token() }}",
                        message_scenario: message_scenario,

                    },

                    success: function(data) {
                        $('.dynamic-body').html(data);
                        initMessageScript(id);
                    }
                })
            });

            $('#dynamic-modal').on('hidden.bs.modal', function() {
                dataTable.draw('page');
            });

            $("select[name='intake'], select[name='year'], select[name='program'],select[name='application_id[]']")
                .change(function() {

                    dataTable.draw();
                });


        });
    </script>
@endsection
