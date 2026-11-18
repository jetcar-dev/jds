@php
    $disabled = $isDisabled || ! in_array($attributes->get('disabled'), [null, false], true);
    $buttonType = $isPending ? 'button' : $type;
@endphp

<button
    type="{{ $buttonType }}"
    @if ($disabled) disabled data-disabled="true" @endif
    @if ($isPending) is-pending data-jds-pending-type="{{ $type }}" aria-disabled="true" aria-busy="true" data-pending="true" @endif
    {{ $attributes
        ->except($isPending ? ['disabled', 'aria-disabled', 'aria-busy', 'data-disabled', 'data-pending'] : ['disabled', 'data-disabled'])
        ->class($classes())
        ->merge(['data-slot' => 'button']) }}
>@if ($loadingText !== null)
    <span class="jds-button__content">{{ $slot }}</span>
    <span class="jds-button__loading-text">{{ $loadingText }}</span>
@else
    {{ $slot }}
@endif</button>
