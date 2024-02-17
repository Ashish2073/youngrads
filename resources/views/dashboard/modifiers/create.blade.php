<div class="row">
    <div class="col-md-12">
        @include('dashboard.inc.message')
        <form id="modifier-create-form" action="{{ route('admin.modifier.store') }}" method="post">
            @csrf

            @include('dashboard.common.fields.text', [
                'label_name' => 'First Name',
                'id' => 'first_name',
                'name' => 'first_name',
                'placeholder' => 'Enter First Name',
                'input_attribute' => [
                    'type' => 'text',
                    'value' => old('first_name'),
                ],
                'classes' => '',
            ])

            @include('dashboard.common.fields.text', [
                'label_name' => 'Last Name',
                'id' => 'last_name',
                'name' => 'last_name',
                'placeholder' => 'Enter Last Name',
                'input_attribute' => [
                    'type' => 'text',
                    'value' => old('last_name'),
                ],
                'classes' => '',
            ])

            @include('dashboard.common.fields.text', [
                'label_name' => 'Email Address',
                'id' => 'email',
                'name' => 'email',
                'placeholder' => 'Enter Email Address',
                'input_attribute' => [
                    'type' => 'email',
                    'value' => old('email'),
                ],
                'classes' => '',
            ])

            @include('dashboard.common.fields.text', [
                'label_name' => 'Password',
                'id' => 'password',
                'name' => 'password',
                'placeholder' => 'Enter Password',
                'input_attribute' => [
                    'type' => 'password',
                    'value' => '',
                ],
                'classes' => '',
                'help_text' => '',
            ])

            @include('dashboard.common.fields.text', [
                'label_name' => 'Confirm Password',
                'id' => 'confirm-password',
                'name' => 'password_confirmation',
                'placeholder' => 'Enter Confirm Password',
                'input_attribute' => [
                    'type' => 'password',
                    'value' => '',
                ],
                'classes' => '',
            ])

            {{-- @php
                $role_options = [
                    '' => '--Select Role--',
                ];
                foreach ($roles as $role) {
                    $role_options[$role] = strtoupper($role);
                }
            @endphp --}}
            {{-- @include('dashboard.common.fields.select', [
                'label_name' => 'Role',
                'id' => 'role',
                'name' => 'role',
                'options' => $role_options,
                'attributes' => [],
                'value' => old('role'),
            ]) --}}

            <div class="col-md-12 col-12">
                <div class="form-group">
                    <label for="rolename">Role</label>
                    <select id="rolename" name="rolename[]" data-live-search="true" multiple
                        class=" select form-control">
                        <option value="" disabled>Please Select Roles</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}">{{ strtoupper($role->name) }}</option>
                        @endforeach

                    </select>
                </div>
            </div>


            <div class="form-group">
                <button type="submit" id="submit-btn" class="btn btn-primary">Add modifier</button>
            </div>
    </div>
    </form>
</div>
</div>

@section('vendor-script')
    {{-- vendor files --}}
    <script src="{{ asset(mix('vendors/js/tables/datatable/pdfmake.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/vfs_fonts.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.buttons.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/buttons.html5.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/buttons.print.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/buttons.bootstrap.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.bootstrap4.min.js')) }}"></script>
@endsection



@section('page-script')
    <script>
        $(document).ready(function() {
            $(".select").selectpicker();
            $("#rolename").selectpicker('refresh');
        });
    </script>
@endsection
