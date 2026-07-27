@props(['value' => ''])

<div data-slot="dropdown-menu-radio-group" data-value="{{ $value }}" role="group" {{ $attributes }}>
    {{ $slot }}
</div>
