@extends('layouts/contentLayoutMaster')

@section('title', 'Universities')



@section('breadcumb-right')
    <button data-toggle="modal" data-target="#dynamic-modal" id="add" data-url="{{ route('admin.university.create') }}"
        class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle">
        <i class="feather icon-plus"></i>
    </button>
@endsection

@section('content')
    {{-- ajaxpermissionerror --}}

    @if (session('permissionerror'))
        <div class="alert alert-danger mt-2 py-2" role="alert" style="font-size: 20px">
            <button type="button" id="permission_error" class="close" data-dismiss="alert" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
            <strong>Fail!</strong> {{ session('permissionerror') }}
        </div>
    @endif





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
                                <table id="unviersity-table" class="table table-hover w-100 zero-configuration">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
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
            dataTable = $("#unviersity-table").DataTable({
                "processing": true,
                "serverSide": true,
                "bInfo": true,
                "pageLength": 50,
                "fixedHeader": true,

                ajax: {
                    url: "{{ route('admin.universities') }}",
                    data: function(d) {
                        // d.quiz_id = $('select[name="quiz_id"]').val();
                    },


                },
                columns: [{
                    data: 'name',
                    name: 'name'
                }],

                'createdRow': function(row, data, dataIndex) {



                    $(row).addClass('action-row');
                    let id = data['id'];
                    let editUrl = "{{ url('/admin/universities') }}" + "/" + id + "/edit";
                    $(row).attr('data-url', editUrl);
                    $(row).attr('data-target', "#dynamic-modal");
                    $(row).attr('data-toggle', "modal");
                },
                initComplete: function(res, json) {

                },



            });


            $('body').on('click', '#add', function(e) {

                $('.dynamic-title').text('Add University');
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
                    },
                    error: function(error) {

                    }
                });
            });




            $('body').on('click', '.action-row', function(e) {

                $('.dynamic-title').text('Update University');
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


            validateForm($('#university-create-form'), {
                rules: {
                    university: {
                        required: true,
                    }
                },
                messages: {
                    university: {
                        required: 'Please enter university name'
                    }
                }
            });

            validateForm($('#university-edit-form'), {
                rules: {
                    university: {
                        required: true,
                    }
                },
                messages: {
                    university: {
                        required: 'Please enter university name'
                    }
                }
            });

            submitForm($('#university-create-form'), {
                beforeSubmit: function() {
                    submitLoader("#submit-btn");
                },
                success: function(data) {
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


            submitForm($('#university-edit-form'), {
                beforeSubmit: function() {
                    submitLoader("#submit-btn");
                },
                success: function(data) {
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
