<div class="row justify-content-center">
    <div class="col-md-6">

        <form id="study-create-form" action="{{ route('admin.study.store') }}" method="post">
            @csrf

            @include('dashboard.common.fields.text', [
                'label_name' => 'Name',
                'id' => 'name',
                'name' => 'name',
                'placeholder' => 'Enter Study Area',
                'input_attribute' => [
                    'type' => 'text',
                    'value' => old('name'),
                ],
                'classes' => '',
            ])

            

            <div class="form-group">
                <label for="parent_id">Parent</label>
                <select class="select2 form-control" name="parent_id" id="parent_id">
                    <option value="0">Parent</option>
                    @foreach(config('study_areas') as $study_area) 
                        <option {{ $study_area->id == old('parent_id') ? "selected" : "" }} value="{{ $study_area->id }}">{{ \Str::limit($study_area->name, 40, "...") }}</option>
                    @endforeach
                </select>
                @error('parent_id')
                    {!! errMsg($message) !!}
                @enderror
            </div>

            <div class="form-group">
                <button type="submit" id="submit-btn" class="btn btn-primary">Add</button>
            </div>
        </form>
    </div>
</div>

