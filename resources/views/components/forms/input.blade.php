@props([
'labelText' => null,
'id',
'required' => false,
'defaultValue' => null,
'type' => null
])

<div class="form-field"> 
    <label for="{{$id}}" class="form-label form-label-modern">
        {{$labelText ?? ucfirst($id) }}
        <span class="text-danger">{{ $required ? '*' : '' }}</span>
    </label>
    <input
        {{$required ? 'required' : ''}}
        type="{{ $type ? $type : 'text'}}"
        @if ($type == 'number')
            step="0.1"
        @endif
        name="{{$id}}"
        id="{{$id}}"
        class="form-control @error($id) is-invalid @enderror"
        value="{{old($id,$defaultValue)}}">
    @error($id)
    <small class="invalid-feedback d-block">{{$message}}</small>
    @enderror
</div>
