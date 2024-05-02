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


            <input type="hidden" name="parent_id" id="parent_id" value="{{ $test->id }}" />
            @include('dashboard.common.fields.text', [
                'label_name' => ' Test Max Number',
                'id' => 'test_number_max',
                'name' => 'test_number_max',
                'placeholder' => 'Enter maximum number',
                'input_attribute' => [
                    'type' => 'number',
                    'value' => $test->max,
                ],
                'classes' => '',
            ])


            @foreach ($childtest as $data)
                <input type="hidden" name="child_id" id="child_id" value="{{ $data->id }}" />

                @include('dashboard.common.fields.text', [
                    'label_name' => 'sub Test',
                    'id' => 'sub_test_name',
                    'name' => 'sub_test_name',
                    'placeholder' => 'Enter Sub Test Name',
                    'input_attribute' => [
                        'type' => 'text',
                        'value' => old('sub_test_name', $data->name),
                    ],
                    'classes' => '',
                ])

                @include('dashboard.common.fields.text', [
                    'label_name' => 'Sub  Test Max Number',
                    'id' => 'sub_test_number_max',
                    'name' => 'sub_test_number_max',
                    'placeholder' => 'Enter Sub maximum number',
                    'input_attribute' => [
                        'type' => 'number',
                        'value' => $data->max,
                    ],
                    'classes' => '',
                ])
            @endforeach


            <div class="form-group">
                <button type="submit" id="submit-btn" class="btn btn-primary">Update</button>
            </div>
    </div>
    </form>

</div>
</div>
