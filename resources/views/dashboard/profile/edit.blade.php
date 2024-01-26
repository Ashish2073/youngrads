@extends('layouts/contentLayoutMaster')

@section('title', 'Edit Profile')

@section('vendor-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/pickadate/pickadate.css')) }}">
@endsection

@section('page-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('css/plugins/forms/validation/form-validation.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/pages/app-user.css')) }}">

@endsection

@section('content')
    <!-- users edit start -->
    <section class="users-edit">
        <div class="card">
            <div class="card-content">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="media mb-2 d-none">
                                <a class="mr-2 my-25" href="javascript:void(0);">
                                    <img src="{{ auth('admin')->user()->profileImage() }}" alt="users avatar"
                                        class="users-avatar-shadow rounded pro-image" height="64" width="64">
                                </a>
                                <div class="media-body mt-50">
                                    <h4 class="media-heading">
                                        {{ auth('admin')->user()->first_name . ' ' . auth('admin')->user()->last_name }}
                                    </h4>
                                    <div class="col-12 d-flex mt-1 px-0">
                                        <form class="form-inline" action="{{ route('admin.profilePic') }}"
                                            enctype="multipart/form-data" method="post" id="profile-pic">
                                            @csrf
                                            <button type="submit" id="profile-submit-btn"
                                                class="btn btn-primary btn-sm text-center">Change
                                            </button>
                                            <input type="file" name="profile" class="d-none">
                                        </form>
                                    </div>


                                </div>
                            </div>
                            <div class="row mb-2 d-none">
                                <div class="col-12">
                                    <span class="text-muted">Note: Click/Tap image to browse new image.</span>
                                </div>
                            </div>
                            <form id="update-profile-form" action="{{ route('admin.profile.update', $user->id) }}"
                                method="post">
                                @csrf
                                @method('PUT')




                                @include('dashboard.common.fields.text', [
                                'label_name' => 'First Name',
                                'id' => 'first_name',
                                'name' => 'first_name',
                                'placeholder' => 'Enter First Name',
                                'input_attribute' => [
                                'type' => 'text',
                                'value' => old('first_name', $user->first_name),
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
                                'label_name' => 'Email Address',
                                'id' => 'email',
                                'name' => 'email',
                                'placeholder' => 'Enter Email Address',
                                'input_attribute' => [
                                'type' => 'email',
                                'value' => old('email', $user->email),
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
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>
    <!-- users edit ends -->
@endsection



@section('page-script')
    <script>
        $(document).ready(function() {
            validateForm($("#update-profile-form"), {
                rules: {
                    first_name: {
                        required: true,
                    },
                    last_name: {
                        required: true,
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        minlength: 6
                    },
                    "password_confirmation": {
                        equalTo: "#password"
                    },
                },
                messages: {}
            });

            submitForm($("#update-profile-form"), {
                beforeSubmit: function() {
                    submitLoader("#submit-btn");
                },
                success: function(data) {
                    setAlert(data);
                },
                error: function(data) {
                    toast("error", "Something went wrong.", "Error");
                    submitReset('#submit-btn');
                },
                complete: function() {
                    submitReset("#submit-btn");
                }
            });
            
            submitForm($('#profile-pic'), {
                beforeSubmit: function() {
                    submitLoader("#profile-submit-btn");
                },
                success: function(data) {

                    if (data.success) {
                        $('.pro-image').attr('src', data.image);
                        //toast('success', data.message);
                        toastr.success(data.message, data.title);
                        submitReset('#profile-submit-btn', 'Change Profile');
                    } else {

                        //toast('error', data.error[0]);
                        toastr.error(data.error[0], data.title)
                        submitReset('#profile-submit-btn', 'Change Profile');
                    }
                },
                error: function(data) {
                    toast("error", "Something went wrong.", "Error");
                    submitReset('#profile-submit-btn', 'Change Profile');
                }
            });

            $(document).on('change', 'input[name="profile"]', (e) => {

                let preview = new FileReader();

                preview.onload = (e) => $('.pro-image').attr('src', e.target.result);

                preview.readAsDataURL(e.target.files[0]);

            });

            validateForm($('#profile-pic'), {
                rules: {
                    profile: {
                        required: true,
                    }
                },
                messages: {
                    profile: {
                        required: 'Please add a picture'
                    }
                }
            });
            $(".pro-image").on("click", function() {
                $("input[name='profile']").click();
            });
        });

    </script>
@endsection
