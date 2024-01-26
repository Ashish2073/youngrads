<form action="{{ route('work-experence-store') }}" class="my-2" method="POST" id="work-experience-add">
    @csrf
    <div class="row">
        <div class="col-md-6 col-12">
            <div class="form-group">
                <label for="organization">Name of the organization & address<span
                        class="required text-danger">*</span></label>
                <input type="text" class="form-control @error('organization') {{ errCls() }} @enderror"
                    id="organization" name="organization" value="{{ old('organization') }}"
                    placeholder="Enter Name Of the organization & Address">
                @error('organization')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>
            <div class="form-group">
                <label for="job_profile">Job Profile<span class="required text-danger">*</span></label>
                <input type="text" class="form-control @error('job_profile') {{ errCls() }} @enderror"
                    id="job_profile" name="job_profile" value="{{ old('job_profile') }}"
                    placeholder="Enter Job Profile">
                @error('job_profile')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>
            <div class="form-group">
                <label for="working_from">Working From<span class="required text-danger">*</span></label>
                <input type="text"
                    class="form-control work-date @error('working_from') {{ errCls() }} @enderror"
                    id="working_from" name="working_from" value="{{ old('working_from') }}"
                    placeholder="Working From...">
                @error('working_from')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>

        </div>
        <div class="col-md-6 col-12">
            <div class="form-group">
                <label for="position">Position<span class="required text-danger">*</span></label>
                <input type="text" class="form-control @error('position') {{ errCls() }} @enderror"
                    id="position" name="position" value="{{ old('position') }}" placeholder="Enter Position">
                @error('position')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="mode">Mode Of Salary<span class="required text-danger">*</span></label>
                <select class="form-control @error('mode') {{ errCls() }} @enderror" id="mode"
                    name="mode">
                    @foreach (config('setting.sallaryMode') as $sallaryMode)
                        <option value="{{ $sallaryMode }}">{{ $sallaryMode }}</option>
                    @endforeach
                </select>
                @error('first_name')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>
            <div class="form-group">
                <label for="working_upto">Working upto<span class="required text-danger">*</span></label>
                <input required {{ old('current_working') == 1 ? 'disabled' : '' }} type="text"
                    class="form-control work-date work-upto @error('working_upto') {{ errCls() }} @enderror work-upto"
                    id="working_upto" name="working_upto" value="{{ old('working_upto') }}"
                    placeholder="Working Upto...">
                @error('working_upto')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
    <div class="vs-checkbox-con vs-checkbox-primary my-1">
        <input type="checkbox" value="1" name="current_working" class="current_working">
        <span class="vs-checkbox">
            <span class="vs-checkbox--check">
                <i class="vs-icon feather icon-check"></i>
            </span>
        </span>
        <span class="">I am currently working here</span>
    </div>
    <button type="submit" class="btn btn-primary" id="add-btn">Add</button>

</form>

<script>
    $('#working_from').pickadate({
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
                $('#working_upto').pickadate('picker').set('min', selectedDate);
            }
        }
    });

    $('#working_upto').pickadate({
        format: 'dd-mmmm-yyyy',
        max: 'Today',
        // min: window.min,
        // disable: window.min,
        selectYears: 60,
        selectMonths: true,
    });
</script>
