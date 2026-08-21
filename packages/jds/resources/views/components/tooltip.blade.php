@props([
    'content' => null,
    'placement' => 'top',
    'color' => 'default',
    'size' => 'md',
    'radius' => 'md',
    'shadow' => 'sm',
    'delay' => 0,
    'closeDelay' => 500,
    'offset' => 7,
    'crossOffset' => 0,
    'containerPadding' => 12,
    'trigger' => null,
    'motion' => 'scale',
    'disabled' => false,
    'showArrow' => false,
    'defaultOpen' => false,
    'open' => null,
    'shouldFlip' => true,
    'dismissable' => false,
    'keyboardDismissDisabled' => false,
    'shouldCloseOnBlur' => true,
    'triggerScaleOnOpen' => true,
    'disableAnimation' => false,
])
@php
    $tooltipId = 'app-tooltip-' . substr(md5(uniqid('', true)), 0, 10);
    $isControlled = !is_null($open);
    $isOpen = $isControlled ? (bool) $open : (bool) $defaultOpen;
    $tooltipContent = $content ?? ($tooltip ?? '');
@endphp
<span
    data-slot="tooltip-root"
    data-ui-component="tooltip"
    data-open="{{ $isOpen ? 'true' : 'false' }}"
    data-controlled="{{ $isControlled ? 'true' : 'false' }}"
    data-disabled="{{ $disabled ? 'true' : 'false' }}"
    data-trigger="{{ $trigger ?: 'hover-focus' }}"
    data-delay="{{ $delay }}"
    data-close-delay="{{ $closeDelay }}"
    data-dismissable="{{ $dismissable ? 'true' : 'false' }}"
    data-keyboard-dismiss-disabled="{{ $keyboardDismissDisabled ? 'true' : 'false' }}"
    data-should-close-on-blur="{{ $shouldCloseOnBlur ? 'true' : 'false' }}"
    data-trigger-scale-on-open="{{ $triggerScaleOnOpen ? 'true' : 'false' }}"
    data-disable-animation="{{ $disableAnimation ? 'true' : 'false' }}"
    {{ $attributes->class('app-tooltip-root') }}
>
    <span data-slot="tooltip-trigger" class="app-tooltip-trigger">{{ $slot }}</span>
    <span
        id="{{ $tooltipId }}"
        data-slot="tooltip"
        data-placement="{{ $placement }}"
        data-preferred-placement="{{ $placement }}"
        data-color="{{ $color }}"
        data-size="{{ $size }}"
        data-radius="{{ $radius }}"
        data-shadow="{{ $shadow }}"
        data-offset="{{ $offset }}"
        data-cross-offset="{{ $crossOffset }}"
        data-container-padding="{{ $containerPadding }}"
        data-should-flip="{{ $shouldFlip ? 'true' : 'false' }}"
        data-show-arrow="{{ $showArrow ? 'true' : 'false' }}"
        data-motion="{{ $motion }}"
        data-disable-animation="{{ $disableAnimation ? 'true' : 'false' }}"
        data-state="{{ $isOpen ? 'open' : 'closed' }}"
        class="app-tooltip app-color-{{ $color }} {{ $attributes->get('class') }}"
        role="tooltip"
        @unless($isOpen) hidden @endunless
    >
        @if ($showArrow)
            <span data-slot="arrow" class="app-tooltip-arrow" aria-hidden="true"></span>
        @endif
        <span data-slot="content" class="app-tooltip-content">{{ $tooltipContent }}</span>
    </span>
</span>
