@extends('layouts/contentLayoutMaster')

@section('title', 'Import')

@section('breadcumb-right')

@endsection

@section('content')



    <section id="basic-datatable">
        <div class="row">
            <div class="col-6">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">
                            University / Campus Import
                        </h2>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard">
                            <form method="POST" id="import-univ-campus-form"
                                action="{{ route('admin.import.univ_campus') }}">
                                @csrf
                                <div class="form-group">
                                    <label for="univ_campus_file">Excel File</label>
                                    <input type="file" name="univ_campus_file" id="univ_campus_file" />
                                    @error('univ_campus_file')
                                        {!! errMsg($message) !!}
                                    @enderror
                                </div>
                                <button id="import-univ-campus-btn" class="btn btn-primary" type="submit">Import</button>
                            </form>
                        </div>
                        <div class="card-footer">

                            <a download href="{{ asset('/import_samples/university.xlsx') }}">Click Here</a> to download
                            sample.
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">
                            Campus Programs Import
                        </h2>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard">
                            <form method="POST" id="import-programs-form" action="{{ route('admin.import.programs') }}">
                                @csrf
                                <div class="form-group">
                                    <label for="programs_file">Excel File</label>
                                    <input type="file" name="programs_file" id="programs_file" />
                                    @error('programs_file')
                                        {!! errMsg($message) !!}
                                    @enderror
                                </div>
                                <button id="import-programs-btn" class="btn btn-primary" type="submit">Import</button>
                            </form>
                        </div>
                        <div class="card-footer">
                            <a download href="{{ asset('import_samples/campus_program.xlsx') }}">Click Here</a> to download
                            sample.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row sheet--error d-none">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Row Number</th>
                                <th>Error Message</th>
                            </tr>
                        </thead>
                        <tbody class='sheet--error-body'>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>




@endsection



@section('page-script')
    <script>
        $(document).ready(function() {
            runScript();
        });

        function runScript() {

            validateForm($('#import-programs-form'), {
                rules: {
                    univ_campus_file: {
                        required: true,
                        extension: 'xls,xlsx'
                    }
                },
                messages: {
                    univ_campus_file: {
                        required: 'Please select a excel file',
                        extension: "Only valid Excel files are allowed!"
                    }
                }
            });

            validateForm($('#import-univ-campus-form'), {
                rules: {
                    univ_campus_file: {
                        required: true,
                        extension: 'xls,xlsx'
                    }
                },
                messages: {
                    univ_campus_file: {
                        required: 'Please select a excel file',
                        extension: "Only valid Excel files are allowed!"
                    }
                }
            });

            submitForm($('#import-univ-campus-form'), {
                beforeSubmit: function() {
                    submitLoader("#import-univ-campus-btn");
                },
                success: function(data) {
                    if (data.success) {
                        toast("success", data.message, "Congratulations");

                    } else {
                        let sheetError = data.sheetError;



                        let sheetErrorCount = sheetError.length;
                        let errrorMessage = "";
                        for (let i = 0; i < sheetErrorCount; i++) {
                            errrorMessage = errrorMessage + " " + (i + 1) + "-" + sheetError[i].message + "\n";

                        }
                        toast("error", errrorMessage, "Error");
                        displaySheetError(data.sheetError);
                    }
                },
                complete: function() {
                    submitReset("#import-univ-campus-btn");
                },
                error: function(data) {
                    let message = "Someting went wrong.";
                    try {
                        message = data.responseJSON.message;
                    } catch (e) {
                        console.error(e);
                    }
                    toast("error", message, "Error");
                }
            });




            submitForm($('#import-programs-form'), {
                beforeSubmit: function() {
                    submitLoader("#import-programs-btn");
                },
                success: function(data) {
                    if (data.success) {
                        toast("success", data.message, "Congratulations");

                    } else {
                        let sheetError = data.sheetError;

                        let sheetErrorCount = sheetError.length;
                        let errrorMessage = "";
                        for (let i = 0; i < sheetErrorCount; i++) {
                            errrorMessage = errrorMessage + " " + (i + 1) + "-" + sheetError[i].message + "\n";

                        }



                        toast("error", errrorMessage, "Error");
                        displaySheetError(data.sheetError);
                    }
                },
                complete: function() {
                    submitReset("#import-programs-btn");
                },
                error: function(data) {
                    let message = "Someting went wrong.";
                    try {
                        message = data.responseJSON.message;
                    } catch (e) {
                        console.error(e);
                    }
                    toast("error", message, "Error");
                }
            });


        }

        function displaySheetError(sheetErrors) {
            $('.sheet--error-body').html('');

            if (sheetErrors == undefined) {
                $(".sheet--error").addClass('d-none');
                return;
            }

            if (sheetErrors.length == 0) {
                $(".sheet--error").addClass('d-none');
                return;
            }

            $('.sheet--error').removeClass('d-none');

            sheetErrors.forEach(function(sheetError) {
                $(".sheet--error-body").append(`
                    <tr>
                        <td>${sheetError.rowNumber}</td>
                        <td>${sheetError.message}</td>
                    <tr>
                `);
            });

            $('html, body').animate({
                scrollTop: $(".sheet--error-body").offset().top
            }, 500);
        }
    </script>
@endsection
