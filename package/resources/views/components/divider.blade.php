@props(['orientation'=>'horizontal'])
<hr data-slot="divider" data-orientation="{{ $orientation==='vertical'?'vertical':'horizontal' }}" aria-orientation="{{ $orientation==='vertical'?'vertical':'horizontal' }}" {{ $attributes->class('app-divider') }} />
