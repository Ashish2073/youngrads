@extends('layouts/contentLayoutMaster')

@section('title', 'Applications')

@section('page-style')
    <link rel="stylesheet" href="{{ asset(mix('css/pages/app-chat.css')) }}">
    <style>
        .modal-dialog-aside {
            width: 44% !important;
        }

        .status {
            cursor: pointer;
        }
    </style>
@endsection

@section('content')
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
                                                <option value="{{ $univ->id }}">{{ $univ->name }}</option>
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
                                                <option value="{{ $campus->id }}">{{ $campus->name }}</option>
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
                                                <option value="{{ $program->id }}">{{ $program->name }}</option>
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
                                </div>
                            </div>
                            <div>
                                <input type="hidden" name="view" value="" />
                                <ul class="nav nav-tabs " role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active work-nav"
                                            data-value="{{ App\Models\UserApplication::ACTIVE }}" aria-controls="home"
                                            role="tab">
                                            Active
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link work-nav"
                                            data-value="{{ App\Models\UserApplication::INACTIVE }}" aria-controls="profile"
                                            role="tab">Inactive</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link work-nav" data-value="favourite" aria-controls="profile"
                                            role="tab">
                                            <i class="fa fa-heart"></i> Favourite(s) </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="table-responsive">

                                <table id="admin-application-table" class="table table-hover w-100 zero-configuration">
                                    <thead>
                                        <tr>
                                            <th>Favourite</th>
                                            <th>Student</th>
                                            <th>Application ID</th>
                                            <th>University</th>
                                            <th>Program</th>
                                            <th>Intake</th>
                                            <th>Status</th>
                                            <th>Applied Date</th>
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
                dataTable.draw();
            });
            //ajax for select
            // $('.university').select2({
            //     placeholder: 'Filter by University',
            //     ajax: {
            //         url: route('select-university'),
            //         dataType: 'json',
            //         type: 'POST',
            //         data: function(params) {
            //             return {
            //                 name: params.term
            //             }
            //         },
            //         processResults: function(data) {
            //             return {
            //                 results: data
            //             }
            //         }
            //     }
            // });

            // $('.campus').select2({
            //     placeholder: 'Filter by Campus',
            //     multiple: false,
            //     ajax: {
            //         url: route('select-campus'),
            //         dataType: 'json',
            //         type: 'POST',
            //         data: function(params) {
            //             return {
            //                 name: params.term
            //             }
            //         },
            //         processResults: function(data) {
            //             return {
            //                 results: data
            //             }
            //         }
            //     }
            // });

            // $('.program').select2({
            //     placeholder: 'Filter by Program',
            //     multiple: false,
            //     ajax: {
            //         url: route('select-programs'),
            //         dataType: 'json',
            //         type: 'POST',
            //         data: function(params) {
            //             return {
            //                 name: params.term
            //             }
            //         },
            //         processResults: function(data) {
            //             return {
            //                 results: data
            //             }
            //         }
            //     }
            // });

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
                        name: 'name',
                        data: 'name'
                    },
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






            //reset filter
            $('#reset-filter').on('click', function() {
                $(".select").selectpicker('deselectAll');
                $(".select").val("");
                $(".select").selectpicker('refresh');

            });

            $(document).on('click', '.admin-message', function() {
                $('.dynamic-title').text('');
                id = $(this).data('id');
                $.ajax({
                    url: route('admin.applicaton-message-admin', $(this).data('id')),
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
@endsection
