@extends('layouts/contentLayoutMaster')

@section('title', 'Students')

@section('vendor-style')
    {{-- vendor css files --}}
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/datatables.min.css')) }}">
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

                            <p class="text-muted">Note: Click/Tap row for viewing profile</p>
                               <div class="row application-filter align-items-center">
                                           <div class="col-md-2 col-12">
                                                  <div class="form-group">
                                                     <label for="userid">Student Id</label>
                                                         <select data-colum="0" id="userid" name="id[]" data-live-search="true" multiple
                                                              class=" select form-control">
                                                            @foreach($userId as $user)
                                                            @if(isset($user->id))
                                                                     <option
                                      
                                         
                                                             value="{{$user->id}}">{{"young_stu_".$user->id}}</option>
                                                               @endif
                                                                    @endforeach
                                                          </select>
                                                   </div>
                                           </div>
                                <div class="col-md-2 col-12">
                                     <div class="form-group">
                                    <label for="useremail">Email</label>
                                    <select id="useremail" name="email[]" data-live-search="true" multiple
                                        class=" select form-control">
                                       

                                      @foreach($userEmail as $user)

                                      @if(isset($user->email))
                                            <option
                                           
                                              value="{{$user->email}}">{{$user->email}}</option>
                                              @endif
                                     @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label for="userphone">Phone Number</label>
                                    <select id="userphone" name="phone[]" data-live-search="true" multiple
                                        class=" select form-control">
                                     
                                           @foreach ( $userPhone as $user )
                                               
                                            @if(isset($user->personal_number))
                                            <option
                                            
                                          
                                            value="{{$user->personal_number}}">{{$user->personal_number}}</option>
                                            @endif
                                            @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4 col-12 text-right">
                                <button class="btn btn-primary" id="reset-filter">Reset</button>
                              
                            </div>



                        </div> 




                            <a href="{{route('admin.students-data-export')}}" class="btn btn-primary mt-3">Export Students Application Data In Excel Form</a>

                            <div class="table-responsive">
                                <table id="user-table" class="table table-hover w-100 zero-configuration">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone No.</th>
                                            <th>Passport No.</th>
                                            <th>DOB</th>
                                            <th>Shortlisted Programs</th>
                                            <th>Action</th>
                                            {{-- <th>Action</th> --}}
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
  

            $(".select").selectpicker();
            $(".application-filter").find("select").on("change", function() {
                console.log('gf');
                console.log($("#userid").val());

                 dataTable.draw();
            });




            // Datatable
            dataTable = $("#user-table").DataTable({
                "processing": true,
                "serverSide": true,
                "bInfo": true,
                "pageLength": 50,
                "fixedHeader": true,
             
                ajax: {
                    url: "{{ route('admin.students') }}",
                   
                    data: function (d) {
                       
                        d.id=$("#userid").val();
                        d.email=$('#useremail').val();
                        d.personal_number=$('#userphone').val();
                    }
                },
                "order": [
                    [7, "desc"]
                ],
                columns: [{
                        data: 'student_id',
                        name: 'student_id',
                      
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
                        data: 'delete',
                        name: 'delete'
                    },

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
                    $(row).addClass('action-row');
                    let id = data['id'];
                    let editUrl = "{{ url('admin/user') }}" + "/" + id + "/edit";
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

            //delete
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
