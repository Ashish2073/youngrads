<div class="divider">
    <div class="divider-text">Highest Education</div>
</div>
<div id="highest-education-box">
    <form action="{{ route('student.profile-step', 'education_history') }}" method="POST" id="hightest-education-form">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="education-country">Country Of Educationss<span class="text-danger">*</span></label>
                    <select name="country" data-style="border-light bg-white" id="education-country"
                        class="select form-control" data-live-search="true">
                        <option value="">--Select Country--</option>
                        @foreach (config('countries') as $country)
                            <option {{ $user->meta('country_of_education') == $country->id ? 'selected' : '' }}
                                value="{{ $country->id }}">{{ $country->name }}</option>
                        @endforeach
                    </select>
                    @error('country')
                        {!! errMsg($message) !!}
                    @enderror
                </div>
            </div>
            <input type="hidden" name="dont_prompt" value="0">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="highest-education">Highest Level of Education<span class="text-danger">*</span></label>
                    <select data-style="border-light bg-white" name="highest_education" id="highest-education"
                        class="form-control select">
                        <option value="">--Select Level--</option>
                        @foreach (config('study_levels') as $studyLevel)
                            @if ($studyLevel->name == 'Other')
                                @continue
                            @endif
                            <option {{ $user->meta('hightest_level') == $studyLevel->id ? 'selected' : '' }}
                                value="{{ $studyLevel->id }}">{{ $studyLevel->name }}</option>
                        @endforeach
                    </select>
                    @error('study_levels')
                        {!! errMsg($message) !!}
                    @enderror
                </div>
            </div>
        </div>
    </form>

    <p id="require-levels">

    </p>
</div>


<div class="divider">
    <div class="divider-text">Education Details</div>
</div>
<button type="button" class="btn btn-primary mb-1" id="add-education"><span class='fa fa-plus'></span> Add Education
    Detail</button>
<div class="educaton-form">

</div>

<div class="table-responsive">
    <table id="education-table" class="table table-hover w-100 zero-configuration">
        <thead>
            <tr>
                <th>Study Level</th>
                <th>Name of Institute</th>
                <th>Marks</th>
                <th>Country</th>
                <th>Language</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
</div>
<div class="row mt-2">
    <div class="col-md-4 text-left">
        <button type="button" class="btn btn-primary previous">Previous</button>
    </div>
    <div class="col-md-8 text-right">
        <input type="hidden" id="move_history" value="0">
        <button type="submit" data-move="0" class="btn btn-primary next" id="hightest-education-btn">Save</button>
        <button type="button" class="btn btn-primary next" id="next-education-btn">Next</button>
    </div>
</div>

<script>
    var educationTable;

    function educationHistoryScript() {
        $("#education-country").selectpicker();
        $("#highest-education").selectpicker();
        if (!$.fn.dataTable.isDataTable("#education-table")) {
            educationTable = $("#education-table").DataTable({
                "processing": true,
                "serverSide": true,
                ajax: {
                    url: "{{ route('education-listing') }}",
                    data: function(d) {

                    }
                },
                columns: [{
                        name: 'study_level',
                        data: 'study_level'
                    },
                    {
                        name: 'board_name',
                        data: 'board_name'
                    },
                    {
                        name: 'marks',
                        data: 'marks'
                    },
                    {
                        name: 'country',
                        data: 'country'
                    },
                    {
                        name: 'language',
                        data: 'language',
                    },
                    {
                        name: 'start_date',
                        data: 'start_date'
                    },
                    {
                        name: 'end_date',
                        data: 'end_date'
                    },
                    {
                        data: 'action'
                    }

                ],
                "language": {
                    "emptyTable": "Please add your Education History"
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
        }

        validateForm($("#hightest-education-form"), {
            rules: {
                "country": {
                    required: false
                },
                "highest_education": {
                    required: false
                }
            },
            message: {

            }
        });
        $("#hightest-education-form").valid();

        submitForm($('#hightest-education-form'), {
            beforeSubmit: function() {
                submitLoader("#hightest-education-btn");
            },
            data: {
                dont_prompt: window.dontPrompt
            },
            success: function(data) {
                setAlert(data);
                if (data.success) {
                    // $(".icons-tab-steps").steps('getStep');
                } else {

                }
                if ($("#move_history").val() == 1) {
                    nextStep();
                }
            },
            error: function(data) {
                toast("error", "Something went wrong.", "Error");
            },
            complete: function() {
                $("input[name='dont_prompt']").val(0);
                submitReset("#hightest-education-btn");
                updateProgressLabel();
            }
        });

    }

    function marksValidation() {
        let marksUnit = $("#marks-unit").val().toLowerCase();
        switch (marksUnit) {
            case 'percentage':
                $("#marks").attr("min", 0);
                $("#marks").attr("max", 100);
                break;
            case 'gpa':
                $("#marks").attr("min", 0);
                $("#marks").attr("max", 10);
                break;
        }
    }


    function adjustDate(inputDateString, daysToAddOrSubtract) {
        // Parse the input date string
        var inputDateParts = inputDateString.split('-');
        var day = parseInt(inputDateParts[0], 10);
        var month = inputDateParts[1];
        var year = parseInt(inputDateParts[2], 10);

        // Create an array of month names
        var monthNames = [
            "January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ];

        // Find the index of the month in the array
        var monthIndex = monthNames.indexOf(month);

        // Create a new date with the adjusted days
        var outputDate = new Date(year, monthIndex, day + daysToAddOrSubtract);

        // Format the output date as "DD-Month-YYYY"
        var outputDateString = outputDate.getDate() + "-" + monthNames[outputDate.getMonth()] + "-" + outputDate
            .getFullYear();

        return outputDateString;
    }







    function manageEducationScript() {
        // Selectpicker
        // Datepicker

        // Input date string

        // Output: "31-December-2019"







        $('#start-date').pickadate({
            format: 'dd-mmmm-yyyy',

            max: (typeof($('#end-date').val()) !== 'undefined' && $('#end-date').val() !== null) ?
                adjustDate($('#end-date').val(), -1) : 'Today',
            min: [1970, 3, 20],
            selectYears: 60,
            selectMonths: true,
            onSet: function(context) {
                if (context.select) {
                    // If a date is selected in the start date picker, update the min date of the end date picker
                    var selectedDate = new Date(context.select);
                    console.log(selectedDate.getDate());
                    selectedDate.setDate(selectedDate.getDate() + 1); // Add 1 day to the selected date
                    $('#end-date').pickadate('picker').set('min', selectedDate);
                }
            }
        });




        $('#end-date').pickadate({
            format: 'dd-mmmm-yyyy',
            max: 'Today',
            min: (typeof($('#start-date').val()) !== 'undefined' && $('#start-date').val() !== null) ?
                adjustDate($('#start-date').val(), +1) : [1970, 3, 20],
            // disable: window.min,
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

        $("#marks-unit").selectpicker();
        $("select[name='country']").selectpicker();
        $("#study-level").selectpicker();

        validateForm($('#education-history, #education-history-edit'), {
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

        marksValidation();

        submitForm($('#education-history'), {
            beforeSubmit: function() {
                submitLoader("#edu-add-btn");
            },
            success: function(data) {

                if (data.success) {
                    setAlert(data);
                    educationTable.draw('page');
                    // checkTable(window.dataTable, $('#steps-uid-0-t-1'));
                    $('.educaton-form').html("");
                } else {
                    $(".educaton-form").html(data);
                    manageEducationScript();
                    // educationHistory();
                    // educationForm();
                }
            },
            error: function(data) {
                toast("error", "Something went wrong.", "Error");
            },
            complete: function() {
                submitReset("#edu-add-btn");
                updateProgressLabel();
            }
        });

        submitForm($('#education-history-edit'), {
            beforeSubmit: function() {
                submitLoader("#edu-edit-btn");
            },
            success: function(data) {
                if (data.success) {
                    educationTable.draw('page');
                    $('.educaton-form').html("");
                    setAlert(data);
                } else {
                    $(".educaton-form").html(data);
                    manageEducationScript();
                }
            },
            error: function(data) {
                toast("error", "Something went wrong.", "Error");
            },
            complete: function() {
                submitReset("#edu-edit-btn");

            }
        });
    }
</script>
