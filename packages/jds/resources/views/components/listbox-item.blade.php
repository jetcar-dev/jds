@props([
    'value',
    'textValue' => null,
    'selected' => false,
    'disabled' => false,
    'readOnly' => false,
    'variant' => null,
    'color' => null,
    'href' => null,
    'description' => null,
    'shortcut' => null,
    'hideSelectedIcon' => false,
    'showDivider' => false,
    'context' => 'listbox',
    'itemKey' => null,
    'closeOnSelect' => null,
    'target' => null,
    'itemAttributes' => null,
])
@php
    $isDropdown = $context === 'dropdown';
    $tag = $href ? 'a' : ($isDropdown ? 'div' : 'li');
    $itemId = 'listbox-option-'.uniqid();
    $rootAttributes = $itemAttributes instanceof \Illuminate\View\ComponentAttributeBag
        ? $itemAttributes
        : $attributes;
@endphp

<{{ $tag }}
    id="{{ $itemId }}"
    data-slot="{{ $isDropdown ? 'dropdown-item' : 'listbox-item' }}"
    data-collection-item="true"
    data-context="{{ $context }}"
    data-ui-interactive
    @if($isDropdown) data-key="{{ $itemKey ?? $value }}" @endif
    data-value="{{ $value }}"
    data-text-value="{{ $textValue ?? trim((string) $slot) }}"
    data-selected="{{ $selected ? 'true' : 'false' }}"
    data-disabled="{{ $disabled ? 'true' : 'false' }}"
    data-readonly="{{ $readOnly ? 'true' : 'false' }}"
    data-item-variant="{{ $variant }}"
    data-item-color="{{ $color }}"
    data-hide-selected-icon="{{ $hideSelectedIcon ? 'true' : 'false' }}"
    data-show-divider="{{ $showDivider ? 'true' : 'false' }}"
    @if($isDropdown) data-close-on-select="{{ $closeOnSelect === null ? 'auto' : ($closeOnSelect ? 'true' : 'false') }}" @endif
    role="{{ $isDropdown ? 'menuitem' : 'option' }}"
    @unless($isDropdown) aria-selected="{{ $selected ? 'true' : 'false' }}" @endunless
    aria-disabled="{{ $disabled ? 'true' : 'false' }}"
    tabindex="-1"
    @if($href) href="{{ $href }}" @endif
    @if($target) target="{{ $target }}" @endif
    {{ $rootAttributes->class(['app-listbox-item', 'app-color-'.$color => $color]) }}
>
    @isset($startContent)<span data-slot="start-content" class="app-listbox-item-start">{{ $startContent }}</span>@endisset

    <span data-slot="wrapper" class="app-listbox-item-copy">
        <span data-slot="title" data-label="true" class="app-listbox-item-label">{{ $slot }}</span>
        @if($description)<span data-slot="description" class="app-listbox-item-description">{{ $description }}</span>@endif
    </span>

    @if($shortcut)
        <span data-slot="shortcut" class="app-listbox-shortcut">
            <x-kbd>{{ $shortcut }}</x-kbd>
        </span>
    @endif

    <span data-slot="selected-icon" class="app-listbox-check" aria-hidden="true">
        @isset($selectedIcon){{ $selectedIcon }}@else
            <svg viewBox="0 0 17 18" focusable="false"><polyline points="1 9 7 14 15 4" /></svg>
        @endisset
    </span>

    @isset($endContent)<span data-slot="end-content" class="app-listbox-item-end">{{ $endContent }}</span>@endisset
</{{ $tag }}>
