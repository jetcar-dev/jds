@props(['icon' => 'chevron', 'iconPosition' => 'right', 'disabled' => false])

@php $iconPosition = $iconPosition === 'left' ? 'left' : 'right'; @endphp

<h3 class="app-accordion-heading">
    <button type="button" data-slot="accordion-trigger" data-icon="{{ $icon }}" data-icon-position="{{ $iconPosition }}" aria-expanded="false" @disabled($disabled) {{ $attributes->class('app-accordion-trigger') }}>
        @if($icon !== 'none')
            <span class="app-accordion-icon" aria-hidden="true">
                @if($icon === 'plus-minus')<x-icon name="add-circle-linear" data-icon-closed /><x-icon name="minus-circle-linear" data-icon-open />
                @elseif($icon === 'plus')<x-icon name="add-circle-linear" />
                @elseif($icon === 'chevron-left')<x-icon name="alt-arrow-left-linear" />
                @elseif($icon === 'chevron-updown')<x-icon name="alt-arrow-down-linear" data-icon-closed /><x-icon name="alt-arrow-up-linear" data-icon-open />
                @else<x-icon name="alt-arrow-down-linear" />
                @endif
            </span>
        @endif
        <span class="app-accordion-trigger-label">{{ $slot }}</span>
    </button>
</h3>
