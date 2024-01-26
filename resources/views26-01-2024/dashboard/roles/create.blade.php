<div class="row">
    <div class="col-md-12">
        @include('dashboard.inc.message')
        <form id="role-create-form" action="{{ route('admin.role.store') }}" method="post">
            @csrf
            @include('dashboard.common.fields.text', [
                'label_name' => 'Role Name',
                'id' => 'name',
                'name' => 'name',
                'placeholder' => 'Enter Role Name',
                'input_attribute' => [
                    'type' => 'text',
                    'value' => old('name'),
                ],
                'classes' => '',
            ])

            <h4>Access Control</h4>

            @include('dashboard.roles.permissions')
            <hr>
            <div class="form-group">
                <button type="submit" id="submit-btn" class="btn btn-primary">Add Role</button>
            </div>
    </div>
    </form>
</div>
</div>