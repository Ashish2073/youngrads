@extends('layouts/contentLayoutMaster')

@section('title', 'Document Type')

@section('breadcumb-right')
    <button data-toggle="modal" data-target="#dynamic-modal" id="add" data-url="{{ route('admin.document-type.create') }}"
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
                                <table id="document-type" class="table table-hover w-100 zero-configuration">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            {{-- <th>Required</th>
                                            <th>Document Limit</th> --}}
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
            dataTable = $("#document-type").DataTable({
                "processing": true,
                "serverSide": true,
                "bInfo": true,
                "pageLength": 100,
                "fixedHeader": true,
                ajax: {
                    url: "{{ route('admin.document-types') }}",
                    data: function(d) {
                        // d.quiz_id = $('select[name="quiz_id"]').val();
                    }
                },
                columns: [{
                        data: 'title',
                        name: 'title'
                    },
                    // {
                    //data: 'is_required',
                    //name: 'is_required'
                    //},
                    //{
                    // data: 'document_limit',
                    //name: 'document_limit'
                    //}
                ],

                'createdRow': function(row, data, dataIndex) {
                    $(row).addClass('action-row');
                    let id = data['id'];
                    let editUrl = "{{ url('admin/document-type') }}" + "/" + id + "/edit";
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

                $('.dynamic-title').text('Add Document Type');
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

                $('.dynamic-title').text('Update Document Type');
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

            validateForm($('#document-create-form'), {
                rules: {
                    title: {
                        required: true,
                    },
                    document_limit: {
                        required: true,
                    },
                    document_required: {
                        required: true,
                    }

                },
                messages: {}
            });

            validateForm($('#document-edit-form'), {
                rules: {
                    name: {
                        required: true,
                    },
                    website: {
                        required: true,
                    },
                    university: {
                        required: true,
                    },
                    address: {
                        required: true,
                    },
                    country: {
                        required: true,
                    },
                    state: {
                        required: true
                    },
                    city: {
                        required: true
                    }
                },
                messages: {}
            });

            submitForm($('#document-create-form'), {
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


            submitForm($('#document-edit-form'), {
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


            // edit logo,fuctionlity,
            $('#program-picture').css('display', 'none');

            $('#program-cover').click(function() {
                $('#cover').click();
            });

            $('#program-logo').click(function() {
                $('#logo').click();
            });


            $('#logo').change(function(e) {
                imgPreview($('#program-logo'), e);
            });

            $('#cover').change(function(e) {
                imgPreview($('#program-cover'), e);
            });

            function imgPreview(fileName, e) {

                let preview = new FileReader();
                preview.onload = (e) => fileName.attr('src', e.target.result);
                preview.readAsDataURL(e.target.files[0]);
            }

            if ($('.no-image').text() != '') {
                $('#program-picture').css('display', 'block');
            }

            //address
            //country ajax select
            $('.country_id').select2({
                placeholder: 'Country',
                multiple: false,
                ajax: {
                    url: `{{ route('get-countries') }}`,
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

            //unversity select
            $('.university').select2({
                placeholder: 'Type Unversity Name',
                ajax: {
                    url: "{{ route('select-university') }}",
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


            $('#country').change(function() {
                id = $(this).val();

                getContent({

                    url: "{{ url('admin/state') }}" + "/" + id,
                    success: function(data) {
                        let html = '';
                        data.forEach(state => html +=
                            `<option value='${state.id}'>${state.name}</option>`);
                        $('#state').html('');

                        $('#state').append(html);
                        $(".select").selectpicker('refresh');
                    }
                });
            });

            $('#state').change(function() {
                id = $(this).val();

                getContent({

                    url: "{{ url('admin.city') }}" + "/" + id,
                    success: function(data) {
                        let html = '';
                        data.forEach(state => html +=
                            `<option value='${state.id}'>${state.name}</option>`);
                        $('#city').html('');
                        $('#city').append(html);
                        $(".select").selectpicker('refresh');
                    }
                });
            });


        }
    </script>
@endsection
