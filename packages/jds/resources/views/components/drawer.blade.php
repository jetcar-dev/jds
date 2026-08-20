@props([
    'id' => null,
    'placement' => 'right',
    'size' => 'md',
    'radius' => 'lg',
    'backdrop' => 'opaque',
    'dismissable' => true,
    'keyboardDismissDisabled' => false,
    'scrollBehavior' => 'inside',
    'hideCloseButton' => false,
    'disableAnimation' => false,
    'defaultOpen' => false,
    'shouldBlockScroll' => true,
    'portalTarget' => null,
    'motion' => null,
])
@php
    $placement = in_array($placement, ['left', 'right', 'top', 'bottom'], true) ? $placement : 'right';
    $size = in_array($size, ['xs', 'sm', 'md', 'lg', 'xl', '2xl', '3xl', '4xl', '5xl', 'full'], true) ? $size : 'md';
    $radius = in_array($radius, ['none', 'sm', 'md', 'lg'], true) ? $radius : 'lg';
    $backdrop = in_array($backdrop, ['transparent', 'opaque', 'blur'], true) ? $backdrop : 'opaque';
    $scrollBehavior = in_array($scrollBehavior, ['inside', 'outside'], true) ? $scrollBehavior : 'inside';
    $motion = is_array($motion) ? json_encode($motion, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) : null;
@endphp
<div
    @if($id) id="{{ $id }}" @endif
    data-slot="drawer"
    data-placement="{{ $placement }}"
    data-size="{{ $size }}"
    data-radius="{{ $radius }}"
    data-backdrop="{{ $backdrop }}"
    data-dismissable="{{ $dismissable ? 'true' : 'false' }}"
    data-keyboard-dismiss-disabled="{{ $keyboardDismissDisabled ? 'true' : 'false' }}"
    data-scroll-behavior="{{ $scrollBehavior }}"
    data-hide-close-button="{{ $hideCloseButton ? 'true' : 'false' }}"
    data-disable-animation="{{ $disableAnimation ? 'true' : 'false' }}"
    data-default-open="{{ $defaultOpen ? 'true' : 'false' }}"
    data-should-block-scroll="{{ $shouldBlockScroll ? 'true' : 'false' }}"
    @if($portalTarget) data-portal-target="{{ $portalTarget }}" @endif
    @if($motion) data-motion='{{ $motion }}' @endif
    data-open="false"
    {{ $attributes->class('app-drawer') }}
>{{ $slot }}</div>
