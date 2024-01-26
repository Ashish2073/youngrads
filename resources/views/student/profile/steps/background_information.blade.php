<form method="POST" action="{{ route('student.profile-step', 'background_information') }}" id="background">
    @csrf
    <div class="row text-center">
        <div class="col-12">
            <p class="text-left"><strong>Disclaimer:</strong> Providing false information about the Immigration
                history will
                result in application rejection by the University</p>
        </div>
    </div>
    
    <div class="row m-2">

        <div class="col-md-6 col-12 text-md-right">
            Have you ever applied for Visa ?
        </div>
        <div class="col-md-6 col-12">
            <div class="form-check-inline">
                <label class="form-check-label">
                    <input {{ $user->meta('applied_visa') == 1 ? 'checked' : '' }} type="radio"
                        class="form-check-input type" name="applied_visa" value="1">Yes
                </label>
            </div>
            <div class="form-check-inline">
                <label class="form-check-label">
                    <input {{ $user->meta('applied_visa') == 0 ? 'checked' : '' }} type="radio"
                        class="form-check-input type" name="applied_visa" value="0" name="applied_visa">No
                </label>
            </div>
            @error('applied_visa')
            {!! errMsg($message) !!}
            @enderror
        </div>
    </div>
    <div class="row m-2">
        <div class="col-md-6 col-12 text-md-right">
            Any Visa Refusal ?
        </div>
        <div class="col-md-6 col-12">
            <div class="form-check-inline">
                <label class="form-check-label">

                    <input {{ $user->meta('visa_refusal') == 1 ? 'checked' : '' }} type="radio"
                        class="form-check-input refusal" name="visa_refusal" value="1">Yes
                </label>
            </div>
            <div class="form-check-inline">
                <label class="form-check-label">
                    <input {{ $user->meta('visa_refusal') == 0 ? 'checked' : '' }} type="radio"
                        class="form-check-input refusal" name="visa_refusal" value="0">No
                </label>
            </div>

            <div id="country-container-refusal"
                class="mt-2 {{ $user->meta('visa_refusal') == 1 || $user->meta('applied_visa') == 1 ? '' : 'd-none' }}">
                <div class="form-group">
                    <textarea name="visa_refusal_details"
                        class="form-control @error('visa_refusal_details') {{ errCls() }} @enderror" cols="10"
                        rows="3"
                        placeholder="Please provide details like Country, Type of Visa, Visa Outcome, Reason of refusal(if any)">{{ old('visa_refusal_details', $user->meta('visa_refusal_details')) }}</textarea>
                    @error('visa_refusal_details')
                    {!! errMsg($message) !!}
                    @enderror
                </div>
            </div>

        </div>
    </div>
        
    <div class="row mt-2">
        <div class="col-md-4 col-12 text-left">
            <button type="button" class="btn btn-primary previous">Previous</button>
        </div>
        <div class="col-md-8 col-12 mt-2 mt-md-0 text-md-right text-sm-left">
            <input type="hidden" id="move_background" value="0" />
            <button type="submit" data-move="0" class="btn btn-primary" id="background-btn">Save</button>
            <button type="button" class="btn btn-primary" id="next-background-btn">Next</button>
        </div>
    </div>
</form>


<script>
    function backgroundInfoScript() {
        submitForm($('#background'), {
            beforeSubmit: function() {
                submitLoader("#background-btn");
            },
            success: function(data) {
                setAlert(data);
                if (data.success) {
                    // $(".icons-tab-steps").steps('next');
                    if($("#move_background").val() == 1) {
                        nextStep();
                    }
                }
            },
            error: function(data) {
                toast("error", "Something went wrong.", "Error");
            },
            complete: function() {
                submitReset("#background-btn");
            }
        });
    }

    

</script>
