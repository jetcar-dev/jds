@props([
    'placement' => 'bottom-start',
    'offset' => 8,
    'disabled' => false,
    'triggerScaleOnOpen' => true,
    'disableAnimation' => false,
])
@php
    $placements = ['top', 'top-start', 'top-end', 'bottom', 'bottom-start', 'bottom-end', 'left', 'left-start', 'left-end', 'right', 'right-start', 'right-end'];
    $placement = in_array($placement, $placements, true) ? $placement : 'bottom-start';
@endphp

<div
    data-slot="dropdown"
    data-ui-component="dropdown"
    data-open="false"
    data-placement="{{ $placement }}"
    data-offset="{{ max(0, (int) $offset) }}"
    data-disabled="{{ $disabled ? 'true' : 'false' }}"
    data-trigger-scale-on-open="{{ $triggerScaleOnOpen ? 'true' : 'false' }}"
    data-disable-animation="{{ $disableAnimation ? 'true' : 'false' }}"
    {{ $attributes->class('app-dropdown') }}
>{{ $slot }}</div>
