@props([
    'placement' => null,
    'offset' => null,
    'variant' => 'solid',
    'color' => 'default',
    'selectionMode' => 'none',
    'selectionBehavior' => 'toggle',
    'selectedKeys' => [],
    'disabledKeys' => [],
    'disallowEmptySelection' => false,
    'closeOnSelect' => null,
    'hideSelectedIcon' => false,
    'emptyContent' => '표시할 항목이 없습니다.',
    'ariaLabel' => '작업 메뉴',
])
@php
    $variant = in_array($variant, ['solid', 'bordered', 'light', 'flat', 'faded', 'shadow'], true) ? $variant : 'solid';
    $color = in_array($color, ['default', 'primary', 'secondary', 'success', 'warning', 'danger'], true) ? $color : 'default';
    $selectionMode = in_array($selectionMode, ['none', 'single', 'multiple'], true) ? $selectionMode : 'none';
    $selectionBehavior = in_array($selectionBehavior, ['toggle', 'replace'], true) ? $selectionBehavior : 'toggle';
    $selectedKeys = is_array($selectedKeys) ? array_values($selectedKeys) : [$selectedKeys];
    $disabledKeys = is_array($disabledKeys) ? array_values($disabledKeys) : [$disabledKeys];
    $menuId = 'dropdown-menu-'.uniqid();
@endphp

<div
    data-slot="dropdown-content"
    data-variant="{{ $variant }}"
    data-color="{{ $color }}"
    data-selection-mode="{{ $selectionMode }}"
    data-selection-behavior="{{ $selectionBehavior }}"
    data-selected-keys='@json($selectedKeys)'
    data-disabled-keys='@json($disabledKeys)'
    data-disallow-empty-selection="{{ $disallowEmptySelection ? 'true' : 'false' }}"
    data-close-on-select="{{ $closeOnSelect === null ? 'auto' : ($closeOnSelect ? 'true' : 'false') }}"
    data-hide-selected-icon="{{ $hideSelectedIcon ? 'true' : 'false' }}"
    @if($placement) data-placement="{{ $placement }}" @endif
    @if($offset !== null) data-offset="{{ max(0, (int) $offset) }}" @endif
    hidden
    {{ $attributes->class("app-dropdown-content app-color-$color") }}
>
    <div data-slot="base" class="app-dropdown-menu-base">
        @isset($topContent)<div data-slot="top-content" class="app-dropdown-edge-content">{{ $topContent }}</div>@endisset

        <div
            id="{{ $menuId }}"
            data-slot="list"
            class="app-dropdown-list"
            role="menu"
            aria-label="{{ $ariaLabel }}"
            @if($selectionMode === 'multiple') aria-multiselectable="true" @endif
        >
            @if(trim((string) $slot) === '')
                <div data-slot="empty-content" class="app-dropdown-empty">{{ $emptyContent }}</div>
            @else
                {{ $slot }}
            @endif
        </div>

        @isset($bottomContent)<div data-slot="bottom-content" class="app-dropdown-edge-content">{{ $bottomContent }}</div>@endisset
    </div>
</div>
