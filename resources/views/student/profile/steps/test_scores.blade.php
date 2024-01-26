<div class="row">
    <div class="col-12">
        <div class="row">
            <div class="col-md-3 col-12">
                <label for="add-test">Add Test Scores</label>
                <select data-style="border-light bg-white" id="add-test" class="form-control">
                    <option value="">--Select Test--</option>
                    @foreach (config('tests') as $testType)
                        <option value="{{ $testType->id }}" @if (in_array($testType->id, $testIds)) disabled
                    @endif>{{ $testType->test_name }}
                    {{ in_array($testType->id, $testIds) ? '( Already added )' : '' }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="test-form"></div>
        <div class="table-responsive">
            <table id="testscore-table" class="table table-hover w-100 zero-configuration">
                <thead class="d-none">
                    <th>Test</th>
                    <th>Action</th>
                </thead>
            </table>
        </div>
    </div>
</div>
<div class="row mt-2">
    <div class="col-md-12">
        <button type="button" class="btn btn-primary previous">Previous</button>
        <button type="button" class="btn btn-primary float-right test-score-next">Next</button>
    </div>
</div>


<script>
    var testScoreTable;

    function testScoresScript() {
        $("#add-test").selectpicker();
        if(!$.fn.dataTable.isDataTable("#testscore-table")) {
            testScoreTable = $("#testscore-table").DataTable({
                "processing": true,
                "serverSide": true,
                ajax: {
                    url: "{{route('test-score-list')}}",
                    data: function(d) {

                    }
                },
                dom: '<<"actions action-btns"><"action-filters">><"clear">rt<"bottom"<"actions">p>',

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
                    "emptyTable": "Please add your Test Scores (if any)"
                },

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
        }
    }

    function manageTestScoresScript() {

        $('.exam-date').pickadate({
            format: 'dd-mmmm-yyyy',
            max: 'Today',
            min: [1990, 3, 20],
            selectYears: 20,
            selectMonths: true,
        });


        validateForm($('#test-score-add, #test-score-edit'), {
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
                    // checkTable(testScoreTable, $('#steps-uid-0-t-2'));
                    $('#add-test').find(':selected').attr("disabled", "disabled");
                    $('#add-test').find(':selected').append(" (Already added)");
                    $("#add-test").selectpicker('refresh');
                } else {
                    $('.test-form').html(data);


                }

                manageTestScoresScript();
            },
            error: function(data) {
                toast("error", "Something went wrong.", "Error");
            },
            complete: function() {
                updateProgressLabel();
            }
        });

        submitForm($('#test-score-edit'), {
            beforeSubmit: function() {
                submitLoader("#test-edit-btn");
            },
            success: function(data) {
                setAlert(data);
                if (data.success) {
                    $("#add-test").selectpicker('refresh');
                    // toast('success', 'Test Score updated Successfully');
                    $('.test-form').html("");
                    testScoreTable.draw('page');
                } else {
                    $('.test-form').html(data);
                    manageTestScoresScript()
                }

            },
            error: function(data) {
                toast("error", "Something went wrong.", "Error");
            },
            complete: function() {
            }
        });

    }

</script>
