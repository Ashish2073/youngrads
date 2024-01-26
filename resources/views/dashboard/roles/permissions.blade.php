@php
    $crud = [
        // 'add',
        // 'edit',
        // 'delete',
        'view'
    ];

    $all_permissions = [
        'dashboard' => [],
        'users' => $crud,
        'roles_and_permissions' => $crud,
        'students' => $crud,
        'universities' => $crud,
        'colleges' => $crud,
        'program' => $crud,
        'universities' => $crud,
        // 'program_level' => $crud,
        // 'pages' => $crud,
        'study' => $crud,
        // 'fee' =>$crud,
        // 'intake' =>$crud,
        // 'curruncy' => $crud,
        // 'messages' => $crud,
        // 'test' => $crud,
        'states' => $crud,
        'countries'=> $crud


    ];

@endphp

<div>
    <div class="form-group">
        <label for="toggle-permissions">
            <input id='toggle-permissions' type="checkbox"> Select/Deselect all
        </label>
    </div>

    <ul class="dd-list permissions-list">
        @php $i = 1; @endphp
        @foreach($all_permissions as $key => $value)


            <li class="dd-item " data-id="{{ $i }}">
                <label for="{{ $key }}">
                    <input
                            name="permissions[]"
                            value="{{ (is_array($value) && !empty($value)) ? "" : $key }}"
                            data-parent="{{ $key }}" class='parent-item' id='{{ $key }}'
                            type="checkbox"
                            {{ havePermission($role ?? "", $key) ? "checked" : "" }}
                    />
                    {{ str_replace("_", " ", Str::title($key)) }}
                </label>
                @if(is_array($value) && !empty($value))

                    <ul class='dd-list'>
                        @foreach($value as $val)
                            @php $i++; @endphp
                            <li data-id="{{ $i }}" class="dd-item">
                                <label for="{{ $key . "_" . $val }}">
                                    <input
                                            name="permissions[]"
                                            value="{{ $key . '_' . $val }}"
                                            data-child="{{ $key }}"
                                            id="{{ $key . "_" . $val }}"
                                            type="checkbox"
                                            {{ havePermission($role ?? "", $key . "_" . $val) ? "checked" : "" }}
                                    />
                                    {{ str_replace("_", " ", Str::title($val)) }}
                                </label>
                            </li>
                            @php $i++; @endphp
                        @endforeach
                    </ul>
                @endif
            </li>
            <hr/>
            @php $i++; @endphp
        @endforeach
    </ul>
</div>
