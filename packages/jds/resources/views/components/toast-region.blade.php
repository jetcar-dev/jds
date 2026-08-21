@props([
    'placement' => 'bottom-right',
    'maxVisibleToasts' => 3,
    'toastOffset' => 0,
    'disableAnimation' => false,
])
@php
    $placement = in_array($placement, ['top-left', 'top-center', 'top-right', 'bottom-left', 'bottom-center', 'bottom-right'], true) ? $placement : 'bottom-right';
@endphp
<div
    data-slot="toast-region"
    data-ui-component="toast-region"
    class="app-toast-region"
    data-placement="{{ $placement }}"
    data-max-visible-toasts="{{ max(1, (int) $maxVisibleToasts) }}"
    data-toast-offset="{{ (float) $toastOffset }}"
    data-disable-animation="{{ $disableAnimation ? 'true' : 'false' }}"
    aria-live="polite"
    aria-relevant="additions removals"
    style="--app-toast-offset: {{ (float) $toastOffset }}px"
    {{ $attributes }}
>{{ $slot }}</div>
