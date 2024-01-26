<div class="row">
    <div class="col-md-12">
        @include('dashboard.inc.message')
        <form id="feetype-edit-form" action="{{ route('admin.feetype.update', $feetype->id) }}" method="post">
            @csrf
            @method('PUT')
            @include('dashboard.common.fields.text', [
                'label_name' => 'Fee Type',
                'id' => 'name',
                'name' => 'name',
                'placeholder' => 'Enter Fee Type',
                'input_attribute' => [
                    'type' => 'text',
                    'value' => old('name', $feetype->name),
                ],
                'classes' => '',
            ])


            <div class="form-group">
                <button type="submit" id="submit-btn" class="btn btn-primary">Update</button>
            </div>
    </div>
    </form>
    {{-- <div class="form-group delete" style="margin-top:1%">
        <form  id="delete-form" method="POST" action="{{ route('admin.feetype.destroy', $feetype->id) }}" >
             @csrf
             @method('DELETE')
         <button type="submit" id="submit-btn-delete" class="btn btn-danger">Delete Program</button>
         </form>
     </div> --}}
</div>
</div>
