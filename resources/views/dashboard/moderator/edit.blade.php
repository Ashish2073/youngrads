<div class="row">
    <div class="col-md-12">
        @include('dashboard.inc.message')
        <form id="moderator-update-form" action="{{ route('admin.moderator.update', $user->id) }}" method="post">
            @csrf
            @method('PUT')
            @include('dashboard.common.fields.text', [
                'label_name' => 'First Name',
                'id' => 'first_name',
                'name' => 'first_name',
                'placeholder' => 'Enter First Name',
                'input_attribute' => [
                    'type' => 'text',
                    'value' => old('first_name', $user->first_name),
                    'readonly' => $user->is_super == 1 ? 'true' : '',
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
                    'readonly' => $user->is_super == 1 ? 'true' : '',
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
                    'readonly' => $user->is_super == 1 ? 'true' : '',
                ],
                'classes' => '',
            ])

            @if ($user->is_super != 1)
                @include('dashboard.common.fields.text', [
                    'label_name' => 'Password',
                    'id' => 'password',
                    'name' => 'password',
                    'placeholder' => 'Enter Password',
                    'input_attribute' => [
                        'type' => 'password',
                        'value' => '',
                        'readonly' => $user->is_super == 1 ? 'true' : '',
                    ],
                    'classes' => '',
                    'help_text' => 'Leave blank to use existing password',
                ])

                @include('dashboard.common.fields.text', [
                    'label_name' => 'Confirm Password',
                    'id' => 'confirm_password',
                    'name' => 'password_confirmation',
                    'placeholder' => 'Enter Confirm Password',
                    'readonly' => $user->is_super == 1 ? 'true' : '',
                    'input_attribute' => [
                        'type' => 'password',
                        'value' => '',
                    ],
                    'classes' => '',
                ])








                <div class="form-group">
                    <button type="submit" id="submit-btn" class="btn btn-primary">Update Modefiers</button>
                </div>
            @endif
    </div>
    </form>

    @php
        $usermoderatorcount = \App\Models\User::where('moderator_id', $user->id)->count();

        if ($user->is_super == 1) {
            $usermoderatorcount = 1;
        }

    @endphp

    <div class="form-group delete mx-1" style="margin-top:1%">
        @if ($usermoderatorcount == 0)
            <form id="delete-form" method="POST" action="{{ route('admin.moderator.destroy', $user->id) }}">
                @csrf
                @method('DELETE')
                <button type="submit" id="submit-btn-delete" class="btn btn-danger">Delete</button>
            </form>
        @else
            @php session()->put('used_moderator',$user->id); @endphp
            @if ($user->is_super == 1)
                <p>This User Is Super Admin</p><a>
                @else
                    <p>{{ config('setting.delete_notice') }}</p>
                    <a href="{{ url('admin/students') }}">
                        <p> click Here to Show Uses</p><a>
            @endif
        @endif
    </div>
</div>
</div>