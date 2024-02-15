@extends('layouts/contentLayoutMaster')

@section('title', 'Roles')

@section('breadcumb-right')
    <button data-toggle="modal" data-target="#dynamic-modal" id="add" data-url="{{ route('admin.role.create') }}"
        class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle">
        <i class="feather icon-plus"></i>
    </button>
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

                            <div class="table-responsive">
                                <table id="role-table" class="table table-hover w-100 zero-configuration">
                                    <thead>
                                        <tr>
                                        <tr>
                                            <th>Role Name</th>
                                            <th>Action</th>
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
            // Datatable
            dataTable = $("#role-table").DataTable({
                "processing": true,
                "serverSide": true,
                "bInfo": true,
                "pageLength": 50,
                "fixedHeader": true,

                ajax: {
                    url: "{{ route('admin.roles') }}",
                    data: function(d) {
                        // d.quiz_id = $('select[name="quiz_id"]').val();
                    }
                },
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'action',
                        name: 'action'
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
                'createdRow': function(row, data, dataIndex) {
                    $(row).addClass('action-row');
                    let id = data['id'];
                    let editUrl = "{{ url('admin/roles') }}" + "/" + id + "/" + "edit";
                    $(row).attr('data-url', editUrl);
                    $(row).attr('data-target', "#dynamic-modal");
                    $(row).attr('data-toggle', "modal");
                },
                initComplete: function(res, json) {

                }
            });


            $("body").on('click', "#add", function(e) {
                let url = $(this).data('url');
                $(".dynamic-title").html('Add Role & Permissions');
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
                $('.dynamic-title').html('Update Role & Permissions');
                getContent({
                    "url": url,
                    success: function(data) {
                        $(".dynamic-body").html(data);

                        runScript();
                    }
                });
            });

            $("body").on("click", ".parent-item", function(e) {
                let checked = $(this).prop('checked');
                let child = $(this).data('parent');
                $("input[data-child='" + child + "'").each(function() {
                    $(this).prop('checked', checked);
                });
            });

            $("body").on("click", "#toggle-permissions", function() {
                $(".permissions-list").find("input").prop('checked', $(this).prop('checked'));
            });

            //delete role
            $(document).on('click', '.role-delete', function(e) {
                e.stopPropagation();
                id = $(this).data('id');
                that = $(this);
                if (confirm("Are you sure you want to delete?")) {
                    $.ajax({
                        url: "{{ url('admin/roles') }}" + "/" + id,
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            _method: "DELETE",
                            id: id
                        },
                        beforeSend: function() {
                            that.attr('disabled', true).prepend(
                                "<i class='fa fa-spinner fa-spin'></i> ");
                        },
                        success: function(data) {
                            setAlert(data);
                            dataTable.draw('page');
                            that.removeAttr('disabled').html("<i class='fa fa-trash'></i>")
                        },
                        error: function(data) {
                            toast("error", "Something went wrong.", "Error");
                            that.removeAttr('disabled').html("<i class='fa fa-trash'></i>");
                        }
                    });

                }
            });

            runScript();
        })

        function runScript() {

            $("input[data-parent").each(function() {
                $parent = $(this);
                $("input[data-child='" + $(this).data('parent') + "']").each(function() {
                    if ($(this).prop('checked')) {
                        $parent.prop('checked', true);
                    }
                })
            });

            validateForm($("#role-create-form"), {
                rules: {
                    name: {
                        required: true,
                    },
                },
                messages: {}
            });

            validateForm($("#role-update-form"), {
                rules: {
                    name: {
                        required: true,
                    },
                },
                messages: {}
            });

            submitForm($("#role-create-form"), {
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

            submitForm($("#role-update-form"), {
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

            submitForm($("#role-delete-form"), {
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
        }
    </script>
@endsection
