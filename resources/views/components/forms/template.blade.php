@props([
'action',
'method',
'patch' => false,
'file' => false
])

<x-ui.card class="form-card" :padding="false">
    <form action="{{ $action }}" method="{{ $method }}" @if($file) enctype="multipart/form-data" @endif>

        @if ($patch)
            @method('PATCH')
        @endif

        @csrf

        @if (isset($header))
            <div class="card-header">
                {{$header}}
            </div>
        @endif

        <div class="card-body form-card-body">
            {{$slot}}
        </div>

        <div class="card-footer form-card-footer">
            {{$footer}}
        </div>

    </form>
</x-ui.card>
