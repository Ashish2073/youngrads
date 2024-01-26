@extends('layouts/contentLayoutMaster')

@section('title', 'Campus')

@section('breadcumb-right')
    <button data-toggle="modal" data-target="#dynamic-modal" id="add" data-url="{{ route('admin.campus.create') }}"
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
                                <table id="course-table" class="table table-hover w-100 zero-configuration">
                                    <thead>
                                        <tr>
                                            <th>Campus Name</th>
                                            <th>University</th>
                                            <th>Website</th>
                                            {{-- <th>Logo</th> --}}
                                            <th>Cover</th>
                                            <th>Action</th>
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
            dataTable = $("#course-table").DataTable({
                "processing": true,
                "serverSide": true,
                "bInfo": true,
                "pageLength": 100,
                "fixedHeader": true,
                ajax: {
                    url: "{{ route('admin.campuses') }}",
                    data: function(d) {
                        // d.quiz_id = $('select[name="quiz_id"]').val();
                    }
                },
                columns: [{
                        data: 'campus',
                        name: 'campus'
                    },
                    {
                        data: 'university',
                        name: 'university'
                    },
                    {
                        data: 'website',
                        name: 'website'
                    },
                    // {
                    //     data: 'logo',
                    //     name: 'logo'
                    // },
                    {
                        data: 'cover',
                        name: 'cover',
                        visible: false,
                    },
                    {
                        data: 'action',
                        name: 'action',
                    },

                ],

                'createdRow': function(row, data, dataIndex) {
                    $(row).addClass('action-row');
                    let id = data['id'];



                    let editUrl = "{{ url('admin/campus') }}/" + id + "/edit";

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

                $('.dynamic-title').text('Add Campus');
                getContent({
                    url: $(this).data('url'),
                    success: function(data) {

                        $('.dynamic-body').html(data);
                        runScript();
                    }
                });
            });

            $('body').on('click', '.action-row', function(e) {

                $('.dynamic-title').text('Update Campus');
                getContent({
                    url: $(this).data('url'),
                    success: function(data) {

                        $('.dynamic-body').html(data);
                        runScript();
                    }
                });
            });

            $("body").on("click", "#program-conver", function() {
                $('#cover').click();
            });

            $("body").on("click", "#program-logo", function() {
                $("#logo").click();
            });

            $("body").on("change", "#logo", function(e) {
                imgPreview($("#program-logo"), e);
            });

            $("body").on("change", "#cover", function(e) {
                imgPreview($('#program-cover'), e);
            });



            $('body').on("change", "#country", function() {
                id = $(this).val();

                getContent({



                    url: "{{ url('admin/state/address') }}/" + id,
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

            $('body').on("change", "#state", function() {

                id = $(this).val();
                window.id = $(this).val();
                getContent({

                    url: "{{ url('admin/city/address') }}/" + id,
                    success: function(data) {

                        let html =
                            "<option value=''>--Select City --</option><option value='new-city'>Add New City</option>";
                        data.forEach(state => html +=
                            `<option value='${state.id}'>${state.name}</option>`);
                        $('#city').html('');
                        $('#city').append(html);
                        // $(".select").select2('refresh');
                    }
                });

            });

            $('body').on("change", "#city", function() {
                if ($(this).val() == 'new-city') {
                    $('#new-city').removeClass('d-none');
                } else {
                    $('#new-city').addClass('d-none');
                    $('#new-city').val("");
                }
            });


        });


        function runScript() {

            $(".select").select2();

            validateForm($('#course-create-form'), {
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
                },
                messages: {}
            });

            validateForm($('#course-edit-form'), {
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
                },
                messages: {}
            });

            submitForm($('#course-create-form'), {
                beforeSubmit: function() {
                    submitLoader("#submit-btn");
                },
                success: function(data) {
                    //setAlert(data);
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
                },
                complete: function() {
                    submitReset($("#submit-btn"));
                }
            });


            submitForm($('#course-edit-form'), {
                beforeSubmit: function() {
                    submitLoader("#submit-btn");
                },
                success: function(data) {
                    // setAlert(data);
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
                },
                complete: function() {
                    submitReset($("#submit-btn"));
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

            if ($('.no-image').text() != '') {
                $('#program-picture').css('display', 'block');
            }

            $('.country_id').select2({
                placeholder: '--Select--',
                multiple: false,
                // ajax: {
                //     url: route('get-countries'),
                //     dataType: 'json',
                //     type: 'POST',
                //     data: function(params) {
                //         return {
                //             name: params.term
                //         }
                //     },
                //     processResults: function(data) {
                //         return {
                //             results: data
                //         }
                //     }
                // }
            });

            //unversity select
            $('.university').select2({
                placeholder: 'Type Unversity Name',
                // ajax: {
                //     url: route('select-university'),
                //     dataType: 'json',
                //     type: 'POST',
                //     data: function(params) {
                //         return {
                //             name: params.term
                //         }
                //     },
                //     processResults: function(data) {
                //         return {
                //             results: data
                //         }
                //     }
                // }
            });

        }

        function imgPreview(fileName, e) {
            let preview = new FileReader();
            preview.onload = (e) => fileName.attr('src', e.target.result);
            preview.readAsDataURL(e.target.files[0]);
        }
    </script>
@endsection
