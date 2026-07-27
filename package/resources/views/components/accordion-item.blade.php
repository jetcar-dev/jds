@props(['value' => null])

<div data-slot="accordion-item" data-value="{{ $value ?? uniqid('accordion-') }}" data-state="closed" {{ $attributes->class('app-accordion-item') }}>
    {{ $slot }}
</div>
