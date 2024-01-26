<div class="row">
    <div class="col-md-12">
        @include('dashboard.inc.message')
        <form id="feetype-create-form" action="{{ route('admin.feetype.store') }}" method="post">
            @csrf

            @include('dashboard.common.fields.text', [
                'label_name' => 'Fee Type',
                'id' => 'name',
                'name' => 'name',
                'placeholder' => 'Enter Fee Type',
                'input_attribute' => [
                    'type' => 'text',
                    'value' => old('name'),
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
