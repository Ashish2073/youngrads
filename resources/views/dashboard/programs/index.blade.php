@extends('layouts/contentLayoutMaster')

@section('title', 'Programs')

@section('breadcumb-right')
    <button data-toggle="modal" data-target="#dynamic-modal" id="add" data-url="{{ route('admin.program.create') }}"
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
                                <table id="program-table" class="table table-hover w-100 zero-configuration">
                                    <thead>
                                        <tr>
                                        <tr>
                                            <th>Name</th>
                                            <th>Program Level</th>
                                            <th>Study Area</th>
                                            <th>Duration</th>
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

            //datatabls
            dataTable = $("#program-table").DataTable({
                "processing": true,
                "serverSide": true,
                "bInfo": true,
                "pageLength": 50,
                ajax: {
                    url: "{{ route('admin.programs') }}",
                    data: function(d) {
                        // d.quiz_id = $('select[name="quiz_id"]').val();
                    }
                },
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'program_level',
                        name: 'program_level'
                    },
                    {
                        data: 'study_area',
                        name: 'study_area'
                    },
                    {
                        data: 'duration',
                        name: 'duration'
                    }

                ],

                'createdRow': function(row, data, dataIndex) {
                    $(row).addClass('action-row');
                    let id = data['id'];
                    let editUrl = "{{ url('admin/program') }}" + "/" + id + "/edit";
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

                $('.dynamic-title').text('Add Program');
                getContent({
                    url: $(this).data('url'),
                    success: function(data) {

                        $('.dynamic-body').html(data);
                        runScript();
                    }
                });
            });

            $('body').on('click', '.action-row', function(e) {

                $('.dynamic-title').text('Update Program');
                getContent({
                    url: $(this).data('url'),
                    success: function(data) {
 
                        $('.dynamic-body').html(data);
                        runScript();
                    }
                });
            });

            $('body').on('change', 'select[name="study_area_id"]', function(e) {
                $.ajax({
                    url:"{{url('sub-study-areas')}}"+"/"+ $(this).find('option:selected').val(),
                    success: function(data) {
                        let html = "";
                        data.forEach(function(value, index) {
                            html += "<option value='" + value.id + "'>" + value.name +
                                "</option>";
                        });
                        $("#sub_study_area_id").html(html);
                    }
                })
            });

        });

        function runScript() {

            $(".select").select2();
            validateForm($('#program-create-form'), {
                rules: {
                    name: {
                        required: true,
                    },
                    program_level_id: {
                        required: true
                    },
                    study_area_id: {
                        required: true
                    },
                    duration: {
                        required: true,
                        number: true,
                    },

                },
                messages: {
                    duration: {
                        number: 'Enter duration in months.'
                    }

                }
            });

            validateForm($('#program-edit-form'), {
                rules: {
                    name: {
                        required: true,
                    },
                    program_level_id: {
                        required: true
                    },
                    study_area_id: {
                        required: true,
                    },
                    duration: {
                        required: true,
                        number: true
                    },

                },
                messages: {

                    duration: {
                        number: 'Enter duration in months.'
                    }
                }
            });

            submitForm($('#program-create-form'), {
                beforeSubmit: function() {
                    submitLoader("#submit-btn");
                },
                success: function(data) {

                    if (data.success) {
                        modalReset();
                        dataTable.draw('page');
                        setAlert(data);

                    } else {
                        $(".dynamic-body").html(data);
                        runScript();
                    }
                },
                error: function(data) {
                    toast("error", "Something went wrong.", "Error");
                }
            });


            submitForm($('#program-edit-form'), {
                beforeSubmit: function() {
                    submitLoader("#submit-btn");
                },
                success: function(data) {

                    if (data.success) {
                        modalReset();
                        dataTable.draw('page');
                        setAlert(data);
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
