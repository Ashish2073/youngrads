<div class="row justify-content-center">
    <div class="col-md-6">


        @include('dashboard.inc.message')
        <form id="university-create-form" action="{{ route('admin.university.store') }}" method="post">
            @csrf

            @include('dashboard.common.fields.text', [
                'label_name' => 'University',
                'id' => 'university',
                'name' => 'university',
                'placeholder' => 'Enter University Name',
                'input_attribute' => [
                    'type' => 'text',
                    'value' => old('university'),
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
