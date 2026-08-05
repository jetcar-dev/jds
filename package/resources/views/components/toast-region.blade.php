@props(['placement' => 'bottom-right'])
<div data-slot="toast-region" class="app-toast-region" data-placement="{{ $placement }}" aria-live="polite" {{ $attributes }}>{{ $slot }}</div>
