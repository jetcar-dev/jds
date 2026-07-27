@props(['orientation' => 'vertical', 'invalid' => false, 'disabled' => false])

@php $orientation = in_array($orientation, ['vertical', 'horizontal', 'responsive'], true) ? $orientation : 'vertical'; @endphp

<div
    role="group"
    data-slot="field"
    data-orientation="{{ $orientation }}"
    data-invalid="{{ $invalid ? 'true' : 'false' }}"
    data-disabled="{{ $disabled ? 'true' : 'false' }}"
    {{ $attributes->class(['app-field', 'app-field-'.$orientation]) }}
>
    {{ $slot }}
</div>
