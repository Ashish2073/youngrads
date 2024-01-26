<div class="row">
    <div class="col-md-12">
        @php
        $userIntakeIds = isset($userIntakeIds)? $userIntakeIds : [];
        @endphp
        <form action="{{ route('applications-store') }}" method="POST" id="form-application">
            @csrf
            @php
            $year = date('Y');
            @endphp
            <div class="form-group">
                <label for="year">Year</label>
                <select required data-style="bg-white border-light" name="year"
                    class="form-control select @error('year') {{ errCls() }} @enderror" id="year">
                    <option value="">--Select Year--</option>
                    @for ($i = date('Y'); $i <= date('Y', strtotime('+2 year')); $i++)
                        <option value="{{ $i }}" @if ($i == old('year')) selected
                    @endif>{{ $i }}</option>
                    @endfor
                </select>
                @error('year')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="intake">Intake</label>
                <select required data-style="bg-white border-light" class='form-control select select-2 @error(' intake')
                    {{ errCls() }} @enderror' name="intake" data-live-search="true" id="intake">
                    <option value="">--Select Intake--</option>
                    @foreach ($intakes as $intake)

                        <option value="{{ $intake->id }}" @if ($intake->id == old('intake')) selected
                    @endif>{{ $intake->name }}</option>
                    @endforeach
                </select>
                @error('intake')
                    <p class="py-1">
                        {!! $message !!}
                    </p>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary" id="submit-btn">Submit</button>
        </form>
    </div>
</div>

<script>
    function applyApplicationScript() {
        $("select[name='intake']").selectpicker();
        $("select[name='year']").selectpicker();
        $(".apply-title").html("Apply Now");
        validateForm($("#form-application"), {});
        submitForm($('#form-application'), {
            beforeSubmit: function() {
                submitLoader("#submit-btn");
            },
            success: function(data) {
                if (data.success) {
                    setAlert(data);
                    $(".dynamic-apply").html("");
                    $('#apply-model').modal('hide');
                    // Redirect to application page
                    window.location = data.application_url;
                } else {
                    $(".dynamic-apply").html(data);
                    applyApplicationScript();
                }
            },
            error: function(data) {
                if(data.responseJSON.errors.intake[0]){
                     toast("error", `${data.responseJSON.errors.intake[0]}`, "Error");
                    $('#apply-model').modal('hide');
                }else{
                    toast("error", "Something went wrong.", "Error");
                }
               
            },
            complete: function() {

            }
        });
    }
    applyApplicationScript();
</script>
