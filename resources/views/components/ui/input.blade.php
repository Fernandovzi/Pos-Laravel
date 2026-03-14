@props([
    'label',
    'name',
    'type' => 'text',
    'value' => null,
    'required' => false,
])

<div class="ui-field">
    <label class="ui-field__label" for="{{ $name }}">{{ $label }} @if($required)<span class="text-danger">*</span>@endif</label>
    <input
        id="{{ $name }}"
        name="{{ $name }}"
        type="{{ $type }}"
        value="{{ old($name, $value) }}"
        @required($required)
        {{ $attributes->merge(['class' => 'ui-input ' . ($errors->has($name) ? 'is-invalid' : '')]) }}
    >
    @error($name)
        <small class="invalid-feedback d-block">{{ $message }}</small>
    @enderror
</div>
