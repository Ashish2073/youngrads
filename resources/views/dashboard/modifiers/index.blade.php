@extends('layouts/contentLayoutMaster')


@section('title', 'Modeifiers')
@section('breadcumb-right')
    <button data-toggle="modal" data-target="#dynamic-modal" id="add" data-url="{{ route('admin.modifier.create') }}"
        class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle">
        <i class="feather icon-plus"></i>
    </button>
@endsection
@section('content')

    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="text-align: center;">
                    <!-- Modal content goes here -->
                    <form id="myForm">
                        <div class="form-group ">

                            <label for="inputName t-center">
                                <h2 id="">
                                    User Roles
                                </h2>
                            </label>

                            {{-- <input type="text" class="form-control" hidden id="modelname" name="name" required
                                value=""> --}}
                        </div>
                        <div class="form-group">
                            <label for="positiveNumber">
                                <h5 id="userrole"></h5>
                            </label>

                        </div>
                        <!-- Add other form fields as needed -->

                        {{-- <button type="button" class="btn btn-primary" id="submitFormApplicationForm">Submit</button> --}}
                    </form>
                </div>

            </div>
        </div>
    </div>




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
                                        <label for="rolename">Role Name</label>
                                        <select data-colum="0" id="rolename" name="rolename[]" data-live-search="true"
                                            multiple class=" select form-control apply-filter-role">
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->name }}">{{ ucfirst($role->name) }}
                                                </option>
                                            @endforeach

                                        </select>
                                    </div>

                                </div>

                                <div class="col-md-3 col-12 text-center">
                                    <button class="btn btn-primary" id="reset-filter">Reset</button>

                                </div>

                            </div>

                            <div class="table-responsive">
                                <table id="modifier-table" class="table table-hover w-100 zero-configuration">
                                    <thead>
                                        <tr>
                                        <tr>
                                            <th>Modifiers Users Name</th>
                                            <th>First Name</th>
                                            <th>Last Name</th>

                                            <th>Email</th>
                                            <th>Role</th>
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



@section('page-script')
    <script>
        var dataTable;
        $(document).ready(function() {
            $(".select").selectpicker();


            $(".application-filter").find(".apply-filter-role").on("change", function() {
                dataTable.draw();
            });


            // Datatable
            dataTable = $("#modifier-table").DataTable({
                "processing": true,
                "serverSide": true,
                "bInfo": true,
                "pageLength": 100,
                "fixedHeader": true,
                ajax: {
                    url: "{{ url('admin/modifiers') }}",
                    data: function(d) {
                        d.rolename = $('#rolename').val();
                        // d.quiz_id = $('select[name="quiz_id"]').val();
                    }
                },
                columns: [{
                        data: 'username',
                        name: 'username'
                    },

                    {

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

                    {
                        data: 'role',
                        name: 'role'
                    }

                    // { data: 'questions', orderable: false, searchable: false },
                    // { data: 'created_at' }
                ],
                // columnDefs: [
                //     {
                //         targets: [7],
                //         visible: false
                //     }
                // ],
                'createdRow': function(row, data, dataIndex) {
                    $(row).addClass('action-row');
                    let id = data['id'];
                    let editUrl = "{{ url('admin/modifiers') }}" + "/" + id + "/" + "edit";
                    $(row).attr('data-url', editUrl);
                    $(row).attr('data-target', "#dynamic-modal");
                    $(row).attr('data-toggle', "modal");
                },
                initComplete: function(res, json) {

                }
            });


            $("body").on('click', "#add", function(e) {
                let url = $(this).data('url');
                $(".dynamic-title").html('Add modifiers');
                getContent({
                    "url": url,
                    success: function(data) {
                        $(".dynamic-body").html(data);
                        runScript();
                    }
                });

            });

            $("body").on('click', ".action-row", function(e) {
                let url = $(this).data('url');

                $('.dynamic-title').html('Update modifiers');
                getContent({
                    "url": url,
                    success: function(data) {

                        $(".dynamic-body").html(data);

                        runScript();
                    }
                });
            });

            runScript();
        })

        function runScript() {
            // $(".select").select2();
            $(".select").selectpicker();


            submitForm($("#modifier-create-form"), {
                beforeSubmit: function() {
                    submitLoader("#submit-btn");
                },
                success: function(data) {
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

            submitForm($("#modifier-update-form"), {
                beforeSubmit: function() {
                    submitLoader("#submit-btn");
                },
                success: function(data) {
                    setAlert(data);
                    if (data.success) {
                        modalReset();
                        dataTable.draw('page');
                    } else {
                        $(".dynamic-body").html(data);
                        runScript();
                    }
                },
                error: function(data) {
                    toast("error", "Something went wrong.", "Error");
                }
            });

            submitForm($("#modifier-delete-form"), {
                beforeSubmit: function() {
                    if (!confirm('Are you sure want to delete?')) {
                        return;
                    }
                },
                success: function(data) {
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

            //delete form
            submitForm($("#delete-form"), {
                beforeSubmit: function() {
                    if (!confirm('Are you sure you want to delete')) return false;
                    submitLoader("#submit-btn-delete");
                },
                success: function(data) {
                    setAlert(data);
                    if (data.success) {
                        modalReset();
                        dataTable.draw('page');
                    } else {
                        $(".dynamic-body").html(data);
                        runScript();
                    }
                },
                error: function(data) {
                    toast("error", "Something went wrong.", "Error");
                }
            });
        }

        function userRole(id) {
            let that = $(this);
            event.stopPropagation();
            $.ajax({
                url: "{{ route('admin.user-roles') }}",
                type: 'post',
                data: {
                    id: id,
                    _token: "{{ csrf_token() }}"
                },
                beforeSend: function() {
                    that.attr('disabled', true).html(
                        "<i class='fa fa-spinner fa-spin'></i> ")
                },
                success: (data) => {

                    $('#myModal').modal('show');

                    let roleLength = data.length;


                    let userRoleHTML = "";
                    for (let i = 0; i < roleLength; i++) {
                        userRoleHTML = userRoleHTML + data[i] + ",";

                    }



                    $('#userrole').html(userRoleHTML);
                    console.log(data);

                    // toast(data.code, data.message, data.title);
                    that.removeAttr('disabled').html('Priority');
                }
            })




        }

        $('#reset-filter').on('click', function() {
            $(".select").selectpicker('deselectAll');
            $(".select").val("");
            $(".select").selectpicker('refresh');

        });
    </script>
@endsection
