@props(['for' => null, 'required' => false])
<label
    @if($for) for="{{ $for }}" @endif
    data-slot="field-label"
    data-required="{{ $required ? 'true' : 'false' }}"
    {{ $attributes->class('app-field-label') }}
>{{ $slot }}</label>
