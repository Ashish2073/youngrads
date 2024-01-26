<div class="form-group">
    <label for="{{$id}}">{{ $label_name ?? "" }}</label>

    <input
            name="{{ $name }}"
            id="{{$id}}"
            class="{{ $classes }} form-control @error($name) {{ errCls() }} @enderror"
            value="{{ isset($input_attribute['value']) ? $input_attribute['value'] : "" }}"
            type="{{ isset($input_attribute['type']) ? $input_attribute['type'] : "" }}"
            placeholder="{{ $placeholder }}"
    >
    @if(isset($help_text))
        <span class="text-muted">{{ $help_text ?? "" }}</span>
    @endif
    @error($name)
    {!! errMsg($message) !!}
    @enderror
</div>
