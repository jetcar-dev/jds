@props([
    'value' => null,
    'defaultValue' => null,
    'focusedValue' => null,
    'defaultFocusedValue' => null,
    'name' => null,
    'label' => null,
    'locale' => 'ko-KR',
    'visibleMonth' => null,
    'visibleMonths' => 1,
    'firstDayOfWeek' => null,
    'selectionAlignment' => 'center',
    'calendarWidth' => 256,
    'pageBehavior' => 'visible',
    'weekdayStyle' => 'narrow',
    'minValue' => null,
    'maxValue' => null,
    'disabledValues' => [],
    'unavailableValues' => [],
    'allowsNonContiguousRanges' => false,
    'color' => 'primary',
    'disabled' => false,
    'readOnly' => false,
    'invalid' => false,
    'autoFocus' => false,
    'showMonthAndYearPickers' => false,
    'presets' => [],
    'presetPosition' => 'top',
    'showHelper' => false,
    'showShadow' => false,
    'headerExpanded' => false,
    'headerDefaultExpanded' => false,
    'hideDisabledDates' => false,
    'disableAnimation' => false,
    'errorMessage' => null,
])

<x-calendar
    selection-mode="range"
    :value="$value"
    :default-value="$defaultValue"
    :focused-value="$focusedValue"
    :default-focused-value="$defaultFocusedValue"
    :name="$name"
    :label="$label"
    :locale="$locale"
    :visible-month="$visibleMonth"
    :visible-months="$visibleMonths"
    :first-day-of-week="$firstDayOfWeek"
    :selection-alignment="$selectionAlignment"
    :calendar-width="$calendarWidth"
    :page-behavior="$pageBehavior"
    :weekday-style="$weekdayStyle"
    :min-value="$minValue"
    :max-value="$maxValue"
    :disabled-values="$disabledValues"
    :unavailable-values="$unavailableValues"
    :allows-non-contiguous-ranges="$allowsNonContiguousRanges"
    :color="$color"
    :disabled="$disabled"
    :read-only="$readOnly"
    :invalid="$invalid"
    :auto-focus="$autoFocus"
    :show-month-and-year-pickers="$showMonthAndYearPickers"
    :presets="$presets"
    :preset-position="$presetPosition"
    :show-helper="$showHelper"
    :show-shadow="$showShadow"
    :header-expanded="$headerExpanded"
    :header-default-expanded="$headerDefaultExpanded"
    :hide-disabled-dates="$hideDisabledDates"
    :disable-animation="$disableAnimation"
    :error-message="$errorMessage"
    {{ $attributes }}
>
    @isset($topContent)
        <x-slot:topContent>{{ $topContent }}</x-slot:topContent>
    @endisset
    @isset($bottomContent)
        <x-slot:bottomContent>{{ $bottomContent }}</x-slot:bottomContent>
    @endisset
</x-calendar>
