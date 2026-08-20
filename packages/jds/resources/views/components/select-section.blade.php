@props(['title' => null])
@php $sectionId = 'select-section-'.uniqid(); @endphp
<section data-slot="listbox-section" role="group" @if($title) aria-labelledby="{{ $sectionId }}" @endif {{ $attributes }}>
    @if($title)<div id="{{ $sectionId }}" data-slot="heading" class="app-listbox-section-label">{{ $title }}</div>@endif
    {{ $slot }}
</section>
