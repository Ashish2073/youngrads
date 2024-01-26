@extends('dashboard.layouts.app')
@section('title')
    User Management
@endsection
@section('content')
    <ul class="breadcrumb">
        <li><a href="{{ route('admin.home') }}">Dashboard</a></li>
        <li class="active"><span class='fa fa-users'></span> Users</li>
    </ul>

    <div id="add-btn" class="hide">
        <button data-toggle="modal" data-target="#dynamic-modal" id="add" data-url="{{ route('admin.user.create') }}"
                class="btn btn-default">
            <span class="fa fa-plus"></span>Add User
        </button>
    </div>


    <div class="row">
        <div class="col-md-12">

            <table id="user-table" class="table dt-responsive nowrap" style="width:100%">
                <thead>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                </tr>
                </thead>
            </table>

        </div>
    </div>

@endsection

@section('foot_script')
    <script>
        var dataTable;
        $(document).ready(function () {
            // Datatable
            dataTable = $("#user-table").DataTable({
                "processing": true,
                "serverSide": true,
                "bInfo": true,
                "pageLength": 100,
                "fixedHeader": true,
                // dom: "<'row custom-row'<'col-sm-3'l><'col-sm-3 first-place'><'col-sm-6 second-place'f>>tr<'row'<'col-sm-6'i><'col-sm-6'p>>",
                dom: "<'panel panel-default'" +
                    "<'panel-heading'" +
                    "<'row'" +
                    "<'col-sm-12 custom-heading'>" +
                    ">" +
                    "<'row custom-row'" +
                    "<'col-sm-3'l>" +
                    "<'col-sm-9 second-place'f>" +
                    ">" +
                    ">" +
                    "<'panel-body'" +
                    "tr" +
                    ">" +
                    "<'panel-footer'" +
                    "<'row'" +
                    "<'col-sm-6'i>" +
                    "<'col-sm-6'p>" +
                    ">" +
                    ">" +
                    ">",
                ajax: {
                    url: route('admin.users').url(),
                    data: function (d) {
                        // d.quiz_id = $('select[name="quiz_id"]').val();
                    }
                },
                columns: [{
                    data: 'first_name',
                    name: 'first_name'
                },
                    {
                        data: 'last_name',
                        name: 'last_name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },

                    // { data: 'questions', orderable: false, searchable: false },
                    // { data: 'created_at' }
                ],
                // columnDefs: [
                //     {
                //         targets: [7],
                //         visible: false
                //     }
                // ],
                'createdRow': function (row, data, dataIndex) {
                    $(row).addClass('action-row');
                    let id = data['id'];
                    let editUrl = route('admin.user.edit', id).url();
                    $(row).attr('data-url', editUrl);
                    $(row).attr('data-target', "#dynamic-modal");
                    $(row).attr('data-toggle', "modal");
                },
                initComplete: function (res, json) {
                    $(".second-place").append($("#add-btn").html());
                    $(".second-place").addClass('text-right');
                    $("#add-btn").remove();
                    initDatatable();
                }
            });


            $("body").on('click', "#add", function (e) {
                let url = $(this).data('url');
                $(".dynamic-title").html('Add Team Member');
                getContent({
                    "url": url,
                    success: function (data) {
                        $(".dynamic-body").html(data);
                        runScript();
                    }
                });
            });

            $("body").on('click', ".action-row", function (e) {
                let url = $(this).data('url');
                $('.dynamic-title').html('Update Team Member');
                getContent({
                    "url": url,
                    success: function (data) {
                        $(".dynamic-body").html(data);

                        runScript();
                    }
                });
            });

            runScript();
        })

        function runScript() {
            initAccordin();
            $(".select").selectpicker();

            validateForm($("#user-create-form"), {
                rules: {
                    "profession_type": {
                        required: true
                    },
                    name: {
                        required: true,
                    },
                    username: {
                        required: true,
                    },
                    email: {
                        required: true
                    },
                    password: {
                        required: true
                    },
                    "password_confirmation": {
                        equalTo: "#password"
                    },
                    "#user_type": {
                        required: true
                    }
                },
                messages: {}
            });

            validateForm($("#user-update-form"), {
                rules: {
                    "profession_type": {
                        required: true
                    },
                    name: {
                        required: true,
                    },
                    username: {
                        required: true,
                    },
                    email: {
                        required: true
                    },
                    "confirm_password": {
                        equalTo: "#password"
                    },
                    "is_active": {
                        required: true
                    }
                },
                messages: {}
            });

            submitForm($("#user-create-form"), {
                beforeSubmit: function () {
                    submitLoader("#submit-btn");
                },
                success: function (data) {
                    setAlert(data);
                    if (data.success) {
                        modalReset();
                        dataTable.draw('page');
                    } else {
                        $(".dynamic-body").html(data);
                        runScript();
                    }
                }
            });

            submitForm($("#user-update-form"), {
                beforeSubmit: function () {
                    submitLoader("#submit-btn");
                },
                success: function (data) {
                    setAlert(data);
                    if (data.success) {
                        modalReset();
                        dataTable.draw('page');
                    } else {
                        $(".dynamic-body").html(data);
                        runScript();
                    }
                },
                error: function (data) {
                    toast("error", "Something went wrong.", "Error");
                }
            });

            submitForm($("#user-delete-form"), {
                beforeSubmit: function () {
                    if (!confirm('Are you sure want to delete?')) {
                        return;
                    }
                },
                success: function (data) {
                    if (isJson(data)) {
                        data = JSON.parse(data);
                        dataTable.ajax.reload();

                        $('#dynamic-modal').modal('hide');
                        if (data.success) {
                            showMessage("The member deleted successfully!", "danger");
                        } else {
                            showMessage("<strong>Error!</strong> Something went wrong.", "danger");
                        }
                        $(".dynamic-body").html("");
                    } else {
                        $(".dynamic-body").html(data);
                        runScript();
                    }
                }
            });
        }


    </script>
@endsection
