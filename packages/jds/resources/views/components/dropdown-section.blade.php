@props(['title' => null, 'showDivider' => false])
@php $headingId = $title ? 'dropdown-section-'.uniqid() : null; @endphp

<section
    data-slot="section"
    data-show-divider="{{ $showDivider ? 'true' : 'false' }}"
    role="presentation"
    {{ $attributes->class('app-dropdown-section') }}
>
    @if($title)<div id="{{ $headingId }}" data-slot="heading" class="app-dropdown-section-heading">{{ $title }}</div>@endif
    <div data-slot="group" class="app-dropdown-section-group" role="group" @if($headingId) aria-labelledby="{{ $headingId }}" @endif>{{ $slot }}</div>
</section>
