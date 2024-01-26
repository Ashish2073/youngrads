@extends('layouts/contentLayoutMaster')

@section('title', 'Favourite Application')

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
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">

                        {{-- <h4 class="card-title">Students</h4> --}}
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard">
                            <div class="row">
                                <div class="col-3"><select class="university form-control"></select></div>
                                <div class="col-3"><select class="campus form-control"></select></div>
                                <div class="col-3"><select class="program form-control"></select></div>
                                <div class="col-3 text-right">
                                    <button class="btn btn-primary" id="reset-filter">Reset</button>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="admin-application-table" class="table table-hover w-100 zero-configuration">
                                    <thead>
                                        <tr>
                                            <th>Favorite</th>
                                            <th>Student Name</th>
                                            <th>University</th>
                                            <th>Campus</th>
                                            <th>Program</th>
                                            <th>Intake</th>
                                            <th>Status</th>
                                            <th>Applied Date</th>
                                            {{-- <th>Count</th> --}}
                                            <th>Message</th>
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
        $(document).ready(function() {
            //ajax for select
            $('.university').select2({
                placeholder: 'Filter by university',
                ajax: {
                    url: {{ route('select-university') }},
                    dataType: 'json',
                    type: 'POST',
                    data: function(params) {
                        return {
                            name: params.term
                        }
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        }
                    }
                }
            });

            $('.campus').select2({
                placeholder: 'Filter by Campus',
                multiple: false,
                ajax: {
                    url: route('select-campus'),
                    dataType: 'json',
                    type: 'POST',
                    data: function(params) {
                        return {
                            name: params.term
                        }
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        }
                    }
                }
            });
            $('.program').select2({
                placeholder: 'Filter by Program',
                multiple: false,
                ajax: {
                    url: "{{ route('select-programs') }}",
                    dataType: 'json',
                    type: 'POST',
                    data: function(params) {
                        return {
                            name: params.term
                        }
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        }
                    }
                }
            });



            var messgeTable;
            var dataTable;
            dataTable = $("#admin-application-table").DataTable({
                "processing": true,
                "serverSide": true,
                //"pageLength": 100,
                ajax: {
                    url: route('admin.favorite-applicatons').url(),
                    data: function(d) {
                        d.program = $('.program').val();
                        d.university = $('.university').val();
                        d.campus = $('.campus').val();
                    }
                },
                // dom: "tps",
                "order": [
                    [7, "desc"]
                ],
                columns: [{
                        data: 'favorite'
                    },
                    {
                        name: 'name',
                        data: 'name'
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
                        name: 'intakes.name',
                        data: 'intake'
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

            //favorite
            $(document).on('click', '.favorite', function() {

                let favorite = $(this).children().hasClass('fa-heart') ? 0 : 1;
                that = $(this);
                $.ajax({
                    url: "{{ route('admin.application-favorite') }}",
                    type: 'Post',
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: $(this).data('id'),
                        favorite: favorite
                    },
                    beforeSend: function() {
                        that.attr('disabled', true).html(
                            "<i class='fa fa-spinner fa-spin'></i> ");
                    },
                    success: function(data) {
                        //toast(data.code, data.message, data.title);
                        dataTable.draw('page');
                    }
                });
            });

            //view profile student/{id}/viewprofile
            $(document).on('click', '.profile', function() {
                id = $(this).data('id');
                var url = `{{ url('student/${id}/viewprofile') }}`;
                $('#apply-model').modal('show');
                $('.apply-title').html('View Profile');
                $(".dynamic-apply").html("Loading..");
                getContent({
                    "url": url,
                    success: function(data) {
                        $('#apply-model').find('.modal-dialog').addClass('modal-lg');
                        $(".dynamic-apply").html(data);
                    }
                });
            });

            // changing status of an application
            $(document).on('click', '.status', function() {
                id = $(this).data('id');
                text = $(this).text();
                console.log(text);
                $('.apply-title').text('Change Status');
                $.ajax({
                    url: "{{ url('admin/application-status') }}" + "/" + id,
                    beforeSend: function() {
                        $('.dynamic-apply').html("Loading");
                    },
                    success: (data) => {
                        $('.dynamic-apply').html(data);
                        $('#status').val(text.toLowerCase());
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
                status = $('#status').val();
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




            $('.program').change(function() {
                dataTable.draw('page');
            });

            $('.campus').change(function() {
                dataTable.draw('page');
            });

            $('.university').change(function() {
                dataTable.draw('page');
            });

            //reset filter
            $('#reset-filter').on('click', function() {
                $('.program').val(null).trigger("change");
                $('.campus').val(null).trigger("change");
                $('.university').val(null).trigger("change");
            });

            $(document).on('click', '.admin-message', function() {
                $('.dynamic-title').text('');
                id = $(this).data('id');
                $.ajax({
                    url: "{{url('admin/applicaton')}}"+"/"+id+"/"+"message",
                    success: function(data) {
                        $('.dynamic-body').html(data);
                        runScript(id);
                    }
                })
            });

            $('.dynamic-body').on('hidden.bs.modal', function() {
                dataTable.draw('page');
            });

        });

        function runScript(id) {

            $(".custom-file input").change(function(e) {
                $(this).next(".custom-file-label").html(e.target.files[0].name);
            });

            messgeTable = $("#chat-table").DataTable({
                "processing": true,
                "serverSide": true,
                ajax: {
                    url: "{{ url('application/message/all') }}" + "/" + id,
                    data: function(d) {

                    }
                },
                dom: "tp",
                "order": [
                    [1, "desc"]
                ],
                columns: [

                    {
                        data: 'html'
                    },
                    {
                        data: 'time',
                        'visible': false
                    }
                ],
                responsive: false,

                drawCallback: function(setting, data) {
                    let setTime;
                    clearTimeout(setTime);
                    setTime = setTimeout(() => {
                        messgeTable.draw('page');
                    }, 10000);

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


            $(document).on('click', '.attachment', (e) => $('.custom-file-input').click());

            $('.custom-file-input').change((e) => $('#attachment-name').text(e.target.files[0].name));

            validateForm($('#message-form'), {
                rules: {
                    message: {
                        required: true,
                    },
                    document: {
                        extension: "png|jpg|docx|doc|pdf"
                    }
                },
                errorPlacement: function(error, element) {
                    if (element.hasClass('select')) {
                        element = element.next();
                    }
                    element = $(".error-message");
                    error.insertAfter(element);
                },
                messages: {}
            });

            submitForm($('#message-form'), {
                beforeSubmit: function() {
                    submitLoader("#submit-btn");
                },
                success: function(data) {
                    messgeTable.draw('page');
                    $('#message-form')[0].reset();
                    submitReset("#submit-btn", 'Send');
                },
                error: function(data) {
                    toast("error", "Something went wrong.", "Error");
                }
            });

            setTimeout(() => {
                messgeTable.draw('page');
            }, 5000)

        }
    </script>
@endsection
