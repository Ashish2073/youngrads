@extends('layouts/contentLayoutMaster')

@section('title', 'State')

@section('breadcumb-right')
    <button data-toggle="modal" data-target="#dynamic-modal" id="add" data-url="{{ route('admin.state.create') }}"
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
                                <table id="state-table" class="table table-hover w-100 zero-configuration">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Country</th>
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
            dataTable = $("#state-table").DataTable({
                "processing": true,
                "serverSide": true,
                "bInfo": true,
                "pageLength": 50,
                "fixedHeader": true,

                ajax: {
                    url: "{{ route('admin.states') }}",
                    data: function(d) {
                        // d.quiz_id = $('select[name="quiz_id"]').val();
                    }
                },
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'country',
                        name: 'country'
                    }
                ],

                'createdRow': function(row, data, dataIndex) {
                    $(row).addClass('action-row');
                    let id = data['id'];
                    let editUrl = "{{ url('admin/state') }}" + "/" + id + "/edit";
                    $(row).attr('data-url', editUrl);
                    $(row).attr('data-target', "#dynamic-modal");
                    $(row).attr('data-toggle', "modal");
                },
                initComplete: function(res, json) {

                }
            });


            $('body').on('click', '#add', function(e) {

                $('.dynamic-title').text('Add Country');
                getContent({
                    url: $(this).data('url'),
                    success: function(data) {

                        $('.dynamic-body').html(data);
                        runScript();
                    }
                });
            });

            $('body').on('click', '.action-row', function(e) {

                $('.dynamic-title').text('Update Country');
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

            $('#country').select2({
                placeholder: 'Country',
                multiple: false,
                ajax: {
                    url: "{{ route('get-countries') }}",
                    dataType: 'json',
                    type: 'POST',
                    data: function(params) {
                        return {
                            name: params.term
                        }
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        }
                    }
                }
            });

            validateForm($('#state-create-form'), {
                rules: {
                    name: {
                        required: true,
                    },
                    country: {
                        required: true,

                    }

                },
                messages: {

                }
            });

            validateForm($('#state-edit-form'), {
                rules: {
                    name: {
                        required: true,
                    },
                    country: {
                        required: true,

                    }

                },
                messages: {

                }
            });

            submitForm($('#state-create-form'), {
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


            submitForm($('#state-edit-form'), {
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
    </script>
@endsection
