<div class="row">
    <div class="col-md-12">
        @include('dashboard.inc.message')
        <form id="modifier-update-form" action="{{ route('admin.modifier.update', $user->id) }}" method="post">
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

                {{-- @php
                $role_options = [
                    '' => '--Select Role--',
                ];
                foreach ($roles as $role) {
                    $role_options[$role] = strtoupper($role);
                }
            @endphp 
            {{-- @include('dashboard.common.fields.select', [
              'label_name' => 'Role',
              'id' => 'role',
              'name' => 'role',
              'options' => $role_options, 
              'attributes' => [],
              'value' => old('role', $user->role)
            ]) --}}




                <div class="col-md-12 col-12">
                    <div class="form-group">
                        <label for="rolenameedit">Role</label>
                        <select id="rolenameedit" name="rolename[]" data-live-search="true" multiple
                            class=" select form-control">
                            <option value="" disabled>Please Select Roles</option>



                            @foreach ($roles as $k => $role)
                                <option value="{{ $role->name }}" @if (in_array($role->name, json_decode($user->role))) selected @endif>
                                    {{ strtoupper($role->name) }}</option>
                            @endforeach

                        </select>
                    </div>
                </div>
                @php
                    $usermoderatorcount = \App\Models\User::where('moderator_id', $user->id)->count();
                    $checkassigntosupermoderator = \App\Models\Admin::role('moderator')
                        ->where('id', $user->id)
                        ->whereNotNull('parent_id')
                        ->exists();

                    if ($user->is_super == 1) {
                        $usermoderatorcount = 1;
                    }

                @endphp

                @php

                    $userassigntomodeartorassupermoderator = \App\Models\Admin::where('parent_id', $user->id)->count();

                @endphp


                <input type="hidden" id="studentscount" value="{{ $usermoderatorcount }}" />

                <input type="hidden" id="supermoderatoraasign" value="{{ $checkassigntosupermoderator }}" />



                <div class="form-group">
                    <button type="submit" id="submit-btn" class="btn btn-primary">Update Modefiers</button>
                </div>
            @endif
    </div>
    </form>




    <div class="form-group delete mx-1" style="margin-top:1%">
        @if (count(json_decode($user->role)) == 0 ||
                (!in_array('moderator', json_decode($user->role)) && !in_array('supermoderator', json_decode($user->role))) ||
                ($usermoderatorcount == 0 &&
                    in_array('moderator', json_decode($user->role)) &&
                    $checkassigntosupermoderator != 1) ||
                ($userassigntomodeartorassupermoderator == 0 && in_array('supermoderator', json_decode($user->role))))
            <form id="delete-form" method="POST" action="{{ route('admin.modifier.destroy', $user->id) }}">
                @csrf
                @method('DELETE')
                <button type="submit" id="submit-btn-delete" class="btn btn-danger">Delete</button>
            </form>
        @else
            @if ($user->is_super == 1)
                <p>This User Is Super Admin</p><a>
                @else
                    @if (in_array('moderator', json_decode($user->role)))
                        @php session()->put('used_modifier',$user->id); @endphp
                        <p>{{ config('setting.delete_notice') }}</p>
                        @if ($usermoderatorcount > 0)
                            <a href="{{ url('admin/students') }}">
                                <p> click Here to Show Uses as Student's Moderator</p><a>
                        @endif
                        @if ($checkassigntosupermoderator == 1)
                            @php session()->put('used_moderators_under_supermoderator',$user->id); @endphp
                            <a href="{{ url('admin/moderators') }}">
                                <p> click Here to Show Uses as Moderators's assign under Supermoderator</p><a>
                        @endif
                    @elseif(in_array('supermoderator', json_decode($user->role)))
                        @php session()->put('used_supermoderators',$user->id); @endphp
                        <p>{{ config('setting.delete_notice') }}</p>
                        <a href="{{ url('admin/moderators') }}">
                            <p> click Here to Show Uses</p><a>
                    @endif
            @endif

        @endif
    </div>
</div>
</div>
