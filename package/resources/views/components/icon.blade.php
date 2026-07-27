@props(['name' => null])
@php
    $name = (string) $name;
    $icon = str_contains($name, ':') ? $name : 'solar:' . $name;
    $directional = preg_match('/(arrow|chevron|caret)/i', $icon) && preg_match('/(left|right)/i', $icon);
@endphp
<span data-slot="icon" data-icon="{{ $icon }}" role="img" aria-hidden="true" {{ $attributes->class(['app-icon', 'app-icon-rtl-flip' => $directional]) }}></span>
