@props(['for' => null, 'required' => false])

<label
    @if($for) for="{{ $for }}" @endif
    data-slot="label"
    data-required="{{ $required ? 'true' : 'false' }}"
    {{ $attributes->class('app-label') }}
>
    {{ $slot }}
</label>
