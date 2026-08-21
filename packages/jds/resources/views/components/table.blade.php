@props([
    'selectionMode' => 'none',
    'selectionBehavior' => 'toggle',
    'disabledBehavior' => 'selection',
    'color' => 'default',
    'layout' => 'auto',
    'radius' => 'lg',
    'shadow' => 'sm',
    'removeWrapper' => false,
    'hideHeader' => false,
    'striped' => false,
    'compact' => false,
    'headerSticky' => false,
    'fullWidth' => true,
    'showSelectionCheckboxes' => null,
    'disallowEmptySelection' => false,
    'disableAnimation' => false,
    'topContentPlacement' => 'inside',
    'bottomContentPlacement' => 'inside',
    'selectedKeys' => [],
    'defaultSelectedKeys' => [],
    'disabledKeys' => [],
    'sortColumn' => null,
    'sortDirection' => 'ascending',
    'autoSort' => true,
    'maxTableHeight' => 600,
    'rowHeight' => 40,
    'name' => null,
    'ariaLabel' => null,
])
@php
    $selectionMode = in_array($selectionMode, ['none', 'single', 'multiple'], true) ? $selectionMode : 'none';
    $selectionBehavior = in_array($selectionBehavior, ['toggle', 'replace'], true) ? $selectionBehavior : 'toggle';
    $disabledBehavior = in_array($disabledBehavior, ['selection', 'all'], true) ? $disabledBehavior : 'selection';
    $color = in_array($color, ['default', 'primary', 'secondary', 'success', 'warning', 'danger'], true) ? $color : 'default';
    $layout = in_array($layout, ['auto', 'fixed'], true) ? $layout : 'auto';
    $radius = in_array($radius, ['none', 'sm', 'md', 'lg'], true) ? $radius : 'lg';
    $shadow = in_array($shadow, ['none', 'sm', 'md', 'lg'], true) ? $shadow : 'sm';
    $topContentPlacement = in_array($topContentPlacement, ['inside', 'outside'], true) ? $topContentPlacement : 'inside';
    $bottomContentPlacement = in_array($bottomContentPlacement, ['inside', 'outside'], true) ? $bottomContentPlacement : 'inside';
    $sortDirection = in_array($sortDirection, ['ascending', 'descending'], true) ? $sortDirection : 'ascending';

    $normalizeKeys = static function ($keys): array {
        if ($keys === null || $keys === '') return [];
        if (is_string($keys)) {
            $decoded = json_decode($keys, true);
            $keys = is_array($decoded) ? $decoded : preg_split('/\s*,\s*/', $keys, -1, PREG_SPLIT_NO_EMPTY);
        }
        return array_values(array_unique(array_map('strval', is_array($keys) ? $keys : [$keys])));
    };

    $controlledKeys = $normalizeKeys($selectedKeys);
    $initialKeys = count($controlledKeys) ? $controlledKeys : $normalizeKeys($defaultSelectedKeys);
    if ($selectionMode === 'single') $initialKeys = array_slice($initialKeys, 0, 1);
    if ($selectionMode === 'none') $initialKeys = [];
    $disabledKeys = $normalizeKeys($disabledKeys);
    $showCheckboxes = $showSelectionCheckboxes === null ? $selectionMode === 'multiple' : (bool) $showSelectionCheckboxes;
    $tableId = $attributes->get('id') ? $attributes->get('id').'-grid' : null;
    $tableLabel = $ariaLabel ?: ($attributes->get('aria-label') ?: '데이터 테이블');
    $rootStyle = '--app-table-max-height: '.max(0, (int) $maxTableHeight).'px; --app-table-row-height: '.max(0, (int) $rowHeight).'px; '.$attributes->get('style', '');
@endphp

<div
    data-slot="base"
    data-ui-component="table"
    data-selection-mode="{{ $selectionMode }}"
    data-selection-behavior="{{ $selectionBehavior }}"
    data-disabled-behavior="{{ $disabledBehavior }}"
    data-color="{{ $color }}"
    data-layout="{{ $layout }}"
    data-radius="{{ $radius }}"
    data-shadow="{{ $shadow }}"
    data-remove-wrapper="{{ $removeWrapper ? 'true' : 'false' }}"
    data-hide-header="{{ $hideHeader ? 'true' : 'false' }}"
    data-striped="{{ $striped ? 'true' : 'false' }}"
    data-compact="{{ $compact ? 'true' : 'false' }}"
    data-header-sticky="{{ $headerSticky ? 'true' : 'false' }}"
    data-full-width="{{ $fullWidth ? 'true' : 'false' }}"
    data-show-selection-checkboxes="{{ $showCheckboxes ? 'true' : 'false' }}"
    data-disallow-empty-selection="{{ $disallowEmptySelection ? 'true' : 'false' }}"
    data-disable-animation="{{ $disableAnimation ? 'true' : 'false' }}"
    data-top-content-placement="{{ $topContentPlacement }}"
    data-bottom-content-placement="{{ $bottomContentPlacement }}"
    data-selected-keys='@json($initialKeys)'
    data-disabled-keys='@json($disabledKeys)'
    data-sort-column="{{ $sortColumn }}"
    data-sort-direction="{{ $sortDirection }}"
    data-auto-sort="{{ $autoSort ? 'true' : 'false' }}"
    data-name="{{ $name }}"
    style="{{ $rootStyle }}"
    {{ $attributes->except(['aria-label', 'style'])->class("app-table-base app-color-$color app-radius-$radius") }}
>
    @if(isset($topContent) && $topContentPlacement === 'outside')
        <div data-slot="topContent" class="app-table-top-content app-table-content-outside">{{ $topContent }}</div>
    @endif

    @if(!$removeWrapper)
        <div data-slot="wrapper" class="app-table-wrapper">
    @endif

    @if(isset($topContent) && $topContentPlacement === 'inside')
        <div data-slot="topContent" class="app-table-top-content">{{ $topContent }}</div>
    @endif

    <div data-slot="tableContainer" class="app-table-container">
        <table
            @if($tableId) id="{{ $tableId }}" @endif
            data-slot="table"
            role="grid"
            aria-label="{{ $tableLabel }}"
            @if($selectionMode === 'multiple') aria-multiselectable="true" @endif
            @if($selectionMode !== 'none') tabindex="0" @endif
            class="app-table"
        >
            {{ $slot }}
        </table>
    </div>

    @if(isset($bottomContent) && $bottomContentPlacement === 'inside')
        <div data-slot="bottomContent" class="app-table-bottom-content">{{ $bottomContent }}</div>
    @endif

    @if(!$removeWrapper)
        </div>
    @endif

    @if(isset($bottomContent) && $bottomContentPlacement === 'outside')
        <div data-slot="bottomContent" class="app-table-bottom-content app-table-content-outside">{{ $bottomContent }}</div>
    @endif

    @if($name)
        <span data-table-inputs hidden>
            @foreach($initialKeys as $key)
                <input type="hidden" name="{{ $selectionMode === 'multiple' ? $name.'[]' : $name }}" value="{{ $key }}">
            @endforeach
        </span>
    @endif

    <span data-table-live-region class="app-visually-hidden" aria-live="polite" aria-atomic="true"></span>
</div>
