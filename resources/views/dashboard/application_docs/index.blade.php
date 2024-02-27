@extends('layouts/contentLayoutMaster')

@section('title', 'Application Documents')

@section('breadcumb-right')
    <button data-toggle="modal" data-target="#dynamic-modal" id="add"
        data-url="{{ route('admin.application-document.create') }}"
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
                        {{-- <h4 class="card-title">Students</h4>
                        --}}
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard">

                            <div class="table-responsive">
                                <table id="course-table" class="table table-hover w-100 zero-configuration">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Countries</th>
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

            //datatabls
            dataTable = $("#course-table").DataTable({
                "processing": true,
                "serverSide": true,
                "bInfo": true,
                "pageLength": 100,
                "fixedHeader": true,
                ajax: {
                    url: "{{ url('admin/application-document/') }}",
                    data: function(d) {
                        console.log(d);
                        // d.quiz_id = $('select[name="quiz_id"]').val();
                    }
                },
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'countries',
                        name: 'countries'
                    },

                ],

                'createdRow': function(row, data, dataIndex) {
                    $(row).addClass('action-row');
                    let id = data['id'];
                    let editUrl = "{{ url('admin/application-document') }}" + "/" + id + "/edit";
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


            $('body').on('click', '#add', function(e) {

                $('.dynamic-title').text('Add Application Document');
                getContent({
                    url: $(this).data('url'),
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

            $('body').on('click', '.action-row', function(e) {

                $('.dynamic-title').text('Update Application Document');
                getContent({
                    url: $(this).data('url'),
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



        });


        function runScript() {

            $(".select").select2();

            validateForm($('#document-create-form,#document-edit-form'), {
                rules: {
                    name: {
                        required: true,
                    },
                    country: {
                        required: true,
                    },

                },
                messages: {}
            });

            submitForm($('#document-create-form'), {
                beforeSubmit: function() {
                    submitLoader("#submit-btn");
                },
                success: function(data) {
                    //setAlert(data);
                    if (data.success) {
                        modalReset();
                        dataTable.draw('page');
                        toast(data.code, data.message, data.title);
                    } else {
                        $(".dynamic-body").html(data);
                        runScript();
                    }
                },
                error: function(data) {
                    toast("error", "Something went wrong.", "Error");
                }
            });


            submitForm($('#document-edit-form'), {
                beforeSubmit: function() {
                    submitLoader("#submit-btn");
                },
                success: function(data) {
                    // setAlert(data);
                    if (data.success) {
                        modalReset();
                        toast(data.code, data.message, data.title);
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

            //country select
            $('.country').select2({
                placeholder: 'Type Country Name',
            });

        }
    </script>
@endsection
