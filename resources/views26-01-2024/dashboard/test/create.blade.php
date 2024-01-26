<div class="row">
    <div class="col-md-12">
        @include('dashboard.inc.message')
        <form id="test-create-form" action="{{ route('admin.test.store') }}" method="post">
            @csrf

            @include('dashboard.common.fields.text', [
                'label_name' => 'Test',
                'id' => 'name',
                'name' => 'test_name',
                'placeholder' => 'Enter Test',
                'input_attribute' => [
                    'type' => 'text',
                    'value' => old('test_name'),
                ],
                'classes' => '',
            ])


            <div class="form-group">
                <button type="submit" id="submit-btn" class="btn btn-primary">Add</button>
            </div>
    </div>
    </form>
</div>
</div>
