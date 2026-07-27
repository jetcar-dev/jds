@props([
    'orientation' => 'horizontal',
])

@php
    $orientation = in_array($orientation, ['horizontal', 'vertical'])
        ? $orientation
        : 'horizontal';
@endphp

<div
    role="group"
    data-slot="button-group"
    data-orientation="{{ $orientation }}"
    {{ $attributes->class('app-button-group') }}
>
    {{ $slot }}
</div>
