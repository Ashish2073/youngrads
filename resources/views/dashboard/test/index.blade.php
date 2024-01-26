@extends('layouts/contentLayoutMaster')

@section('title', 'Test')
@section('breadcumb-right')
    <button data-toggle="modal" data-target="#dynamic-modal" id="add"
    data-url="{{ route('admin.test.create') }}" class="d-none btn btn-primary float-right"
    >
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
                                <table id="feetype-table" class="table table-hover w-100 zero-configuration">
                                    <thead>
                                    <tr>
                                    <tr>
                                        <th>Name</th>
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
        $(document).ready(function () {

            //datatabls
            dataTable = $("#feetype-table").DataTable({
                "processing": true,
                "serverSide": true,
                "bInfo": true,
                "pageLength": 50,
                "fixedHeader": true,

                ajax: {
                    url: route('admin.tests').url(),
                    data: function (d) {
                        // d.quiz_id = $('select[name="quiz_id"]').val();
                    }
                },
                columns: [{
                    data: 'test_name',
                    name: 'test_name'
                }
                ],

                'createdRow': function (row, data, dataIndex) {
                    $(row).addClass('action-row');
                    let id = data['id'];
                    let editUrl = route('admin.test.edit', id).url();
                    $(row).attr('data-url', editUrl);
                    $(row).attr('data-target', "#dynamic-modal");
                    $(row).attr('data-toggle', "modal");
                },
                initComplete: function (res, json) {

                }
            });


            $('body').on('click', '#add', function (e) {

                $('.dynamic-title').text('Add Test');
                getContent({
                    url: $(this).data('url'),
                    success: function (data) {

                        $('.dynamic-body').html(data);
                        runScript();
                    }
                });
            });

            $('body').on('click', '.action-row', function (e) {

                $('.dynamic-title').text('Update Test');
                getContent({
                    url: $(this).data('url'),
                    success: function (data) {

                        $('.dynamic-body').html(data);
                        runScript();
                    }
                });
            });

        });

        function runScript() {


            validateForm($('#test-create-form'), {
                rules: {
                  test_name: {
                        required: true,
                    }
                },
                messages: {}
            });

            validateForm($('#test-edit-form'), {
                rules: {
                  test_name: {
                        required: true,
                    }
                },
                messages: {}
            });

            submitForm($('#test-create-form'), {
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


            submitForm($('#test-edit-form'), {
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

            submitForm($("#delete-form"), {
                beforeSubmit: function () {
                    if (!confirm('Are you sure you want to delete')) return false;
                    submitLoader("#submit-btn-delete");
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

        }


    </script>
@endsection
