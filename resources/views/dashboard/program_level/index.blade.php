@extends('layouts/contentLayoutMaster')

@section('title', 'Program Levels')

@section('breadcumb-right')
    <button data-toggle="modal" data-target="#dynamic-modal" id="add" data-url="{{ route('admin.programlevel.create') }}"
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
                            {{-- <button data-toggle="modal" data-target="#dynamic-modal" id="add"
                                    data-url="{{ route('admin.programlevel.create') }}"
                                    class="btn btn-primary float-right">
                                <span class="fa fa-plus"></span> Add Program Levels
                            </button> --}}
                            <div class="table-responsive">
                                <table id="program-level-table" class="table table-hover w-100 zero-configuration">
                                    <thead>
                                        <tr>
                                        <tr>
                                            <th>Name</th>
                                            <th>Slug</th>
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
        $(document).ready(function() {
            @if (session('success'))
                toast('success', "{{ session('success') }}")
            @endif

            @if (session('error'))
                toast('error', "{{ session('error') }}")
            @endif
            // Datatable
            dataTable = $("#program-level-table").DataTable({
                "processing": true,
                "serverSide": true,
                "bInfo": true,
                "pageLength": 100,
                "fixedHeader": true,
                ajax: {
                    url: "{{ route('admin.programlevels') }}",
                    data: function(d) {
                        // d.quiz_id = $('select[name="quiz_id"]').val();
                    }
                },
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'slug',
                        name: 'slug'
                    },

                ],
                // programlevel
                'createdRow': function(row, data, dataIndex) {
                    $(row).addClass('action-row');
                    let id = data['id'];
                    let editUrl = "{{ url('admin/programlevel') }}" + "/" + id + "/" + "edit";
                    $(row).attr('data-url', editUrl);
                    $(row).attr('data-target', "#dynamic-modal");
                    $(row).attr('data-toggle', "modal");
                },
                initComplete: function(res, json) {
                    $(".second-place").append($("#add-btn").html());
                    $(".second-place").addClass('text-right');
                    $("#add-btn").remove();
                    initDatatable();
                }
            });

            $("body").on('click', "#add", function(e) {
                let url = $(this).data('url');
                $(".dynamic-title").html('Add Program Level');
                getContent({
                    "url": url,
                    success: function(data) {
                        if (data.errorpermissionmessage) {

                            let html = `<div class="alert alert-danger mt-2 py-2" role="alert" style="font-size: 20px">
                                        <button type="button" id="permission_error" class="close" data-dismiss="alert"
                                          aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                         <strong>Fail!</strong> ${data.errorpermissionmessage}
                                         </div>`;



                            $('.dynamic-body').html(html);

                            window.setTimeout(function() {
                                $(".alert").fadeTo(500, 0).slideUp(500, function() {
                                    $(this).remove();
                                });
                            }, 2000);




                        } else {
                            $('.dynamic-body').html(data);
                        }

                        runScript();
                    }
                });
            });

            $("body").on('click', ".action-row", function(e) {
                let url = $(this).data('url');
                $('.dynamic-title').html('Update Program Level');
                getContent({
                    "url": url,
                    success: function(data) {
                        if (data.errorpermissionmessage) {

                            let html = `<div class="alert alert-danger mt-2 py-2" role="alert" style="font-size: 20px">
                                       <button type="button" id="permission_error" class="close" data-dismiss="alert"
                                        aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                           <strong>Fail!</strong> ${data.errorpermissionmessage}
                                           </div>`;



                            $('.dynamic-body').html(html);

                            window.setTimeout(function() {
                                $(".alert").fadeTo(500, 0).slideUp(500, function() {
                                    $(this).remove();
                                });
                            }, 2000);




                        } else {
                            $('.dynamic-body').html(data);
                        }

                        runScript();
                    }
                });
            });

            runScript();

        });

        function runScript() {

            validateForm($('#program-create-form'), {
                rules: {
                    name: {
                        required: true,
                    },
                    slug: {
                        required: true
                    }
                },
                messages: {}
            });

            validateForm($('#program-edit-form'), {
                rules: {
                    name: {
                        required: true,
                    },
                    slug: {
                        required: true
                    }
                },
                messages: {}
            });


            submitForm($("#program-create-form"), {
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

            submitForm($("#program-edit-form"), {
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
    </script>
@endsection
