@props([
    'type' => 'text',
    'size' => 'md',
    'variant' => 'flat',
    'toggle' => null,
    'color' => 'default',
    'fullWidth' => false,
    'mask' => null,
])

@php
    $sizes = [
        'xs' => 'app-input-xs',
        'sm' => 'app-input-sm',
        'md' => 'app-input-default',
        'lg' => 'app-input-lg',
        'xl' => 'app-input-xl',
    ];

    $variant = $variant === 'outline' ? 'bordered' : $variant;
    $variant = in_array($variant, ['solid', 'faded', 'bordered', 'light', 'flat', 'ghost', 'shadow'], true) ? $variant : 'flat';
    $color = in_array($color, ['default', 'primary', 'secondary', 'success', 'warning', 'danger'], true) ? $color : 'default';
    $size = in_array($size, ['xs', 'sm', 'md', 'lg', 'xl'], true) ? $size : 'md';
    $classes = 'app-input '
        . $sizes[$size] . ' '
        . 'app-input-' . $variant
        . ' app-color-' . $color
        . ($fullWidth ? ' app-input-full' : '');
    $isPassword = $type === 'password' && $toggle !== false;
    $hasLeading = isset($leading) && $leading->isNotEmpty();
    $hasTrailing = isset($trailing) && $trailing->isNotEmpty();
    $wrap = $isPassword || $hasLeading || $hasTrailing;
    $padding = ($hasLeading ? ' app-input-leading-padding' : '')
        . (($isPassword || $hasTrailing) ? ' app-input-trailing-padding' : '');

    $userStyle = (string) $attributes->get('style', '');
    $fieldStyle = trim($userStyle);
    $attributes = $attributes->except('style');
@endphp

@if($wrap)
    <div
        data-slot="input-wrapper"
        data-variant="{{ $variant }}"
        data-color="{{ $color }}"
        @if($isPassword) data-password-toggle="true" @endif
        {{ $attributes->only('class')->class(['app-input-wrapper', 'app-input-wrapper-full' => $fullWidth]) }}
    >
        @if($hasLeading)
            <span data-slot="input-leading" class="app-input-leading">
                {{ $leading }}
            </span>
        @endif

        <input
            type="{{ $type }}"
            data-slot="input"
            data-size="{{ $size }}"
            data-color="{{ $color }}"
            @if($mask) data-mask="{{ $mask }}" @endif
            @if($fieldStyle) style="{{ $fieldStyle }}" @endif
            {{ $attributes->except('class')->class($classes . $padding) }}
        />

        @if($isPassword)
            <button
                type="button"
                data-slot="input-password-toggle"
                aria-label="비밀번호 보기"
                aria-pressed="false"
                class="app-input-password-toggle"
            >
                <x-icon name="eye-linear" data-password-hidden />
                <x-icon name="eye-closed-linear" data-password-visible hidden />
            </button>
        @elseif($hasTrailing)
            <span data-slot="input-trailing" class="app-input-trailing">
                {{ $trailing }}
            </span>
        @endif
    </div>
@else
    <input
        type="{{ $type }}"
        data-slot="input"
        data-size="{{ $size }}"
        data-color="{{ $color }}"
        @if($mask) data-mask="{{ $mask }}" @endif
        @if($fieldStyle) style="{{ $fieldStyle }}" @endif
        {{ $attributes->class($classes) }}
    />
@endif
