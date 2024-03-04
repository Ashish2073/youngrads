@php
    // $uppercasecrud = ['View', 'Add', 'Edit', 'Delete'];

    $crud = ['view', 'add', 'edit', 'delete'];

    $all_permissions = [
        'dashboard' => ['view', 'N/A', 'N/A', 'N/A'],
        'admin_users' => $crud,
        'roles_and_permissions' => $crud,
        'students' => ['view', 'N/A', 'N/A', 'delete'],
        'assign_students_to_moderator' => ['view', 'add', 'N/A', 'N/A'],
        'universities' => $crud,
        'campus' => $crud,
        'campus_details' => ['view', 'add', 'N/A', 'N/A'],
        'campus_program' => $crud,
        'program' => $crud,
        'universities' => $crud,
        'applications' => $crud,
        'application_apply_limit' => ['view', 'add', 'N/A', 'N/A'],
        'program_level' => $crud,
        // 'pages' => $crud,
        'study' => $crud,
        'application_document' => $crud,
        'user_activity' => ['view', 'N/A', 'N/A', 'N/A'],
        'modifiers' => $crud,
        'moderators' => $crud,

        // 'fee' =>$crud,
        // 'intake' =>$crud,
        // 'curruncy' => $crud,
        // 'messages' => $crud,
        // 'test' => $crud,
        'mandatory_document' => $crud,
        'cities' => $crud,
        'import_data' => ['view', 'add', 'N/A', 'N/A'],
        'states' => $crud,
        'countries' => $crud,
    ];

@endphp

<div>
    <div class="form-group">
        <label for="toggle-permissions">
            <input id='toggle-permissions' type="checkbox"> Select/Deselect all
        </label>
    </div>

    {{-- <ul class="dd-list permissions-list">
        @php $i = 1; @endphp
        @foreach ($all_permissions as $key => $value)
            <li class="dd-item " data-id="{{ $i }}">
                <label for="{{ $key }}">
                    <input name="permissions[]" value="{{ is_array($value) && !empty($value) ? '' : $key }}"
                        data-parent="{{ $key }}" class='parent-item' id='{{ $key }}' type="checkbox"
                        {{ havePermission($role ?? '', $key) ? 'checked' : '' }} />
                    {{ str_replace('_', ' ', Str::title($key)) }}
                </label>
                @if (is_array($value) && !empty($value))
                    <ul class='dd-list'>
                        @foreach ($value as $val)
                            @php $i++; @endphp
                            <li data-id="{{ $i }}" class="dd-item">
                                <label for="{{ $key . '_' . $val }}">
                                    <input name="permissions[]" value="{{ $key . '_' . $val }}"
                                        data-child="{{ $key }}" id="{{ $key . '_' . $val }}" type="checkbox"
                                        {{ havePermission($role ?? '', $key . '_' . $val) ? 'checked' : '' }} />
                                    {{ str_replace('_', ' ', Str::title($val)) }}
                                </label>
                            </li>
                            @php $i++; @endphp
                        @endforeach
                    </ul>
                @endif
            </li>
            <hr />
            @php $i++; @endphp
        @endforeach
    </ul> --}}



    {{-- <ul class="dd-list permissions-list">
    @php $i = 1; @endphp
    @foreach ($all_permissions as $key => $value)
        <li class="dd-item " data-id="{{ $i }}">
            <label for="{{ $key }}">
                <input name="permissions[]" value="{{ is_array($value) && !empty($value) ? '' : $key }}"
                    data-parent="{{ $key }}" class='parent-item' id='{{ $key }}' type="checkbox"
                    {{ havePermission($role ?? '', $key) ? 'checked' : '' }} />
                {{ str_replace('_', ' ', Str::title($key)) }}
            </label>
            @if (is_array($value) && !empty($value))
                <td class='dd-list'>
                    @foreach ($value as $val)
                        @php $i++; @endphp
                        <tr data-id="{{ $i }}" class="dd-item">
                             <label for="{{ $key . '_' . $val }}">
                                <input name="permissions[]" value="{{ $key . '_' . $val }}"
                                    data-child="{{ $key }}" id="{{ $key . '_' . $val }}" type="checkbox"
                                    {{ havePermission($role ?? '', $key . '_' . $val) ? 'checked' : '' }} />
                                {{ str_replace('_', ' ', Str::title($val)) }}
                            </label>
                        </tr>
                        @php $i++; @endphp
                    @endforeach
                </td>
            @endif
        </li>
        <hr />
        @php $i++; @endphp
    @endforeach
</ul> --}}



    <div class="container mt-5">
        <h2>Permission Table</h2>
        <table class="table table-bordered">
            <thead>

                <tr>
                    <td>Website Section</td>

                    <td style="padding: 0px 26px;">
                        @foreach ($crud as $value)
                            <div class="form-check form-check-inline px-1">
                                {{ ucfirst($value) }}

                            </div>
                        @endforeach
                    </td>

                </tr>


            </thead>
            <tbody>

                @foreach ($all_permissions as $key => $value)
                    <tr>

                        <td>
                            <label for="{{ ucfirst($key) }}">

                                <input name="permissions[]" value="" data-parent="{{ $key }}"
                                    {{ havePermission($role ?? '', $key) ? 'checked' : '' }} class="parent-item"
                                    id="{{ $key }}" type="checkbox">
                                {{ ucfirst($key) }}
                            </label>

                        </td>


                        <td>
                            @foreach ($value as $k => $val)
                                @if ($val != 'N/A')
                                    <div class="form-check form-check-inline px-2">
                                        <input class="form-check-input" type="checkbox" id="{{ $key . '_' . $val }}"
                                            value="{{ $key . '_' . $val }}" data-child="{{ $key }}"
                                            {{ havePermission($role ?? '', $key . '_' . $val) ? 'checked' : '' }}
                                            name="permissions[]">
                                        <label class="form-check-label" for="{{ $key . '_' . $val }}"></label>
                                    </div>
                                @else
                                    <div class="form-check form-check-inline px-1">
                                        (N/A)
                                    </div>
                                @endif
                            @endforeach
                        </td>

                    </tr>
                @endforeach
                <!-- Add more rows as needed -->
            </tbody>
        </table>
    </div>
