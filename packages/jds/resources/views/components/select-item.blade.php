@props(['value', 'textValue' => null, 'selected' => false, 'disabled' => false, 'description' => null])
<div
    id="select-option-{{ uniqid() }}"
    data-slot="listbox-item"
    data-ui-interactive
    data-value="{{ $value }}"
    data-text-value="{{ $textValue ?? trim((string) $slot) }}"
    data-selected="{{ $selected ? 'true' : 'false' }}"
    data-disabled="{{ $disabled ? 'true' : 'false' }}"
    role="option"
    aria-selected="{{ $selected ? 'true' : 'false' }}"
    aria-disabled="{{ $disabled ? 'true' : 'false' }}"
    tabindex="-1"
    {{ $attributes->class('app-listbox-item') }}
>
    @isset($startContent)<span data-slot="startContent" class="app-listbox-item-start">{{ $startContent }}</span>@endisset
    <span data-slot="textWrapper" class="app-listbox-item-copy">
        <span data-slot="title" data-label="true" class="app-listbox-item-label">{{ $slot }}</span>
        @if($description)<span data-slot="description" class="app-choice-description">{{ $description }}</span>@endif
    </span>
    @isset($endContent)<span data-slot="endContent" class="app-listbox-item-end">{{ $endContent }}</span>@endisset
    <span data-slot="selectedIcon" class="app-listbox-check" aria-hidden="true">
        @isset($selectedIcon){{ $selectedIcon }}@else
            <svg viewBox="0 0 17 18" focusable="false"><polyline points="1 9 7 14 15 4" /></svg>
        @endisset
    </span>
</div>
