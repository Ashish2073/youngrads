<div class="row justify-content-center">
    <div class="col-md-6 col-12">
        @include('dashboard.inc.message')
        <form id="program-create-form" action="{{ route('admin.program.store') }}" method="post"
              enctype="multipart/form-data">
            @csrf

            @include('dashboard.common.fields.text', [
                'label_name' => 'Program',
                'id' => 'name',
                'name' => 'name',
                'placeholder' => 'Enter Program Name',
                'input_attribute' => [
                    'type' => 'text',
                    'value' => old('name'),
                ],
                'classes' => '',
            ])
            
            @include('dashboard.common.fields.text', [
                'label_name' => 'Duration',
                'id' => 'duration',
                'name' => 'duration',
                'placeholder' => 'Enter Program Duration',
                'input_attribute' => [
                    'type' => 'text',
                    'value' => old('duration'),
                ],
                'classes' => '',
                'help_text' => 'Enter duration in months e.g 12'
            ])



            {{-- @include('dashboard.common.fields.text', [
                'label_name' => 'Program Link',
                'id' => 'program_link',
                'name' => 'program_link',
                'placeholder' => 'Enter Program Link',
                'input_attribute' => [
                    'type' => 'text',
                    'value' => old('program_link'),
                ],
                'classes' => '',
            ]) --}}

            {{-- <div class="form-group">
                <label for="intake">Intakes</label>
                <select class="form-control select @error('intake') {{ errCls() }} @enderror" data-live-search="true" name="intake[]" id="intake" multiple size="6">
                @foreach ($intakes as $intake)
                    <option value={{ $intake->id }}>{{ $intake->name }}</option>
                @endforeach
                </select>
                @error('intake')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div> --}}

            <div class="form-group">
                <label for="program_level_id">Program Level</label>
                <select class="form-control select @error('program_level_id') {{ errCls() }} @enderror"
                        name="program_level_id" id="program_level_id" data-live-search="true">
                    <option value="">----Program Level----</option>
                    @foreach ($programLevels as $programLevel)
                        <option value={{ $programLevel->id }}>{{ $programLevel->name }}</option>
                    @endforeach
                </select>
                @error('program_level_id')
                <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="study_area_id">Study Area</label>
                <select class="form-control select @error('study_area_id') {{ errCls() }} @enderror"
                        name="study_area_id" data-live-search="true">
                    <option value="">----Select Study Area----</option>
                    @foreach (config('study_areas') as $studyArea)
                        <option {{ old('study_area_id') == $studyArea->id ? "selected" : "" }} value={{ $studyArea->id }}>{{ $studyArea->name }}</option>
                    @endforeach
                </select>
                @error('study_area_id')
                <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="sub_study_area_id">Sub Study Areas</label>
                <select id="sub_study_area_id" multiple class="form-control select @error('sub_study_area_id') {{ errCls() }} @enderror"
                        name="sub_study_area_ids[]" data-live-search="true">
                    <option value="">----Select Sub Study Areas----</option>
                    @foreach (config('sub_study_areas') ?? [] as $sub_area)
                        <option {{ in_array($sub_area->id, old('sub_study_area_id')) ? "selected" : "" }} value={{ $sub_area->id }}>{{ $sub_area->name }}</option>
                    @endforeach
                </select>
                @error('sub_study_area_id')
                <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <button type="submit" id="submit-btn" class="btn btn-primary">Add</button>
            </div>
    </div>
    </form>
</div>
</div>
