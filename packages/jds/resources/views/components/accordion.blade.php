@props([
    'variant' => 'light',
    'selectionMode' => 'single',
    'selectionBehavior' => 'toggle',
    'compact' => false,
    'disabled' => false,
    'showDivider' => true,
    'hideIndicator' => false,
    'disableAnimation' => false,
    'disableIndicatorAnimation' => false,
    'disallowEmptySelection' => false,
    'keepContentMounted' => false,
    'fullWidth' => true,
    'disabledKeys' => [],
    'defaultSelectedKeys' => [],
    'selectedKeys' => null,
    'defaultExpandedKeys' => null,
    'expandedKeys' => null,
])
@php
    $variant = in_array($variant, ['light', 'shadow', 'bordered', 'splitted'], true) ? $variant : 'light';
    $selectionMode = in_array($selectionMode, ['none', 'single', 'multiple'], true) ? $selectionMode : 'single';
    $selectionBehavior = in_array($selectionBehavior, ['toggle', 'replace'], true) ? $selectionBehavior : 'toggle';
    $initialKeys = $expandedKeys ?? $selectedKeys ?? $defaultExpandedKeys ?? $defaultSelectedKeys;
@endphp

<div
    data-slot="accordion"
    data-ui-component="accordion"
    data-orientation="vertical"
    data-variant="{{ $variant }}"
    data-selection-mode="{{ $selectionMode }}"
    data-selection-behavior="{{ $selectionBehavior }}"
    data-compact="{{ $compact ? 'true' : 'false' }}"
    data-disabled="{{ $disabled ? 'true' : 'false' }}"
    data-show-divider="{{ $showDivider ? 'true' : 'false' }}"
    data-hide-indicator="{{ $hideIndicator ? 'true' : 'false' }}"
    data-disable-animation="{{ $disableAnimation ? 'true' : 'false' }}"
    data-disable-indicator-animation="{{ $disableIndicatorAnimation ? 'true' : 'false' }}"
    data-disallow-empty-selection="{{ $disallowEmptySelection ? 'true' : 'false' }}"
    data-keep-content-mounted="{{ $keepContentMounted ? 'true' : 'false' }}"
    data-full-width="{{ $fullWidth ? 'true' : 'false' }}"
    data-disabled-keys="{{ json_encode($disabledKeys, JSON_UNESCAPED_UNICODE) }}"
    data-selected-keys="{{ json_encode($initialKeys, JSON_UNESCAPED_UNICODE) }}"
    {{ $attributes->class('app-accordion') }}
>
    {{ $slot }}
</div>
