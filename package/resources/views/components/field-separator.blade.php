<div data-slot="field-separator" {{ $attributes->class('app-field-separator') }}>
    <span></span>
    @if(trim($slot) !== '')<em>{{ $slot }}</em>@endif
</div>
