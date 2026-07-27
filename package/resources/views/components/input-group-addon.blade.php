@props([
    'align' => 'inline-start',
])

@php
    $align = in_array($align, ['inline-start', 'inline-end', 'block-start', 'block-end'])
        ? $align
        : 'inline-start';
@endphp

<div
    data-slot="input-group-addon"
    data-align="{{ $align }}"
    {{ $attributes->class('app-input-group-addon') }}
>
    {{ $slot }}
</div>
