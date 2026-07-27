{{--
    Select 루트

    options를 전달하면 Trigger, Value, Content, Item을 자동으로 구성
    options를 생략하면 하위 Select 컴포넌트를 slot에 직접 조합
    native가 true이면 JavaScript 없이 동작하는 기본 select를 출력
--}}
@props([
    'name' => null,
    'value' => '',
    'native' => false,
    'size' => 'default',
    'multiple' => false,
    'options' => null,
    'placeholder' => '',
    'color' => null,
    'indicator' => 'check',
    'disabled' => false,
    'required' => false,
    'invalid' => false,
    'fullWidth' => false,
    'autoComplete' => null,
])

@php
    $indicator = in_array($indicator, ['check', 'checkbox', 'radio'], true) ? $indicator : 'check';
    $size = in_array($size, ['sm', 'default', 'lg'], true) ? $size : 'default';

    $normalizedOptions = [];
    if (is_iterable($options)) {
        foreach ($options as $optionValue => $optionLabel) {
            if (is_int($optionValue)) {
                $normalizedOptions[(string)$optionLabel] = (string)$optionLabel;
            } else {
                $normalizedOptions[(string)$optionValue] = (string)$optionLabel;
            }
        }
    }

    $selectedValues = is_array($value) ? $value : (($value === '' || $value === null) ? [] : [$value]);
    $selectedValues = array_values(array_map('strval', $selectedValues));
    $initialValue = $multiple ? $selectedValues : (string)($value ?? '');

    $colorStyle = $color
        ? "--ring: {$color}; --primary: {$color}; --primary-foreground: #ffffff;"
        : '';
    $userStyle = (string)$attributes->get('style', '');
    $style = trim($colorStyle . ($colorStyle && $userStyle ? ' ' : '') . $userStyle);
    $fieldAttributes = $attributes->except(['style']);
@endphp

@if($native)
    <select
        @if($name) name="{{ $name }}{{ $multiple ? '[]' : '' }}" @endif
        @if($autoComplete) autocomplete="{{ $autoComplete }}" @endif
        @if($multiple) multiple @endif
        data-slot="select"
        data-native="true"
        data-size="{{ $size }}"
        @if($style) style="{{ $style }}" @endif
        @disabled($disabled)
        @required($required)
        @if($invalid) aria-invalid="true" @endif
        {{ $fieldAttributes->class([
            'app-select-native',
            'app-select-native-' . $size,
            'app-select-full' => $fullWidth,
        ]) }}
    >
        @if($placeholder !== '' && !$multiple)
            <option value="" disabled @selected($initialValue === '')>{{ $placeholder }}</option>
        @endif

        @if(count($normalizedOptions))
            @foreach($normalizedOptions as $optionValue => $optionLabel)
                <option value="{{ $optionValue }}" @selected(in_array((string)$optionValue, $selectedValues, true))>
                    {{ $optionLabel }}
                </option>
            @endforeach
        @else
            {{ $slot }}
        @endif
    </select>
@else
    <div
        data-slot="select"
        data-native="false"
        data-value="{{ $multiple ? '' : $initialValue }}"
        data-multiple="{{ $multiple ? 'true' : 'false' }}"
        data-required="{{ $required ? 'true' : 'false' }}"
        data-disabled="{{ $disabled ? 'true' : 'false' }}"
        data-invalid="{{ $invalid ? 'true' : 'false' }}"
        data-indicator="{{ $indicator }}"
        @if($style) style="{{ $style }}" @endif
        class="app-select-root {{ $fullWidth ? 'app-select-full' : '' }}"
    >
        @if($multiple)
            <span data-select-inputs data-name="{{ $name }}">
                @foreach($selectedValues as $selectedValue)
                    <input
                        type="hidden"
                        @if($name) name="{{ $name }}[]" @endif
                        value="{{ $selectedValue }}"
                    >
                @endforeach
            </span>
        @else
            <input
                type="hidden"
                data-select-input
                @if($name) name="{{ $name }}" @endif
                value="{{ $initialValue }}"
                @disabled($disabled)
                {{ $fieldAttributes }}
            >
        @endif

        @if(count($normalizedOptions))
            <x-select-trigger :size="$size" :disabled="$disabled" :aria-label="$placeholder">
                <x-select-value :placeholder="$placeholder" :multiple="$multiple"/>
            </x-select-trigger>

            <x-select-content :indicator="$indicator">
                @foreach($normalizedOptions as $optionValue => $optionLabel)
                    <x-select-item :value="$optionValue" :indicator="$indicator">
                        {{ $optionLabel }}
                    </x-select-item>
                @endforeach
            </x-select-content>
        @else
            {{ $slot }}
        @endif
    </div>
@endif
