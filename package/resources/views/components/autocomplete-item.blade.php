@props(['value','textValue'=>null,'selected'=>false,'disabled'=>false,'description'=>null])
<div data-slot="listbox-item" data-ui-interactive data-value="{{ $value }}" data-text-value="{{ $textValue ?? trim((string)$slot) }}" data-selected="{{ $selected?'true':'false' }}" data-disabled="{{ $disabled?'true':'false' }}" role="option" aria-selected="{{ $selected?'true':'false' }}" aria-disabled="{{ $disabled?'true':'false' }}" {{ $attributes->class('app-listbox-item') }}>
    @isset($startContent){{ $startContent }}@endisset
    <span>{{ $slot }}</span>
    @if($description)<span class="app-choice-description">{{ $description }}</span>@endif
    @isset($endContent){{ $endContent }}@endisset
    <span class="app-listbox-check" aria-hidden="true">✓</span>
</div>
