<div class="row justify-content-center">
    <div class="col-md-6">

        <form id="test-create-form" action="{{ route('admin.test.store') }}" method="post">
            @csrf

            @include('dashboard.common.fields.text', [
                'label_name' => 'Name',
                'id' => 'test_name',
                'name' => 'test_name',
                'placeholder' => 'Enter test name ',
                'input_attribute' => [
                    'type' => 'text',
                    'value' => old('test_name'),
                ],
                'classes' => '',
            ])

            @include('dashboard.common.fields.text', [
                'label_name' => ' Test Max Number',
                'id' => 'test_number',
                'name' => 'test_number',
                'placeholder' => 'Enter maximum number',
                'input_attribute' => [
                    'type' => 'number',
                    'value' => old('test_number'),
                ],
                'classes' => '',
            ])



            <div class="form-group">
                <label for="parent_id">Parent</label>
                <select class="select2 form-control" name="parent_id" id="parent_id">
                    <option value="0">Parent</option>
                    @foreach (config('parent_test') as $test)
                        <option {{ $test->id == old('parent_id') ? 'selected' : '' }} value="{{ $test->id }}">
                            {{ \Str::limit($test->test_name, 40, '...') }}</option>
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
