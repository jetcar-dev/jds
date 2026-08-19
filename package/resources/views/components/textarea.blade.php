@props([
    'id' => null,
    'name' => null,
    'value' => '',
    'label' => null,
    'placeholder' => null,
    'description' => null,
    'errorMessage' => null,
    'variant' => 'flat',
    'color' => 'default',
    'size' => 'md',
    'radius' => 'md',
    'labelPlacement' => 'inside',
    'disabled' => false,
    'readOnly' => false,
    'required' => false,
    'invalid' => false,
    'clearable' => false,
    'minRows' => 3,
    'maxRows' => 8,
    'disableAutosize' => false,
    'disableAnimation' => false,
    'fullWidth' => true,
])
@php
    $variant = in_array($variant, ['flat', 'bordered', 'faded', 'underlined'], true) ? $variant : 'flat';
    $color = in_array($color, ['default', 'primary', 'secondary', 'success', 'warning', 'danger'], true) ? $color : 'default';
    $size = in_array($size, ['sm', 'md', 'lg'], true) ? $size : 'md';
    $radius = in_array($radius, ['none', 'sm', 'md', 'lg', 'full'], true) ? $radius : 'md';
    $labelPlacement = in_array($labelPlacement, ['inside', 'outside', 'outside-left'], true) ? $labelPlacement : 'inside';
    $minRows = max(1, (int) $minRows);
    $maxRows = max($minRows, (int) $maxRows);
    $id = $id ?: 'textarea-'.uniqid();
    $labelId = $id.'-label';
    $descriptionId = $id.'-description';
    $errorId = $id.'-error';
    $hasStartContent = isset($startContent);
    $hasEndContent = isset($endContent);
    $filled = (string) $value !== '' || (bool) $placeholder || $hasStartContent;
    $fieldAttributes = $attributes->except(['class']);
@endphp

<div
    data-slot="base"
    data-ui-component="textarea"
    data-variant="{{ $variant }}"
    data-color="{{ $color }}"
    data-size="{{ $size }}"
    data-radius="{{ $radius }}"
    data-label-placement="{{ $labelPlacement }}"
    data-filled="{{ $filled ? 'true' : 'false' }}"
    data-has-value="{{ (string) $value !== '' ? 'true' : 'false' }}"
    data-has-label="{{ $label ? 'true' : 'false' }}"
    data-has-start-content="{{ $hasStartContent ? 'true' : 'false' }}"
    data-has-end-content="{{ $hasEndContent ? 'true' : 'false' }}"
    data-has-helper="{{ ($description || $errorMessage) ? 'true' : 'false' }}"
    data-disabled="{{ $disabled ? 'true' : 'false' }}"
    data-readonly="{{ $readOnly ? 'true' : 'false' }}"
    data-required="{{ $required ? 'true' : 'false' }}"
    data-invalid="{{ $invalid ? 'true' : 'false' }}"
    data-clearable="{{ $clearable ? 'true' : 'false' }}"
    data-disable-autosize="{{ $disableAutosize ? 'true' : 'false' }}"
    data-disable-animation="{{ $disableAnimation ? 'true' : 'false' }}"
    data-min-rows="{{ $minRows }}"
    data-max-rows="{{ $maxRows }}"
    data-full-width="{{ $fullWidth ? 'true' : 'false' }}"
    class="app-textarea-base app-field app-color-{{ $color }} app-size-{{ $size }} app-radius-{{ $radius }}"
>
    @if($label && $labelPlacement !== 'inside')
        <label id="{{ $labelId }}" data-slot="label" for="{{ $id }}" data-required="{{ $required ? 'true' : 'false' }}" class="app-field-label">{{ $label }}</label>
    @endif

    <div
        data-slot="input-wrapper"
        data-variant="{{ $variant }}"
        data-invalid="{{ $invalid ? 'true' : 'false' }}"
        data-disabled="{{ $disabled ? 'true' : 'false' }}"
        data-has-multiple-rows="{{ $minRows > 1 ? 'true' : 'false' }}"
        {{ $attributes->only('class')->class('app-input-wrapper app-textarea-wrapper') }}
    >
        @if($label && $labelPlacement === 'inside')
            <label id="{{ $labelId }}" data-slot="label" for="{{ $id }}" data-required="{{ $required ? 'true' : 'false' }}" class="app-textarea-label-inside">{{ $label }}</label>
        @endif

        <div data-slot="inner-wrapper" class="app-textarea-inner">
            @isset($startContent)<span data-slot="start-content" class="app-textarea-content">{{ $startContent }}</span>@endisset
            <textarea
                data-slot="input"
                id="{{ $id }}"
                class="app-textarea-control"
                rows="{{ $minRows }}"
                @if($name) name="{{ $name }}" @endif
                @if($placeholder) placeholder="{{ $placeholder }}" @endif
                @if($label) aria-labelledby="{{ $labelId }}" @endif
                @if($description && !$invalid) aria-describedby="{{ $descriptionId }}" @endif
                @if($errorMessage && $invalid) aria-errormessage="{{ $errorId }}" @endif
                @if($invalid) aria-invalid="true" @endif
                @disabled($disabled)
                @readonly($readOnly)
                @required($required)
                {{ $fieldAttributes }}
            >{{ $value }}</textarea>
            @isset($endContent)<span data-slot="end-content" class="app-textarea-content">{{ $endContent }}</span>@endisset
        </div>

        @if($clearable)
            <button type="button" tabindex="-1" data-slot="clear-button" data-input-clear data-visible="{{ (string) $value !== '' ? 'true' : 'false' }}" class="app-textarea-clear" aria-label="입력값 지우기">
                <svg aria-hidden="true" focusable="false" viewBox="0 0 24 24"><path fill="currentColor" d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20Zm3.53 12.47a.75.75 0 0 1-1.06 1.06L12 13.06l-2.47 2.47a.75.75 0 0 1-1.06-1.06L10.94 12 8.47 9.53a.75.75 0 0 1 1.06-1.06L12 10.94l2.47-2.47a.75.75 0 0 1 1.06 1.06L13.06 12l2.47 2.47Z"/></svg>
            </button>
        @endif
    </div>

    @if($description || $errorMessage)
        <div data-slot="helper-wrapper" class="app-input-helper">
            @if($description)<div id="{{ $descriptionId }}" data-slot="description" class="app-field-description" @if($invalid) hidden @endif>{{ $description }}</div>@endif
            @if($errorMessage)<div id="{{ $errorId }}" data-slot="error-message" class="app-field-error" @if(!$invalid) hidden @endif>{{ $errorMessage }}</div>@endif
        </div>
    @endif
</div>
