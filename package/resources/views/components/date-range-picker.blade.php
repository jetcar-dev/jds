@props([
    'name' => null,
    'startName' => null,
    'endName' => null,
    'value' => null,
    'defaultValue' => null,
    'placeholderValue' => null,
    'label' => null,
    'description' => null,
    'errorMessage' => null,
    'variant' => 'flat',
    'color' => 'default',
    'size' => 'md',
    'radius' => null,
    'labelPlacement' => null,
    'locale' => 'ko-KR',
    'granularity' => 'day',
    'hourCycle' => null,
    'timeZone' => null,
    'hideTimeZone' => false,
    'minValue' => null,
    'maxValue' => null,
    'disabledValues' => [],
    'unavailableValues' => [],
    'allowsNonContiguousRanges' => false,
    'disabled' => false,
    'readOnly' => false,
    'required' => false,
    'invalid' => false,
    'clearable' => false,
    'visibleMonths' => 1,
    'firstDayOfWeek' => null,
    'selectionAlignment' => 'center',
    'selectorButtonPlacement' => 'end',
    'calendarWidth' => 256,
    'pageBehavior' => 'visible',
    'showMonthAndYearPickers' => false,
    'presets' => [],
    'presetPosition' => 'top',
    'autoFocus' => false,
    'disableAnimation' => false,
    'shouldForceLeadingZeros' => true,
    'fullWidth' => false,
])
@php
    $variant = in_array($variant, ['flat', 'bordered', 'underlined', 'faded'], true) ? $variant : 'flat';
    $color = in_array($color, ['default', 'primary', 'secondary', 'success', 'warning', 'danger'], true) ? $color : 'default';
    $size = in_array($size, ['sm', 'md', 'lg'], true) ? $size : 'md';
    $radius = in_array($radius, ['none', 'sm', 'md', 'lg', 'full'], true) ? $radius : $size;
    $labelPlacement = in_array($labelPlacement, ['inside', 'outside', 'outside-left', 'outside-top'], true)
        ? $labelPlacement
        : ($label ? 'inside' : 'outside');
    $granularity = in_array($granularity, ['day', 'hour', 'minute', 'second'], true) ? $granularity : 'day';
    $visibleMonths = max(1, min(3, (int) $visibleMonths));
    $selectionAlignment = in_array($selectionAlignment, ['start', 'center', 'end'], true) ? $selectionAlignment : 'center';
    $selectorButtonPlacement = in_array($selectorButtonPlacement, ['start', 'end'], true) ? $selectorButtonPlacement : 'end';
    $pageBehavior = in_array($pageBehavior, ['single', 'visible'], true) ? $pageBehavior : 'visible';
    $selectedValue = $value ?? $defaultValue ?? [];
    $range = is_array($selectedValue)
        ? [
            'start' => (string) ($selectedValue['start'] ?? $selectedValue['from'] ?? ''),
            'end' => (string) ($selectedValue['end'] ?? $selectedValue['to'] ?? ''),
        ]
        : ['start' => '', 'end' => ''];
    $placeholderRange = is_array($placeholderValue)
        ? [
            'start' => $placeholderValue['start'] ?? $placeholderValue['from'] ?? null,
            'end' => $placeholderValue['end'] ?? $placeholderValue['to'] ?? null,
        ]
        : ['start' => $placeholderValue, 'end' => $placeholderValue];
    $calendarRange = [
        'start' => strlen($range['start']) >= 10 ? substr($range['start'], 0, 10) : '',
        'end' => strlen($range['end']) >= 10 ? substr($range['end'], 0, 10) : '',
    ];
    $extractTime = static function (string $date, string $granularity): string {
        if (!str_contains($date, 'T')) return '';
        $time = preg_replace('/(?:Z|[+-]\d{2}:?\d{2}|\[[^]]+\])$/', '', substr($date, 11));
        [$hour, $minute, $second] = array_pad(explode(':', $time), 3, '');
        if ($hour === '' || $minute === '') return '';
        return str_pad((string) ((int) $hour), 2, '0', STR_PAD_LEFT)
            . ':' . str_pad((string) ((int) $minute), 2, '0', STR_PAD_LEFT)
            . ($granularity === 'second' ? ':' . str_pad((string) ((int) $second), 2, '0', STR_PAD_LEFT) : '');
    };
    $startTime = $extractTime($range['start'], $granularity);
    $endTime = $extractTime($range['end'], $granularity);
    $showTimeFields = $granularity !== 'day';
    $language = strtolower(strtok(str_replace('_', '-', (string) $locale), '-'));
    [$startTimeLabel, $endTimeLabel] = match ($language) {
        'ko' => ['시작 시간', '종료 시간'],
        'ja' => ['開始時刻', '終了時刻'],
        'zh' => ['开始时间', '结束时间'],
        default => ['Start time', 'End time'],
    };
    $uid = 'date-range-picker-' . uniqid();
    $labelId = $uid . '-label';
    $descriptionId = $uid . '-description';
    $errorId = $uid . '-error';
    $popoverId = $uid . '-popover';
    $hasHelper = (bool) $description || (bool) $errorMessage;
    $describedBy = collect([
        $description ? $descriptionId : null,
        $errorMessage ? $errorId : null,
    ])->filter()->implode(' ');
    $config = [
        'granularity' => $granularity,
        'disabled' => (bool) $disabled,
        'readOnly' => (bool) $readOnly,
        'invalid' => (bool) $invalid,
        'disableAnimation' => (bool) $disableAnimation,
    ];
@endphp

<div
    data-slot="base"
    data-ui-component="date-range-picker"
    data-variant="{{ $variant }}"
    data-color="{{ $color }}"
    data-size="{{ $size }}"
    data-label-placement="{{ $labelPlacement }}"
    data-has-label="{{ $label ? 'true' : 'false' }}"
    data-has-helper="{{ $hasHelper ? 'true' : 'false' }}"
    data-has-start-content="{{ isset($startContent) ? 'true' : 'false' }}"
    data-has-end-content="{{ isset($endContent) ? 'true' : 'false' }}"
    data-has-multiple-months="{{ $visibleMonths > 1 ? 'true' : 'false' }}"
    data-open="false"
    data-focus="false"
    data-focus-visible="false"
    data-hover="false"
    data-invalid="{{ $invalid ? 'true' : 'false' }}"
    data-required="{{ $required ? 'true' : 'false' }}"
    data-readonly="{{ $readOnly ? 'true' : 'false' }}"
    data-disabled="{{ $disabled ? 'true' : 'false' }}"
    data-selector-button-placement="{{ $selectorButtonPlacement }}"
    data-full-width="{{ $fullWidth ? 'true' : 'false' }}"
    data-disable-animation="{{ $disableAnimation ? 'true' : 'false' }}"
    data-date-range-picker-config='@json($config)'
    {{ $attributes->class("app-date-range-picker app-color-$color app-size-$size app-radius-$radius") }}
>
    @if($label && $labelPlacement !== 'inside')
        <span id="{{ $labelId }}" data-slot="label" class="app-date-input-label app-date-input-label-outside">{{ $label }}</span>
    @endif

    <div
        data-slot="input-wrapper"
        data-date-range-wrapper
        class="app-date-range-picker-wrapper"
        role="group"
        @if($label) aria-labelledby="{{ $labelId }}" @else aria-label="날짜 범위" @endif
        @if($describedBy) aria-describedby="{{ $describedBy }}" @endif
        @if($disabled) aria-disabled="true" @endif
        @if($readOnly) aria-readonly="true" @endif
        @if($required) aria-required="true" @endif
        @if($invalid) aria-invalid="true" @endif
    >
        @if($label && $labelPlacement === 'inside')
            <span id="{{ $labelId }}" data-slot="label" class="app-date-input-label app-date-input-label-inside">{{ $label }}</span>
        @endif

        <div data-slot="inner-wrapper" class="app-date-range-picker-inner">
            @if($selectorButtonPlacement === 'start')
                <button type="button" data-slot="selector-button" class="app-date-picker-selector" aria-label="달력 열기" aria-haspopup="dialog" aria-expanded="false" aria-controls="{{ $popoverId }}" @disabled($disabled || $readOnly)>
                    <span data-slot="selector-icon" class="app-date-picker-selector-icon">@isset($selectorIcon){{ $selectorIcon }}@else<x-icon name="solar:calendar-bold" />@endisset</span>
                </button>
            @endif

            @isset($startContent)
                <span data-slot="start-content" class="app-date-input-content app-date-input-start">{{ $startContent }}</span>
            @endisset

            <x-date-input
                :name="$startName ?: ($name ? $name . '[start]' : null)"
                :value="$range['start']"
                :placeholder-value="$placeholderRange['start']"
                aria-label="시작 날짜"
                :locale="$locale"
                :color="$color"
                :size="$size"
                :radius="$radius"
                :granularity="$granularity"
                :hour-cycle="$hourCycle"
                :time-zone="$timeZone"
                :hide-time-zone="$hideTimeZone"
                :min-value="$minValue"
                :max-value="$maxValue"
                :disabled="$disabled"
                :read-only="$readOnly"
                :required="$required"
                :invalid="$invalid"
                :auto-focus="$autoFocus"
                :disable-animation="$disableAnimation"
                :should-force-leading-zeros="$shouldForceLeadingZeros"
                data-slot="start-input"
                data-range-part="start"
                class="app-date-range-picker-part"
            />

            <span data-slot="separator" class="app-date-range-picker-separator" aria-hidden="true" role="separator">~</span>

            <x-date-input
                :name="$endName ?: ($name ? $name . '[end]' : null)"
                :value="$range['end']"
                :placeholder-value="$placeholderRange['end']"
                aria-label="종료 날짜"
                :locale="$locale"
                :color="$color"
                :size="$size"
                :radius="$radius"
                :granularity="$granularity"
                :hour-cycle="$hourCycle"
                :time-zone="$timeZone"
                :hide-time-zone="$hideTimeZone"
                :min-value="$minValue"
                :max-value="$maxValue"
                :disabled="$disabled"
                :read-only="$readOnly"
                :required="$required"
                :invalid="$invalid"
                :disable-animation="$disableAnimation"
                :should-force-leading-zeros="$shouldForceLeadingZeros"
                data-slot="end-input"
                data-range-part="end"
                class="app-date-range-picker-part"
            />

            @isset($endContent)
                <span data-slot="end-content" class="app-date-input-content app-date-input-end">{{ $endContent }}</span>
            @endisset

            @if($clearable)
                <button type="button" data-slot="clear-button" data-date-range-clear class="app-date-input-clear" aria-label="날짜 범위 지우기" @disabled($disabled || $readOnly)>
                    <svg aria-hidden="true" viewBox="0 0 24 24"><path d="m7 7 10 10M17 7 7 17" fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="1.7"/></svg>
                </button>
            @endif

            @if($selectorButtonPlacement === 'end')
                <button type="button" data-slot="selector-button" class="app-date-picker-selector" aria-label="달력 열기" aria-haspopup="dialog" aria-expanded="false" aria-controls="{{ $popoverId }}" @disabled($disabled || $readOnly)>
                    <span data-slot="selector-icon" class="app-date-picker-selector-icon">@isset($selectorIcon){{ $selectorIcon }}@else<x-icon name="solar:calendar-bold" />@endisset</span>
                </button>
            @endif
        </div>
    </div>

    @if($hasHelper)
        <div data-slot="helper-wrapper" class="app-date-input-helper">
            @if($description)
                <span id="{{ $descriptionId }}" data-slot="description" class="app-date-input-description">{{ $description }}</span>
            @endif
            @if($errorMessage)
                <span id="{{ $errorId }}" data-slot="error-message" class="app-date-input-error" role="alert">{{ $errorMessage }}</span>
            @endif
        </div>
    @endif

    <input type="hidden" data-date-range-value value='@json($range)'>

    <div id="{{ $popoverId }}" data-slot="popover-content" class="app-date-picker-popover" role="dialog" aria-label="{{ $label ? $label . ' 달력' : '날짜 범위 선택' }}" hidden>
        <x-range-calendar
            :value="$calendarRange"
            :locale="$locale"
            :visible-months="$visibleMonths"
            :first-day-of-week="$firstDayOfWeek"
            :selection-alignment="$selectionAlignment"
            :calendar-width="$calendarWidth"
            :page-behavior="$pageBehavior"
            :min-value="$minValue"
            :max-value="$maxValue"
            :disabled-values="$disabledValues"
            :unavailable-values="$unavailableValues"
            :allows-non-contiguous-ranges="$allowsNonContiguousRanges"
            :color="$color === 'default' ? 'primary' : $color"
            :disabled="$disabled"
            :read-only="$readOnly"
            :invalid="$invalid"
            :show-month-and-year-pickers="$showMonthAndYearPickers"
            :presets="$presets"
            :preset-position="$presetPosition"
            :disable-animation="$disableAnimation"
            data-slot="calendar"
            class="app-date-picker-calendar app-date-range-picker-calendar"
        >
            @isset($calendarTopContent)
                <x-slot:topContent>{{ $calendarTopContent }}</x-slot:topContent>
            @endisset
            @if($showTimeFields || isset($calendarBottomContent))
                <x-slot:bottomContent>
                    @if($showTimeFields)
                        <div data-slot="time-fields" class="app-date-range-picker-times">
                            <x-time-input :value="$startTime" :label="$startTimeLabel" :locale="$locale" :granularity="$granularity" :hour-cycle="$hourCycle" :time-zone="$timeZone" :hide-time-zone="$hideTimeZone" size="sm" label-placement="outside-left" :disabled="$disabled" :read-only="$readOnly" data-picker-time data-range-time="start" />
                            <x-time-input :value="$endTime" :label="$endTimeLabel" :locale="$locale" :granularity="$granularity" :hour-cycle="$hourCycle" :time-zone="$timeZone" :hide-time-zone="$hideTimeZone" size="sm" label-placement="outside-left" :disabled="$disabled" :read-only="$readOnly" data-picker-time data-range-time="end" />
                        </div>
                    @endif
                    @isset($calendarBottomContent){{ $calendarBottomContent }}@endisset
                </x-slot:bottomContent>
            @endif
        </x-range-calendar>
    </div>
</div>
