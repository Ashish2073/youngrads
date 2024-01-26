@extends('layouts/contentLayoutMaster')

@section('title', 'Setting')
@section('content')
    {{-- Dashboard Analytics Start --}}
    <section id="users-edit">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Profile Setting (<span class="">
                        {{ auth()->user()->name . ' ' . auth()->user()->last_name ?? '' }} -
                        {{ auth()->user()->email }}</span>)</h4>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 col-12 d-none">
                            <div class="media mb-2">
                                <a class="mr-2 my-25" href="javascript:void(0);">
                                    <img src="{{ auth()->user()->getprofileImg() }}" {{--
                                        src="{{ asset('uploads/profile_pic/student/' . $user->profile_img) }}"
                                        --}} alt="users avatar"
                                        class="users-avatar-shadow rounded pro-image" height="64" width="64">
                                </a>
                                <div class="media-body mt-50">
                                    <h4 class="media-heading">{{ $user->name }}</h4>
                                    <div class="col-12 d-flex mt-1 px-0">
                                        <form class="form-inline" action="{{ route('update-profilepic') }}"
                                            enctype="multipart/form-data" method="post" id="profile-pic">
                                            @csrf
                                            <input type="file" name="profile" class="d-none" id="profile">
                                            <button type="submit" id="profile-submit-btn"
                                                class="btn btn-primary btn-sm text-center">Change
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-12">
                                    <span class="text-muted">Note: Click/Tap image to browse new image.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-12">
                          <h4>Update Profile</h4><hr>
                            <form id="update-profile-form" action="{{ route('update-profile', $user->id) }}" method="post">
                                @csrf
                                @method('PUT')




                                @include('dashboard.common.fields.text', [
                                'label_name' => 'First Name',
                                'id' => 'first_name',
                                'name' => 'first_name',
                                'placeholder' => 'Enter First Name',
                                'input_attribute' => [
                                'type' => 'text',
                                
                                'value' => old('first_name', $user->name),
                                ],
                                'classes' => '',
                                ])

                                @include('dashboard.common.fields.text', [
                                'label_name' => 'Last Name',
                                'id' => 'last_name',
                                'name' => 'last_name',
                                'placeholder' => 'Enter Last Name',
                                'input_attribute' => [
                                'type' => 'text',
                                'value' => old('last_name', $user->last_name),
                                ],
                                'classes' => '',
                                ])


                                @include('dashboard.common.fields.text', [
                                'label_name' => 'Password',
                                'id' => 'password',
                                'name' => 'password',
                                'placeholder' => 'Enter Password',
                                'input_attribute' => [
                                'type' => 'password',
                                'value' => '',
                                ],
                                'classes' => '',
                                'help_text' => 'Leave blank to use existing password'
                                ])

                                @include('dashboard.common.fields.text', [
                                'label_name' => 'Confirm Password',
                                'id' => 'confirm-password',
                                'name' => 'password_confirmation',
                                'placeholder' => 'Enter Confirm Password',
                                'input_attribute' => [
                                'type' => 'password',
                                'value' => '',
                                ],
                                'classes' => '',
                                ])


                                <div class="form-group">
                                    <button type="submit" id="submit-btn" class="btn btn-primary">Update Profile
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-4 col-12 ">
                          <h4>Change Email Address</h4><hr>
                          <div class="change-email">
                              @include('student.change_email')

                          </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('foot_script')

    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js">
    </script>
    <script type="text/javascript" src="{{ asset('dashboard/js/jquery.form.js') }}"></script>
    <script type="text/javascript" src="{{ asset('dashboard/js/plugins/jquery-validation/jquery.validate.js') }}"></script>
    --}}
    <script>
        $(document).ready(function() {
            changeEmailScript();

            $('.pro-image').click(() => {
                $('#profile').click();
            })

            $('#profile').change(function(e) {
                //console.log($(this).val());
                let preview = new FileReader();
                preview.onload = (e) => $('.pro-image').attr('src', e.target.result);
                preview.readAsDataURL(e.target.files[0]);
            });

            submitForm($('#profile-pic'), {
                beforeSubmit: function() {
                    submitLoader("#profile-submit-btn");
                },
                success: function(data) {

                    // setAlert(data);
                    if (data.success) {
                        $('.pro-image').attr('src', data.image);
                        toastr.success(data.message, data.title);
                        submitReset('#profile-submit-btn', 'Change Profile');
                    } else {
                        toastr.error(data.error[0], data.title);
                        submitReset('#profile-submit-btn', 'Change Profile');
                    }
                },
                error: function(data) {
                    toast("error", "Something went wrong.", "Error");
                    submitReset('#profile-submit-btn', 'Change Profile');
                }
            });

        });


        validateForm($('#update-profile-form'), {
            rules: {
                first_name: {
                    required: true
                },
               
                "password_confirmation": {
                    equalTo: "#password"
                },
            },
            message: {}
        });


        submitForm($('#update-profile-form'), {
            beforeSubmit: function() {
                submitLoader("#submit-btn");
            },
            success: function(data) {
                if (data.success) {
                    setAlert(data);
                    submitReset('#submit-btn', 'Update');
                } else {
                    submitReset('#submit-btn', 'Update');
                }
            },
            error: function(data) {
                toast("error", "Something went wrong.", "Error");
                submitReset('#submit-btn', 'Update');
            }

        });

        function changeEmailScript() {


            validateForm($('#change-email'), {
                rules: {
                    email: {
                        required: true,
                        email: true
                    },
                    confirm_email: {
                        equalTo: "#email"
                    },
                },
                message: {}
            });

            submitForm($('#change-email'), {

                beforeSubmit: function() {
                    submitLoader("#change-email-btn");
                },
                success: function(data) {

                    // setAlert(data);
                    if (data.success) {
                        // $('.pro-image').attr('src', data.image);
                        toastr.success(data.message, data.title);
                        submitReset("#change-email-btn", 'Change Email');
                        location.reload();
                    } else {
                        $('.change-email').html(data);
                        submitReset("#change-email-btn", 'Change Email');
                    }

                    changeEmailScript();
                },
                error: function(data) {
                    toast("error", "Something went wrong.", "Error");
                    submitReset("#change-email-btn", 'Change Email');
                    changeEmailScript();
                }

            });
        }

    </script>
@endsection
