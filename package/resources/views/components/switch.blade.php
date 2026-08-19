@props([
    'name' => null,
    'id' => null,
    'value' => '1',
    'checked' => false,
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'color' => 'primary',
    'size' => 'md',
    'label' => null,
    'description' => null,
    'disableAnimation' => false,
])
@php
    $color = in_array($color, ['default', 'primary', 'secondary', 'success', 'warning', 'danger'], true)
        ? $color
        : 'primary';
    $size = in_array($size, ['sm', 'md', 'lg'], true) ? $size : 'md';
    $hasLabel = $label !== null || trim((string) $slot) !== '';
    $ariaLabel = $attributes->get('aria-label');
    $rootAttributes = $attributes->except(['aria-label', 'id', 'required']);
@endphp

<label
    data-ui-component="switch"
    data-ui-interactive
    data-slot="base"
    data-selected="{{ $checked ? 'true' : 'false' }}"
    data-disabled="{{ $disabled ? 'true' : 'false' }}"
    data-readonly="{{ $readonly ? 'true' : 'false' }}"
    data-color="{{ $color }}"
    data-size="{{ $size }}"
    data-disable-animation="{{ $disableAnimation ? 'true' : 'false' }}"
    {{ $rootAttributes->class("app-switch-base app-color-$color") }}
>
    <input
        data-slot="hidden-input"
        class="app-sr-only"
        type="checkbox"
        role="switch"
        @if($id) id="{{ $id }}" @endif
        @if($name) name="{{ $name }}" @endif
        value="{{ $value }}"
        @checked($checked)
        @if($required) required @endif
        @disabled($disabled)
        @if($readonly) aria-readonly="true" @endif
        @if($ariaLabel) aria-label="{{ $ariaLabel }}" @endif
    >

    <span data-slot="wrapper" class="app-switch-wrapper" aria-hidden="true">
        @isset($startContent)
            <span data-slot="start-content" class="app-switch-start-content">{{ $startContent }}</span>
        @endisset

        <span data-slot="thumb" class="app-switch-thumb">
            @if(isset($thumbIconOn) || isset($thumbIconOff))
                <span data-slot="thumb-icon" class="app-switch-thumb-icon">
                    @isset($thumbIconOn)
                        <span data-slot="thumb-icon-selected" class="app-switch-thumb-icon-selected">{{ $thumbIconOn }}</span>
                    @endisset
                    @isset($thumbIconOff)
                        <span data-slot="thumb-icon-unselected" class="app-switch-thumb-icon-unselected">{{ $thumbIconOff }}</span>
                    @endisset
                </span>
            @elseif(isset($thumbIcon))
                <span data-slot="thumb-icon" class="app-switch-thumb-icon">{{ $thumbIcon }}</span>
            @endif
        </span>

        @isset($endContent)
            <span data-slot="end-content" class="app-switch-end-content">{{ $endContent }}</span>
        @endisset
    </span>

    @if($hasLabel || $description)
        <span data-slot="label-wrapper" class="app-switch-copy">
            @if($hasLabel)<span data-slot="label" class="app-switch-label">{{ $label ?? $slot }}</span>@endif
            @if($description)<span data-slot="description" class="app-switch-description">{{ $description }}</span>@endif
        </span>
    @endif
</label>
