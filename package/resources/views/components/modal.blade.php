@props([
    'id' => null,
    'size' => 'md',
    'radius' => 'lg',
    'shadow' => 'sm',
    'backdrop' => 'opaque',
    'placement' => 'auto',
    'dismissable' => true,
    'keyboardDismissDisabled' => false,
    'scrollBehavior' => 'normal',
    'hideCloseButton' => false,
    'disableAnimation' => false,
    'defaultOpen' => false,
    'shouldBlockScroll' => true,
    'portalTarget' => null,
    'motion' => null,
    'draggable' => false,
    'draggableOverflow' => false,
])
@php
    $size = in_array($size, ['xs', 'sm', 'md', 'lg', 'xl', '2xl', '3xl', '4xl', '5xl', 'full'], true) ? $size : 'md';
    $radius = in_array($radius, ['none', 'sm', 'md', 'lg'], true) ? $radius : 'lg';
    $shadow = in_array($shadow, ['none', 'sm', 'md', 'lg'], true) ? $shadow : 'sm';
    $backdrop = in_array($backdrop, ['transparent', 'opaque', 'blur'], true) ? $backdrop : 'opaque';
    $placement = in_array($placement, ['auto', 'top', 'bottom', 'center', 'top-center', 'bottom-center'], true) ? $placement : 'auto';
    $scrollBehavior = in_array($scrollBehavior, ['normal', 'inside', 'outside'], true) ? $scrollBehavior : 'normal';
    $motion = is_array($motion) ? json_encode($motion, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) : null;
@endphp
<div
    @if($id) id="{{ $id }}" @endif
    data-slot="modal"
    data-size="{{ $size }}"
    data-radius="{{ $radius }}"
    data-shadow="{{ $shadow }}"
    data-backdrop="{{ $backdrop }}"
    data-placement="{{ $placement }}"
    data-dismissable="{{ $dismissable ? 'true' : 'false' }}"
    data-keyboard-dismiss-disabled="{{ $keyboardDismissDisabled ? 'true' : 'false' }}"
    data-scroll-behavior="{{ $scrollBehavior }}"
    data-hide-close-button="{{ $hideCloseButton ? 'true' : 'false' }}"
    data-disable-animation="{{ $disableAnimation ? 'true' : 'false' }}"
    data-default-open="{{ $defaultOpen ? 'true' : 'false' }}"
    data-should-block-scroll="{{ $shouldBlockScroll ? 'true' : 'false' }}"
    @if($portalTarget) data-portal-target="{{ $portalTarget }}" @endif
    @if($motion) data-motion='{{ $motion }}' @endif
    data-draggable="{{ $draggable ? 'true' : 'false' }}"
    data-draggable-overflow="{{ $draggableOverflow ? 'true' : 'false' }}"
    data-open="false"
    {{ $attributes->class('app-modal') }}
>{{ $slot }}</div>
