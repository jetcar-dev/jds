<{{ $elementType }} {{ $attributes->class(['jds-surface', "jds-surface--{$variant}"])->merge([
    'data-slot' => 'surface',
    'data-surface-variant' => $variant,
]) }}>
    {{ $slot }}
</{{ $elementType }}>
