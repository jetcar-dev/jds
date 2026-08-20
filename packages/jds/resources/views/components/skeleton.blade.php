@props([
    'loaded' => false,
    'radius' => 'none',
    'disableAnimation' => false,
])
@php
    $radius = in_array($radius, ['none', 'sm', 'md', 'lg', 'full'], true) ? $radius : 'md';
@endphp
<div
    data-slot="base"
    data-ui-component="skeleton"
    data-loaded="{{ $loaded ? 'true' : 'false' }}"
    data-disable-animation="{{ $disableAnimation ? 'true' : 'false' }}"
    aria-busy="{{ $loaded ? 'false' : 'true' }}"
    {{ $attributes->class("app-skeleton app-radius-$radius") }}
>
    @if(!$slot->isEmpty())
        <div data-slot="content" class="app-skeleton-content" @if(!$loaded) aria-hidden="true" inert @endif>{{ $slot }}</div>
    @endif
</div>
