@extends('layouts/contentLayoutMaster')

@section('title', 'Students')

@section('vendor-style')
    {{-- vendor css files --}}
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/datatables.min.css')) }}">
@endsection

<style>
    .form-check.warning .form-check-input:checked {
        background-color: #ffc107;
        /* Bootstrap warning color */
    }
</style>

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

                            <p class="text-muted">Note: Click/Tap row for viewing profile</p>
                            <div class="row application-filter align-items-center">
                                <div class="col-md-3 col-12">
                                    <div class="form-group">
                                        <label for="userid">Student Id</label>
                                        <select data-colum="0" id="userid" name="id[]" data-live-search="true"
                                            multiple class=" select form-control apply-filter-student">
                                            @foreach ($userId as $user)
                                                @if (isset($user->id))
                                                    <option value="{{ $user->id }}">{{ 'young_stu_' . $user->id }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 col-12">
                                    <div class="form-group">
                                        <label for="useremail">Email</label>
                                        <select id="useremail" name="email[]" data-live-search="true" multiple
                                            class=" select form-control apply-filter-student">


                                            @foreach ($userEmail as $user)
                                                @if (isset($user->email))
                                                    <option value="{{ $user->email }}">{{ $user->email }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 col-12">
                                    <div class="form-group">
                                        <label for="userphone">Phone Number</label>
                                        <select id="userphone" name="phone[]" data-live-search="true" multiple
                                            class=" select form-control apply-filter-student">

                                            @foreach ($userPhone as $user)
                                                @if (isset($user->personal_number))
                                                    <option value="{{ $user->personal_number }}">
                                                        {{ $user->personal_number }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3 col-12">
                                    <div class="form-group">
                                        <label for="moderator-filter-id">Moderator Id</label>
                                        <select id="moderator-filter-id" name="moderatorid[]" data-live-search="true"
                                            multiple class="select form-control apply-filter-student">


                                            @foreach ($moderator as $moderatoruser)
                                                @if (isset($moderatoruser->username))
                                                    <option value="{{ $moderatoruser->id }}"
                                                        @if (session()->has('used_modifier')) @if (session()->get('used_modifier') == $moderatoruser->id)
                                                         
                                                        selected @endif
                                                        @endif


                                                        >
                                                        {{ $moderatoruser->username }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>




                                <div class="col-md-3 col-12 text-center">
                                    <button class="btn btn-primary" id="reset-filter">Reset</button>

                                </div>



                            </div>





                            <a href="{{ route('admin.students-data-export') }}" class="btn btn-primary mt-3">Export
                                Students
                                Application Data In Excel Form</a>


                            @php $userrole=json_decode(auth('admin')->user()->getRoleNames(),true)?? []; @endphp
                            @if (hasPermissionForRoles('assign_students_to_moderator_view', $userrole) ||
                                    auth('admin')->user()->getRoleNames()[0] == 'Admin')
                                <a href="javascript:void(0)" class="btn btn-primary mt-3"
                                    id="assignstudentmoderator">Assign/Dissociate
                                    Students
                                    To Moderator</a>
                            @endif

                            <div class="row application-filter align-items-center" id="studentassigndiv" hidden>

                                <div class="col-md-3 col-12">
                                    <div class="form-group">
                                        <label for="moderatorid">Moderator Id</label>
                                        <select id="moderatorid" name="moderatorid" data-live-search="true"
                                            class=" select form-control">
                                            <option value="" selected disabled>Please Select Moderator</option>




                                            @foreach ($moderator as $moderatoruser)
                                                @if (isset($moderatoruser->username))
                                                    <option value="{{ $moderatoruser->id }}">





                                                        {{ $moderatoruser->username }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>




                                {{-- <div class="col-md-3 col-12">
                                    <div class="form-group">
                                        <label for="userid">Select Student </label>
                                        <select data-colum="0" id="userid" name="id[]" data-live-search="true"
                                            multiple class=" select form-control">
                                            @foreach ($userId as $user)
                                                @if (isset($user->id))
                                                    <option value="{{ $user->id }}">{{ 'young_stu_' . $user->id }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div> --}}
                                <div class="col-md-3 col-12">
                                    <div class="form-group">
                                        <input class="form-check-input " type="checkbox" value=""
                                            id="assign-all-student">
                                        <label for="assignstudent form-check-label">Select/Deselect To All Students</label>

                                    </div>
                                </div>


                                <div class="col-md-3 col-12">
                                    <div class="form-group">
                                        <input class="form-check-input " type="checkbox" value="0"
                                            id="non-assign-student">
                                        <label for="assignstudent form-check-label">Non Assign Students</label>

                                    </div>
                                </div>





                                {{-- <div class="col-md-3 col-12 ">
                                    <div class="form-group ">
                                        <input class="form-check-input " type="checkbox" value=""
                                            id="nonassignstudents">
                                        <label for="nonassignstudents form-check-label">Non Assign Students</label>


                                    </div>
                                </div> --}}




                                <div class="row" style="gap:20px">

                                    <div class="form-group mb-2">
                                        <button class="btn btn-primary btn-block" id="moderator-assign">Assign</button>
                                    </div>


                                    <div class="form-group mb-2">
                                        <button class="btn btn-danger btn-block"
                                            id="moderator-dissociate">Dissociate</button>
                                    </div>

                                </div>






                            </div>





                            <div class="table-responsive">
                                <table id="user-table" class="table table-hover w-100 zero-configuration">
                                    <thead>
                                        <tr>
                                            <th id="thead-moderator-checkbox" hidden>Checkbox</th>
                                            <th>Id</th>
                                            <th>Moderator Id</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone No.</th>
                                            <th>Passport No.</th>
                                            <th>DOB</th>
                                            <th>Shortlisted Programs</th>
                                            @php $userrole=json_decode(auth('admin')->user()->getRoleNames(),true)?? []; @endphp
                                            @if (hasPermissionForRoles('students_delete', $userrole) || auth('admin')->user()->getRoleNames()[0] == 'Admin')
                                                <th>Action</th>
                                            @else
                                                <th hidden>Action</th>
                                            @endif
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

@section('vendor-script')
    {{-- vendor files --}}
    <script src="{{ asset(mix('vendors/js/tables/datatable/pdfmake.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/vfs_fonts.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.buttons.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/buttons.html5.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/buttons.print.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/buttons.bootstrap.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.bootstrap4.min.js')) }}"></script>
@endsection


@section('page-script')
    <script>
        var dataTable;
        jQuery(document).ready(function() {

            let moderators_fileter_id = "";


            $("#assignstudentmoderator").on('click', function() {

                if ($("#studentassigndiv").is(":hidden")) {
                    $("#studentassigndiv").removeAttr("hidden");
                    $(".moderator-checkbox").removeAttr("hidden");
                    $("#moderator-assign").val('');
                    $("#non-assign-student").val('');

                    $(".moderator-checkbox").closest("td").removeAttr("hidden", true);
                    $("#thead-moderator-checkbox").removeAttr("hidden");



                } else {
                    $("#studentassigndiv").attr("hidden", true);
                    $(".moderator-checkbox").attr("hidden", true);
                    $("#thead-moderator-checkbox").attr("hidden", true);
                    $(".moderator-checkbox").closest("td").attr("hidden", true);
                }

            })


            $(".select").selectpicker();
            $(".application-filter").find(".apply-filter-student").on("change", function() {
                moderators_fileter_id = $('#moderator-filter-id').val();

                dataTable.draw();
            });


            $("#non-assign-student").change(function() {
                if (this.checked) {
                    let checkedvalue = this.value;
                    console.log(checkedvalue);
                    moderators_fileter_id = [checkedvalue];
                    dataTable.draw();


                } else {
                    moderators_fileter_id = '';

                    dataTable.draw();

                }
            })




            // Datatable
            dataTable = $("#user-table").DataTable({
                "processing": true,
                "serverSide": true,
                "bInfo": true,
                "pageLength": 50,
                "fixedHeader": true,

                ajax: {
                    url: "{{ route('admin.students') }}",

                    data: function(d) {

                        d.id = $("#userid").val();
                        d.email = $('#useremail').val();
                        d.personal_number = $('#userphone').val();
                        d.moderator_filter_id = moderators_fileter_id;
                    }
                },
                "order": [
                    [7, "desc"]
                ],
                columns: [{
                        data: 'moderator_checkbox',
                        name: 'moderator_checkbox',
                        // searchable: true,
                        // visible: false

                    },
                    {
                        data: 'student_id',
                        name: 'student_id',

                    },


                    {
                        data: 'moderator_username',
                        name: 'moderator_username',

                    },

                    {
                        data: 'name',
                        name: 'name',

                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'personal_number',
                        name: 'personal_number'
                    },
                    {
                        data: 'passport',
                        name: 'passport'
                    },
                    {
                        data: 'dob',
                        name: 'dob'
                    },
                    {
                        data: 'shortlist',
                        name: 'shortlist'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },
                    // {
                    //     data: 'edit',
                    //     name: 'edit'
                    // },

                    // { data: 'questions', orderable: false, searchable: false },
                    // { data: 'created_at' }
                ],
                // columnDefs: [
                //     {
                //         targets: [7],
                //         visible: false
                //     }
                // ],

                'createdRow': function(row, data, dataIndex) {
                    var checkboxCell = $(row).find('.moderator-checkbox').closest('td');

                    var ckeckbox = $(row).find('.moderator-checkbox');


                    $("#assign-all-student").on('change', function() {
                        if ($(this).is(':checked')) {


                            ckeckbox.prop("checked", true);


                        } else {
                            ckeckbox.prop("checked", false);




                        }
                    })


                    // Hide the cell
                    checkboxCell.attr('hidden', true);
                    if ($("#studentassigndiv").is(":hidden") == false) {
                        checkboxCell.attr('hidden', false);
                        ckeckbox.attr("hidden", false);
                    }

                    $(row).addClass('action-row');
                    let id = data['id'];

                    $(row).attr('data-id', id);
                    $(row).attr('data-target', "#apply-model");
                    $(row).attr('data-toggle', "modal");
                },
                initComplete: function(res, json) {
                    // $(".second-place").append($("#add-btn").html());
                    // $(".second-place").addClass('text-right');
                    // $("#add-btn").remove();
                    // initDatatable();
                }
            });



            // $('body').on('click', '.student-edit', function(e) {
            //     e.stopPropagation();
            //     id = $(this).data('id');
            //     $('#dynamic-modal').modal();
            //     $('.dynamic-title').text('Update Student Moderator');

            //     $.ajax({
            //         url: "{{ url('admin/students') }}" + "/" + id + "/edit",
            //         beforeSend: function() {
            //             $('.dynamic-body').html("Loading...");
            //         },
            //         success: function(data) {
            //             console.log(data);
            //             $('.dynamic-body').html(data);

            //         }
            //     })






            // });


            // runScript();










            $("body").on('click', "#add", function(e) {
                let url = $(this).data('url');
                $(".dynamic-title").html('Add User');

                getContent({
                    "url": url,
                    success: function(data) {
                        $(".dynamic-apply").html(data);
                        runScript();
                    }
                });
            });

            $("body").on('click', ".action-row", function(e) {
                let id = $(this).data('id');
                var url = `{{ url('student/${id}/viewprofile') }}`;
                $('.apply-title').html('View Profile');
                $(".dynamic-apply").html("Loading..");
                getContent({
                    "url": url,
                    success: function(data) {
                        $('#apply-model').find('.modal-dialog').addClass('modal-lg');
                        $(".dynamic-apply").html(data);
                        // $(".dynamic-apply").parent().addClass('modal-lg');
                    }
                });
            });

            /////////////////////checkBox to select to student //////////// 

            $(document).on('click', '.moderator-checkbox', function(e) {
                e.stopPropagation();
            })
            /////////////////////////Moderator assign to staudent value save/////////////////////////////////
            $("#moderator-assign").on('click', function(e) {
                e.preventDefault();
                that = $(this);
                var checkedValues = $(".moderator-checkbox:checked").map(function() {
                    return $(this).val();
                }).get();
                var moderatorid = $('#moderatorid').val();

                $.ajax({
                    url: "{{ url('admin/moderator-assign-students') }}",
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        _method: "POST",
                        moderatorid: moderatorid,
                        checkedValues: checkedValues
                    },
                    beforeSend: function() {

                        that.attr('disabled', true).prepend(
                            "<i class='fa fa-spinner fa-spin'></i> ");
                    },
                    success: function(data) {


                        if (data.error) {
                            toast("error", data.message, "Error");
                            that.removeAttr('disabled').html(
                                "Assign"
                            );
                        } else {

                            setAlert(data);

                            $("#non-assign-student").prop("checked", false);
                            $('#assign-all-student').prop("checked", false);

                            moderators_fileter_id = '';

                            dataTable.draw();

                            $("#thead-moderator-checkbox").attr('hidden', true);
                            $(".moderator-checkbox").closest("td").removeAttr("hidden", true);
                            $("#studentassigndiv").attr("hidden", true);



                            that.removeAttr('disabled').html(
                                "Assign"
                            );
                        }

                    },
                    error: function(data) {


                        if (data.responseJSON.errors.checkedValues) {
                            message = data.responseJSON.errors.checkedValues[0];

                            toast("error", message, "Error");
                        } else if (data.responseJSON.errors.moderatorid) {
                            message = data.responseJSON.errors.moderatorid[0];
                            toast("error", message, "Error");


                        } else {
                            toast("error", "Something went wrong.", "Error");
                        }





                        // moderatorid

                    }


                })




            });






            ////////////////////////////Modeartor dissociate to student////////////




            $("#moderator-dissociate").on('click', function(e) {
                e.preventDefault();
                that = $(this);
                var checkedValues = $(".moderator-checkbox:checked").map(function() {
                    return $(this).val();
                }).get();
                var moderatorid = $('#moderatorid').val();

                $.ajax({
                    url: "{{ url('admin/moderator-dissociate-students') }}",
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        _method: "POST",
                        moderatorid: moderatorid,
                        checkedValues: checkedValues
                    },
                    beforeSend: function() {

                        that.attr('disabled', true).prepend(
                            "<i class='fa fa-spinner fa-spin'></i> ");
                    },
                    success: function(data) {


                        if (data.error) {
                            toast("error", data.message, "Error");
                            that.removeAttr('disabled').html(
                                "Dissociate"
                            );
                        } else {

                            setAlert(data);
                            $("#non-assign-student").prop("checked", false);
                            $('#assign-all-student').prop("checked", false);
                            moderators_fileter_id = '';

                            dataTable.draw();


                            $("#thead-moderator-checkbox").attr('hidden', true);
                            $(".moderator-checkbox").closest("td").removeAttr("hidden", true);
                            $("#studentassigndiv").attr("hidden", true);



                            that.removeAttr('disabled').html(
                                "Dissociate"
                            );
                        }

                    },
                    error: function(data) {

                        if (data.responseJSON.errors.checkedValues[0]) {
                            message = data.responseJSON.errors.checkedValues[0];

                            toast("error", message, "Error");
                        } else {
                            toast("error", "Something went wrong.", "Error");
                        }



                    }
                });




            })






            //delete///////////////////////////////////////////
            $(document).on('click', '.student-delete', function(e) {
                e.stopPropagation();
                id = $(this).data('id');
                that = $(this);
                if (confirm("Are you sure you want to delete?")) {
                    $.ajax({
                        url: "{{ url('admin/students') }}" + "/" + id,
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            _method: "DELETE",
                            id: id
                        },
                        beforeSend: function() {
                            that.attr('disabled', true).prepend(
                                "<i class='fa fa-spinner fa-spin'></i> ");
                        },
                        success: function(data) {
                            setAlert(data);
                            dataTable.draw('page');
                            that.removeAttr('disabled').html("<i class='fa fa-trash'></i>")
                        },
                        error: function(data) {
                            toast("error", "Something went wrong.", "Error");
                        }
                    });

                }
            });

            //listing shortlist program
            $(document).on('click', '.student-shortlist', function(e) {
                e.stopPropagation();
                id = $(this).data('id');
                that = $(this);
                $('#dynamic-modal').modal();
                $('.dynamic-title').text('Shortlisted Program(s)');

                $.ajax({
                    url: "{{ url('admin/shortlist-courses') }}" + "/" + id,
                    beforeSend: function() {
                        $('.dynamic-body').html("Loading...");
                    },
                    success: function(data) {

                        $('.dynamic-body').html(data);
                        $("#shortlist-table").dataTable();
                    }
                })

            });


            runScript();
        })

        function runScript() {
            // initAccordin();
            $(".select").selectpicker();


            submitForm($("#user-create-form"), {
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

            submitForm($("#user-update-form"), {
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

            submitForm($("#user-delete-form"), {
                beforeSubmit: function() {
                    if (!confirm('Are you sure want to delete?')) {
                        return;
                    }
                },
                success: function(data) {
                    if (isJson(data)) {
                        data = JSON.parse(data);
                        dataTable.ajax.reload();

                        $('#dynamic-modal').modal('hide');
                        if (data.success) {
                            showMessage("The member deleted successfully!", "danger");
                        } else {
                            showMessage("<strong>Error!</strong> Something went wrong.", "danger");
                        }
                        $(".dynamic-body").html("");
                    } else {
                        $(".dynamic-body").html(data);
                        runScript();
                    }
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
