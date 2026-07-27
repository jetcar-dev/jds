@props([
    'align' => 'start',
    'side' => 'bottom',
    'sideOffset' => 4,
])

@php
    $align = in_array($align, ['start', 'end'], true) ? $align : 'start';
    $side = in_array($side, ['top', 'bottom'], true) ? $side : 'bottom';
@endphp

<div
    data-slot="dropdown-menu-content"
    data-side="{{ $side }}"
    data-align="{{ $align }}"
    data-side-offset="{{ max(0, (int) $sideOffset) }}"
    role="menu"
    aria-orientation="vertical"
    tabindex="-1"
    hidden
    {{ $attributes->class('app-dropdown-menu') }}
>
    {{ $slot }}
</div>
