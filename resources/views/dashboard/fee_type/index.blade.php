@extends('layouts/contentLayoutMaster')

@section('title', 'Fee Types')
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
                            <button data-toggle="modal" data-target="#dynamic-modal" id="add"
                                    data-url="{{ route('admin.feetype.create') }}" class="btn btn-primary float-right">
                                <span class="fa fa-plus"></span> Add Fee
                            </button>
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
                    url: route('admin.feetypes').url(),
                    data: function (d) {
                        // d.quiz_id = $('select[name="quiz_id"]').val();
                    }
                },
                columns: [{
                    data: 'name',
                    name: 'name'
                }
                ],

                'createdRow': function (row, data, dataIndex) {
                    $(row).addClass('action-row');
                    let id = data['id'];
                    let editUrl = route('admin.feetype.edit', id).url();
                    $(row).attr('data-url', editUrl);
                    $(row).attr('data-target', "#dynamic-modal");
                    $(row).attr('data-toggle', "modal");
                },
                initComplete: function (res, json) {

                }
            });


            $('body').on('click', '#add', function (e) {

                $('.dynamic-title').text('Add Fee Type');
                getContent({
                    url: $(this).data('url'),
                    success: function (data) {

                        $('.dynamic-body').html(data);
                        runScript();
                    }
                });
            });

            $('body').on('click', '.action-row', function (e) {

                $('.dynamic-title').text('Update Fee Type');
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


            validateForm($('#feetype-create-form'), {
                rules: {
                    name: {
                        required: true,
                    }
                },
                messages: {}
            });

            validateForm($('#feetype-edit-form'), {
                rules: {
                    name: {
                        required: true,
                    }
                },
                messages: {}
            });

            submitForm($('#feetype-create-form'), {
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


            submitForm($('#feetype-edit-form'), {
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
