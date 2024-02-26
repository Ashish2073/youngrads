@php
    // $uppercasecrud = ['View', 'Add', 'Edit', 'Delete'];

    $crud = ['view', 'add', 'edit', 'delete'];

    $all_permissions = [
        // 'dashboard' => [],
        'users' => $crud,
        'roles_and_permissions' => $crud,
        'students' => $crud,
        'universities' => $crud,
        'colleges' => $crud,
        'program' => $crud,
        'universities' => $crud,
        'appliction' => $crud,
        // 'program_level' => $crud,
        // 'pages' => $crud,
        'study' => $crud,
        // 'fee' =>$crud,
        // 'intake' =>$crud,
        // 'curruncy' => $crud,
        // 'messages' => $crud,
        // 'test' => $crud,
        'import_data' => $crud,
        'states' => $crud,
        'countries' => $crud,
    ];

@endphp

{{-- <div>
    <div class="form-group">
        <label for="toggle-permissions">
            <input id='toggle-permissions' type="checkbox"> Select/Deselect all
        </label>
    </div> --}}

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

                <td>
                    @foreach ($crud as $value)
                        <div class="form-check form-check-inline px-2">
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
                            <div class="form-check form-check-inline px-2">
                                <input class="form-check-input" type="checkbox" id="{{ $key . '_' . $val }}"
                                    value="{{ $key . '_' . $val }}" data-child="{{ $key }}"
                                    {{ havePermission($role ?? '', $key . '_' . $val) ? 'checked' : '' }}
                                    name="permissions[]">
                                <label class="form-check-label" for="view1"></label>
                            </div>
                        @endforeach
                    </td>

                </tr>
            @endforeach
            <!-- Add more rows as needed -->
        </tbody>
    </table>
</div>
