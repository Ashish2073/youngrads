@extends('layouts/contentLayoutMaster')

@section('title', 'User Activities')

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




{{-- <select data-live-search='true' data-style="bg-white border-light" class="select form-control"
name="user" id="user">
<option value="">All Users</option>
@foreach (config('users') as $user)
    <option {{ request()->get('user_id') == $user->id ? 'selected' : '' }}
        value="{{ $user->id }}">
        {{ \Str::limit($user->name . ' - ' . $user->email, 40, '...') }}
    </option>
@endforeach
</select> --}}

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



                            <div class="row application-filter align-items-center">
                                <div class="col-md-3 col-12">
                                    <div class="form-group">
                                        <label for="users">Users</label>
                                        <select data-live-search='true' data-style="bg-white border-light" multiple
                                            class="select form-control" name="user" id="user">

                                            @foreach (config('users') as $user)
                                                <option {{ request()->get('user_id') == $user->id ? 'selected' : '' }}
                                                    value="{{ $user->id }}">
                                                    {{ \Str::limit($user->name . ' - ' . $user->email, 40, '...') }}
                                                </option>
                                            @endforeach
                                        </select>

                                    </div>
                                </div>



                                <div class="col-md-3 col-12">
                                    <div class="form-group">
                                        <label for="moderator">Moderators</label>
                                        <select data-live-search='true' data-style="bg-white border-light" multiple
                                            class="select form-control" name="moderator" id="moderator">

                                            @foreach ($moderator as $user)
                                                <option value="{{ $user->moderatorid }}">
                                                    {{ \Str::limit($user->full_name . ' - ' . $user->email, 40, '...') }}
                                                </option>
                                            @endforeach
                                        </select>

                                    </div>
                                </div>











                                <div class="col-md-2 col-12 text-right">
                                    <button class="btn btn-primary" id="reset-filter">Reset</button>

                                </div>




                            </div>


                            <div class="table-responsive">
                                <table id="table" class="table table-hover w-100 zero-configuration">
                                    <thead>
                                        <tr>
                                        <tr>
                                            <th>Action Date</th>
                                            <th>Action</th>
                                            <th>IP Address</th>
                                            <th>User</th>
                                        </tr>
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
            $(".select").selectpicker();

            $('#reset-filter').on('click', function() {
                $(".select").selectpicker('deselectAll');
                $(".select").val("");
                $(".select").selectpicker('refresh');
                dataTable.draw();
            });


            $(".application-filter").find("select").on("change", function(e) {

                console.log('hello');

                dataTable.draw();
            });





            $(".clear-filter").click(function() {
                $("select[name='intake'], select[name='year'],select[name='program']").val("");
                $(".select").selectpicker('refresh');
                dataTable.draw();
            })
            var messgeTable;
            let dataTable;
            dataTable = $("#table").DataTable({
                "processing": true,
                "serverSide": true,
                ajax: {
                    url: "{{ route('admin.activities') }}",
                    data: function(d) {
                        d.user_id = $("#user").val();
                        d.moderator_id = $("#moderator").val();
                    }
                },
                //dom: "tps",
                "order": [
                    [3, "desc"]
                ],
                columns: [{
                        name: 'created_at',
                        data: 'created_at'
                    },

                    {
                        name: 'description',
                        data: 'description'
                    },

                    {
                        name: 'ip_address',
                        data: 'ip_address'
                    },

                    {
                        name: 'user',
                        data: 'user',
                        orderable: false,
                        searchable: false
                    },


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

                $('.dynamic-title').text('Chat With Admin');
                id = $(this).data('id');
                $.ajax({
                    url: "{{ url('applicaton-message-user') }}" + "/" + id,
                    success: function(data) {
                        $('.dynamic-body').html(data);
                        runScript(id);
                    }
                })
            });

            $('#dynamic-modal').on('hidden.bs.modal', function() {
                dataTable.draw('page');
            });

            $("#user").change(function() {
                dataTable.draw();
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
                    url: route('messages-all', id),
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
                "language": {
                    "emptyTable": "No messages yet!"
                },
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

            //set important message
            $(document).on('click', '.btn-important', function() {
                let id = $(this).data('id');
                let important = $(this).children().hasClass('fa-heart') ? 0 : 1;
                let that = $(this);
                $.ajax({
                    url: route('message-important'),
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id,
                        important: important
                    },
                    beforeSend: function() {
                        that.attr('disabled', true).html(
                            "<i class='text-danger fa fa-spinner fa-spin'></i> ");
                    },
                    success: function(data) {
                        // toast(data.code, data.message, data.title);
                        messgeTable.draw('page');
                        let html = (important == 1) ?
                            `<i class='fa fa-heart  text-danger text-danger'></i>` :
                            `<i class='feather icon-heart pink text-danger'></i>`;

                        that.html(html);

                    }
                });
            });

            $('.custom-file-input').change((e) => $('#attachment-name').text(e.target.files[0].name));

            $(document).on('click', '.copy-attachment', function() {
                $value = $(this).data('path');
                $html = `<input type="text" value='${$value}' id="temp">`;
                $(this).parent().append($html);
                $('#temp').select();
                document.execCommand("copy");
                $(this).parent().find('#temp').remove();
                toastr.info('File path copied', '');
            });

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
                messages: {

                }
            });

            submitForm($('#message-form'), {
                beforeSubmit: function() {
                    submitLoader("#submit-btn");
                },
                success: function(data) {

                    //setAlert(data);
                    // if (data.success) {
                    //    // modalReset();
                    //     messgeTable.draw('page');
                    // } else {
                    // $(".dynamic-body").html(data);
                    //runScript(id);
                    messgeTable.draw('page');
                    $('#message-form')[0].reset();
                    // submitReset("#submit-btn");
                    //
                },
                error: function(data) {
                    toast("error", "Something went wrong.", "Error");
                },
                complete: function() {
                    submitReset("#submit-btn");
                    $("#attachment-name").html("");
                }
            });




        }
    </script>
@endsection
