@props(['showClose' => true])
<div data-overlay-layer class="app-overlay-root" hidden>
    <div data-overlay-backdrop data-slot="backdrop" class="app-overlay-backdrop">
        @isset($backdrop){{ $backdrop }}@endisset
    </div>
    <div data-overlay-wrapper data-slot="wrapper" class="app-overlay-layer">
        <section data-drawer-content data-slot="base" class="app-drawer-panel" role="dialog" aria-modal="true" tabindex="-1" {{ $attributes }}>
            @if($showClose)
                <button type="button" data-overlay-close data-ui-interactive data-slot="closeButton" class="app-drawer-close" aria-label="닫기">
                    @isset($closeButton)
                        {{ $closeButton }}
                    @else
                        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true" focusable="false">
                            <path d="M18 6 6 18M6 6l12 12" />
                        </svg>
                    @endisset
                </button>
            @endif
            {{ $slot }}
        </section>
    </div>
</div>
