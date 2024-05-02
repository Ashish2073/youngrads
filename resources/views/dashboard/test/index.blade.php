@extends('layouts/contentLayoutMaster')

@section('title', 'Test')
@section('breadcumb-right')
    <button data-toggle="modal" data-target="#dynamic-modal" id="add" data-url="{{ route('admin.test.create') }}"
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
                                <table id="feetype-table" class="table table-hover w-100 zero-configuration">
                                    <thead>
                                        <tr>
                                        <tr>
                                            <th>Test Name</th>
                                            <th>Max Number</th>
                                            <th>Sub Category Test Name</th>
                                            <th>Sub Category Max Number</th>

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

            //datatabls
            dataTable = $("#feetype-table").DataTable({
                "processing": true,
                "serverSide": true,
                "bInfo": true,
                "pageLength": 50,
                "fixedHeader": true,

                ajax: {
                    url: "{{ route('admin.tests') }}",
                    data: function(d) {
                        // d.quiz_id = $('select[name="quiz_id"]').val();
                    }
                },
                columns: [{
                        data: 'testname',
                        name: 'tests.test_name '
                    }, {
                        data: 'testsmax',
                        name: 'tests.max'
                    }, {
                        data: 'childtestname',
                        name: 'special_test_sub.name'
                    },
                    {
                        data: 'childtestmax',
                        name: 'special_test_sub.max'
                    }
                ],

                'createdRow': function(row, data, dataIndex) {
                    $(row).addClass('action-row');
                    let id = data['id'];
                    let childtestname = data['childtestname'];
                    let editUrl = "{{ url('/admin/test') }}" + "/" + childtestname + id + "/" + "edit";
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

                $('.dynamic-title').text('Add Test');
                getContent({
                    url: $(this).data('url'),
                    success: function(data) {

                        $('.dynamic-body').html(data);
                        runScript();
                    }
                });
            });

            $('body').on('click', '.action-row', function(e) {

                $('.dynamic-title').text('Update Test');
                getContent({
                    url: $(this).data('url'),
                    success: function(data) {

                        $('.dynamic-body').html(data);
                        runScript();
                    }
                });
            });

        });

        function runScript() {
            $(".select").selectpicker();
            $(".select2").selectpicker();

            validateForm($('#test-create-form'), {
                rules: {
                    test_name: {
                        required: true,
                    },
                    test_number: {
                        required: true,

                    },

                },
                messages: {}
            });

            validateForm($('#test-edit-form'), {
                rules: {
                    test_name: {
                        required: true,
                    },
                    test_number_max: {
                        required: true,

                    },
                    sub_test_name: {
                        required: true,
                    },
                    sub_test_number_max: {
                        required: true,

                    }
                },
                messages: {}
            });

            submitForm($('#test-create-form'), {
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


            submitForm($('#test-edit-form'), {
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
