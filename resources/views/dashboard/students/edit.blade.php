<div class="row">
    <div class="col-md-12">
        @include('dashboard.inc.message')

        <form id="user-update-form" action="{{ route('admin.student.update', $user->id) }}" method="post">
            @csrf
            @method('PUT')

            {{-- 
            @include('dashboard.common.fields.text', [
                'label_name' => 'First Name',
                'id' => 'first_name',
                'name' => 'first_name',
                'placeholder' => 'Enter First Name',
                'input_attribute' => [
                    'type' => 'text',
                    'value' => old('first_name', $user->name),
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
                    'value' => old('last_name', $user->last_name),
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
                    'value' => old('email', $user->email),
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
                'help_text' => 'Leave blank to use existing password',
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
            ]) --}}

            @php
                $role_options = [
                    '' => '--Select Role--',
                ];
                foreach ($moderator as $role) {
                    $role_options[$role->id] = strtoupper($role->username);
                }
            @endphp
            @include('dashboard.common.fields.select', [
                'label_name' => 'Moderator ID',
                'id' => 'moderatorid',
                'name' => 'moderatorid',
                'options' => $role_options,
                'attributes' => [],
                'value' => old('role', $user->moderator_id),
            ])


            <div class="form-group">
                <button type="submit" id="submit-btn" class="btn btn-primary">Update Students Moderator</button>
            </div>
    </div>
    </form>

</div>
</div>
