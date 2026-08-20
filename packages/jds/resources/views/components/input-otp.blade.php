@props([
    'id' => null,
    'name' => null,
    'value' => '',
    'length' => 4,
    'allowedKeys' => '^[0-9]*$',
    'type' => 'text',
    'variant' => 'flat',
    'color' => 'default',
    'size' => 'md',
    'radius' => 'md',
    'description' => null,
    'errorMessage' => null,
    'disabled' => false,
    'readOnly' => false,
    'required' => false,
    'invalid' => false,
    'fullWidth' => false,
    'autoFocus' => false,
    'textAlign' => 'center',
    'disableAnimation' => false,
    'placeholder' => null,
    'pushPasswordManagerStrategy' => 'increase-width',
])
@php
    $variant = in_array($variant, ['flat', 'bordered', 'faded', 'underlined'], true) ? $variant : 'flat';
    $color = in_array($color, ['default', 'primary', 'secondary', 'success', 'warning', 'danger'], true) ? $color : 'default';
    $size = in_array($size, ['sm', 'md', 'lg'], true) ? $size : 'md';
    $radius = in_array($radius, ['none', 'sm', 'md', 'lg', 'full'], true) ? $radius : 'md';
    $type = $type === 'password' ? 'password' : 'text';
    $textAlign = in_array($textAlign, ['left', 'center', 'right'], true) ? $textAlign : 'center';
    $length = max(1, (int) $length);
    $value = mb_substr((string) $value, 0, $length);
    $id = $id ?: 'input-otp-'.uniqid();
    $descriptionId = $id.'-description';
    $errorId = $id.'-error';
    $filled = mb_strlen($value) === $length;
    $ariaLabel = $attributes->get('aria-label', '인증 코드');
    $inputMode = $attributes->get('inputmode', $allowedKeys === '^[0-9]*$' ? 'numeric' : 'text');
    $autocomplete = $attributes->get('autocomplete', 'one-time-code');
    $pushPasswordManagerStrategy = in_array($pushPasswordManagerStrategy, ['increase-width', 'none'], true) ? $pushPasswordManagerStrategy : 'increase-width';
    $placeholder = $placeholder === null ? '' : mb_substr((string) $placeholder, 0, $length);
    $fieldAttributes = $attributes->except(['class', 'aria-label', 'inputmode', 'autocomplete', 'minlength', 'maxlength', 'pattern', 'spellcheck']);
@endphp

<div
    data-slot="base"
    data-ui-component="input-otp"
    data-variant="{{ $variant }}"
    data-color="{{ $color }}"
    data-size="{{ $size }}"
    data-radius="{{ $radius }}"
    data-text-align="{{ $textAlign }}"
    data-allowed-keys="{{ $allowedKeys }}"
    data-filled="{{ $filled ? 'true' : 'false' }}"
    data-disabled="{{ $disabled ? 'true' : 'false' }}"
    data-readonly="{{ $readOnly ? 'true' : 'false' }}"
    data-required="{{ $required ? 'true' : 'false' }}"
    data-invalid="{{ $invalid ? 'true' : 'false' }}"
    data-full-width="{{ $fullWidth ? 'true' : 'false' }}"
    data-disable-animation="{{ $disableAnimation ? 'true' : 'false' }}"
    data-placeholder="{{ $placeholder }}"
    data-password-manager-strategy="{{ $pushPasswordManagerStrategy }}"
    aria-label="{{ $ariaLabel }}"
    class="app-input-otp app-color-{{ $color }} app-size-{{ $size }} app-radius-{{ $radius }} {{ $attributes->get('class') }}"
>
    <div data-slot="wrapper" class="app-input-otp-wrapper">
        <input
            data-slot="input"
            data-otp-input
            id="{{ $id }}"
            class="app-input-otp-input"
            @if($name) name="{{ $name }}" @endif
            type="{{ $type }}"
            value="{{ $value }}"
            minlength="{{ $length }}"
            maxlength="{{ $length }}"
            pattern="{{ $allowedKeys }}"
            inputmode="{{ $inputMode }}"
            autocomplete="{{ $autocomplete }}"
            spellcheck="false"
            aria-label="{{ $ariaLabel }}"
            @if($description && !$invalid) aria-describedby="{{ $descriptionId }}" @endif
            @if($errorMessage && $invalid) aria-errormessage="{{ $errorId }}" @endif
            @if($invalid) aria-invalid="true" @endif
            aria-required="{{ $required ? 'true' : 'false' }}"
            @disabled($disabled)
            @readonly($readOnly)
            @required($required)
            @if($autoFocus) autofocus @endif
            {{ $fieldAttributes }}
        >

        <div data-slot="segment-wrapper" class="app-input-otp-segments" aria-label="{{ $ariaLabel }}" aria-hidden="true">
            @for($index = 0; $index < $length; $index++)
                @php($character = mb_substr($value, $index, 1))
                <div
                    data-slot="segment"
                    data-otp-segment
                    data-index="{{ $index }}"
                    data-active="false"
                    data-focus="false"
                    data-focus-visible="false"
                    data-has-value="{{ $character !== '' ? 'true' : 'false' }}"
                    class="app-input-otp-segment"
                    role="presentation"
                >
                    <span data-slot="segment-value" class="app-input-otp-value" @if($character === '') hidden @endif>
                        @if($type === 'password' && $character !== '')
                            <span data-slot="password-char" class="app-input-otp-password-char"></span>
                        @else
                            {{ $character }}
                        @endif
                    </span>
                    <span data-slot="placeholder-char" class="app-input-otp-placeholder" @if($character !== '' || mb_substr($placeholder, $index, 1) === '') hidden @endif>{{ mb_substr($placeholder, $index, 1) }}</span>
                    <span data-slot="caret" class="app-input-otp-caret" hidden></span>
                </div>
            @endfor
        </div>
    </div>

    @if($description || $errorMessage)
        <div data-slot="helper-wrapper" class="app-input-otp-helper">
            @if($description)
                <div id="{{ $descriptionId }}" data-slot="description" class="app-input-otp-description" @if($invalid) hidden @endif>{{ $description }}</div>
            @endif
            @if($errorMessage)
                <div id="{{ $errorId }}" data-slot="error-message" class="app-input-otp-error" @if(!$invalid) hidden @endif>{{ $errorMessage }}</div>
            @endif
        </div>
    @endif
</div>
