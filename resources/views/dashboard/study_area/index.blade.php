@extends('layouts/contentLayoutMaster')

@section('title', 'Study Areas')

@section('breadcumb-right')
    <button data-toggle="modal" data-target="#dynamic-modal" id="add" data-url="{{ route('admin.study.create') }}"
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
                            {{-- <button data-toggle="modal" data-target="#dynamic-modal" id="add"
                                data-url="{{ route('admin.study.create') }}" class="btn btn-primary float-right">
                                <span class="fa fa-plus"></span> Add Study Area
                            </button> --}}
                            <div class="row application-filter align-items-center">
                                <div class="col-md-2 col-12">
                                    <div class="form-group">
                                        <label for="studyid">Study Area</label>
                                        <select data-colum="0" id="studyid" name="studyid[]" data-live-search="true"
                                            multiple class=" select form-control">
                                            @foreach ($study as $stddata)
                                                @if (isset($stddata->id))
                                                    <option value="{{ $stddata->id }}">{{ $stddata->name }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 col-12">
                                    <div class="form-group">
                                        <label for="substudyid">Sub Study Area</label>
                                        <select id="substudyid" name="substudyid[]" data-live-search="true" multiple
                                            class=" select form-control">



                                            @foreach ($substudy as $data)
                                                @if (isset($data->id))
                                                    <option value="{{ $data->id }}">{{ $data->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>


                                <div class="col-md-4 col-12 text-right">
                                    <button class="btn btn-primary" id="reset-filter">Reset</button>

                                </div>



                            </div>
                            <div class="table-responsive">
                                <table id="study-table" class="table table-hover w-100 zero-configuration">
                                    <thead>
                                        <tr>
                                        <tr>
                                            <th>Name</th>
                                            <th>Parent</th>
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
            $(".select").selectpicker();


            function studytosubstudy(id) {

                if (id == "studyid") {

                    $.ajax({
                        url: "{{ route('admin.study-to-substudy') }}",
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            studyid: $('#studyid').val(),

                        },
                        success: (data) => {
                            let studyLength = data.length;

                            if (studyLength > 0) {
                                var studyHTML =
                                    `<option value="${data[0].id}">${data[0].name}</option>`;
                                for (let i = 1; i < studyLength; i++) {

                                    var studyHTML = studyHTML +
                                        `<option value="${data[i].id}">${data[i].name}</option>`


                                }
                            } else {
                                var studyHTML = `<option value=""  disabled>No Data Found</option>`;

                            }


                            $('#substudyid').html(studyHTML);
                            $("#substudyid").selectpicker('refresh');


                        }
                    })

                }

            }

            $(".application-filter").find("select").on("change", function(e) {
                studytosubstudy(e.target.id);

                dataTable.draw();
            });
            // Datatable
            dataTable = $("#study-table").DataTable({
                "processing": true,
                "serverSide": true,
                "bInfo": true,
                "pageLength": 100,
                "fixedHeader": true,
                ajax: {
                    url: "{{ route('admin.studies') }}",
                    data: function(d) {
                        d.studyid = $('#studyid').val();
                        d.substudyid = $('#substudyid').val();
                    }
                },
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'parent_name',
                        name: 'parent_study.name'
                    },

                ],

                'createdRow': function(row, data, dataIndex) {
                    $(row).addClass('action-row');
                    let id = data['id'];
                    let editUrl = "{{ url('/admin/study') }}" + "/" + id + "/edit";
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
                $(".dynamic-title").html('Add Study Area');
                getContent({
                    "url": url,
                    success: function(data) {
                        $(".dynamic-body").html(data);
                        runScript();
                    }
                });
            });

            $("body").on('click', ".action-row", function(e) {
                let url = $(this).data('url');
                $('.dynamic-title').html('Update Study Area');
                getContent({
                    "url": url,
                    success: function(data) {
                        $(".dynamic-body").html(data);

                        runScript();
                    }
                });
            });

            runScript();

        });

        function runScript() {
            $(".select2").select2();
            validateForm($('#study-create-form'), {
                rules: {
                    name: {
                        required: true,
                    },
                    slug: {
                        required: true
                    },
                    parent_id: {
                        required: true
                    }
                },
                messages: {}
            });

            validateForm($('#study-edit-form'), {
                rules: {
                    name: {
                        required: true,
                    },
                    slug: {
                        required: true
                    },
                    parent_id: {
                        required: true
                    }
                },
                messages: {}
            });


            submitForm($("#study-create-form"), {
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

            submitForm($("#study-edit-form"), {
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

        $('#reset-filter').on('click', function() {
            $(".select").selectpicker('deselectAll');
            $(".select").val("");
            $(".select").selectpicker('refresh');
            dataTable.draw();
        });
    </script>
@endsection
