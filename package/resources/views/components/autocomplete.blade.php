@props([
    'id' => null,
    'name' => null,
    'value' => '',
    'defaultValue' => null,
    'inputValue' => null,
    'defaultInputValue' => '',
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
    'disabledKeys' => [],
    'required' => false,
    'readOnly' => false,
    'invalid' => false,
    'clearable' => true,
    'isClearable' => null,
    'allowsCustomValue' => false,
    'allowsEmptyCollection' => true,
    'shouldCloseOnBlur' => true,
    'menuTrigger' => 'focus',
    'placement' => 'bottom-start',
    'disableSelectorIconRotation' => false,
    'disableAnimation' => false,
    'loading' => false,
    'showScrollIndicators' => true,
    'virtualized' => null,
    'maxListboxHeight' => 256,
    'itemHeight' => 32,
    'emptyContent' => '검색 결과가 없습니다.',
    'fullWidth' => true,
])
@php
    $variant = in_array($variant, ['flat', 'bordered', 'faded', 'underlined'], true) ? $variant : 'flat';
    $color = in_array($color, ['default', 'primary', 'secondary', 'success', 'warning', 'danger'], true) ? $color : 'default';
    $size = in_array($size, ['sm', 'md', 'lg'], true) ? $size : 'md';
    $radius = in_array($radius, ['none', 'sm', 'md', 'lg', 'full'], true) ? $radius : 'md';
    $labelPlacement = in_array($labelPlacement, ['inside', 'outside', 'outside-left', 'outside-top'], true) ? $labelPlacement : 'inside';
    $menuTrigger = in_array($menuTrigger, ['focus', 'input', 'manual'], true) ? $menuTrigger : 'focus';
    $selectedValue = $value !== '' ? $value : ($defaultValue ?? '');
    $initialInputValue = $inputValue ?? $defaultInputValue;
    $clearable = $isClearable ?? $clearable;
    $autocompleteId = $id ?: 'autocomplete-'.uniqid();
    $inputId = $autocompleteId.'-input';
    $listboxId = $autocompleteId.'-listbox';
    $labelId = $autocompleteId.'-label';
    $descriptionId = $autocompleteId.'-description';
    $errorId = $autocompleteId.'-error';
    $hasInputContent = isset($startContent) || isset($endContent);
@endphp

<div
    id="{{ $autocompleteId }}"
    data-slot="base"
    data-ui-component="autocomplete"
    data-value="{{ $selectedValue }}"
    data-variant="{{ $variant }}"
    data-color="{{ $color }}"
    data-size="{{ $size }}"
    data-radius="{{ $radius }}"
    data-label-placement="{{ $labelPlacement }}"
    data-disabled="{{ $disabled ? 'true' : 'false' }}"
    data-required="{{ $required ? 'true' : 'false' }}"
    data-readonly="{{ $readOnly ? 'true' : 'false' }}"
    data-invalid="{{ $invalid ? 'true' : 'false' }}"
    data-open="false"
    data-filled="{{ ($selectedValue !== '' || $initialInputValue !== '' || $placeholder || $hasInputContent) ? 'true' : 'false' }}"
    data-disabled-keys="{{ json_encode($disabledKeys, JSON_UNESCAPED_UNICODE) }}"
    data-clearable="{{ $clearable ? 'true' : 'false' }}"
    data-allows-custom-value="{{ $allowsCustomValue ? 'true' : 'false' }}"
    data-allows-empty-collection="{{ $allowsEmptyCollection ? 'true' : 'false' }}"
    data-should-close-on-blur="{{ $shouldCloseOnBlur ? 'true' : 'false' }}"
    data-menu-trigger="{{ $menuTrigger }}"
    data-disable-selector-icon-rotation="{{ $disableSelectorIconRotation ? 'true' : 'false' }}"
    data-disable-animation="{{ $disableAnimation ? 'true' : 'false' }}"
    data-loading="{{ $loading ? 'true' : 'false' }}"
    data-show-scroll-indicators="{{ $showScrollIndicators ? 'true' : 'false' }}"
    data-virtualized="{{ $virtualized === null ? 'auto' : ($virtualized ? 'true' : 'false') }}"
    data-max-listbox-height="{{ (int) $maxListboxHeight }}"
    data-item-height="{{ (int) $itemHeight }}"
    data-full-width="{{ $fullWidth ? 'true' : 'false' }}"
    {{ $attributes->except(['aria-label', 'aria-labelledby', 'aria-describedby'])->class("app-autocomplete app-color-$color app-size-$size app-radius-$radius") }}
>
    @if($label && $labelPlacement !== 'inside')
        <label id="{{ $labelId }}" data-slot="label" for="{{ $inputId }}" data-required="{{ $required ? 'true' : 'false' }}" class="app-field-label">{{ $label }}</label>
    @endif

    <div data-slot="mainWrapper" class="app-autocomplete-main">
        <input type="hidden" data-autocomplete-value @if($name) name="{{ $name }}" @endif value="{{ $selectedValue }}">
        <div data-slot="input-wrapper" data-variant="{{ $variant }}" data-invalid="{{ $invalid ? 'true' : 'false' }}" data-disabled="{{ $disabled ? 'true' : 'false' }}" class="app-input-wrapper">
            <span data-slot="inner-wrapper" class="app-input-inner app-autocomplete-inner">
                @if($label && $labelPlacement === 'inside')
                    <label id="{{ $labelId }}" data-slot="label" for="{{ $inputId }}" data-required="{{ $required ? 'true' : 'false' }}" class="app-autocomplete-label-inside">{{ $label }}</label>
                @endif
                @isset($startContent)<span data-slot="start-content" class="app-autocomplete-start-content">{{ $startContent }}</span>@endisset
                <input
                    id="{{ $inputId }}"
                    data-slot="input"
                    data-autocomplete-input
                    class="app-autocomplete-input app-input"
                    type="text"
                    value="{{ $initialInputValue }}"
                    @if($placeholder) placeholder="{{ $placeholder }}" @endif
                    autocomplete="off"
                    autocorrect="off"
                    spellcheck="false"
                    role="combobox"
                    aria-autocomplete="list"
                    aria-expanded="false"
                    aria-controls="{{ $listboxId }}"
                    @if($label) aria-labelledby="{{ $labelId }}" @endif
                    @if($attributes->has('aria-label')) aria-label="{{ $attributes->get('aria-label') }}" @endif
                    @if($description && !$invalid) aria-describedby="{{ $descriptionId }}" @endif
                    @if($errorMessage && $invalid) aria-errormessage="{{ $errorId }}" @endif
                    @if($invalid) aria-invalid="true" @endif
                    @disabled($disabled)
                    @readonly($readOnly)
                    @required($required)
                >
                @isset($endContent)<span data-slot="end-content">{{ $endContent }}</span>@endisset
                <span data-slot="endContentWrapper" class="app-autocomplete-end-content-wrapper">
                    @if($clearable)
                        <button type="button" tabindex="-1" data-slot="clearButton" data-autocomplete-clear data-visible="false" class="app-autocomplete-action app-autocomplete-clear" aria-label="입력값 지우기" @disabled($disabled || $readOnly)>
                            @isset($clearIcon){{ $clearIcon }}@else
                                <svg data-slot="clearIcon" aria-hidden="true" focusable="false" viewBox="0 0 24 24"><path d="M18 6 6 18M6 6l12 12" /></svg>
                            @endisset
                        </button>
                    @endif
                    <button type="button" tabindex="-1" data-slot="selectorButton" data-autocomplete-selector data-open="false" class="app-autocomplete-action app-autocomplete-selector" aria-label="추천 항목 보기" aria-haspopup="listbox" aria-expanded="false" aria-controls="{{ $listboxId }}" @disabled($disabled || $readOnly)>
                        @if($loading)<span class="app-autocomplete-spinner" aria-hidden="true"></span>@else
                            @isset($selectorIcon){{ $selectorIcon }}@else
                                <svg data-slot="selectorIcon" aria-hidden="true" focusable="false" viewBox="0 0 24 24"><path d="m6 9 6 6 6-6" /></svg>
                            @endisset
                        @endif
                    </button>
                </span>
            </span>
        </div>
        @if($description && !$invalid)<div id="{{ $descriptionId }}" data-slot="description" class="app-field-description">{{ $description }}</div>@endif
        @if($errorMessage && $invalid)<div id="{{ $errorId }}" data-slot="errorMessage" class="app-field-error">{{ $errorMessage }}</div>@endif
    </div>

    <div data-slot="popoverContent" data-autocomplete-popover data-placement="{{ $placement }}" data-disable-animation="{{ $disableAnimation ? 'true' : 'false' }}" class="app-autocomplete-popover app-color-{{ $color }}" hidden>
        <div data-slot="listboxWrapper" data-show-scroll-indicators="{{ $showScrollIndicators ? 'true' : 'false' }}" class="app-autocomplete-listbox-wrapper" style="max-height:{{ (int) $maxListboxHeight }}px">
            <div id="{{ $listboxId }}" data-slot="listbox" data-collection-owned="true" data-selection-mode="single" class="app-listbox" role="listbox" @if($label) aria-labelledby="{{ $labelId }}" @endif>
                {{ $slot }}
                <div data-slot="emptyContent" class="app-autocomplete-empty" hidden>{{ $emptyContent }}</div>
            </div>
        </div>
    </div>
</div>
