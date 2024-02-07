<div class="row justify-content-center">
    <div class="col-md-6 col-12">
        {{-- @php dd($program->subAreaIds()); @endphp --}}
        @include('dashboard.inc.message')
        <form id="program-create-form" action="{{ route('admin.program.update', $program->id) }}" method="post">
            @csrf
            @method('PUT')
            @include('dashboard.common.fields.text', [
              'label_name' => 'Program',
              'id' => 'name',
              'name' => 'name',
              'placeholder' => 'Enter Program Name',
              'input_attribute' => [
                  'type' => 'text',
                  'value' => old('name', $program->name),
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
                     'value' => old('duration',$program->duration),
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
                    'value' => old('program_link', $program->course_link),
                ],
                'classes' => '',
            ]) --}}

            <div class="form-group">
                <label for="program_level_id">Program Level</label>
                <select data-live-search="true" data-live-search="true"
                        class="form-control select @error('program_level_id') {{ errCls() }} @enderror"
                        name="program_level_id" id="program_level_id">
                    <option value="">----Program Level----</option>
                    @foreach ($programLevels as $programLevel)
                        <option @if ($program->program_level_id == $programLevel->id) selected
                                @endif  value={{ $programLevel->id }}>{{ $programLevel->name }}</option>
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
                        <option {{ old('study_area_id', $program->study_area_id) == $studyArea->id ? "selected" : "" }} value={{ $studyArea->id }}>{{ $studyArea->name }}</option>
                    @endforeach
                </select>
                @error('study_area_id')
                <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="sub_study_area_ids">Sub Study Areas</label>
                <select id="sub_study_area_id" multiple class="form-control select @error('sub_study_area_ids') {{ errCls() }} @enderror"
                        name="sub_study_area_ids[]" data-live-search="true">
                    <option value="">----Select Sub Study Areas----</option>
                    @foreach (config('sub_study_areas') ?? [] as $sub_area)
                        <option {{ in_array($sub_area->id, old('sub_study_area_ids', $program->subAreaIds()))  ? "selected" : "" }} value={{ $sub_area->id }}>{{ $sub_area->name }}</option>
                    @endforeach
                </select>
                @error('sub_study_area_ids')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <button type="submit" id="submit-btn" class="btn btn-primary">Update</button>
            </div>
        </form>
        <div class="form-group delete " style="margin-top:1%">
         @if($program->campusProgram->count() > 0)
               <p>{{ config('setting.delete_notice') }}</p>

               @php session()->put('used_program',[$program->id, $program->subAreaIds(),$program->study_area_id,$program->program_level_id,$program->duration]); @endphp


               <a href="{{url('admin/campus-program')}}"><p> click Here to Show Uses</p><a>
         @else
         <form  id="delete-form" method="POST" action="{{ route('admin.program.destroy', $program->id) }}" >
          @csrf
          @method('DELETE')
            <button type="submit" id="submit-btn-delete" class="btn btn-danger">Delete</button>
           </form>
         @endif
       </div>
    </div>
</div>
</div>
