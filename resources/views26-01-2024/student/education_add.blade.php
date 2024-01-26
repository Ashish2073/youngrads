<form method="POST" action="{{ route('education-history-store') }}" id="education-history">
    @csrf
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="study-level">Study Level<span class="required text-danger">*</span></label>
                <select data-style="border-light bg-white" type="text"
                    class="form-control @error('study_level') {{ errCls() }} @enderror select" id="study-level"
                    name="study_level">
                    <option value="">--Select Level--</option>
                    @foreach ($studyLevels as $studyLevel)
                        @if($highest_education->sequence >= $studyLevel->sequence + 1 )
                            @continue
                        @endif
                        @if ($studyLevel->name != 'Other')
                            <option value="{{ $studyLevel->id }}" @if (in_array($studyLevel->id, $studyIds)) disabled
                        @endif>{{ $studyLevel->name }}
                        {{ in_array($studyLevel->id, $studyIds) ? '(Already added)' : '' }}</option>
                    @else
                        <option value="{{ $studyLevel->id }}">{{ $studyLevel->name }}</option>
                    @endif
                    @endforeach
                </select>
                
                
                <input type="text" name="sub_other" class="form-control my-2 d-none"
                    placeholder="Enter Other Education Name" id="other-sub">
            </div>
            <div class="form-group">
                <label for="board">Name of Institute<span class="required text-danger">*</span></label>
                <input type="text" class="form-control @error('board') {{ errCls() }} @enderror" id="board" name="board"
                    value="{{ old('board') }}">
                @error('board')
                <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>

            <div class="row">
                <div class="col-8">
                    <div class="form-group">
                        <label for="marks">Marks<span class="required text-danger">*</span></label>
                        <input type="text" class="form-control @error('marks') {{ errCls() }} @enderror" id="marks"
                            name="marks" value="{{ old('marks') }}">
                        @error('marks')
                        <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="col-4">
                    <div class="form-group">
                        <label for="marks-unit">Marks Unit<span class="required text-danger">*</span></label>
                        <select data-style="border-light bg-white" class="select form-control" id="marks-unit"
                            name="marks_unit">
                            <option value="">--Marks Units--</option>
                            @foreach (config('setting.units') as $value => $text)
                                <option value="{{ $value }}">{{ $text }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="start-date">Start Date<span class="required text-danger">*</span></label>
                <input type="text" class="form-control @error('start_date') {{ errCls() }} @enderror" id="start-date"
                    name="start_date" value="{{ old('start_date') }}">
                @error('start_date')
                <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="language"> Primary Instruction Language<span class="required text-danger">*</span></label>
                <input type="text" class="form-control @error('language') {{ errCls() }} @enderror" id="language"
                    name="language" value="{{ old('language') }}">
                @error('language')
                <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>
            <div class="form-group">
                <label for="education-country">Country of Study<span class="required text-danger">*</span></label>
                {{-- <select name="country" id="education-country"
                    class="country_id form-control">
                    <option value="">--Select Country--</option>
                </select> --}}
                <select name="country" class="select form-control" data-live-search="true"
                    data-style="border-light bg-white">
                    <option value="">--Select Country--</option>
                    @foreach ($countries as $country)
                        <option value="{{ $country->id }}" @if (old('address_country') == $country->id) selected
                    @endif>{{ $country->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="qualification">Qualification/Degree<span class="required text-danger">*</span></label>
                <input type="text" class="form-control @error('qualification') {{ errCls() }} @enderror"
                    id="qualification" name="qualification" value="{{ old('qualification') }}">
                @error('qualification')
                <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>
            <div class="form-group">
                <label for="end-date">End Date<span class="required text-danger">*</span></label>
                <input type="text" class="form-control @error('end_date') {{ errCls() }} @enderror" id="end-date"
                    name="end_date" value="{{ old('end_date') }}">
                @error('end_date')
                <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <button type="submit" class="btn btn-primary" id="edu-add-btn">Add</button>
        </div>
    </div>
</form>
