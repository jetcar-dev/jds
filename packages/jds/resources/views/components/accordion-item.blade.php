@props([
    'value' => null,
    'title' => null,
    'subtitle' => null,
    'open' => false,
    'disabled' => false,
    'compact' => null,
    'hideIndicator' => null,
    'disableAnimation' => null,
    'disableIndicatorAnimation' => null,
    'keepContentMounted' => null,
    'headingTag' => 'h2',
])
@php
    $value = $value ?? uniqid('accordion-');
    $contentId = 'accordion-content-' . uniqid();
    $triggerId = 'accordion-trigger-' . uniqid();
    $headingTag = in_array($headingTag, ['h2', 'h3', 'h4', 'h5', 'h6', 'div'], true) ? $headingTag : 'h2';
    $inherit = fn ($value) => $value === null ? 'inherit' : ($value ? 'true' : 'false');
@endphp

<div
    data-slot="base"
    data-ui-part="accordion-item"
    data-value="{{ $value }}"
    data-open="{{ $open ? 'true' : 'false' }}"
    data-disabled="{{ $disabled ? 'true' : 'false' }}"
    data-item-disabled="{{ $disabled ? 'true' : 'false' }}"
    data-compact="{{ $inherit($compact) }}"
    data-hide-indicator="{{ $inherit($hideIndicator) }}"
    data-disable-animation="{{ $inherit($disableAnimation) }}"
    data-disable-indicator-animation="{{ $inherit($disableIndicatorAnimation) }}"
    data-keep-content-mounted="{{ $inherit($keepContentMounted) }}"
    @if($title !== null) aria-label="{{ $title }}" @endif
    {{ $attributes->class('app-accordion-item') }}
>
    <{{ $headingTag }} data-slot="heading" data-open="{{ $open ? 'true' : 'false' }}" data-disabled="{{ $disabled ? 'true' : 'false' }}" class="app-accordion-heading">
        <button
            id="{{ $triggerId }}"
            type="button"
            data-slot="trigger"
            data-open="{{ $open ? 'true' : 'false' }}"
            data-disabled="{{ $disabled ? 'true' : 'false' }}"
            class="app-accordion-trigger"
            aria-expanded="{{ $open ? 'true' : 'false' }}"
            aria-controls="{{ $contentId }}"
            @disabled($disabled)
        >
            @isset($startContent)
                <div data-slot="startContent" class="app-accordion-start-content">{{ $startContent }}</div>
            @endisset

            <div data-slot="titleWrapper" class="app-accordion-title-wrapper">
                @if($title !== null)<span data-slot="title" data-open="{{ $open ? 'true' : 'false' }}" data-disabled="{{ $disabled ? 'true' : 'false' }}" class="app-accordion-title">{{ $title }}</span>@endif
                @if($subtitle !== null)<span data-slot="subtitle" data-open="{{ $open ? 'true' : 'false' }}" data-disabled="{{ $disabled ? 'true' : 'false' }}" class="app-accordion-subtitle">{{ $subtitle }}</span>@endif
            </div>

            <span data-slot="indicator" data-open="{{ $open ? 'true' : 'false' }}" data-disabled="{{ $disabled ? 'true' : 'false' }}" class="app-accordion-indicator" aria-hidden="true">
                @isset($indicator)
                    {{ $indicator }}
                @else
                    <x-icon name="solar:alt-arrow-left-linear" />
                @endisset
            </span>
        </button>
    </{{ $headingTag }}>

    <section
        id="{{ $contentId }}"
        data-slot="content-motion"
        data-open="{{ $open ? 'true' : 'false' }}"
        class="app-accordion-content-motion"
        @if(!$open && $keepContentMounted !== true) hidden @endif
    >
        <div
            data-slot="content"
            data-open="{{ $open ? 'true' : 'false' }}"
            data-disabled="{{ $disabled ? 'true' : 'false' }}"
            class="app-accordion-content"
            role="region"
            aria-labelledby="{{ $triggerId }}"
        >{{ $slot }}</div>
    </section>
</div>
