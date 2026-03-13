@props([
    'title' => null,
])

<x-ui.card :title="$title" class="ui-table-card" :padding="false">
    <div class="ui-table-responsive">
        <table {{ $attributes->merge(['class' => 'table ui-table']) }}>
            {{ $slot }}
        </table>
    </div>
</x-ui.card>
