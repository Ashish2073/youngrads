@extends('layouts/contentLayoutMaster')

@section('title', 'Edit Profile')

@section('page-style')
    <!-- Page css files -->
    <link rel="stylesheet" href="{{ asset('css/plugins/forms/wizard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pages/data-list-view.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    <style>
        .bs-select-all,
        .bs-select-all:hover,
        .bs-deselect-all,
        .bs-deselect-all:hover {
            background: white !important;
            color: black !important;
            border: 1px solid #babfc7 !important;
        }

        .dropdown-header {
            font-weight: bold !important;
            color: #626262;
            cursor: pointer;
        }

        .dropdown .dropdown-menu::before {
            content: unset !important;
        }

        .dropdown-toggle:focus {
            outline-color: white !important;
        }
    </style>
@endsection
@section('vendor-style')
    <!-- vendor css files -->
    <link rel="stylesheet" href="{{ asset('vendors/css/pickers/pickadate/pickadate.css') }}">
@endsection
@section('content')
    <!-- Form wizard with icon tabs section start -->
    <section id="icon-tabs">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Edit Profile</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div class="icons-tab-steps wizard-circle">

                                <!-- Step 1 -->
                                <h6><i class="step-icon feather icon-home "></i> General Information</h6>
                                <fieldset>
                                    <div id="step-1">
                                        @include('student.general_information', compact('user'))
                                    </div>
                                </fieldset>

                                <!-- Step 2 -->
                                <h6><i class="step-icon feather icon-briefcase"></i> Education History</h6>
                                <fieldset>
                                    <div id="step-2">
                                        @include(
                                            'student.education_history',
                                            compact('highestEducation', 'educationCountryId'))
                                    </div>
                                </fieldset>

                                <!-- Step 3 -->
                                <h6><i class="step-icon feather icon-image"></i> Test Scores</h6>
                                <fieldset>
                                    <div id="step-3">
                                        @include('student.test_score', compact('userTests'))
                                    </div>
                                </fieldset>
                                <!--Step 4-->
                                <h6><i class="step-icon feather icon-briefcase"></i>Work Experience</h6>
                                <fieldset>
                                    <div id="work-experience">
                                        @include('student.work_experience.index')
                                    </div>
                                </fieldset>
                                <!--Step 5-->
                                <h6><i class="step-icon feather icon-briefcase"></i>Background Information</h6>
                                <fieldset>
                                    <div id="step-4">
                                        @include('student.background_infomation')
                                    </div>
                                </fieldset>
                                <!--Step 6 -->
                                <h6 id="upload-document-icon"><i class="step-icon feather icon-image"></i> Upload Documents
                                </h6>
                                <fieldset>
                                    <div id="document">
                                        @include(
                                            'student.upload_documents',
                                            compact('studyLevels', 'userTests', 'userTestIds'))
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

@endsection

@section('vendor-script')
    <!-- vendor files -->
    <script src="{{ asset('vendors/js/extensions/jquery.steps.min.js') }}"></script>
    <script src="{{ asset('vendors/js/forms/validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('vendors/js/pickers/pickadate/picker.js') }}"></script>
    <script src="{{ asset('vendors/js/pickers/pickadate/picker.date.js') }}"></script>
    <script src="{{ asset('vendors/js/pickers/pickadate/legacy.js') }}"></script>
@endsection
@section('page-script')
    <!-- Page js files -->
    <script src="{{ asset('js/scripts/forms/wizard-steps.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
@endsection
@section('foot_script')
    <script>
        // Functions
        function previousStep() {
            $('.previous').on('click', function() {
                $(".icons-tab-steps").steps('previous');
            });
        }
    </script>
    <script>
        $(document).ready(function() {
            // Global Listeners
            // General Information Listeners
            $("body").on("change", "#address_country", function() {
                id = $(this).val();
                updateState(id);
            });

            $('body').on("change", "#state", function() {
                id = $(this).val();
                window.id = $(this).val();
                updateCity(id);
            });

            updateAddressDropdowns();


        });

        function enableBoostrapSelectOptgroup() {
            let that = $(this).data('selectpicker'),
                inner = that.$menu.children('.inner');

            // remove default event
            inner.off('click', '.divider, .dropdown-header');
            // add new event
            inner.on('click', '.divider, .dropdown-header', function(e) {
                // original functionality
                e.preventDefault();
                e.stopPropagation();
                if (that.options.liveSearch) {
                    that.$searchbox.trigger('focus');
                } else {
                    that.$button.trigger('focus');
                }

                // extended functionality
                let position0 = that.isVirtual() ? that.selectpicker.view.position0 : 0,
                    clickedData = that.selectpicker.current.data[$(this).index() + position0];

                // copied parts from changeAll function
                let selected = null;
                for (let i = 0, data = that.selectpicker.current.data, len = data.length; i < len; i++) {
                    let element = data[i];
                    if (element.type === 'option' && element.optID === clickedData.optID) {
                        if (selected === null) {
                            selected = !element.selected;
                        }
                        element.option.selected = selected;
                    }
                }
                that.setOptionStatus();
                that.$element.triggerNative('change');
            });
        }


        $(document).ready(function() {
            // $('select').addClass('select');
            // $('.select').attr('data-style','border-light bg-white');
            $('.select').selectpicker();
            $('.select').selectpicker().on('loaded.bs.select', enableBoostrapSelectOptgroup);
            generalInformation();

            backgroundInformation();

            previousStep();
            checkGenInfo();
            window.dataTable = $("#education-table").DataTable({
                "processing": true,
                "serverSide": true,
                ajax: {
                    url: "{{ route('education-listing') }}",
                    data: function(d) {

                    }
                },
                //dom: "tps",

                columns: [{
                        name: 'study_levels.name',
                        data: 'study_level'
                    },
                    {
                        name: 'user_academics.board_name',
                        data: 'board_name'
                    },
                    {
                        name: 'user_academics.marks',
                        data: 'marks'
                    },
                    {
                        name: 'user_academics.country',
                        data: 'country'
                    },
                    {
                        name: 'user_academics.language',
                        data: 'language',
                    },
                    {
                        name: 'user_academics.start_date',
                        data: 'start_date'
                    },
                    {
                        name: 'user_academics.end_date',
                        data: 'start_date'
                    },
                    {
                        data: 'action'
                    }

                ],
                "language": {
                    "emptyTable": "No Education History Added"
                },
                responsive: false,

                drawCallback: function(setting, data) {


                },
                bInfo: true,
                pageLength: 100,
                initComplete: function(settings, json) {
                    rowCount = this.api().data().length;
                    // $('#steps-uid-0-t-1').css('color','blue');
                    if (rowCount > 0) {
                        $('#steps-uid-0-t-1').css('color', '#5cb85c');
                    } else {
                        $('#steps-uid-0-t-1').css('color', '#d9534f');
                    }
                }
            });



            educationHistory();

            experienceTable = $("#experience-table").DataTable({
                "processing": true,
                "serverSide": true,
                ajax: {
                    url: "{{ route('experence-listing') }}",
                    data: function(d) {

                    }
                },
                //dom: "tps",

                columns: [{
                        name: 'organization',
                        data: 'organization'
                    },

                    {
                        name: 'position',
                        data: 'position'
                    },
                    {
                        name: 'profile',
                        data: 'profile'
                    },
                    {
                        name: 'is_working',
                        data: 'is_working'
                    },
                    {
                        name: 'work_from',
                        data: 'work_from'
                    },
                    {
                        name: 'work_upto',
                        data: 'work_upto'
                    },
                    {
                        data: 'action'
                    }

                ],
                "language": {
                    "emptyTable": "No Work Experience Added"
                },
                responsive: false,

                drawCallback: function(setting, data) {

                },
                bInfo: true,
                pageLength: 100,
                initComplete: function(settings, json) {
                    rowCount = this.api().data().length;
                    if (rowCount > 0) {
                        $('#steps-uid-0-t-3').css('color', '#5cb85c');
                    } else {
                        $('#steps-uid-0-t-3').css('color', '#d9534f');
                    }
                }
            });

            //incomplete profile indication
            //document
            if ($('#add-document').find('option').length == $('#add-document').find('option:disabled').length) {
                $('#steps-uid-0-t-5').css('color', '#5cb85c');
            } else {
                $('#steps-uid-0-t-5').css('color', '#d9534f');
            }
            //background information
            let appliedVisa = "{{ $appliedVisa }}";
            let refuseVisa = "{{ $refuseVisa }}";

            if (appliedVisa == "" && refuseVisa == "") $('#steps-uid-0-t-4').css("color", "#d9534f");
            else $('#steps-uid-0-t-4').css("color", "#5cb85c");

            $('#add-experience').on('click', function() {
                $.ajax({
                    url: route('work-experence'),
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

            $(document).on('click', '.experience-delete', function() {
                that = $(this);
                if (confirm('Are you sure you want to delete ?')) {
                    id = $(this).data('id')
                    $.ajax({
                        url: "{{ route('experence-delete') }}",
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

            $(document).on('click', '.experience-edit', function() {
                id = $(this).data('id');

                $.ajax({
                    url: "{{ route('experence-edit') }}",
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

            //testscore


            $('#add-test').on('change', function() {
                id = $(this).val();
                $.ajax({
                    url: "{{ route('test-score-add') }}",
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
                        testScoreScript();
                    }
                });

            });

            testScoreTable = $("#testscore-table").DataTable({
                "processing": true,
                "serverSide": true,
                ajax: {
                    url: "{{ route('test-score-list') }}",
                    data: function(d) {

                    }
                },
                dom: '<"top"<"actions action-btns"><"action-filters">><"clear">rt<"bottom"<"actions">p>',

                columns: [{
                        name: 'test',
                        data: 'test'
                    },
                    {
                        name: 'action',
                        data: 'action',
                    }

                ],
                "language": {
                    "emptyTable": "No TestScore Added"
                },
                responsive: false,

                drawCallback: function(setting, data) {


                },
                bInfo: true,
                pageLength: 100,
                initComplete: function(settings, json) {
                    let rowCount = this.api().data().length;
                    if (rowCount > 0) {
                        $('#steps-uid-0-t-2').css('color', '#5cb85c');
                    } else {
                        $('#steps-uid-0-t-2').css('color', '#d9534f');
                    }
                }
            });

            $(document).on('click', '.test-delete', function() {
                if (confirm("Are you want to delete ?")) {
                    id = $(this).data('id');
                    that = $(this);
                    test_id = $(this).data('test');
                    $.ajax({
                        url: "{{ route('test-score-delete') }}",
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
                            checkTable(testScoreTable, $('#steps-uid-0-t-2'));
                        }
                    });
                }
            });

            $(document).on('click', '.test-edit', function() {

                id = $(this).data('id');
                that = $(this);
                test_id = $(this).data('test');
                $.ajax({
                    url: "{{ route('test-score-edit') }}",
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
                        testScoreScript();

                    }
                });


            });

            documentTable = $("#document-table").DataTable({
                "processing": true,
                "serverSide": true,
                ajax: {
                    url: route('user-document-listing').url(),
                    data: function(d) {

                    }
                },
                //dom: "tps",

                columns: [{
                        name: 'title',
                        data: 'title'
                    },
                    {
                        name: 'type',
                        data: 'type'
                    },
                    {
                        name: 'action',
                        data: 'action',
                    }

                ],
                "language": {
                    "emptyTable": "No Document Added"
                },
                responsive: false,

                drawCallback: function(setting, data) {


                },
                bInfo: true,
                pageLength: 100,
                initComplete: function(settings, json) {}
            });

            $(document).on('change', '#add-document', function() {
                let tableName = $(this).children().find('option:selected').parent().data('cat');
                let type = $(this).children().find('option:selected').data('type');
                let tableId = $(this).val();
                let limit = $(this).children().find('option:selected').data('limit');
                $.ajax({
                    url: "{{ route('user-document') }}",
                    beforeSend: function() {
                        $('.document-form').html(
                            "<i class='fa fa-spin fa-spinner'></i> Loading..")
                    },
                    data: {
                        table_name: tableName,
                        table_id: tableId,
                        type: type,
                        limit: limit
                    },
                    success: function(data) {
                        $('.document-form').html(data);
                        uploadDocument();
                    }
                });

            });

            $(document).on('click', '.document-delete', function() {
                id = $(this).data('id');
                that = $(this);
                if (confirm('Are you want to delete ?')) {
                    $.ajax({
                        url: "{{ route('user-document-delete') }}",
                        type: 'POST',
                        beforeSend: function() {
                            that.attr('disabled', true).prepend(
                                "<i class='fa fa-spinner fa-spin'></i> ");
                        },
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: id,
                        },
                        success: function(data) {
                            setAlert(data);
                            documentTable.draw('page');
                        }
                    });
                }
            });

            $('#hightest-education-btn').on('click', function() {
                if ($('#hightest-education-form').valid()) {
                    $('#hightest-education-form').submit();
                }
            })

            validateForm($('#hightest-education-form'), {
                rules: {
                    country: {
                        required: true,
                    },
                    highest_education: {
                        required: true,
                    },
                },
                messages: {}
            });

            submitForm($('#hightest-education-form'), {
                beforeSubmit: function() {
                    submitLoader("#hightest-education-btn");
                },
                success: function(data) {
                    submitReset("#hightest-education-btn", "Save");
                    if (data.success) {
                        setAlert(data);
                        $(".icons-tab-steps").steps('next');
                    } else {
                        // $("#step-2").html(data);

                    }
                    workExperienceScript();
                },
                error: function(data) {
                    submitReset("#hightest-education-btn", "Save");
                    toast("error", "Something went wrong.", "Error");
                }
            });

            $(document).on('click', '.document-edit', function() {
                id = $(this).data('id');
                $.ajax({
                    url: "{{ route('user-document-edit') }}",
                    data: {
                        id: id
                    },
                    beforeSend: function() {
                        $('.document-form').html(
                            "<i class='fa fa-spin fa-spinner'></i> Loading..")
                    },
                    success: function(data) {
                        $('.document-form').html(data);
                        uploadDocument();
                    }
                });
            });




        });

        function workExperienceScript() {

            $('.work-date').pickadate({
                format: 'dd-mmmm-yyyy',
                max: 'Today',
                min: [1990, 3, 20],
                selectYears: 20,
                selectMonths: true,
            });

            $(document).on('change', '.current_working', function() {
                if (this.checked) {
                    $(".work-upto").prop('disabled', true);
                    $(".work-upto").val("");
                } else {
                    $(".work-upto").prop('disabled', false);
                }
            });

            validateForm($('#work-experience-add'), {
                rules: {
                    organization: {
                        required: true,
                    },
                    job_profile: {
                        required: true,
                    },
                    working_upto: {
                        required: true,
                    },
                    position: {
                        required: true,
                    },
                    working_from: {
                        required: true,
                    },

                },
                messages: {}
            });

            validateForm($('#work-experience-edit'), {
                rules: {
                    organization: {
                        required: true,
                    },
                    job_profile: {
                        required: true,
                    },
                    working_upto: {
                        required: true,
                    },
                    position: {
                        required: true,
                    },
                    working_from: {
                        required: true,
                    },

                },
                messages: {}
            });

            submitForm($('#work-experience-add'), {
                beforeSubmit: function() {
                    submitLoader("#add-btn");
                },
                success: function(data) {
                    if (data.success) {
                        setAlert(data);
                        $('.form-work-experience').html("");
                        experienceTable.draw('page');
                        checkTable(experienceTable, $('#steps-uid-0-t-3'));
                    } else {
                        // $("#step-2").html(data);

                    }
                    workExperienceScript();
                },
                error: function(data) {
                    toast("error", "Something went wrong.", "Error");
                }
            });

            submitForm($('#work-experience-edit'), {
                beforeSubmit: function() {
                    submitLoader("#add-btn");
                },
                success: function(data) {
                    setAlert(data);
                    if (data.success) {

                        $('.form-work-experience').html("");
                        experienceTable.draw('page');
                    } else {
                        // $("#step-2").html(data);

                    }
                    workExperienceScript();
                },
                error: function(data) {
                    toast("error", "Something went wrong.", "Error");
                }
            });

            //next step
            $('.next').click(function() {
                $(".icons-tab-steps").steps('next');
            });


        }

        function updateState(id) {
            getContent({
                url: "{{ url('admin/state') }}" + '/' + id,
                success: function(data) {
                    let html = '';
                    data.forEach(state => html += `<option value='${state.id}'>${state.name}</option>`);
                    $('#state').html('');
                    $('#state').append(html);
                    $("#state").selectpicker('refresh');
                }
            });
        }

        function updateCity(id) {
            getContent({
                url: "{{ url('admin/city') }}" + '/' + id,
                success: function(data) {
                    let html =
                        "<option value=''>--Select City --</option><option value='new-city'>Add New City</option>";
                    data.forEach(state => html += `<option value='${state.id}'>${state.name}</option>`);
                    $('#city').html('');
                    $('#city').append(html);
                    $("#city").selectpicker('refresh');
                }
            });
        }

        // function updateAddressDropdowns() {
        //   updateState($("select[name='state']").val())
        //   updateState($("select[name='state']").val())
        // }


        // function deleteDocument(){

        //   $('.close').on('click',function(){
        //      if(confirm('Are you want to delete this')){
        //       let close =  $(this)
        //        $.ajax({
        //          url:route('delete-file'),
        //          data:{
        //            id : $(this).data('id'),
        //            document_id : $(this).data('id'),
        //            file_id :  $(this).data('id')
        //          },
        //          success: function(data){
        //           $("#document").html(data);
        //           deleteDocument();
        //           uploadDocument();
        //           toast("success", "Document Delete Successfully");
        //           previousStep();
        //          }
        //        })
        //      }
        //   });
        // }


        function generalInformation() {
            validateForm($('#general-information'), {
                rules: {
                    first_name: {
                        required: true,
                    },
                    email: {
                        required: true,
                    },
                    dob: {
                        required: true,
                    },
                    country: {
                        required: true,
                    },
                    last_name: {
                        required: true,
                    },
                    personal_number: {
                        required: true,
                        number: true,
                        maxlength: 10
                    },
                    gender: {
                        required: true
                    },
                    maritial_status: {
                        required: true
                    },
                    language: {
                        required: true
                    },
                    postal: {
                        required: true,
                        number: true,
                        maxlength: 6,
                    },
                    address: {
                        required: true,
                    },
                    passport_number: {
                        required: true
                    }

                },
                messages: {}
            });

            submitForm($('#general-information'), {
                beforeSubmit: function() {
                    submitLoader("#first");
                },
                success: function(data) {
                    setAlert(data);
                    if (data.success) {
                        $(".icons-tab-steps").steps('next');
                        submitReset('#first', 'Next');
                        checkGenInfo();
                        generalInformation();
                    } else {
                        $("#step-1").html(data);
                        generalInformation();
                    }
                    previousStep();
                },
                error: function(data) {
                    toast("error", "Something went wrong.", "Error");
                }
            });

            $('#dob').pickadate({
                format: 'dd-mmmm-yyyy',
                max: 'Today',
                min: [1970, 3, 20],
                selectYears: 60,
                selectMonths: true,
            });

            $("#address_country").selectpicker();
            $("#state").selectpicker();
            $("#city").selectpicker();
            $("#maritial_status").selectpicker();
            $("#country").selectpicker();
            $("#gender").selectpicker();
        }

        function educationForm() {
            $.ajax({
                url: "{{ route('education-add') }}",
                beforeSend: function() {
                    $('.educaton-form').html("Loading.. <i class='fa fa-spinner fa-spin'></i>");
                    $('.educaton-form').addClass('text-center');
                },
                success: function(data) {
                    $('.educaton-form').removeClass('text-center');
                    $('.educaton-form').html(data);
                    educationHistory();
                }
            });
        }

        function educationHistory() {
            // $('select').addClass('select');
            // $('.select').attr('data-style','border-light bg-white');
            $('.select').selectpicker();
            $('.select').selectpicker().on('loaded.bs.select', enableBoostrapSelectOptgroup);

            //  $('.country_id').select2({
            //         placeholder:'Country',
            //         multiple: false,
            //         ajax:{
            //             url: route('get-countries'),
            //             dataType: 'json',
            //             type: 'POST',
            //             data: function(params){
            //                 return {
            //                     name: params.term
            //                 }
            //             },
            //             processResults: function(data){
            //                 return{
            //                     results: data
            //                 }
            //              }
            //          }
            //     });

            $('#study-level').on('change', function() {
                if ($(this).val() == 8) {
                    $('#other-sub').removeClass('d-none');
                } else {
                    $('#other-sub').addClass('d-none');
                    $('#other-sub').val("");
                }
            });

            $('#start-date').pickadate({
                format: 'dd-mmmm-yyyy',
                max: 'Today',
                min: [1970, 3, 20],
                selectYears: 60,
                selectMonths: true,
                onSet: function(context) {
                    if (context.select) {
                        // If a date is selected in the start date picker, update the min date of the end date picker
                        var selectedDate = new Date(context.select);
                        selectedDate.setDate(selectedDate.getDate() + 1); // Add 1 day to the selected date
                        $('#end-date').pickadate('picker').set('min', selectedDate);
                    }
                }
            });


            $('#start-date').on('change', function() {
                window.min = Date.parse($(this).val());

            });

            $('#end-date').pickadate({
                format: 'dd-mmmm-yyyy',
                max: 'Today',
                min: window.min,
                disable: window.min,
                selectYears: 60,
                selectMonths: true,
                onSet: function(context) {
                    if (context.select) {
                        // If a date is selected in the start date picker, update the min date of the end date picker
                        var selectedDate = new Date(context.select);
                        selectedDate.setDate(selectedDate.getDate() - 1); // Add 1 day to the selected date
                        $('#start-date').pickadate('picker').set('max', selectedDate);
                    }
                }
            });



            $('#add-education').click(function() {
                educationForm();
            });



            $(document).on('click', '.education-delete', function() {
                let that = $(this);
                if (confirm('Are you sure you want to delete ?')) {
                    id = $(this).data('id')
                    $.ajax({
                        url: route('education-delete'),
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
                            window.dataTable.draw('page');
                            checkTable(window.dataTable, $('#steps-uid-0-t-1'));
                        }
                    });
                }
            });

            $(document).on('click', '.education-edit', function() {
                let id = $(this).data('id');
                $.ajax({
                    url: route('education-edit'),
                    type: 'post',
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id,
                    },
                    beforeSend: function() {
                        $('.educaton-form').html("Loading.. <i class='fa fa-spinner fa-spin'></i>");
                        $('.educaton-form').addClass('text-center');
                    },
                    success: function(data) {
                        $('.educaton-form').removeClass('text-center');
                        $('.educaton-form').html(data);
                        educationHistory();

                    }

                })

            });

            validateForm($('#education-history'), {

                rules: {
                    course_name: {
                        required: true,
                    },
                    year: {
                        required: true,
                        number: true,
                        maxlength: 4,
                    },
                    board: {
                        required: true,
                    },
                    marks: {
                        required: true,
                    },
                    marks_unit: {
                        required: true,
                    },
                    study_level: {
                        required: true,
                    },
                    country: {
                        required: true,
                    },
                    start_date: {
                        required: true,
                    },
                    end_date: {
                        required: true,
                    },
                    language: {
                        required: true,
                    },
                    qualification: {
                        required: true,
                    },


                },
                messages: {}
            });

            validateForm($('#education-history-edit'), {

                rules: {
                    course_name: {
                        required: true,
                    },
                    year: {
                        required: true,
                        number: true,
                        maxlength: 4,
                    },
                    board: {
                        required: true,
                    },
                    marks: {
                        required: true,
                    },
                    marks_unit: {
                        required: true,
                    },
                    study_level: {
                        required: true,
                    },
                },
                messages: {}
            });


            submitForm($('#education-history'), {
                beforeSubmit: function() {
                    submitLoader("#add-btn");
                },
                success: function(data) {


                    if (data.success) {
                        setAlert(data);
                        window.dataTable.draw('page');
                        checkTable(window.dataTable, $('#steps-uid-0-t-1'));
                        $('.educaton-form').html("");
                    } else {
                        $("#step-2").html(data);
                        educationHistory();
                        educationForm();
                    }

                },
                error: function(data) {
                    toast("error", "Something went wrong.", "Error");
                }
            });

            submitForm($('#education-history-edit'), {
                beforeSubmit: function() {
                    submitLoader("#edit-btn");
                },
                success: function(data) {

                    if (data.success) {
                        window.dataTable.draw('page');
                        $('.educaton-form').html("");
                        setAlert(data);
                    }


                },
                error: function(data) {
                    toast("error", "Something went wrong.", "Error");
                }
            });

        }

        function testScoreScript() {

            $('.exam-date').pickadate({
                format: 'dd-mmmm-yyyy',
                max: 'Today',
                min: [1990, 3, 20],
                selectYears: 20,
                selectMonths: true,
            });


            validateForm($('#test-score-add'), {
                score: {
                    required: true,
                },
                exam_date: {
                    required: true,
                },
                messages: {

                }
            });


            submitForm($('#test-score-add'), {
                beforeSubmit: function() {
                    submitLoader("#test-add-btn");
                },
                success: function(data) {
                    if (data.success) {
                        toast('success', 'Test Score Saved Successfully');
                        $('.test-form').html("");
                        testScoreTable.draw('page');
                        checkTable(testScoreTable, $('#steps-uid-0-t-2'));
                        $('#add-test').find(':selected').attr("disabled", "disabled");
                        $('#add-test').find(':selected').append("(Document added)");

                    } else {
                        $('.test-form').html(data);


                    }

                    testScoreScript();
                },
                error: function(data) {
                    toast("error", "Something went wrong.", "Error");
                }
            });

            submitForm($('#test-score-edit'), {
                beforeSubmit: function() {
                    submitLoader("#test-edit-btn");
                },
                success: function(data) {
                    if (data.success) {
                        toast('success', 'Test Score Saved Successfully');
                        $('.test-form').html("");
                        testScoreTable.draw('page');
                    } else {
                        $('.test-form').html(data);


                    }

                    testScoreScript();
                },
                error: function(data) {
                    toast("error", "Something went wrong.", "Error");
                }
            });

        }

        function uploadDocument() {

            $('.custom-file-input').change(function(e) {
                $(this).parent().find('.custom-file-label').text(e.target.files[0].name);
            });

            validateForm($('.upload-document'), {
                document: {
                    required: true,
                    extension: "pdf,jpg,png"
                },
                messages: {

                }
            });

            validateForm($('.upload-document-edit'), {
                document: {
                    required: true,
                    extension: "pdf,jpg,png"
                },
                messages: {

                }
            });

            submitForm($('.upload-document'), {
                beforeSubmit: function(arr, $form, options) {
                    let btn = $form.find(':submit');
                    submitLoader(btn);

                },
                success: function(data) {
                    if (data.success) {
                        setAlert(data);
                        $(".document-form").html('');
                        documentTable.draw('page');
                        $('#add-document').children().find('option:selected').attr("disabled", "disabled");
                        $('#add-document').children().find('option:selected').append("[Document added");
                    } else {
                        $(".document-form").html(data);
                        uploadDocument();
                    }

                },
                error: function(data) {
                    // submitReset('.upload');
                    toast("error", "Something went wrong.", "Error");
                }
            });

            submitForm($('.upload-document-edit'), {
                beforeSubmit: function(arr, $form, options) {
                    let btn = $form.find(':submit');
                    submitLoader(btn);

                },
                success: function(data) {

                    if (data.success) {
                        setAlert(data);
                        $(".document-form").html('');
                        documentTable.draw('page');
                    } else {
                        $(".document-form").html(data);
                        uploadDocument();
                    }

                },
                error: function(data) {
                    // submitReset('.upload');
                    toast("error", "Something went wrong.", "Error");
                }
            });


        }

        function backgroundInformation() {


            //country ajax
            $('.country_id').select2({
                placeholder: 'Specify Country',
                multiple: false,
                ajax: {
                    url: route('get-countries'),
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



            //refusal radio select

            if ($('.refusal:checked').val() == 1) $('#country-container-refusal').show();
            else $('#country-container-refusal').hide();

            $('.refusal').change(function() {
                if ($(this).val() == 1) {
                    $('#country-container-refusal').show();
                } else {
                    $('#country-container-refusal').hide();
                }
            });

            //type radio select
            if ($('.type:checked').val() == 1) $('#country-container-type').show();
            else $('#country-container-type').hide();
            $('.type').change(function() {
                if ($(this).val() == 1) {
                    $('#country-container-type').show();
                } else {
                    $('#country-container-type').hide();
                }
            });

            //form submit
            submitForm($('#background'), {
                beforeSubmit: function() {
                    submitLoader("#background-btn");
                },
                success: function(data) {
                    if (data.success) {
                        $(".icons-tab-steps").steps('next');
                        toast("success", "Background Information Saved Successfully");
                        submitReset('#background-btn', 'Next');
                        $('#steps-uid-0-t-4').css("color", "#5cb85c");
                    } else {
                        $("#step-5").html(data);
                        previousStep();

                    }
                    backgroundInformation();
                },
                error: function(data) {
                    toast("error", "Something went wrong.", "Error");
                }
            });
        }

        function checkTable(dataTable, element) {
            dataLength = dataTable.data().length;
            if (dataLength > 0) {
                element.css('color', '#5cb85c');
            } else {
                element.css('color', '#d9534f');
            }
        }

        function checkGenInfo() {
            let fillInput = $("*[data-check='complete'][value !='']");
            let allInput = $("*[data-check='complete']");
            if (allInput.length == fillInput.length) {
                console.log("complete");
                $('#steps-uid-0-t-0').css("color", "#5cb85c");
            } else {
                console.log("Incomplete");
                $('#steps-uid-0-t-0').css("color", "#d9534f");
            }
        }
    </script>
@endsection
