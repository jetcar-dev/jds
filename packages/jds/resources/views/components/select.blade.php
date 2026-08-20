@props([
    'id' => null,
    'name' => null,
    'value' => '',
    'defaultValue' => null,
    'label' => null,
    'placeholder' => null,
    'description' => null,
    'errorMessage' => null,
    'selectionMode' => 'single',
    'variant' => 'flat',
    'color' => 'default',
    'size' => 'md',
    'radius' => 'md',
    'labelPlacement' => 'inside',
    'disabledKeys' => [],
    'disabled' => false,
    'required' => false,
    'invalid' => false,
    'clearable' => false,
    'isClearable' => null,
    'disallowEmptySelection' => false,
    'placement' => 'bottom',
    'disableSelectorIconRotation' => false,
    'disableAnimation' => false,
    'showScrollIndicators' => true,
    'defaultOpen' => false,
    'hideEmptyContent' => false,
    'emptyContent' => '선택할 항목이 없습니다.',
    'virtualized' => null,
    'maxListboxHeight' => 256,
    'itemHeight' => 36,
    'fullWidth' => true,
    'autoFocus' => false,
])
@php
    $variant = in_array($variant, ['flat', 'bordered', 'faded', 'underlined'], true) ? $variant : 'flat';
    $color = in_array($color, ['default', 'primary', 'secondary', 'success', 'warning', 'danger'], true) ? $color : 'default';
    $size = in_array($size, ['sm', 'md', 'lg'], true) ? $size : 'md';
    $radius = in_array($radius, ['none', 'sm', 'md', 'lg', 'full'], true) ? $radius : 'md';
    $labelPlacement = in_array($labelPlacement, ['inside', 'outside', 'outside-left', 'outside-top'], true) ? $labelPlacement : 'inside';
    $selectionMode = $selectionMode === 'multiple' ? 'multiple' : 'single';
    $selectedValue = $value !== '' ? $value : ($defaultValue ?? ($selectionMode === 'multiple' ? [] : ''));
    $selectedValues = $selectionMode === 'multiple' ? array_values((array) $selectedValue) : [(string) $selectedValue];
    $encodedValue = $selectionMode === 'multiple' ? json_encode($selectedValues, JSON_UNESCAPED_UNICODE) : (string) ($selectedValues[0] ?? '');
    $clearable = $isClearable ?? $clearable;
    $selectId = $id ?: 'select-'.uniqid();
    $triggerId = $selectId.'-trigger';
    $listboxId = $selectId.'-listbox';
    $labelId = $selectId.'-label';
    $descriptionId = $selectId.'-description';
    $errorId = $selectId.'-error';
    $hasValue = collect($selectedValues)->contains(fn ($entry) => (string) $entry !== '');
    $hasContent = isset($startContent) || isset($endContent);
    $filled = $hasValue || $placeholder !== null || $hasContent;
    $hasHelper = (bool) ($description || $errorMessage);
@endphp

<div
    id="{{ $selectId }}"
    data-slot="base"
    data-ui-component="select"
    data-value="{{ $encodedValue }}"
    data-selection-mode="{{ $selectionMode }}"
    data-variant="{{ $variant }}"
    data-color="{{ $color }}"
    data-size="{{ $size }}"
    data-radius="{{ $radius }}"
    data-label-placement="{{ $labelPlacement }}"
    data-disabled="{{ $disabled ? 'true' : 'false' }}"
    data-required="{{ $required ? 'true' : 'false' }}"
    data-invalid="{{ $invalid ? 'true' : 'false' }}"
    data-open="false"
    data-filled="{{ $filled ? 'true' : 'false' }}"
    data-has-value="{{ $hasValue ? 'true' : 'false' }}"
    data-has-label="{{ $label ? 'true' : 'false' }}"
    data-has-helper="{{ $hasHelper ? 'true' : 'false' }}"
    data-has-end-content="{{ isset($endContent) ? 'true' : 'false' }}"
    data-disabled-keys="{{ json_encode($disabledKeys, JSON_UNESCAPED_UNICODE) }}"
    data-clearable="{{ $clearable ? 'true' : 'false' }}"
    data-disallow-empty-selection="{{ $disallowEmptySelection ? 'true' : 'false' }}"
    data-disable-selector-icon-rotation="{{ $disableSelectorIconRotation ? 'true' : 'false' }}"
    data-disable-animation="{{ $disableAnimation ? 'true' : 'false' }}"
    data-show-scroll-indicators="{{ $showScrollIndicators ? 'true' : 'false' }}"
    data-default-open="{{ $defaultOpen ? 'true' : 'false' }}"
    data-hide-empty-content="{{ $hideEmptyContent ? 'true' : 'false' }}"
    data-virtualized="{{ $virtualized === null ? 'auto' : ($virtualized ? 'true' : 'false') }}"
    data-max-listbox-height="{{ (int) $maxListboxHeight }}"
    data-item-height="{{ (int) $itemHeight }}"
    data-full-width="{{ $fullWidth ? 'true' : 'false' }}"
    {{ $attributes->except(['aria-label', 'aria-labelledby', 'aria-describedby'])->class("app-select app-color-$color app-size-$size app-radius-$radius") }}
>
    @if($label && $labelPlacement !== 'inside')
        <label id="{{ $labelId }}" data-slot="label" data-required="{{ $required ? 'true' : 'false' }}" class="app-field-label" for="{{ $triggerId }}">{{ $label }}</label>
    @endif

    <div data-slot="mainWrapper" class="app-select-main">
        <input type="hidden" data-select-input @if($name && $selectionMode === 'single') name="{{ $name }}" @endif value="{{ $encodedValue }}">
        @if($required)<input type="text" data-select-validation class="app-sr-only" value="{{ $hasValue ? 'selected' : '' }}" required tabindex="-1" aria-hidden="true">@endif
        @if($selectionMode === 'multiple')
            <span data-select-form-values data-name="{{ $name }}">
                @if($name)@foreach($selectedValues as $selected)<input type="hidden" name="{{ $name }}[]" value="{{ $selected }}">@endforeach @endif
            </span>
        @endif

        <button
            id="{{ $triggerId }}"
            type="button"
            data-slot="trigger"
            data-variant="{{ $variant }}"
            data-invalid="{{ $invalid ? 'true' : 'false' }}"
            data-disabled="{{ $disabled ? 'true' : 'false' }}"
            class="app-select-trigger"
            aria-haspopup="listbox"
            aria-expanded="false"
            aria-controls="{{ $listboxId }}"
            @if($label) aria-labelledby="{{ $labelId }} {{ $triggerId }}-value" @endif
            @if($attributes->has('aria-label')) aria-label="{{ $attributes->get('aria-label') }}" @endif
            @if($description && !$invalid) aria-describedby="{{ $descriptionId }}" @endif
            @if($errorMessage && $invalid) aria-errormessage="{{ $errorId }}" @endif
            @if($invalid) aria-invalid="true" @endif
            @if($required) aria-required="true" @endif
            @disabled($disabled)
            @if($autoFocus) autofocus @endif
        >
            @if($label && $labelPlacement === 'inside')
                <span id="{{ $labelId }}" data-slot="label" data-required="{{ $required ? 'true' : 'false' }}" class="app-select-label-inside">{{ $label }}</span>
            @endif
            <span data-slot="innerWrapper" class="app-select-inner">
                @isset($startContent)<span data-slot="startContent" class="app-select-start-content">{{ $startContent }}</span>@endisset
                <span id="{{ $triggerId }}-value" data-slot="value" data-placeholder="{{ $hasValue ? 'false' : 'true' }}" data-placeholder-text="{{ $placeholder ?? '' }}" class="app-select-value">{{ $displayValue ?? ($placeholder ?? '') }}</span>
                @if($clearable)
                    <span data-slot="end-wrapper" class="app-select-end-wrapper">
                    <span role="button" tabindex="-1" data-slot="clear-button" data-select-clear data-visible="{{ $hasValue ? 'true' : 'false' }}" class="app-select-clear" aria-label="선택 지우기">
                        @isset($clearIcon){{ $clearIcon }}@else
                            <svg data-slot="clearIcon" aria-hidden="true" focusable="false" viewBox="0 0 24 24"><path d="M18 6 6 18M6 6l12 12" /></svg>
                        @endisset
                    </span>
                    @isset($endContent)<span data-slot="end-content" class="app-select-end-content">{{ $endContent }}</span>@endisset
                    </span>
                @else
                    @isset($endContent)<span data-slot="end-content" class="app-select-end-content">{{ $endContent }}</span>@endisset
                @endif
            </span>
            <span data-slot="selectorIcon" data-open="false" class="app-select-selector" aria-hidden="true">
                @isset($selectorIcon){{ $selectorIcon }}@else
                    <svg viewBox="0 0 24 24" focusable="false"><path d="m6 9 6 6 6-6" /></svg>
                @endisset
            </span>
        </button>

        @if($hasHelper)
            <div data-slot="helperWrapper" class="app-select-helper">
                @if($invalid && $errorMessage)<div id="{{ $errorId }}" data-slot="error-message" class="app-field-error">{{ $errorMessage }}</div>
                @elseif($description)<div id="{{ $descriptionId }}" data-slot="description" class="app-field-description">{{ $description }}</div>@endif
            </div>
        @endif
    </div>

    <div data-slot="popoverContent" data-select-popover data-placement="{{ $placement }}" data-disable-animation="{{ $disableAnimation ? 'true' : 'false' }}" class="app-select-popover" hidden>
        <div data-slot="listboxWrapper" data-show-scroll-indicators="{{ $showScrollIndicators ? 'true' : 'false' }}" class="app-select-listbox-wrapper" style="max-height:{{ (int) $maxListboxHeight }}px">
            <div id="{{ $listboxId }}" data-slot="listbox" data-collection-owned="true" data-selection-mode="{{ $selectionMode }}" data-variant="flat" data-color="{{ $color }}" data-disable-animation="{{ $disableAnimation ? 'true' : 'false' }}" class="app-listbox app-listbox-base" role="listbox" @if($selectionMode === 'multiple') aria-multiselectable="true" @endif @if($label) aria-labelledby="{{ $labelId }}" @endif>
                {{ $slot }}
                <div data-slot="emptyContent" class="app-select-empty" hidden>{{ $emptyContent }}</div>
            </div>
        </div>
    </div>
</div>
