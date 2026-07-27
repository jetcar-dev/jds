@props(['for' => null])
<span data-slot="modal-trigger" @if($for) data-modal-target="{{ $for }}" @endif aria-haspopup="dialog" {{ $attributes->class('app-modal-trigger') }}>
    {{ $slot }}
</span>
