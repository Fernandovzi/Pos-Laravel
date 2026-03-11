@props([
'labelText' => null,
'id',
'required' => false,
'defaultValue' => null
])

<div class="form-field">
    <label for="{{$id}}" class="form-label form-label-modern">
        {{$labelText ?? ucfirst($id) }}
        <span class="text-danger">{{ $required ? '*' : '' }}</span>
    </label>
    <textarea
        {{$required ? 'required' : ''}}
        rows="3"
        name="{{$id}}"
        id="{{$id}}"
        class="form-control @error($id) is-invalid @enderror">{{old($id,$defaultValue)}}</textarea>
    @error($id)
    <small class="invalid-feedback d-block">{{$message}}</small>
    @enderror
</div>
