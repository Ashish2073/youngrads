<div class="row">
    <div class="col-md-12">
        @include('dashboard.inc.message')
        <form id="test-edit-form" action="{{ route('admin.test.update', $test->id) }}" method="post">
            @csrf
            @method('PUT')
            @include('dashboard.common.fields.text', [
                'label_name' => 'Test',
                'id' => 'name',
                'name' => 'test_name',
                'placeholder' => 'Enter Test',
                'input_attribute' => [
                    'type' => 'text',
                    'value' => old('test_name', $test->test_name),
                ],
                'classes' => '',
            ])

            <div class="form-group">
                <button type="submit" id="submit-btn" class="btn btn-primary">Update</button>
            </div>
    </div>
    </form>
    @if($test->campusProgramTest->count() > 0)
        <div class="col-md-12">
            <p>{{ config('setting.delete_notice') }}</p>
        </div>
    
    @else
        <div class="form-group delete" style="margin-top:1%">
            <form  id="delete-form" method="POST" action="{{ route('admin.feetype.destroy', $feetype->id) }}" >
                @csrf
                @method('DELETE')
            <button type="submit" id="submit-btn-delete" class="btn btn-danger">Delete Program</button>
            </form>
        </div>
    @endif
</div>
</div>
