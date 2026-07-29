{{--
    검색 가능한 선택 입력

    trigger="button"은 버튼을 누르면 검색 목록이 열림
    trigger="input"은 입력창에서 바로 검색하는 자동완성 형태
--}}
@props([
    'name' => null,
    'options' => [],
    'value' => '',
    'placeholder' => null,
    'searchPlaceholder' => '검색',
    'empty' => '검색 결과가 없습니다',
    'searchable' => true,
    'disabled' => false,
    'multiple' => false,
    'trigger' => 'button',
    'variant' => null,
    'color' => 'default',
    'size' => 'md',
    'icon' => null,
    'indicator' => 'check',
    'fullWidth' => false,
    'required' => false,
    'invalid' => false,
])

@php
    $hasExplicitVariant = $variant !== null;
    $variant ??= 'flat';
    $trigger = in_array($trigger, ['button', 'input'], true) ? $trigger : 'button';
    $variant = $variant === 'outline' ? 'bordered' : $variant;
    $variant = in_array($variant, ['solid', 'faded', 'bordered', 'light', 'flat', 'ghost', 'shadow'], true) ? $variant : 'flat';
    $color = in_array($color, ['default', 'primary', 'secondary', 'success', 'warning', 'danger'], true) ? $color : 'default';
    $size = in_array($size, ['xs', 'sm', 'md', 'lg', 'xl'], true) ? $size : 'md';
    $indicator = in_array($indicator, ['check', 'checkbox', 'radio'], true) ? $indicator : 'check';
    $placeholder = $placeholder ?? ($trigger === 'input' ? '검색' : '선택');

    $normalizedOptions = [];
    $isOptionList = is_array($options) && array_is_list($options);

    foreach ($options as $optionValue => $optionLabel) {
        $optionDisabled = false;

        if (is_array($optionLabel)) {
            $optionValue = $optionLabel['value'] ?? '';
            $optionDisabled = (bool)($optionLabel['disabled'] ?? false);
            $optionLabel = $optionLabel['label'] ?? $optionValue;
        } elseif ($isOptionList) {
            $optionValue = $optionLabel;
        }

        $normalizedOptions[] = [
            'value' => (string)$optionValue,
            'label' => (string)$optionLabel,
            'disabled' => $optionDisabled,
        ];
    }

    $selectedValues = is_array($value) ? $value : (($value === '' || $value === null) ? [] : [$value]);
    $selectedValues = array_values(array_map('strval', $selectedValues));
    $selectedLabel = '';

    if (!$multiple) {
        foreach ($normalizedOptions as $option) {
            if ($option['value'] === ($selectedValues[0] ?? '')) {
                $selectedLabel = $option['label'];
                break;
            }
        }
    }

    $listboxId = 'app-combobox-list-'.uniqid();
@endphp

<div
    data-slot="combobox"
    data-trigger="{{ $trigger }}"
    data-multiple="{{ $multiple ? 'true' : 'false' }}"
    data-searchable="{{ $searchable ? 'true' : 'false' }}"
    data-disabled="{{ $disabled ? 'true' : 'false' }}"
    data-required="{{ $required ? 'true' : 'false' }}"
    data-invalid="{{ $invalid ? 'true' : 'false' }}"
    data-variant="{{ $variant }}"
    @if($hasExplicitVariant) data-group-variant="explicit" @endif
    data-color="{{ $color }}"
    {{ $attributes->except('class') }}
    class="app-combobox app-color-{{ $color }} {{ $fullWidth ? 'app-combobox-full' : '' }} {{ $attributes->get('class') }}"
>
    @if($multiple)
        <span data-combobox-inputs data-name="{{ $name }}">
            @foreach($selectedValues as $selectedValue)
                <input type="hidden" @if($name) name="{{ $name }}[]" @endif value="{{ $selectedValue }}">
            @endforeach
        </span>
    @else
        <input
            type="hidden"
            data-combobox-value
            @if($name) name="{{ $name }}" @endif
            value="{{ $selectedValues[0] ?? '' }}"
            @disabled($disabled)
        >
    @endif

    @if($trigger === 'button')
        <button
            type="button"
            data-combobox-trigger
            data-size="{{ $size }}"
            role="combobox"
            aria-haspopup="listbox"
            aria-expanded="false"
            aria-controls="{{ $listboxId }}"
            aria-label="{{ $placeholder }}"
            @disabled($disabled)
            class="app-combobox-trigger app-combobox-trigger-{{ $size }} app-combobox-{{ $variant }}"
        >
            <span data-combobox-display class="app-combobox-display" data-placeholder="{{ $placeholder }}"></span>
            <x-icon name="alt-arrow-down-linear" class="app-combobox-chevron" />
        </button>
    @else
        <div class="app-combobox-input-wrap app-combobox-input-wrap-{{ $size }} app-combobox-{{ $variant }}">
            @if($icon)
                <i class="{{ $icon }} app-combobox-icon" aria-hidden="true"></i>
            @endif
            <span data-combobox-chips class="app-combobox-chips"></span>
            <input
                type="text"
                data-combobox-trigger
                data-combobox-search
                role="combobox"
                aria-autocomplete="list"
                aria-expanded="false"
                aria-controls="{{ $listboxId }}"
                autocomplete="off"
                value="{{ $selectedLabel }}"
                placeholder="{{ $placeholder }}"
                @disabled($disabled)
                class="app-combobox-input"
            >
            <x-icon name="alt-arrow-down-linear" class="app-combobox-chevron" />
        </div>
    @endif

    <div data-combobox-content data-side="bottom" data-preferred-side="bottom" data-side-offset="4" hidden class="app-combobox-content">
        @if($trigger === 'button' && $searchable)
            <div class="app-combobox-search-wrap">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="11" cy="11" r="8"/>
                    <path d="m21 21-4.35-4.35"/>
                </svg>
                <input
                    type="text"
                    data-combobox-search
                    autocomplete="off"
                    placeholder="{{ $searchPlaceholder }}"
                    class="app-combobox-search"
                >
            </div>
        @endif

        <div
            id="{{ $listboxId }}"
            data-combobox-list
            role="listbox"
            aria-multiselectable="{{ $multiple ? 'true' : 'false' }}"
            tabindex="-1"
            class="app-combobox-list"
        >
            <div data-combobox-empty class="app-combobox-empty" hidden>{{ $empty }}</div>
            @foreach($normalizedOptions as $option)
                <div
                    data-combobox-option
                    data-value="{{ $option['value'] }}"
                    data-disabled="{{ $option['disabled'] ? 'true' : 'false' }}"
                    role="option"
                    id="{{ $listboxId }}-option-{{ $loop->index }}"
                    tabindex="-1"
                    aria-selected="false"
                    aria-disabled="{{ $option['disabled'] ? 'true' : 'false' }}"
                    class="app-combobox-option"
                >
                    <span class="app-combobox-indicator app-combobox-indicator-{{ $indicator }}" aria-hidden="true">
                        @if($indicator === 'radio')
                            <span class="app-combobox-radio-dot"></span>
                        @else
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m20 6-11 11-5-5"/>
                            </svg>
                        @endif
                    </span>
                    <span data-combobox-option-label>{{ $option['label'] }}</span>
                </div>
            @endforeach
        </div>
    </div>
</div>
