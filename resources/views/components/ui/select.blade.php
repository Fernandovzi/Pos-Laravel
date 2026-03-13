@props([
    'label',
    'name',
    'options' => [],
    'value' => null,
    'required' => false,
])

<div class="ui-field">
    <label class="ui-field__label" for="{{ $name }}">{{ $label }} @if($required)<span class="text-danger">*</span>@endif</label>
    <select
        id="{{ $name }}"
        name="{{ $name }}"
        @required($required)
        {{ $attributes->merge(['class' => 'ui-select ' . ($errors->has($name) ? 'is-invalid' : '')]) }}
    >
        {{ $slot }}
        @foreach($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" @selected(old($name, $value) == $optionValue)>{{ $optionLabel }}</option>
        @endforeach
    </select>
    @error($name)
        <small class="invalid-feedback d-block">{{ $message }}</small>
    @enderror
</div>
