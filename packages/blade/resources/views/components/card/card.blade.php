<{{ $elementType }} {{ $attributes->class(['jds-card', "jds-card--{$variant}"])->merge([
    'data-slot' => 'card',
    'data-surface-variant' => $variant === 'transparent' ? null : $variant,
]) }}>
    {{ $slot }}
</{{ $elementType }}>
