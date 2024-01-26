@extends('layouts/contentLayoutMaster')

@section('title', 'Currency')

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
                                    data-url="{{ route('admin.currency.create') }}" class="btn btn-primary float-right">
                                <span class="fa fa-plus"></span> Add Currency
                            </button>
                            <div class="table-responsive">
                                <table id="curruncy-table" class="table table-hover w-100 zero-configuration">
                                    <thead>
                                    <tr>
                                    <tr>
                                        <th>Name</th>
                                        <th>Symbol</th>
                                        <th>Rate</th>
                                        <th>Code</th>
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
            dataTable = $("#curruncy-table").DataTable({
                "processing": true,
                "serverSide": true,
                "bInfo": true,
                "pageLength": 50,
                "fixedHeader": true,
                ajax: {
                    url: route('admin.currencies').url(),
                    data: function (d) {
                        // d.quiz_id = $('select[name="quiz_id"]').val();
                    }
                },
                columns: [{
                    data: 'name',
                    name: 'name'
                },
                    {
                        data: 'symbol',
                        name: 'symbol'
                    },
                    {
                        data: 'rate',
                        name: 'rate'
                    },
                    {
                        data: 'code',
                        name: 'code'
                    },


                ],

                'createdRow': function (row, data, dataIndex) {
                    $(row).addClass('action-row');
                    let id = data['id'];
                    let editUrl = route('admin.currency.edit', id).url();
                    $(row).attr('data-url', editUrl);
                    $(row).attr('data-target', "#dynamic-modal");
                    $(row).attr('data-toggle', "modal");
                },
                initComplete: function (res, json) {

                }
            });


            $('body').on('click', '#add', function (e) {

                $('.dynamic-title').text('Add Currency');
                getContent({
                    url: $(this).data('url'),
                    success: function (data) {

                        $('.dynamic-body').html(data);
                        runScript();
                    }
                });
            });

            $('body').on('click', '.action-row', function (e) {

                $('.dynamic-title').text('Edit Currency');
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


            validateForm($('#currency-create-form'), {
                rules: {
                    name: {
                        required: true,
                    },
                    symbol: {
                        required: true
                    },
                    rate: {
                        required: true
                    },
                    code: {
                        required: true
                    }
                },
                messages: {}
            });

            validateForm($('#intake-edit-form'), {
                rules: {
                    name: {
                        required: true,
                    },
                    type: {
                        required: true
                    }
                },
                messages: {}
            });

            submitForm($('#currency-create-form'), {
                beforeSubmit: function () {
                    submitLoader("#submit-btn");
                },
                success: function (data) {
                    setAlert(data);
                    if (data.success) {
                        //modalReset();
                        //dataTable.draw('page');
                        console.log(data);
                    } else {
                        $(".dynamic-body").html(data);
                        runScript();
                    }
                },
                error: function (data) {
                    toast("error", "Something went wrong.", "Error");
                }
            });


            submitForm($('#intake-edit-form'), {
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


        }


    </script>
@endsection
