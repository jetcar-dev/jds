@props([
    'name' => null,
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
    'disabled' => false,
    'readOnly' => false,
    'required' => false,
    'invalid' => false,
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
    'fullWidth' => true,
])
@php
    $variant = in_array($variant, ['flat', 'bordered', 'underlined', 'faded'], true) ? $variant : 'flat';
    $color = in_array($color, ['default', 'primary', 'secondary', 'success', 'warning', 'danger'], true) ? $color : 'default';
    $size = in_array($size, ['sm', 'md', 'lg'], true) ? $size : 'md';
    $radius = in_array($radius, ['none', 'sm', 'md', 'lg', 'full'], true) ? $radius : $size;
    $granularity = in_array($granularity, ['day', 'hour', 'minute', 'second'], true) ? $granularity : 'day';
    $visibleMonths = max(1, min(3, (int) $visibleMonths));
    $selectionAlignment = in_array($selectionAlignment, ['start', 'center', 'end'], true) ? $selectionAlignment : 'center';
    $selectorButtonPlacement = in_array($selectorButtonPlacement, ['start', 'end'], true) ? $selectorButtonPlacement : 'end';
    $pageBehavior = in_array($pageBehavior, ['single', 'visible'], true) ? $pageBehavior : 'visible';
    $selectedValue = $value ?? $defaultValue ?? '';
    $calendarValue = is_string($selectedValue) && strlen($selectedValue) >= 10 ? substr($selectedValue, 0, 10) : null;
    $showTimeField = $granularity !== 'day';
    $timeSource = $showTimeField && is_string($selectedValue) && str_contains($selectedValue, 'T')
        ? preg_replace('/(?:Z|[+-]\d{2}:?\d{2}|\[[^]]+\])$/', '', substr($selectedValue, 11))
        : '';
    [$timeHour, $timeMinute, $timeSecond] = array_pad(explode(':', $timeSource), 3, '');
    $canonicalHour = $timeHour === '' ? '' : str_pad((string) ((int) $timeHour), 2, '0', STR_PAD_LEFT);
    $timeValue = $canonicalHour === '' || $timeMinute === ''
        ? ''
        : $canonicalHour . ':' . str_pad((string) ((int) $timeMinute), 2, '0', STR_PAD_LEFT)
            . ($granularity === 'second' ? ':' . str_pad((string) ((int) $timeSecond), 2, '0', STR_PAD_LEFT) : '');
    $timeLabel = str_starts_with(strtolower($locale), 'ko') ? '시간' : 'Time';
    $uid = 'date-picker-' . uniqid();
    $popoverId = $uid . '-popover';
    $config = [
        'granularity' => $granularity,
        'disabled' => (bool) $disabled,
        'readOnly' => (bool) $readOnly,
        'disableAnimation' => (bool) $disableAnimation,
    ];
@endphp

<div
    data-slot="base"
    data-ui-component="date-picker"
    data-label-placement="{{ $labelPlacement ?: ($label ? 'inside' : 'outside') }}"
    data-open="false"
    data-invalid="{{ $invalid ? 'true' : 'false' }}"
    data-required="{{ $required ? 'true' : 'false' }}"
    data-readonly="{{ $readOnly ? 'true' : 'false' }}"
    data-disabled="{{ $disabled ? 'true' : 'false' }}"
    data-selector-button-placement="{{ $selectorButtonPlacement }}"
    data-full-width="{{ $fullWidth ? 'true' : 'false' }}"
    data-disable-animation="{{ $disableAnimation ? 'true' : 'false' }}"
    data-date-picker-config='@json($config)'
    {{ $attributes->class('app-date-picker') }}
>
    <x-date-input
        :name="$name"
        :value="$selectedValue"
        :placeholder-value="$placeholderValue"
        :label="$label"
        :description="$description"
        :error-message="$errorMessage"
        :variant="$variant"
        :color="$color"
        :size="$size"
        :radius="$radius"
        :label-placement="$labelPlacement"
        :locale="$locale"
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
        data-date-picker-field
    >
        @if($selectorButtonPlacement === 'start' || isset($startContent))
            <x-slot:startContent>
                @if($selectorButtonPlacement === 'start')
                    <button
                        type="button"
                        data-slot="selector-button"
                        class="app-date-picker-selector"
                        aria-label="달력 열기"
                        aria-haspopup="dialog"
                        aria-expanded="false"
                        aria-controls="{{ $popoverId }}"
                        @disabled($disabled)
                    >
                        <span data-slot="selector-icon" class="app-date-picker-selector-icon">
                            @isset($selectorIcon){{ $selectorIcon }}@else<x-icon name="solar:calendar-bold" />@endisset
                        </span>
                    </button>
                @endif
                @isset($startContent){{ $startContent }}@endisset
            </x-slot:startContent>
        @endif

        @if($selectorButtonPlacement === 'end' || isset($endContent))
            <x-slot:endContent>
                @isset($endContent){{ $endContent }}@endisset
                @if($selectorButtonPlacement === 'end')
                    <button
                        type="button"
                        data-slot="selector-button"
                        class="app-date-picker-selector"
                        aria-label="달력 열기"
                        aria-haspopup="dialog"
                        aria-expanded="false"
                        aria-controls="{{ $popoverId }}"
                        @disabled($disabled)
                    >
                        <span data-slot="selector-icon" class="app-date-picker-selector-icon">
                            @isset($selectorIcon){{ $selectorIcon }}@else<x-icon name="solar:calendar-bold" />@endisset
                        </span>
                    </button>
                @endif
            </x-slot:endContent>
        @endif
    </x-date-input>

    <div
        id="{{ $popoverId }}"
        data-slot="popover-content"
        class="app-date-picker-popover"
        role="dialog"
        aria-label="{{ $label ? $label . ' 달력' : '날짜 선택' }}"
        hidden
    >
        <x-calendar
            :value="$calendarValue"
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
            :color="$color === 'default' ? 'primary' : $color"
            :disabled="$disabled"
            :read-only="$readOnly"
            :invalid="$invalid"
            :show-month-and-year-pickers="$showMonthAndYearPickers"
            :presets="$presets"
            :preset-position="$presetPosition"
            :disable-animation="$disableAnimation"
            data-slot="calendar"
            class="app-date-picker-calendar"
        >
            @isset($calendarTopContent)
                <x-slot:topContent>{{ $calendarTopContent }}</x-slot:topContent>
            @endisset
            @if($showTimeField || isset($calendarBottomContent))
                <x-slot:bottomContent>
                    @if($showTimeField)
                        <x-time-input
                            :value="$timeValue"
                            :label="$timeLabel"
                            :locale="$locale"
                            :granularity="$granularity"
                            :hour-cycle="$hourCycle"
                            :time-zone="$timeZone"
                            :hide-time-zone="$hideTimeZone"
                            size="sm"
                            label-placement="outside-left"
                            :disabled="$disabled"
                            :read-only="$readOnly"
                            data-picker-time
                        />
                    @endif
                    @isset($calendarBottomContent)
                        {{ $calendarBottomContent }}
                    @endisset
                </x-slot:bottomContent>
            @endif
        </x-calendar>
    </div>
</div>
