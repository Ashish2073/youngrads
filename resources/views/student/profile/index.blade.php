@extends('layouts/contentLayoutMaster')

@section('title', 'Edit Profile')

@section('page-style')
    <!-- Page css files -->
    <link rel="stylesheet" href="{{ asset(('css/plugins/forms/wizard.css')) }}" />
    <link rel="stylesheet" href="{{ asset(('css/pages/data-list-view.css')) }}" />
@endsection
@section('vendor-style')
    <!-- vendor css files -->
    <link rel="stylesheet" href="{{ asset(('vendors/css/pickers/pickadate/pickadate.css')) }}">
@endsection
@section('content')
    @php
    $progress = config('progress_detail');
    @endphp

    <!-- Form wizard with icon tabs section start -->
    <section id="icon-tabs">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="icons-tab-steps wizard-circle">
                                <!-- Step 1 -->
                                <h6><i class="step-icon feather icon-home"></i> <span data-label='general_information' class='{{ $progress['general_information']['status'] ? "text-success" : "text-danger" }} '>General Information</span>
                                </h6>
                                <fieldset class='general_information_box' data-mode='async'
                                    data-url="{{ route('student.stepView', 'general_information') }}">

                                </fieldset>

                                <!-- Step 2 -->
                                <h6><i class="step-icon feather icon-briefcase"></i> <span data-label='education_history' class='{{ $progress['education_history']['status'] ? "text-success" : "text-danger" }}'>Education History</span>
                                </h6>
                                <fieldset data-mode='async' data-url="{{ route('student.stepView', 'education_history') }}">
                                    <div id="step-2">
                                    </div>
                                </fieldset>

                                <!-- Step 3 -->
                                <h6><i class="step-icon feather icon-image"></i> <span data-label='test_scores' class='{{ $progress['test_scores']['status'] ? "text-success" : "text-danger" }}'> Test Scores</span></h6>
                                <fieldset data-mode='async' data-url="{{ route('student.stepView', 'test_scores') }}">
                                    <div id="step-3">
                                    </div>
                                </fieldset>

                                <!--Step 4-->
                                <h6><i class="step-icon feather icon-briefcase"></i> <span data-label='work_experience' class='{{ $progress['work_experience']['status'] ? "text-success" : "text-danger" }}'> Work Experience</span>
                                </h6>
                                <fieldset data-mode='async' data-url="{{ route('student.stepView', 'work_experience') }}">
                                    <div id="work-experience">
                                    </div>
                                </fieldset>

                                <!--Step 5-->
                                <h6><i class="step-icon feather icon-briefcase"></i><span data-label='background_information' class='{{ $progress['background_information']['status'] ? "text-success" : "text-danger" }}'>Background
                                        Information</span></h6>
                                <fieldset data-mode='async'
                                    data-url="{{ route('student.stepView', 'background_information') }}">
                                    <div id="step-4">
                                    </div>
                                </fieldset>

                                <!--Step 6 -->
                                <h6 id="upload-document-icon"><i class="step-icon feather icon-image"></i> <span data-label='upload_documents'
                                        class='{{ $progress['upload_documents']['status'] ?? false ? "text-success" : "text-danger" }}'>Upload Documents
                                    </span></h6>
                                <fieldset data-mode='async' class="px-0 px-md-1" data-url="{{ route('student.stepView', 'upload_documents') }}">
                                    <div id="document">
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Form wizard with icon tabs section end -->
    <input type="hidden" name="step" value="{{ request()->get('step') ?? 0 }}">
@endsection

@section('vendor-script')
    <!-- vendor files -->
    <script src="{{ asset(('vendors/js/extensions/jquery.steps.min.js')) }}"></script>
    <script src="{{ asset(('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
    <script src="{{ asset(('vendors/js/pickers/pickadate/picker.js')) }}"></script>
    <script src="{{ asset(('vendors/js/pickers/pickadate/picker.date.js')) }}"></script>
    <script src="{{ asset(('vendors/js/pickers/pickadate/legacy.js')) }}"></script>
@endsection
@section('page-script')
    <!-- Page js files -->
    {{-- <script src="{{ asset(mix('js/scripts/forms/wizard-steps.js')) }}"></script>
    --}}
    <script>
        var index =  $("input[name='step']").val();
        let steps = $(".icons-tab-steps").steps({
            // autoFocus: true,
            headerTag: "h6",
            bodyTag: "fieldset",
            enablePagination: true,
            enableAllSteps: true,
            transitionEffect: "fade",
            titleTemplate: '<span class="step">#index#</span> #title#',
            loadingTemplate: '<div style="height: 400px; font-size: 1.9rem;" class="d-flex justify-content-center align-items-center text-center my-5 text-primary"> <i class="fa fa-spin fa-spinner mr-1"></i> #text#</div>',
            preloadContent: true,
            startIndex: index,
            labels: {
                finish: 'Submit',
                loading: 'Please wait...'
            },
            onFinished: function(event, currentIndex) {
                // alert("Form submitted.");
            },
            onStepChanging: function (event, currentIndex, newIndex) { 
                return true; 
            },
            onContentLoaded: function(event, currentIndex) {
                if(window.lastScrollPosition) {
                    window.scrollTo(0, lastScrollPosition); 
                }
                if (typeof generalInformationScript == "function") {
                    generalInformationScript();
                }
                if (typeof educationHistoryScript == "function") {
                    educationHistoryScript();
                }
                if (typeof testScoresScript == "function") {
                    testScoresScript();
                }
                if (typeof workExpScript == "function") {
                    workExpScript();
                }
                if (typeof backgroundInfoScript == "function") {
                    backgroundInfoScript();
                }
                if (typeof uploadDocumentScript == "function") {
                    uploadDocumentScript();
                }
                
                updateProgressLabel();
            }
        });

    </script>
@endsection
@section('foot_script')
    <script>
        $("#icon-tabs").find(".actions").hide();
        $(document).ready(function() {
            $("#steps-uid-0-p-" + index).css('display', 'block');

            // Common
            $("body").on("click", ".previous", function() {
                previousStep();
            });

            // Event Listeners
            // 1. General Information
            $("body").on("change", "#address_country", function() {
                id = $(this).val();
                updateState(id);
            });

            $('body').on("change", "#state", function() {
                id = $(this).val();
                window.id = $(this).val();
                updateCity(id);
            });

            $("body").on("change", "#city", function() {
                if($(this).find("option:selected").val() == 'new-city') {
                    $(".city-box").removeClass('d-none');
                    $("input[name='city_name']").attr('required', 'required');
                } else {
                    $(".city-box").addClass("d-none");
                    $("input[name='city_name']").removeAttr('required');
                }
            });

            $("body").on("click", "#next-general-btn", function() {
                $("#move_edu").val(1);
                $("#general-information-btn").click();
            });


            // 2. Education History
            $("body").on("click", "#add-education", function() {
                $.ajax({
                    url: "{{route('education-add')}}",
                    beforeSend: function() {
                        $('.educaton-form').html(
                            "<i class='fa fa-spinner fa-spin'></i> Loading");
                        // $('.educaton-form').addClass('text-center');
                    },
                    success: function(data) {
                        // $('.educaton-form').removeClass('text-center');
                        $('.educaton-form').html(data);
                        // educationHistory();
                        manageEducationScript();
                    }
                });
            })

            $("body").on('click', '.education-delete', function() {
                let that = $(this);
                if (confirm('Are you sure you want to delete ?')) {
                    id = $(this).data('id')
                    $.ajax({
                        url: "{{route('education-delete')}}",
                        type: 'Post',
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: id,
                        },
                        beforeSend: function() {
                            that.attr('disabled', true).prepend(
                                "<i class='fa fa-spinner fa-spin'></i> ");
                        },
                        success: function(data) {
                            setAlert(data);
                            educationTable.draw('page');
                            // checkTable(window.dataTable, $('#steps-uid-0-t-1'));
                        },
                        complete: function() {
                            updateProgressLabel();
                        }

                    });
                }
            });

            $("body").on('click', '.education-edit', function() {
                let id = $(this).data('id');
                $.ajax({
                    url: "{{route('education-edit')}}",
                    type: 'post',
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id,
                    },
                    beforeSend: function() {
                        $('.educaton-form').html(
                            "<i class='fa fa-spinner fa-spin'></i> Loading");
                        // $('.educaton-form').addClass('text-center');
                    },
                    success: function(data) {
                        // $('.educaton-form').removeClass('text-center');
                        $('.educaton-form').html(data);
                        manageEducationScript();
                    }

                })

            });

            $("body").on("change", "#start-date", function() {
                // Set min date for datepicker
            });

            $('body').on('change', '#study-level', function() {
                let val = $(this).find('option:selected').html();
                val = val.toLowerCase();
                val = val.trim();
                
                if (val == "other") {
                    $('#other-sub').removeClass('d-none');  
                } else {
                    $('#other-sub').addClass('d-none');
                    $('#other-sub').val("");
                }
            });

            $("body").on("change", "#marks-unit", function() {
                marksValidation();
            });

            $("body").on("click", "#hightest-education-btn", function() {
                $("#hightest-education-form").submit();
            });

            $("body").on("click", "#next-education-btn", function() {
                $("#move_history").val(1);
                $("#hightest-education-btn").click();
            });

            $("body").on("change", "#education-country, #highest-education", function() {
                $("input[name='dont_prompt']").val(1);
                $("#hightest-education-btn").click();
            });

            

            // 3. Test Scores
            $("body").on("change", "#add-test", function() {
                id = $(this).val();
                $.ajax({
                    url: "{{route('test-score-add')}}",
                    type: 'POST',
                    data: {
                        id: id,
                        _token: "{{ csrf_token() }}"
                    },
                    beforeSend: function() {
                        $('.test-form').html("<i class='fa fa-spin fa-spinner'></i> Loading");
                    },
                    success: function(data) {
                        $('.test-form').html(data);
                        manageTestScoresScript();
                    }
                });
            });

            $("body").on('click', '.test-delete', function() {
                if (confirm("Are you sure?")) {
                    id = $(this).data('id');
                    that = $(this);
                    test_id = $(this).data('test');
                    $.ajax({
                        url: "{{route('test-score-delete')}}",
                        type: 'post',
                        beforeSend: function() {
                            that.attr('disabled', true).prepend(
                                "<i class='fa fa-spinner fa-spin'></i> ");
                        },
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: id,
                            test_id: test_id
                        },
                        success: function(data) {
                            if (data.success) {
                                setAlert(data);
                            } else {
                                toast("error", "Something went wrong.", "Error");
                            }
                            testScoreTable.draw('page');
                            // checkTable(testScoreTable, $('#steps-uid-0-t-2'));
                        },
                        complete: function() {
                            updateProgressLabel();

                        }
                    });
                }
            });

            $("body").on('click', '.test-edit', function() {

                id = $(this).data('id');
                that = $(this);
                test_id = $(this).data('test');
                $.ajax({
                    url: "{{route('test-score-edit')}}",
                    type: 'post',
                    beforeSend: function() {
                        that.attr('disabled', true).prepend(
                            "<i class='fa fa-spinner fa-spin'></i> ");
                        $('.test-form').html("<i class='fa fa-spinner fa-spin'></i> ");
                    },
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id,
                        test_id: test_id
                    },
                    success: function(data) {
                        testScoreTable.draw('page');
                        $('.test-form').html(data);
                        manageTestScoresScript();

                    }
                });


            });

            $("body").on("click", ".test-score-next", function() {
                // $(".icons-tab-steps").steps('next');
                nextStep();
            });

            // 4. Work Experience Script
            $("body").on('change', '.current_working', function() {
                if (this.checked) {
                    $(".work-upto").prop('disabled', true);
                    $(".work-upto").val("");
                    $(".work-upto").attr('required', "required");
                } else {
                    $(".work-upto").prop('disabled', false);
                    $(".work-upto").removeAttr('required');

                }
            });

            $('body').on('click', '#add-experience', function() {
                $.ajax({
                    url: "{{route('work-experence')}}",
                    beforeSend: function() {
                        $('.form-work-experience').html(
                            "Loading.. <i class='fa fa-spinner fa-spin'></i>");
                        $('.form-work-experience').addClass('text-center');
                    },
                    success: function(data) {
                        $('.form-work-experience').removeClass('text-center');
                        $('.form-work-experience').html(data);
                        workExperienceScript();
                    }
                });

            });

            $("body").on('click', '.experience-delete', function() {
                that = $(this);
                if (confirm('Are you sure you want to delete ?')) {
                    id = $(this).data('id')
                    $.ajax({
                        url: "{{route('experence-delete')}}",
                        type: 'Post',
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: id,
                        },
                        beforeSend: function() {
                            that.attr('disabled', true).prepend(
                                "<i class='fa fa-spinner fa-spin'></i> ");
                        },
                        success: function(data) {
                            setAlert(data);
                            experienceTable.draw('page');
                            checkTable(experienceTable, $('#steps-uid-0-t-3'));
                        }
                    });
                }
            });

            $("body").on('click', '.experience-edit', function() {
                id = $(this).data('id');

                $.ajax({
                    url: "{{route('experence-edit')}}",
                    type: 'post',
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id,
                    },
                    beforeSend: function() {
                        $('.form-work-experience').html(
                            "Loading.. <i class='fa fa-spinner fa-spin'></i>");
                        $('.form-work-experience').addClass('text-center');
                    },
                    success: function(data) {
                        $('.form-work-experience').removeClass('text-center');
                        $('.form-work-experience').html(data);
                        workExperienceScript();

                    }
                });

            });

            $("body").on("click", ".work-next-btn", function() {
                // $(".icons-tab-steps").steps('next');
                nextStep();
            });

            // 5. Background Information
            $('body').on('change', '.type, .refusal', function() {
                toggleVisaInformation();
            });
            $("body").on("click", "#next-background-btn", function() {
                // $("#background-btn").attr('data-move', 1);
                $("#move_background").val(1);
                $("#background-btn").click();
            });
            

            function toggleVisaInformation() {
                let applied_visa = $(".refusal:checked").val();
                let visa_refusal = $(".type:checked").val();
                console.log(applied_visa);
                console.log(visa_refusal);
                if (applied_visa == "1" || visa_refusal == "1") {
                    $('#country-container-refusal').removeClass('d-none');
                } else {
                    $('#country-container-refusal').addClass('d-none');
                }
            }
            

            // 6. Upload Documents
            $("body").on("change", "#document-list", function() {
                // alert('test');
            });

            $("body").on("change", "input[name='document_file']", function() {
                $(this).closest('.document-upload-form').submit();
                // $(".document-upload-form").submit();
            });

            // $("body").on("click", ".other-document-upload", function() {
            //     $(this).closest('.add-document-form').submit();
            // });

            $("body").on("click", ".delete-document", function() {
                let url = $(this).data('url');
                $.ajax({
                    url: url,
                    beforeSend: function() {
                        if(!confirm("Are you sure want to delete document?")) {
                            return false;
                        }
                    },
                    success: function() {
                        window.lastScrollPosition = window.scrollY;
                        // $(".icons-tab-steps").steps('previous');
                        // $(".icons-tab-steps").steps('next');
                        previousStep();
                        nextStep();
                    },
                    error: function() {

                    },
                    complete: function() {

                    }
                })
            });

            $("body").on("click", "#add-document-btn", function() {
                let btn = $(this);
                submitLoader(btn);
                let url = $(this).data('url');
                getContent({
                    "url": url,
                    success: function (data) {
                        $("#document-action-box").html(data);
                        manageOtherDocumentScript();
                    },
                    complete: function() {
                        submitReset(btn);
                    },
                    error: function() {
                        alert('Oops! Something went wrong');
                    }
                });
            });
        })
        function updateProgressLabel(data) {
            $.get("{{route('student.profile.progress')}}", function(data) {
                for(key in data) {
                    let label = $("[data-label='" + key + "']");
                    if(data[key]['status'] === true) {
                        label.addClass('text-success');
                        label.removeClass('text-danger');
                    } else {
                        label.removeClass('text-success');
                        label.addClass('text-danger');
                    }
                    if(key == 'education_history') {
                        if($(".icons-tab-steps").steps('getCurrentIndex') == 1) {
                            requiredLevels = data['education_history']['require_levels'];
                            name = "";
                            if(requiredLevels.length > 0) {
                                levelsName = [];
                                requiredLevels.forEach(function(value, index) {
                                    levelsName.push("<strong>" + value.name + "</strong>");
                                });

                            }
                            if(requiredLevels.length > 0) {
                                $("#require-levels").html("Please add " + levelsName.join(", ") + " Education details");
                            } else {
                                $("#require-levels").html("");
                            }
                        }
                    }
                }
            })
        }

        function nextStep() {
            let step = $(".icons-tab-steps").steps('getCurrentIndex');
            step = parseInt(step) + 1;
            $("#steps-uid-0-t-" + step).click();
        }

        function previousStep() {
            let step = $(".icons-tab-steps").steps('getCurrentIndex');
            step = parseInt(step) - 1;
            $("#steps-uid-0-t-" + step).click();
        }
    </script>
@endsection
