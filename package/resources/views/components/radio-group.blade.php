@props([
    'name' => null,
    'value' => null,
])

<div
    data-slot="radio-group"
    data-value="{{ $value }}"
    data-has-value="{{ $value === null ? 'false' : 'true' }}"
    role="radiogroup"
    {{ $attributes->class('app-radio-group') }}
>
    @if($name)
        <input
            type="hidden"
            name="{{ $name }}"
            value="{{ $value }}"
            data-radio-group-input
        >
    @endif

    {{ $slot }}
</div>
