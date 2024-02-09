<div class="row justify-content-center">
    <div class="col-md-6">

        <form id="study-edit-form" action="{{ route('admin.study.update', $study->id) }}" method="post">
            @csrf
            @method('put')
            @include('dashboard.common.fields.text', [
            'label_name' => 'Name',
            'id' => 'name',
            'name' => 'name',
            'placeholder' => 'Enter Page Name',
            'input_attribute' => [
            'type' => 'text',
            'value' => old('name', $study->name),
            ],
            'classes' => '',
            ])
           

            @if($study->parent_id != 0)
            <div class="form-group">
                <label for="parent_id">Parent</label>
                <select class="select2 form-control" name="parent_id" id="parent_id">
                    <option value="0">Parent</option>
                    @foreach (config('study_areas') as $study_area)
                        <option {{ $study_area->id == old('parent_id', $study->parent_id) ? 'selected' : '' }}
                            value="{{ $study_area->id }}">{{ Str::limit($study_area->name, 40, '...') }}</option>
                    @endforeach
                </select>
                @error('parent_id')
                    {!! errMsg($message) !!}
                @enderror
            </div>

            @endif

            <div class="form-group">
                <button type="submit" id="submit-btn" class="btn btn-primary">Update</button>
                
            </div>
        </form>
        @if($study->hasChild())
            <p>{{ config('setting.delete_notice') }}</p>
{{-- 
            @php Study::@endphp --}}
           
            @php session()->put('used_study_area',[$study->id]); @endphp
            <a href="{{url('admin/study')}}"><p> click Here to Show Uses</p><a>

       @else
           <div class="form-group delete" style="margin-top:1%">
            <form id="delete-form" method="POST" action="{{ route('admin.study.destroy', $study->id) }}">
                @csrf
                @method('DELETE')
                <button type="submit" id="submit-btn-delete" class="btn btn-danger">Delete</button>
            </form>
           </div>
       @endif
    </div>
</div>
