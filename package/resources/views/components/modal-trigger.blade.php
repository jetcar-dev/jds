@props(['for'=>null])
<span data-slot="modal-trigger" @if($for) data-overlay-target="{{ $for }}" @endif class="app-modal-trigger" {{ $attributes }}>{{ $slot }}</span>
