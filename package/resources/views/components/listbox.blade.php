@props([
    'name' => null,
    'value' => null,
    'selectionMode' => 'none',
    'selectionBehavior' => 'toggle',
    'variant' => 'solid',
    'color' => 'default',
    'label' => null,
    'disabledKeys' => [],
    'disallowEmptySelection' => false,
    'shouldHighlightOnFocus' => false,
    'shouldFocusWrap' => false,
    'hideSelectedIcon' => false,
    'emptyContent' => '표시할 항목이 없습니다.',
    'hideEmptyContent' => false,
    'virtualized' => false,
    'maxListboxHeight' => 256,
    'itemHeight' => 40,
    'disableAnimation' => false,
])
@php
    $selectionMode = in_array($selectionMode, ['none', 'single', 'multiple'], true) ? $selectionMode : 'none';
    $selectionBehavior = in_array($selectionBehavior, ['toggle', 'replace'], true) ? $selectionBehavior : 'toggle';
    $variant = in_array($variant, ['solid', 'bordered', 'light', 'flat', 'faded', 'shadow'], true) ? $variant : 'solid';
    $color = in_array($color, ['default', 'primary', 'secondary', 'success', 'warning', 'danger'], true) ? $color : 'default';
    $values = is_array($value) ? array_values($value) : ($value === null || $value === '' ? [] : [$value]);
    $disabledKeys = is_array($disabledKeys) ? array_values($disabledKeys) : [];
    $listboxId = 'listbox-'.uniqid();
@endphp

<div
    data-slot="base"
    data-ui-component="listbox"
    data-name="{{ $name }}"
    data-values='@json($values)'
    data-selection-mode="{{ $selectionMode }}"
    data-selection-behavior="{{ $selectionBehavior }}"
    data-variant="{{ $variant }}"
    data-color="{{ $color }}"
    data-disabled-keys='@json($disabledKeys)'
    data-disallow-empty-selection="{{ $disallowEmptySelection ? 'true' : 'false' }}"
    data-should-highlight-on-focus="{{ $shouldHighlightOnFocus ? 'true' : 'false' }}"
    data-should-focus-wrap="{{ $shouldFocusWrap ? 'true' : 'false' }}"
    data-hide-selected-icon="{{ $hideSelectedIcon ? 'true' : 'false' }}"
    data-hide-empty-content="{{ $hideEmptyContent ? 'true' : 'false' }}"
    data-virtualized="{{ $virtualized ? 'true' : 'false' }}"
    data-max-listbox-height="{{ max(1, (int) $maxListboxHeight) }}"
    data-item-height="{{ max(1, (int) $itemHeight) }}"
    data-disable-animation="{{ $disableAnimation ? 'true' : 'false' }}"
    class="app-listbox-base app-color-{{ $color }}"
>
    <input data-listbox-input type="hidden" value="{{ $selectionMode === 'multiple' ? json_encode($values) : ($values[0] ?? '') }}">
    <span data-listbox-form-values>
        @if($name)
            @foreach($values as $selectedValue)<input type="hidden" name="{{ $name }}{{ $selectionMode === 'multiple' ? '[]' : '' }}" value="{{ $selectedValue }}">@endforeach
        @endif
    </span>

    @isset($topContent)<div data-slot="top-content" class="app-listbox-content">{{ $topContent }}</div>@endisset

    <ul
        id="{{ $listboxId }}"
        data-slot="list"
        data-selection-mode="{{ $selectionMode }}"
        data-virtualize="{{ $virtualized ? 'true' : 'false' }}"
        data-item-height="{{ max(1, (int) $itemHeight) }}"
        class="app-listbox"
        role="listbox"
        @if($selectionMode === 'multiple') aria-multiselectable="true" @endif
        @if($label) aria-label="{{ $label }}" @endif
        {{ $attributes }}
    >
        @if(trim((string) $slot) === '')
            @unless($hideEmptyContent)<li role="presentation"><div data-slot="empty-content" class="app-listbox-empty">{{ $emptyContent }}</div></li>@endunless
        @else
            {{ $slot }}
        @endif
    </ul>

    @isset($bottomContent)<div data-slot="bottom-content" class="app-listbox-content">{{ $bottomContent }}</div>@endisset
</div>
