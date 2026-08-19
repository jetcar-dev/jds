@props(['value', 'selected' => false])

<div
    data-slot="panel"
    data-value="{{ $value }}"
    data-selected="{{ $selected ? 'true' : 'false' }}"
    role="tabpanel"
    tabindex="0"
    @unless($selected) hidden inert @endunless
    {{ $attributes->class('app-tab-panel') }}
>{{ $slot }}</div>
