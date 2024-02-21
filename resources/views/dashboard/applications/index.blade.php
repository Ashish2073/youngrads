@extends('layouts/contentLayoutMaster')

@section('title', 'Applications')

@section('page-style')
    <link rel="stylesheet" href="{{ asset(mix('css/pages/app-chat.css')) }}">
    <!-- Add these links to your HTML file -->
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> --}}

    <style>
        .modal-dialog-aside {
            width: 44% !important;
        }

        .status {
            cursor: pointer;
        }

        @media only screen and (min-width:941px) {
            .export-app-mob {
                display: none;
            }
        }

        @media only screen and (max-width:941px) {
            .export-app-web {
                display: none;
            }
        }
    </style>
@endsection

@section('content')


    @if (session()->has('used_campus_program'))
        @php $usedCampusProgram=session()->get('used_campus_program'); @endphp

        @php
            $usedCampusProgramUniversityId = $usedCampusProgram[0];
            $usedCampusProgramCampusId = $usedCampusProgram[1];
            $usedCampusProgramId = $usedCampusProgram[2];

        @endphp
    @endif

    <input type="hidden" value={{ $usedCampusProgramUniversityId ?? '' }} id="useduniversityid" />
    <input type="hidden" value={{ $usedCampusProgramCampusId ?? '' }} id="usedcampusid" />
    <input type="hidden" value={{ $usedCampusProgramId ?? '' }} id="usedprogramid" />



    <section id="basic-datatable">
        <input type="hidden" name="favourite" value="{{ request()->segment(3) }}">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-none">
                        <h4 class="card-title"></h4>
                        <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="expand"><i class="feather icon-maximize"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard">
                            <div class="row application-filter align-items-center">
                                <div class="col-md-2 col-12">
                                    <div class="form-group">
                                        <label for="univ">Universities</label>
                                        <select id="univs" name="univs[]" data-live-search="true" multiple
                                            class=" select form-control">
                                            @foreach ($univs ?? [] as $univ)
                                                <option
                                                    {{ $univ->id == ($usedCampusProgramUniversityId ?? '') ? 'selected' : '' }}
                                                    value="{{ $univ->id }}">{{ $univ->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 col-12">
                                    <div class="form-group">
                                        <label for="campus">Campus</label>
                                        <select id="campus" name="campus[]" data-live-search="true" multiple
                                            class=" select form-control">
                                            @foreach ($campuses ?? [] as $campus)
                                                <option
                                                    {{ $campus->id == ($usedCampusProgramCampusId ?? '') ? 'selected' : '' }}
                                                    value="{{ $campus->id }}">{{ $campus->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 col-12">
                                    <div class="form-group">
                                        <label for="program">Program</label>
                                        <select id="program" name="program[]" data-live-search="true" multiple
                                            class=" select form-control">
                                            @foreach ($programs ?? [] as $program)
                                                <option
                                                    {{ $program->id == ($usedCampusProgramId ?? '') ? 'selected' : '' }}
                                                    value="{{ $program->id }}">{{ $program->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-2 col-12">
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select id="status_filter" name="status[]" data-live-search="true" multiple
                                            name="status_filter" class='form-control select' id="status-filter">
                                            @foreach (config('setting.application.status') as $key => $value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 col-12 text-right">
                                    <button class="btn btn-primary" id="reset-filter">Reset</button>
                                    <button type="button" id="openModalButton" class="btn btn-primary">One Time Limit Of
                                        Aplication</button>
                                    <a href="{{ route('admin.students-application-export') }}"
                                        class="btn btn-primary mt-3 export-app-mob">Export Students Application Data In
                                        Excel Form</a>
                                </div>

                                <div class="modal fade" id="myModal" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel"></h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- Modal content goes here -->
                                                <form id="myForm">
                                                    <div class="form-group">

                                                        <label for="inputName">
                                                            <h3 id="allow_permission">
                                                                {{ json_decode($limitApplyApplication, true)[0]['count'] }}
                                                                Application Allow Permission To Submit </h3>
                                                        </label>

                                                        <input type="text" class="form-control" hidden id="modelname"
                                                            name="name" required value="{{ request()->segment(2) }}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="positiveNumber">Number of Application Apply</label>
                                                        <input type="number" min="0" class="form-control"
                                                            id="positiveNumber" name="number_of_application" required>
                                                    </div>
                                                    <!-- Add other form fields as needed -->

                                                    <button type="button" class="btn btn-primary"
                                                        id="submitFormApplicationForm">Submit</button>
                                                </form>
                                            </div>

                                        </div>
                                    </div>
                                </div>



                                <div>
                                    <input type="hidden" name="view" value="" />
                                    <ul class="nav nav-tabs " role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active work-nav"
                                                data-value="{{ App\Models\UserApplication::ACTIVE }}"
                                                aria-controls="home" role="tab">
                                                Active
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link work-nav"
                                                data-value="{{ App\Models\UserApplication::INACTIVE }}"
                                                aria-controls="profile" role="tab">Inactive</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link work-nav" data-value="favourite" aria-controls="profile"
                                                role="tab">
                                                <i class="fa fa-heart"></i> Favourite(s) </a>
                                        </li>
                                        <li class="nav-item ml-2">
                                            <a href="{{ route('admin.students-application-export') }}"
                                                class="btn btn-primary export-app-web">Export Students Application Data In
                                                Excel Form</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="table-responsive">

                                    <table id="admin-application-table"
                                        class="table table-hover w-100 zero-configuration">
                                        <thead>
                                            <tr>
                                                <th>Favourite</th>
                                                <th>Student</th>
                                                <th>Moderator ID</th>
                                                <th>Application ID</th>
                                                <th>University</th>
                                                <th>Campus</th>
                                                <th>Program</th>
                                                <th>Intake</th>
                                                <th>Status</th>
                                                <th>Applied Date</th>
                                                {{-- <th>Count</th> --}}
                                                {{-- <th>Count</th>
                                            --}}
                                                <th>Message</th>
                                                <th>Action</th>
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

@section('page-script')
    <script>
        var setTime;
        var messgeTable;
        var dataTable;



        $(document).ready(function() {

            try {
                let hash = location.hash;
                if (hash.length > 1) {
                    $(".filter-options").removeClass('d-none');
                    setTimeout(function() {
                        $("a[href='" + hash + "']").click();
                    }, 500);
                }
            } catch (e) {}


            // var usedUniversityId=$('#useduniversityid').val();

            // var usedCampusId=$('#usedcampusid').val();
            // var usedProgramId=$('#usedprogramid').val();


            // if((usedUniversityId !== "" && usedUniversityId !== null && usedUniversityId !== undefined)&&
            // (usedCampusId !== "" && usedCampusId !== null && usedCampusId !== undefined)&&
            // (usedProgramId !== "" && usedProgramId !== null && usedProgramId !== undefined)){



            //    console.log(usedUniversityId);
            //    console.log(usedCampusId);
            //    console.log(usedProgramId); 

            //     console.log('hello');



            //     dataTable = $("#admin-application-table").DataTable({
            //     "processing": true,
            //     "serverSide": true,
            //     //"pageLength": 100,
            //     ajax: {
            //         url: "{{ route('admin.applications-all') }}",
            //         data: function(d) {

            //             d.university = $('#useduniversityid').val();
            //             d.campus = $('#usedcampusid').val();
            //             d.program = $('#usedprogramid').val();
            //             d.status = $("#status_filter").val();
            //             d.favourite = $("input[name='favourite']").val();
            //             d.view = $("input[name='view']").val();

            //         }
            //     },
            //     // dom: "tps",
            //     "order": [
            //         [7, "desc"]
            //     ],
            //     columns: [{
            //             data: 'favorite',
            //             name: 'is_favorite'
            //         },
            //         {
            //             name: 'users.name',
            //             data: 'name',
            //             orderable: true,
            //             searchable: true

            //         },
            //         {
            //             name: 'users_applications.application_number',
            //             data: 'application_number'
            //         },
            //         {
            //             name: 'universities.name',
            //             data: 'university'
            //         },
            //         {
            //             name: 'campus.name',
            //             data: 'campus'
            //         },

            //         {
            //             name: 'programs.name',
            //             data: 'program'
            //         },
            //         {
            //             name: 'users_applications.year ',
            //             data: 'year'
            //         },
            //         {
            //             name: 'status',
            //             data: 'status'
            //         },
            //         {
            //             name: 'users_applications.created_at',
            //             data: 'apply_date'
            //         },
            //         {
            //             name: 'count',
            //             data: 'count',
            //             searchable: false
            //             // "visible":false,
            //         },
            //         {
            //             //   name: 'toggle-admin-status',
            //             data: 'toggle_status'
            //         }

            //         // {
            //         //   name: 'message',
            //         //   data: 'message',
            //         // }
            //     ],
            //     responsive: false,

            //     drawCallback: function(setting, data) {
            //         // let setTime;
            //         // clearTimeout(setTime);
            //         // setTime = setTimeout(()=>{
            //         //     dataTable.draw('page');
            //         //   },10000);

            //     },
            //     bInfo: false,
            //     pageLength: 100,
            //     initComplete: function(settings, json) {
            //         $(".dt-buttons .btn").removeClass("btn-secondary");
            //         $(".table-img").each(function() {
            //             $(this).parent().addClass('product-img');
            //         });

            //         // setTimeout(()=>{
            //         //   dataTable.ajax.reload(null,false);
            //         // },5000);

            //     }
            // });

            // dataTable.draw();


            // }




            // Navigation
            $('.work-nav').click(function() {
                $(".work-nav").removeClass('active');
                $(this).addClass('active');
                $("input[name='view']").val($(this).data('value'));
                if ($(this).data('value') != "find_work") {
                    $(".filter-options").removeClass('d-none');
                } else {
                    $(".filter-options").addClass('d-none');
                }

                dataTable.draw();
            });

            $(".select").selectpicker();
            $(".application-filter").find("select").on("change", function() {
                console.log('gf');
                console.log($('#program').val());
                dataTable.draw();
            });


            dataTable = $("#admin-application-table").DataTable({
                "processing": true,
                "serverSide": true,
                //"pageLength": 100,
                ajax: {
                    url: "{{ route('admin.applications-all') }}",
                    data: function(d) {
                        d.program = $('#program').val();
                        d.university = $('#univs').val();
                        d.campus = $('#campus').val();
                        d.status = $("#status_filter").val();
                        d.favourite = $("input[name='favourite']").val();
                        d.view = $("input[name='view']").val();
                    }
                },
                // dom: "tps",
                "order": [
                    [7, "desc"]
                ],
                columns: [{
                        data: 'favorite',
                        name: 'is_favorite'
                    },
                    {
                        name: 'users.name',
                        data: 'name',
                        orderable: true,
                        searchable: true


                    },
                    {
                        name: 'modifiers.username ',
                        data: 'moderator_username',



                    },
                    {
                        name: 'users_applications.application_number',
                        data: 'application_number',

                    },
                    {
                        name: 'universities.name',
                        data: 'university'
                    },
                    {
                        name: 'campus.name',
                        data: 'campus'
                    },

                    {
                        name: 'programs.name',
                        data: 'program'
                    },
                    {
                        name: 'users_applications.year ',
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
                        searchable: false
                        // "visible":false,
                    },
                    {
                        //   name: 'toggle-admin-status',
                        data: 'toggle_status'
                    }

                    // {
                    //   name: 'message',
                    //   data: 'message',
                    // }
                ],
                responsive: false,

                drawCallback: function(setting, data) {
                    // let setTime;
                    // clearTimeout(setTime);
                    // setTime = setTimeout(()=>{
                    //     dataTable.draw('page');
                    //   },10000);

                },
                bInfo: false,
                pageLength: 100,
                initComplete: function(settings, json) {
                    $(".dt-buttons .btn").removeClass("btn-secondary");
                    $(".table-img").each(function() {
                        $(this).parent().addClass('product-img');
                    });

                    // setTimeout(()=>{
                    //   dataTable.ajax.reload(null,false);
                    // },5000);

                }
            });

            // Mark as favorite
            $(document).on('click', '.favorite', function() {

                let favorite = $(this).children().hasClass('fa-heart') ? 0 : 1;
                let that = $(this);
                $.ajax({
                    url: "{{ route('admin.application-favorite') }}",
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: $(this).data('id'),
                        favorite: favorite
                    },
                    beforeSend: function() {
                        that.attr('disabled', true).html(
                            "<i class='text-danger fa fa-spinner fa-spin'></i> ");
                    },
                    success: function(data) {
                        // toast(data.code, data.message, data.title);
                        dataTable.draw('page');
                    }
                });
            });
            //delete
            $(document).on('click', '.application-toggle-status', function(e) {
                id = $(this).data('id');
                that = $(this);
                if (confirm("Are you sure?")) {
                    $.ajax({
                        url: "{{ url('admin/application-toggle-status') }}" + "/" + id,
                        data: {
                            id: id
                        },
                        beforeSend: function() {
                            that.attr('disabled', true).prepend(
                                "<i class='fa fa-spinner fa-spin'></i> ");
                        },
                        success: function(data) {
                            setAlert(data);
                            dataTable.draw('page');
                        },
                        error: function(data) {
                            toast("error", "Something went wrong.", "Error");
                        },
                        complete: function() {
                            submitReset(that);
                        }
                    });

                }
            });


            //view profile
            $(document).on('click', '.profile', function() {
                id = $(this).data('id');
                var url = `{{ url('student/${id}/viewprofile') }}`;
                console.log(url);
                window.application = ($(this).data('application'))
                $('#apply-model').modal('show');
                $('.apply-title').html('View Profile');
                $(".dynamic-apply").html("Loading...");
                getContent({
                    "url": url,
                    beforeSend: function() {
                        $(".dynamic-apply").html("");
                        $(".dynamic-body").html("");
                    },
                    data: {
                        application: application
                    },
                    success: function(data) {
                        $('#apply-model').find('.modal-dialog').addClass('modal-lg');
                        $(".dynamic-apply").html(data);
                        // runScript(window.application);
                        initMessageScript(window.application);
                    }
                });
            });

            //priority
            $(document).on('click', '.priority', function() {

                let that = $(this);
                $.ajax({
                    url: "{{ route('admin.student-priority') }}",
                    type: 'post',
                    data: {
                        id: window.application,
                        priority: $('#priority-number').val(),
                        _token: "{{ csrf_token() }}"
                    },
                    beforeSend: function() {
                        that.attr('disabled', true).html(
                            "<i class='fa fa-spinner fa-spin'></i> ")
                    },
                    success: (data) => {
                        toast(data.code, data.message, data.title);
                        that.removeAttr('disabled').html('Priority');
                    }
                })

            });

            // changing status of an application
            $(document).on('click', '.status', function() {
                id = $(this).data('id');
                $('.apply-title').text('Change Status');
                $.ajax({
                    url: "{{ url('admin/application-status') }}" + "/" + id,
                    beforeSend: function() {
                        $('.dynamic-apply').html("Loading");
                    },
                    success: (data) => {
                        $('.dynamic-apply').html(data);
                        // $('#status').val(text.toLowerCase());
                    }
                })

                //$('#test').html(option);
                // $(this).popover({
                //   title: $(this).text(),
                //   content: selectHtml($(this).text()),
                //   placement: "top",
                //   animation:true,
                //   html:true,
                // });
            });

            $(document).on('click', '#chage-status', function() {
                id = $(this).data('id');
                status = $('#status').find("option:selected").val();
                $(this).attr('disabled', true).prepend("<i class='fa fa-spinner fa-spin'></i> ");

                $.ajax({
                    url: "{{ route('admin.update-status') }}",
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id,
                        status: status,
                    },
                    success: (data) => {
                        if (data.success) {
                            setAlert(data);
                        }
                        $('#apply-model').modal("hide");
                        dataTable.draw('page');
                    }
                })
            });






            //reset filter application/{id}/message
            $('#reset-filter').on('click', function() {
                $(".select").selectpicker('deselectAll');
                $(".select").val("");
                $(".select").selectpicker('refresh');

            });

            $(document).on('click', '.admin-message', function() {
                $('.dynamic-title').text('');
                id = $(this).data('id');
                $.ajax({
                    url: "{{ url('admin/application') }}" + "/" + id + "/" + "message",
                    beforeSend: function() {
                        $(".dynamic-apply").html("");
                        $(".dynamic-body").html("");
                    },
                    success: function(data) {
                        $('.dynamic-body').html(data);
                        initMessageScript(id);
                    }
                })
            });

            $('.dynamic-body').on('hidden.bs.modal', function() {
                $(".dynamic-apply").html("");
                $(".dynamic-body").html("");
                dataTable.draw('page');
            });
            $('.dynamic-apply').on('hidden.bs.modal', function() {
                $(".dynamic-apply").html("");
                $(".dynamic-body").html("");
                dataTable.draw('page');
            });


        });
    </script>

    <script>
        $(document).ready(function() {
            // Open the modal on button click positiveNumber  modelname
            $('#openModalButton').on('click', function() {

                $('#myModal').modal('show');
                $('#exampleModalLabel').html('Add Number');

                $('#submitFormApplicationForm').on('click', function() {



                    let count = $('#positiveNumber').val();
                    let modelName = $('#modelname').val();

                    $.ajax({
                        url: "{{ route('admin.number-application-allow') }}",
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            count: count,
                            modelName: modelName,
                        },

                        success: (data) => {



                            $('#allow_permission').html(
                                `${data.Data.count} Application Allow Permission To Submit`
                            );

                            $('#myModal').modal("hide");
                            let html = $('.toast-success');


                            html.each(function(index) {

                                $(this).hide();
                            });


                            toast("success", "Data Submited Successfully", "Success");


                            $('#positiveNumber').val('');
                            dataTable.draw('page');



                        },
                        error: function(data) {

                            let html = $('.toast-error');


                            html.each(function(index) {

                                $(this).hide();
                            });


                            if (data.responseJSON.errors.count[0]) {
                                toast("error", "count value required!!!.", "Error");
                                $('#myModal').modal('hide');
                            } else {
                                toast("error", "Something went wrong.", "Error");
                                $('#myModal').modal('hide');
                            }




                        }
                    });





                });
            });
        });
    </script>
@endsection
