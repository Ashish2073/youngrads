<div class="row">
    <div class="col-md-12">
        @include('dashboard.inc.message')
        <form id="role-update-form" action="{{ route('admin.role.update', $role->id) }}" method="post">
            @csrf
            @method('PUT')
            @include('dashboard.common.fields.text', [
                'label_name' => 'Role Name',
                'id' => 'name',
                'name' => 'name',
                'placeholder' => 'Enter Role Name',
                'input_attribute' => [
                    'type' => 'text',
                    'value' => old('name', $role->name),
                    'readonly' => true
                ],
                'classes' => '',
            ])

            <h4>Access Control</h4>

            @include('dashboard.roles.permissions')
            <hr>
            <div class="form-group">
                <button type="submit" id="submit-btn" class="btn btn-primary">Update Role</button>
            </div>
    </div>
    </form>
</div>
</div>