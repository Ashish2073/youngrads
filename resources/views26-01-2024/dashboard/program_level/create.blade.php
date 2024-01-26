<div class="row">
    <div class="col-md-12">

        <form id="program-create-form" action="{{ route('admin.programlevel.store') }}" method="post">
            @csrf

            @include('dashboard.common.fields.text', [
                'label_name' => 'Name',
                'id' => 'name',
                'name' => 'name',
                'placeholder' => 'Enter Program Name',
                'input_attribute' => [
                    'type' => 'text',
                    'value' => old('name'),
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
                    'value' => old('slug'),
                ],
                'classes' => '',
            ])

            <div class="form-group">
                <button type="submit" id="submit-btn" class="btn btn-primary">Add</button>
            </div>
        </form>
    </div>
</div>

