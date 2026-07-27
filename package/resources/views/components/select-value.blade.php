@props([
    'placeholder' => '',
    'multiple' => false,
])

<span
    data-slot="select-value"
    data-placeholder="{{ $placeholder }}"
    data-multiple="{{ $multiple ? 'true' : 'false' }}"
    {{ $attributes->class('app-select-value') }}
>
    {{ $slot }}
</span>
