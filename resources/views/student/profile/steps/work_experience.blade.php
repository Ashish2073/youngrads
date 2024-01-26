<div class="row">
    <div class="col-12">
        <button class="btn btn-primary" id="add-experience"><span class='fa fa-plus'></span> Add Experience</button>
        <div class="form-work-experience">

        </div>
        <div class="table-responsive">
            <table id="experience-table" class="table table-hover w-100 zero-configuration">
                <thead>
                    <tr>
                        <th>Organization</th>
                        <th>Position</th>
                        <th>Job Profile</th>
                        <th>Currently Working</th>
                        <th>Working From</th>
                        <th>Working Upto</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div class="row mt-2">
            <div class="col-md-12">
                <button type="button" class="btn btn-primary previous">Previous</button>
                <button type="button" class="btn btn-primary float-right next work-next-btn">Next</button>
            </div>
        </div>
    </div>
</div>

<script>
    var experienceTable;
    function workExpScript() {
        if(!$.fn.dataTable.isDataTable("#experience-table")) {
            experienceTable = $("#experience-table").DataTable({
                "processing": true,
                "serverSide": true,
                ajax: {
                    url: "{{route('experence-listing')}}",
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
                    "emptyTable": "Please add your Work Experiences (if any)"
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
        }
    }

    function workExperienceScript() {

        $('.work-date').pickadate({
            format: 'dd-mmmm-yyyy',
            max: 'Today',
            min: [1990, 3, 20],
            selectYears: 20,
            selectMonths: true,
        });

        validateForm($('#work-experience-add, #work-experience-edit'), {
            rules: {
                organization: {
                    required: true,
                },
                job_profile: {
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
            },
            complete: function() {
                submitReset("#add-btn");
                updateProgressLabel();

            }
        });

        submitForm($('#work-experience-edit'), {
            beforeSubmit: function() {
                submitLoader("#edit-btn");
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
            },
            complete: function() {
                submitReset("#edit-btn");
            }

        });

    }

</script>
