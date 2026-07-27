@props(['showClose' => true, 'fullscreen' => false, 'position' => 'center'])
@php
    $positions = ['center','top','bottom','left','right','top-left','top-right','bottom-left','bottom-right'];
    $position = in_array($position, $positions, true) ? $position : 'center';
@endphp
<div data-modal-layer data-slot="modal-layer" hidden class="app-modal-layer">
    <div data-slot="modal-overlay" data-modal-overlay class="app-modal-overlay" aria-hidden="true"></div>
    <div role="dialog" aria-modal="true" tabindex="-1" data-slot="modal-content" data-modal-panel data-position="{{ $position }}" @if($fullscreen) data-fullscreen="true" @endif {{ $attributes->class('app-modal-content') }}>
        {{ $slot }}
        @if($showClose)
            <button type="button" data-slot="modal-close" data-modal-close class="app-modal-x" aria-label="Close">
                <x-icon name="close-circle-linear" />
                <span class="app-sr-only">Close</span>
            </button>
        @endif
    </div>
</div>
