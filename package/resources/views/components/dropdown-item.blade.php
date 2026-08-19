@props([
    'key' => null,
    'textValue' => null,
    'href' => null,
    'target' => null,
    'selected' => false,
    'disabled' => false,
    'readonly' => false,
    'color' => null,
    'variant' => null,
    'description' => null,
    'shortcut' => null,
    'closeOnSelect' => null,
    'hideSelectedIcon' => false,
    'showDivider' => false,
])
@php
    $key = $key ?? trim((string) $slot);
    $color = in_array($color, ['default', 'primary', 'secondary', 'success', 'warning', 'danger'], true) ? $color : null;
    $variant = in_array($variant, ['solid', 'bordered', 'light', 'flat', 'faded', 'shadow'], true) ? $variant : null;
@endphp

<x-listbox-item
    context="dropdown"
    :value="$key"
    :item-key="$key"
    :text-value="$textValue"
    :href="$href"
    :target="$target"
    :selected="$selected"
    :disabled="$disabled"
    :read-only="$readonly"
    :color="$color"
    :variant="$variant"
    :description="$description"
    :shortcut="$shortcut"
    :close-on-select="$closeOnSelect"
    :hide-selected-icon="$hideSelectedIcon"
    :show-divider="$showDivider"
    :item-attributes="$attributes"
>
    @isset($startContent)
        <x-slot:startContent>{{ $startContent }}</x-slot:startContent>
    @endisset
    @isset($selectedIcon)
        <x-slot:selectedIcon>{{ $selectedIcon }}</x-slot:selectedIcon>
    @endisset
    @isset($endContent)
        <x-slot:endContent>{{ $endContent }}</x-slot:endContent>
    @endisset
    {{ $slot }}
</x-listbox-item>
