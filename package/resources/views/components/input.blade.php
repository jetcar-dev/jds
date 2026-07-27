@props([
    'type' => 'text',
    'size' => 'default',
    'variant' => 'outline',
    'toggle' => null,
    'color' => null,
    'fullWidth' => false,
])

@php
    $sizes = [
        'sm' => 'app-input-sm',
        'default' => 'app-input-default',
        'lg' => 'app-input-lg',
    ];

    $variant = in_array($variant, ['outline', 'flat', 'underlined', 'faded', 'ghost'], true) ? $variant : 'outline';
    $classes = 'app-input '
        . ($sizes[$size] ?? $sizes['default']) . ' '
        . 'app-input-' . $variant
        . ($fullWidth ? ' app-input-full' : '');
    $isPassword = $type === 'password' && $toggle !== false;
    $hasLeading = isset($leading) && $leading->isNotEmpty();
    $hasTrailing = isset($trailing) && $trailing->isNotEmpty();
    $wrap = $isPassword || $hasLeading || $hasTrailing;
    $padding = ($hasLeading ? ' app-input-leading-padding' : '')
        . (($isPassword || $hasTrailing) ? ' app-input-trailing-padding' : '');

    $colorStyle = $color
        ? "--app-input-ring: {$color}; --app-input-primary: {$color}; --app-input-primary-foreground: #ffffff;"
        : '';
    $userStyle = (string) $attributes->get('style', '');
    $fieldStyle = trim($colorStyle . ($colorStyle && $userStyle ? ' ' : '') . $userStyle);
    $attributes = $attributes->except('style');
@endphp

@if($wrap)
    <div
        data-slot="input-wrapper"
        data-variant="{{ $variant }}"
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
        @if($fieldStyle) style="{{ $fieldStyle }}" @endif
        {{ $attributes->class($classes) }}
    />
@endif
