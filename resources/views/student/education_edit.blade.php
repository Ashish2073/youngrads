<form method="POST" action="{{ route('education-update', $academics->id) }}" id="education-history-edit">
    @csrf
    @method('PUT')
    @php

        $studyLevel = DB::table('study_levels')
            ->where('id', $academics->study_levels_id)
            ->select('id', 'name')
            ->orderBy('sequence', 'desc')
            ->get();
        $country = DB::table('countries')
            ->where('id', $academics->country)
            ->select('name', 'id')
            ->orderBy('name', 'asc')
            ->get();
    @endphp
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="study-level">Study Level<span class="required text-danger">*</span></label>
                <select data-style="border-light bg-white" type="text"
                    class="form-control @error('study_level') {{ errCls() }} @enderror select" id="study-level"
                    name="study_level">
                    <option value="{{ $studyLevel[0]->id }}">{{ $studyLevel[0]->name }}</option>
                </select>
                @if ($studyLevel[0]->name == 'Other')
                    <input type="text" name="sub_other" class="form-control my-2  "
                        placeholder="Enter Other Education Name" id="other-sub" value="{{ $academics->sub_other }}">
                @endif
            </div>
            <div class="form-group">
                <label for="board">Name of Institute<span class="required text-danger">*</span></label>
                <input type="text" class="form-control @error('board') {{ errCls() }} @enderror" id="board"
                    name="board" value="{{ old('board', $academics->board_name) }}">
                @error('board')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>

            <div class="row">
                <div class="col-8">
                    <div class="form-group">
                        <label for="marks">Marks<span class="required text-danger">*</span></label>
                        <input type="text" class="form-control @error('marks') {{ errCls() }} @enderror"
                            id="marks" name="marks" value="{{ old('marks', $academics->marks) }}">
                        @error('marks')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="col-4">
                    <div class="form-group">
                        <label for="marks-unit">Marks Unit<span class="required text-danger">*</span></label>
                        <select data-style="border-light bg-white" class="form-control select" id="marks-unit"
                            name="marks_unit">
                            <option value="">--Marks Units--</option>
                            @foreach (config('setting.units') as $value => $text)
                                <option value="{{ $value }}" @if ($academics->marks_unit == $value) selected @endif>
                                    {{ $text }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="start-date">Start Date<span class="required text-danger">*</span></label>
                <input type="text" class="form-control @error('start_date') {{ errCls() }} @enderror"
                    id="start-date" name="start_date"
                    value="{{ old('start_date', date('d-F-Y', strtotime($academics->start_date))) }}">
                @error('start_date')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="language"> Primary Instruction Language<span class="required text-danger">*</span></label>
                <input type="text" class="form-control @error('language') {{ errCls() }} @enderror"
                    id="language" name="language" value="{{ old('language', $academics->language) }}">
                @error('language')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>
            <div class="form-group">
                <label for="education-country">Country of Study<span class="required text-danger">*</span></label>
                {{-- <select name="country" id="education-country"
                    class="country_id form-control">
                    @if (!empty($country[0]))
                        <option value="{{ $country[0]->id }}">{{ $country[0]->name }}</option>
                    @endif
                    <option value="">--Select Country--</option>
                </select> --}}
                @php
                    $studyCountry = !empty($country[0]) ? $country[0]->id : '';
                @endphp
                <select data-style="border-light bg-white" name="country" class="select form-control"
                    data-live-search="true" data-style="bg-white border-li">
                    <option value="">--Select Country--</option>
                    @foreach ($countries as $country)
                        <option value="{{ $country->id }}" @if (old('address_country') == $country->id || $studyCountry == $country->id) selected @endif>
                            {{ $country->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="qualification">Qualification/Degree<span class="required text-danger">*</span></label>
                <input type="text" class="form-control @error('qualification') {{ errCls() }} @enderror"
                    id="qualification" name="qualification"
                    value="{{ old('qualification', $academics->qualification) }}">
                @error('qualification')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>
            <div class="form-group">
                <label for="end-date">End Date<span class="required text-danger">*</span></label>
                <input type="text" class="form-control @error('end_date') {{ errCls() }} @enderror"
                    id="end-date" name="end_date"
                    value="{{ old('end_date', date('d-F-Y', strtotime($academics->end_date))) }}">
                @error('end_date')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <button type="submit" class="btn btn-primary" id="edu-edit-btn">Updatee</button>
        </div>
    </div>
</form>
