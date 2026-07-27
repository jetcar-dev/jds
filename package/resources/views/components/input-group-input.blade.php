@props([
    'type' => 'text',
])

<input
    type="{{ $type }}"
    data-slot="input-group-control"
    {{ $attributes->class('app-input-group-input') }}
>
