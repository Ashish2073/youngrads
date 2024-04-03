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

                            <div class="row application-filter align-items-center">
                                <div class="col-md-10">
                                    <div class="row">
                                        <div class="col-md-3 col-12">
                                            <div class="form-group">
                                                <label for="programeid">Program Name</label>
                                                <select id="programeid" name="programeid[]" data-live-search="true" multiple
                                                    class=" select form-control">
                                                    @foreach ($program as $prodata)
                                                        <option value="{{ $prodata->id }}"
                                                            @if (request()->has('promostkeysearch')) @if (request()->get('promostkeysearch') == $prodata->id)

                                                              selected @endif
                                                            @endif

                                                            >{{ $prodata->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-12">
                                            <div class="form-group">
                                                <label for="programelevelid">Program Level</label>
                                                <select id="programelevelid" name="programelevelid[]"
                                                    data-live-search="true" multiple class=" select form-control">

                                                    @foreach ($programlevel as $leveldata)
                                                        <option value="{{ $leveldata->id }}">{{ $leveldata->name }}</option>
                                                    @endforeach

                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-12">
                                            <div class="form-group">
                                                <label for="studyareaid">Study Area</label>
                                                <select id="studyareaid" name="studyareaid[]" data-live-search="true"
                                                    multiple class=" select form-control">
                                                    @foreach ($studyareas as $std)
                                                        <option value="{{ $std->id }}">{{ $std->name }}</option>
                                                    @endforeach

                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3 col-12">
                                            <div class="form-group">
                                                <label for="duration">Duration</label>
                                                <select id="duration" name="duration[]" data-live-search="true" multiple
                                                    class=" select form-control">

                                                    @foreach ($programduration as $pro)
                                                        <option value="{{ $pro->duration }}">{{ $pro->duration }}</option>
                                                    @endforeach


                                                </select>
                                            </div>
                                        </div>

                                    </div>

                                </div>


                                <div class="col-md-2 col-12 text-right">
                                    <button class="btn btn-primary" id="reset-filter">Reset</button>

                                </div>



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
            $(".select").selectpicker();


            $(".application-filter").find("select").on("change", function() {

                dataTable.draw();
            });




            //datatabls
            dataTable = $("#program-table").DataTable({
                "processing": true,
                "serverSide": true,
                "bInfo": true,
                "pageLength": 50,
                ajax: {
                    url: "{{ route('admin.programs') }}",
                    data: function(d) {
                        d.programeid = $('#programeid').val();
                        d.programelevelid = $('#programelevelid').val();
                        d.studyareaid = $('#studyareaid').val();
                        d.duration = $('#duration').val();
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

                $('.dynamic-title').text('Update Program');
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

            $('body').on('change', 'select[name="study_area_id"]', function(e) {
                $.ajax({
                    url: "{{ url('sub-study-areas') }}" + "/" + $(this).find('option:selected')
                        .val(),
                    success: function(data) {
                        console.log(data);
                        let html = "";
                        data.forEach(function(value, index) {
                            html += "<option value='" + value.id + "'>" + value.name +
                                "</option>";
                        });



                        $("#sub_study_area_id").html(html);

                        $("#sub_study_area_id").selectpicker('refresh');
                    }
                })
            });

        });

        function runScript() {
            $(".select").selectpicker();
            // $(".select").select2();
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

        $('#reset-filter').on('click', function() {
            $(".select").selectpicker('deselectAll');
            $(".select").val("");
            $(".select").selectpicker('refresh');

        });
    </script>
@endsection
