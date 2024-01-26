<div class="row">
    <div class="col-md-12">

        <form id="program-edit-form" action="{{ route('admin.programlevel.update', $program->id) }}" method="post">
            @csrf
            @method('put')
            @include('dashboard.common.fields.text', [
                'label_name' => 'Name',
                'id' => 'name',
                'name' => 'name',
                'placeholder' => 'Enter Page Name',
                'input_attribute' => [
                    'type' => 'text',
                    'value' => old('name', $program->name),
                ],
                'classes' => '',
            ])

            @include('dashboard.common.fields.text', [
                'label_name' => 'Slug',
                'id' => 'slug',
                'name' => 'slug',
                'placeholder' => 'Enter Page Slug',
                'input_attribute' => [
                    'type' => 'text',
                    'value' => old('slug', $program->slug),
                ],
                'classes' => '',
            ])

            <div class="form-group">
                <button type="submit" id="submit-btn" class="btn btn-primary">Update</button>
            </div>

        </form>
        @if($program->getProgram->count() > 0)
            <div class="col-md-12">
                <p>{{ config('setting.delete_notice') }}</p>
            </div>
         @else
          <div class="form-group delete" style="margin-top:1%">
            <form  id="delete-form" method="POST" action="{{ route('admin.programlevel.destroy', $program->id) }}" >
                @csrf
                @method('DELETE')
            <button type="submit" id="submit-btn-delete" class="btn btn-danger">Delete</button>
            </form>
          </div>
         @endif
    </div>
</div>

