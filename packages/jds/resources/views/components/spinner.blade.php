@props([
    'label' => null,
    'size' => 'md',
    'color' => 'primary',
    'variant' => 'default',
    'labelColor' => 'default',
])

@php
    $visibleLabel = $label ?? (trim((string) $slot) !== '' ? trim((string) $slot) : null);
    $ariaLabel = $attributes->get('aria-label') ?? $visibleLabel ?? 'Loading';
@endphp

<div
    data-ui-component="spinner"
    data-slot="base"
    data-size="{{ $size }}"
    data-color="{{ $color }}"
    data-variant="{{ $variant }}"
    data-label-color="{{ $labelColor }}"
    aria-label="{{ $ariaLabel }}"
    {{ $attributes->except('aria-label')->class(["app-spinner", "app-color-$color"]) }}
>
    @if(in_array($variant, ['wave', 'dots'], true))
        <div data-slot="wrapper" class="app-spinner-wrapper">
            @for($index = 0; $index < 3; $index++)
                <i data-slot="dots" class="app-spinner-dot" style="--spinner-index:{{ $index }}"></i>
            @endfor
        </div>
    @elseif($variant === 'simple')
        <svg data-slot="wrapper" class="app-spinner-wrapper" fill="none" viewBox="0 0 24 24" aria-hidden="true">
            <circle data-slot="circle1" class="app-spinner-circle-1" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
            <path data-slot="circle2" class="app-spinner-circle-2" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" fill="currentColor" />
        </svg>
    @elseif($variant === 'spinner')
        <div data-slot="wrapper" class="app-spinner-wrapper">
            @for($index = 0; $index < 12; $index++)
                <i data-slot="spinnerBars" class="app-spinner-bar" style="--spinner-index:{{ $index }}"></i>
            @endfor
        </div>
    @else
        <div data-slot="wrapper" class="app-spinner-wrapper">
            <i data-slot="circle1" class="app-spinner-circle-1"></i>
            <i data-slot="circle2" class="app-spinner-circle-2"></i>
        </div>
    @endif

    @if($visibleLabel)
        <span data-slot="label" class="app-spinner-label">{{ $visibleLabel }}</span>
    @endif
</div>
