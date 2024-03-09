@extends('layouts/contentLayoutMaster')


@section('title', 'Moderators')
@section('breadcumb-right')
    <button data-toggle="modal" data-target="#dynamic-modal" id="add" data-url="{{ route('admin.moderator.create') }}"
        class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle">
        <i class="feather icon-plus"></i>
    </button>
@endsection
<style>
    #userrole {
        display: flex;
        gap: 10px;
        /* text-align: center; */
        /* justify-content: center; */
    }

    .role-card-1 {
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
        transition: all 0.3s cubic-bezier(.25, .8, .25, 1);
        width: 135px;
        height: 35px;
        background-color: #ff8510;
        display: flex;
        justify-content: center;
        align-items: center;
        position: relative;
        text-align: center;

    }

    .role-card-1 h5 {
        color: hsl(0deg 25.67% 97.53%);
    }

    .role-card-1:hover {
        box-shadow: 0 14px 28px rgba(0, 0, 0, 0.25), 0 10px 10px rgba(0, 0, 0, 0.22);
    }

    .close-button {
        position: absolute;
        top: -5px;
        right: -5px;
        cursor: pointer;
        color: #ff8510;
        background-color: #f1eaea;
        width: 15px;
        height: 15px;
        transition: top 0.3s ease;

    }

    .close-button:hover {
        top: -10px;
        /* Adjust to move the button inside on hover */
    }
</style>
@section('content')








    <section id="basic-datatable">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        {{-- <h4 class="card-title">moderators</h4> --}}
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard">
                            <p class="text-muted">Note: Click/Tap row for viewing profile</p>

                            <div class="row application-filter align-items-center">

                                <div class="col-md-3 col-12">
                                    <div class="form-group">
                                        <label for="moderatorid">Moderator Id</label>
                                        <select data-colum="0" id="moderatorid" name="moderatorid[]" data-live-search="true"
                                            multiple class=" select form-control apply-filter-moderator">
                                            @foreach ($moderator as $user)
                                                <option value="{{ $user->id }}"
                                                    @if (session()->has('used_moderators_under_supermoderator')) @if (session()->get('used_moderators_under_supermoderator') == $user->id)

                                                         selected @endif
                                                    @endif>
                                                    {{ $user->username }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3 col-12">
                                    <div class="form-group">
                                        <label for="userid">Super Moderator Id</label>
                                        <select data-colum="0" id="supermoderatorid" name="supermoderatorid[]"
                                            data-live-search="true" multiple
                                            class=" select form-control apply-filter-moderator">
                                            @foreach ($supermoderator as $user)
                                                <option value="{{ $user->id }}"
                                                    @if (session()->has('used_supermoderators')) @if (session()->get('used_supermoderators') == $user->id)

                                                     selected @endif
                                                    @endif


                                                    >{{ $user->username }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>


                                <div class="col-md-3 col-12 text-center">
                                    <button class="btn btn-primary" id="reset-filter">Reset</button>

                                </div>

                            </div>


                            @php $userrole=json_decode(auth('admin')->user()->getRoleNames(),true)?? []; @endphp
                            @if (hasPermissionForRoles('assign_moderators_to_moderator_view', $userrole) ||
                                    auth('admin')->user()->getRoleNames()[0] == 'Admin')
                                <a href="javascript:void(0)" class="btn btn-primary mt-3"
                                    id="assignmoderatorsupermoderator">(Assign/Dissociate)
                                    Moderators
                                    To Supermoderator</a>
                            @endif
                            <div class="row application-filter align-items-center" id="moderatorassigndiv" hidden>

                                <div class="col-md-3 col-12">
                                    <div class="form-group">
                                        <label for="aasignsupermoderatorid">Super Moderator Id</label>
                                        <select id="assignsupermoderatorid" name="assignsupermoderatorid"
                                            data-live-search="true" class=" select form-control">
                                            <option value="" selected disabled>Please Select
                                            </option>




                                            @foreach ($supermoderator as $supermoderatoruser)
                                                @if (isset($supermoderatoruser->username))
                                                    <option value="{{ $supermoderatoruser->id }}">





                                                        {{ $supermoderatoruser->username }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>




                                {{-- <div class="col-md-3 col-12">
                                    <div class="form-group">
                                        <label for="userid">Select moderator </label>
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
                                            id="assign-all-moderator">
                                        <label for="assignmoderator form-check-label">Select/Deselect To
                                            All
                                            moderators</label>

                                    </div>
                                </div>


                                <div class="col-md-3 col-12">
                                    <div class="form-group">
                                        <input class="form-check-input " type="checkbox" value="0"
                                            id="non-assign-moderator">
                                        <label for="assignmoderator form-check-label">Non Assign
                                            moderators</label>

                                    </div>
                                </div>





                                {{-- <div class="col-md-3 col-12 ">
                                    <div class="form-group ">
                                        <input class="form-check-input " type="checkbox" value=""
                                            id="nonassignmoderators">
                                        <label for="nonassignmoderators form-check-label">Non Assign moderators</label>


                                    </div>
                                </div> --}}




                                <div class="row" style="gap:20px">

                                    <div class="form-group mb-2">
                                        <button class="btn btn-primary btn-block" id="supermoderator-assign">Assign</button>
                                    </div>


                                    <div class="form-group mb-2">
                                        <button class="btn btn-danger btn-block"
                                            id="supermoderator-dissociate">Dissociate</button>
                                    </div>

                                </div>






                            </div>






                            <div class="table-responsive">
                                <table id="moderator-table" class="table table-hover w-100 zero-configuration">
                                    <thead>
                                        <tr>
                                        <tr>
                                            <th id="thead-moderator-checkbox" hidden>Checkbox</th>
                                            <th>Moderators Id</th>
                                            <th>Assign SuperModerator Id</th>
                                            <th>Moderator Name</th>
                                            <th>Assign SuperModerator (Name)</th>
                                            <th>Moderators Email</th>
                                            <th>Number Of students Assign to moderator</th>
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




            let moderators_fileter_id = "";

            let supermoderators_fileter_id = "";


            $("#assignmoderatorsupermoderator").on('click', function() {

                if ($("#moderatorassigndiv").is(":hidden")) {
                    $("#moderatorassigndiv").removeAttr("hidden");
                    $(".moderator-checkbox").removeAttr("hidden");

                    $("#non-assign-moderator").val('0');

                    $(".moderator-checkbox").closest("td").removeAttr("hidden", true);
                    $("#thead-moderator-checkbox").removeAttr("hidden");



                } else {
                    $("#moderatorassigndiv").attr("hidden", true);
                    $(".moderator-checkbox").attr("hidden", true);
                    $("#thead-moderator-checkbox").attr("hidden", true);
                    $(".moderator-checkbox").closest("td").attr("hidden", true);
                }

            })









            $(".select").selectpicker();


            $(".application-filter").find(".apply-filter-moderator").on("change", function() {
                moderators_fileter_id = $("#moderatorid").val();

                supermoderators_fileter_id = $("#supermoderatorid").val();

                dataTable.draw();
            });


            $("#non-assign-moderator").change(function() {
                if (this.checked) {
                    let checkedvalue = this.value;
                    console.log(checkedvalue);
                    supermoderators_fileter_id = [checkedvalue];
                    dataTable.draw();


                } else {
                    supermoderators_fileter_id = '';

                    dataTable.draw();

                }
            })



            // Datatable
            dataTable = $("#moderator-table").DataTable({
                "processing": true,
                "serverSide": true,
                "bInfo": true,
                "pageLength": 100,
                "fixedHeader": true,
                ajax: {
                    url: "{{ url('admin/moderators') }}",
                    data: function(d) {
                        d.moderators = moderators_fileter_id;
                        d.supermoderators = supermoderators_fileter_id;
                        // d.quiz_id = $('select[name="quiz_id"]').val();
                    }
                },
                columns: [{
                        data: 'moderator_checkbox',
                        name: 'moderator_checkbox',


                    }, {
                        data: 'moderator_username',
                        name: 'username'
                    },

                    {

                        data: 'supermoderator',
                        name: 'supermoderator'
                    },
                    {
                        data: 'moderator_name',
                        name: 'moderator_name'
                    },
                    {
                        data: 'supermoderator_name',
                        name: 'supermoderator_name'
                    },

                    {
                        data: 'email',
                        name: 'email'
                    },

                    {
                        data: 'studentcount',
                        name: 'studentcount'
                    }

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


                    $("#assign-all-moderator").on('change', function() {
                        if ($(this).is(':checked')) {


                            ckeckbox.prop("checked", true);


                        } else {
                            ckeckbox.prop("checked", false);




                        }
                    })


                    // Hide the cell
                    checkboxCell.attr('hidden', true);
                    if ($("#moderatorassigndiv").is(":hidden") == false) {
                        checkboxCell.attr('hidden', false);
                        ckeckbox.attr("hidden", false);
                    }






                    $(row).addClass('action-row');
                    let id = data['id'];
                    let editUrl = "{{ url('admin/moderators') }}" + "/" + id + "/" + "edit";

                    $(row).attr('data-url', editUrl);
                    $(row).attr('data-target', "#dynamic-modal");
                    $(row).attr('data-toggle', "modal");
                },
                initComplete: function(res, json) {

                }
            });


            $("body").on('click', "#add", function(e) {
                let url = $(this).data('url');
                $(".dynamic-title").html('Add moderators');
                getContent({
                    "url": url,
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

            $("body").on('click', ".action-row", function(e) {
                let url = $(this).data('url');

                $('.dynamic-title').html('Update moderators');
                getContent({
                    "url": url,
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

            runScript();
        })

        function runScript() {
            // $(".select").select2();
            $(".select").selectpicker();

            validateForm($('#moderator-create-form'), {
                rules: {
                    first_name: {
                        required: true,
                    },
                    last_name: {
                        required: true
                    },
                    email: {
                        required: true
                    },
                    password: {
                        required: true,
                        minlength: 8,
                    },
                    password_confirmation: {
                        required: true,
                        equalTo: '#password',
                    },
                    // 'rolename[]': {
                    //     required: true,
                    //     minlength: 1,

                    // }

                },
                // messages: {
                //     rolename: {
                //         minlength: 'Enter duration in months.'
                //     }

                // }
            });








            submitForm($("#moderator-create-form"), {
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


            validateForm($('#moderator-update-form'), {
                rules: {
                    first_name: {
                        required: true,
                    },
                    last_name: {
                        required: true
                    },
                    email: {
                        required: true
                    },
                    password: {

                        minlength: 8,
                    },
                    password_confirmation: {

                        equalTo: '#password',
                    },
                    // password: {
                    //     required: true,
                    //     number: true,
                    // },
                    // password_confirmation: {
                    //     required: true,
                    //     number: true,
                    // },
                    // 'rolename[]': {
                    //     required: true,
                    //     minlength: 1,

                    // }

                },
                messages: {
                    // duration: {
                    //     number: 'Enter duration in months.'
                    // }

                }
            });







            submitForm($("#moderator-update-form"), {
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

            submitForm($("#moderator-delete-form"), {
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
        /////////////////////checkBox to select to student //////////// 

        $(document).on('click', '.moderator-checkbox', function(e) {
            e.stopPropagation();
        })

        //////////////////////Assign Super Moderators to Moderators ///////////////////



        $("#supermoderator-assign").on('click', function(e) {
            e.preventDefault();

            that = $(this);
            var checkedValues = $(".moderator-checkbox:checked").map(function() {
                return $(this).val();
            }).get();
            var supermoderatorid = $('#assignsupermoderatorid').val();

            $.ajax({
                url: "{{ url('admin/supermoderator-assign-moderators') }}",
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    _method: "POST",
                    supermoderatorid: supermoderatorid,
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

                        $("#non-assign-moderator").prop("checked", false);
                        $('#assign-all-moderator').prop("checked", false);

                        let moderators_fileter_id = '';
                        let supermoderators_fileter_id = "";

                        dataTable.draw();

                        $("#thead-moderator-checkbox").attr('hidden', true);
                        $(".moderator-checkbox").closest("td").removeAttr("hidden", true);
                        $("#moderatorassigndiv").attr("hidden", true);



                        that.removeAttr('disabled').html(
                            "Assign"
                        );
                    }

                },
                error: function(data) {


                    if (data.responseJSON.errors.checkedValues) {
                        message = data.responseJSON.errors.checkedValues[0];

                        toast("error", message, "Error");
                    } else if (data.responseJSON.errors.supermoderatorid) {
                        message = data.responseJSON.errors.moderatorid[0];
                        toast("error", message, "Error");


                    } else {
                        toast("error", "Something went wrong.", "Error");
                    }





                    // moderatorid

                }


            })




        });




        ////////////////////////////////////////////////////////////////////
        ////////////////////////Dissociate/////////////////

        $("#supermoderator-dissociate").on('click', function(e) {


            e.preventDefault();
            that = $(this);
            var checkedValues = $(".moderator-checkbox:checked").map(function() {
                return $(this).val();
            }).get();
            var supermoderatorid = $('#assignsupermoderatorid').val();

            $.ajax({
                url: "{{ url('admin/supermoderator-dissociate-moderators') }}",
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    _method: "POST",
                    supermoderatorid: supermoderatorid,
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
                        $("#moderatorassigndiv").attr("hidden", true);



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





        //////////////////////////////////////////////////////////////////



        $('#reset-filter').on('click', function() {
            $(".select").selectpicker('deselectAll');
            $(".select").val("");
            $(".select").selectpicker('refresh');

        });
    </script>
@endsection
