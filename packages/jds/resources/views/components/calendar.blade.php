@props([
    'value' => null,
    'defaultValue' => null,
    'focusedValue' => null,
    'defaultFocusedValue' => null,
    'selectionMode' => 'single',
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
@php
    $selectionMode = in_array($selectionMode, ['single', 'multiple', 'range'], true) ? $selectionMode : 'single';
    $color = in_array($color, ['default', 'primary', 'secondary', 'success', 'warning', 'danger'], true) ? $color : 'default';
    $visibleMonths = max(1, min(3, (int) $visibleMonths));
    $firstDayOfWeek = in_array($firstDayOfWeek, ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat', 0, 1, 2, 3, 4, 5, 6], true) ? $firstDayOfWeek : null;
    $selectionAlignment = in_array($selectionAlignment, ['start', 'center', 'end'], true) ? $selectionAlignment : 'center';
    $pageBehavior = in_array($pageBehavior, ['single', 'visible'], true) ? $pageBehavior : 'visible';
    $weekdayStyle = in_array($weekdayStyle, ['narrow', 'short', 'long'], true) ? $weekdayStyle : 'narrow';
    $presetPosition = in_array($presetPosition, ['top', 'bottom'], true) ? $presetPosition : 'top';
    $presetSource = $presets === true
        ? ['오늘' => 'today', '다음 주' => '+1 week', '다음 달' => '+1 month']
        : (array) $presets;
    $presetLabels = [
        'today' => '오늘',
        'tomorrow' => '내일',
        'nextWeek' => '다음 주',
        'nextMonth' => '다음 달',
    ];
    $normalisedPresets = [];
    foreach ($presetSource as $key => $preset) {
        if (is_array($preset)) {
            $presetValue = $selectionMode === 'range' && isset($preset['start'], $preset['end'])
                ? $preset['start'] . '/' . $preset['end']
                : ($preset['value'] ?? $preset['date'] ?? null);
            $presetLabel = $preset['label'] ?? (is_string($key) ? $key : $presetValue);
        } else {
            $presetValue = $preset;
            $presetLabel = is_string($key) ? $key : ($presetLabels[$preset] ?? $preset);
        }
        if (is_string($presetValue) && $presetValue !== '') {
            $normalisedPresets[] = ['label' => (string) $presetLabel, 'value' => $presetValue];
        }
    }
    $selectedValue = $value ?? $defaultValue;
    $accessibleLabel = $label ?: ($selectionMode === 'range' ? '날짜 범위' : '날짜');
    $errorId = 'calendar-error-' . uniqid();
    $config = [
        'value' => $selectedValue,
        'selectionMode' => $selectionMode,
        'locale' => $locale,
        'visibleMonth' => $visibleMonth,
        'visibleMonths' => $visibleMonths,
        'firstDayOfWeek' => $firstDayOfWeek,
        'selectionAlignment' => $selectionAlignment,
        'calendarWidth' => $calendarWidth,
        'pageBehavior' => $pageBehavior,
        'weekdayStyle' => $weekdayStyle,
        'focusedValue' => $focusedValue,
        'defaultFocusedValue' => $defaultFocusedValue,
        'minValue' => $minValue,
        'maxValue' => $maxValue,
        'disabledValues' => array_values((array) $disabledValues),
        'unavailableValues' => array_values((array) $unavailableValues),
        'allowsNonContiguousRanges' => (bool) $allowsNonContiguousRanges,
        'disabled' => (bool) $disabled,
        'readOnly' => (bool) $readOnly,
        'invalid' => (bool) $invalid,
        'autoFocus' => (bool) $autoFocus,
        'showMonthAndYearPickers' => (bool) $showMonthAndYearPickers && $visibleMonths === 1,
        'headerExpanded' => (bool) $headerExpanded,
        'headerDefaultExpanded' => (bool) $headerDefaultExpanded,
        'hideDisabledDates' => (bool) $hideDisabledDates,
        'disableAnimation' => (bool) $disableAnimation,
        'label' => $accessibleLabel,
        'errorId' => $errorId,
    ];
@endphp

<div
    data-slot="base"
    data-ui-component="calendar"
    data-color="{{ $color }}"
    data-selection-mode="{{ $selectionMode }}"
    data-disabled="{{ $disabled ? 'true' : 'false' }}"
    data-readonly="{{ $readOnly ? 'true' : 'false' }}"
    data-invalid="{{ $invalid ? 'true' : 'false' }}"
    data-visible-months="{{ $visibleMonths }}"
    data-show-shadow="{{ $showShadow ? 'true' : 'false' }}"
    data-disable-animation="{{ $disableAnimation ? 'true' : 'false' }}"
    data-calendar-config='@json($config)'
    role="application"
    aria-label="{{ $accessibleLabel }}"
    @if($disabled) aria-disabled="true" @endif
    style="--calendar-width: {{ is_numeric($calendarWidth) ? $calendarWidth . 'px' : $calendarWidth }}; --visible-months: {{ $visibleMonths }};"
    {{ $attributes->class("app-calendar app-color-$color") }}
>
    @if($name)
        <input
            type="hidden"
            data-calendar-input
            name="{{ $name }}"
            value="{{ is_array($selectedValue) ? json_encode($selectedValue, JSON_UNESCAPED_UNICODE) : $selectedValue }}"
            @disabled($disabled)
        >
    @endif

    @if(isset($topContent) || ($presetPosition === 'top' && count($normalisedPresets)))
        <div data-slot="top-content" class="app-calendar-top-content">
            @isset($topContent)
                <div data-slot="top-content-inner" class="app-calendar-content-inner">{{ $topContent }}</div>
            @endisset
            @if($presetPosition === 'top' && count($normalisedPresets))
                <div data-slot="preset-group" class="app-calendar-preset-group" role="group" aria-label="빠른 날짜 선택">
                    @foreach($normalisedPresets as $preset)
                        <button type="button" data-slot="preset" class="app-calendar-preset" data-calendar-preset="{{ $preset['value'] }}">{{ $preset['label'] }}</button>
                    @endforeach
                </div>
            @endif
        </div>
    @endif

    <div data-slot="content" data-calendar-ui class="app-calendar-content"></div>

    @if(isset($bottomContent) || ($presetPosition === 'bottom' && count($normalisedPresets)))
        <div data-slot="bottom-content" class="app-calendar-bottom-content">
            @isset($bottomContent)
                <div data-slot="bottom-content-inner" class="app-calendar-content-inner">{{ $bottomContent }}</div>
            @endisset
            @if($presetPosition === 'bottom' && count($normalisedPresets))
                <div data-slot="preset-group" class="app-calendar-preset-group" role="group" aria-label="빠른 날짜 선택">
                    @foreach($normalisedPresets as $preset)
                        <button type="button" data-slot="preset" class="app-calendar-preset app-calendar-preset-pill" data-calendar-preset="{{ $preset['value'] }}">{{ $preset['label'] }}</button>
                    @endforeach
                </div>
            @endif
        </div>
    @endif

    @if($showHelper || $invalid || $errorMessage)
        <div data-slot="helper-wrapper" class="app-calendar-helper">
            @if($invalid || $errorMessage)
                <div id="{{ $errorId }}" data-slot="error-message" class="app-calendar-error" role="alert">{{ $errorMessage ?: '선택한 날짜를 확인해 주세요.' }}</div>
            @endif
        </div>
    @endif
</div>
