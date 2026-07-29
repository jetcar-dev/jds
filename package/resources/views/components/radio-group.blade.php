@props([
    'name' => null,
    'value' => null,
    'orientation' => 'vertical',
    'variant' => 'default',
    'showIndicator' => true,
])

@php
    $orientation = $orientation === 'horizontal' ? 'horizontal' : 'vertical';
    $variant = $variant === 'box' ? 'box' : 'default';
@endphp

<div
    data-slot="radio-group"
    data-value="{{ $value }}"
    data-has-value="{{ $value === null ? 'false' : 'true' }}"
    data-variant="{{ $variant }}"
    data-show-indicator="{{ $showIndicator ? 'true' : 'false' }}"
    role="radiogroup"
    {{ $attributes->class(['app-radio-group', 'app-radio-group-'.$variant, 'app-radio-group-horizontal' => $orientation === 'horizontal']) }}
>
    @if($name)
        <input
            type="hidden"
            name="{{ $name }}"
            value="{{ $value }}"
            data-radio-group-input
        >
    @endif

    {{ $slot }}
</div>
