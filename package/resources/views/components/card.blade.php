@props([
    'radius' => 'lg',
    'shadow' => 'md',
    'pressable' => false,
    'hoverable' => false,
    'blurred' => false,
    'footerBlurred' => false,
    'disabled' => false,
    'fullWidth' => false,
    'disableAnimation' => false,
    'disableRipple' => false,
    'allowTextSelectionOnPress' => false,
])
@php
    $radius = in_array($radius, ['none', 'sm', 'md', 'lg'], true) ? $radius : 'lg';
    $shadow = in_array($shadow, ['none', 'sm', 'md', 'lg'], true) ? $shadow : 'md';
    $tag = $pressable ? 'button' : 'div';
@endphp

<{{ $tag }}
    data-slot="base"
    data-ui-component="card"
    data-radius="{{ $radius }}"
    data-shadow="{{ $shadow }}"
    data-pressable="{{ $pressable ? 'true' : 'false' }}"
    data-hoverable="{{ $hoverable ? 'true' : 'false' }}"
    data-blurred="{{ $blurred ? 'true' : 'false' }}"
    data-footer-blurred="{{ $footerBlurred ? 'true' : 'false' }}"
    data-disabled="{{ $disabled ? 'true' : 'false' }}"
    data-full-width="{{ $fullWidth ? 'true' : 'false' }}"
    data-disable-animation="{{ $disableAnimation ? 'true' : 'false' }}"
    data-disable-ripple="{{ $disableRipple ? 'true' : 'false' }}"
    data-allow-text-selection="{{ $allowTextSelectionOnPress ? 'true' : 'false' }}"
    @if($pressable) type="button" @endif
    @if($pressable && $disabled) disabled @elseif($disabled) aria-disabled="true" @endif
    {{ $attributes->class("app-card app-radius-$radius") }}
>{{ $slot }}</{{ $tag }}>
