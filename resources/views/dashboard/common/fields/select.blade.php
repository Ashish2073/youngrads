@php
    $attribute_string = "";
    foreach($attributes as $key => $value) {
        $attribute_string .= " $key=$value ";
    }
@endphp
<div class="form-group ">
    <label for="{{$id}}">{{ $label_name ?? "" }}</label>
    <select class='form-control select' name="{{ $name }}" id="{{ $id }}">
        @foreach($options as $val => $html)
            <option {{ $val == $value ? "selected" : "" }} value="{{ $val }}">{{ $html }}</option>
        @endforeach
    </select>
    @if(isset($help_text))
        <span class="help-block">{{ $help_text ?? "" }}</span>
    @endif
    @error($name)
    {!! errMsg($message) !!}
    @enderror
</div>