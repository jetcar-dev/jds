<span {{ $attributes->class('jds-checkbox__indicator')->merge(['data-slot' => 'checkbox-indicator', 'aria-hidden' => 'true']) }}>
    @if ($slot->isEmpty())
        <svg data-slot="checkbox-default-indicator--checkmark" viewBox="0 0 17 18" fill="none" stroke="currentColor" stroke-dasharray="22" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" role="presentation">
            <polyline points="1 9 7 14 15 4" />
        </svg>
        <svg data-slot="checkbox-default-indicator--indeterminate" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" aria-hidden="true" role="presentation">
            <line x1="21" x2="3" y1="12" y2="12" />
        </svg>
    @else
        {{ $slot }}
    @endif
</span>
