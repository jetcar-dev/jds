@props(['title' => null, 'label' => null, 'showDivider' => false])
@php $sectionId = 'listbox-section-'.uniqid(); @endphp
<li
    data-slot="section"
    data-has-title="{{ $title ? 'true' : 'false' }}"
    data-show-divider="{{ $showDivider ? 'true' : 'false' }}"
    class="app-listbox-section"
    role="presentation"
>
    @if($title)<div id="{{ $sectionId }}" data-slot="heading" class="app-listbox-section-label">{{ $title }}</div>@endif
    <ul data-slot="group" role="group" @if($title) aria-labelledby="{{ $sectionId }}" @elseif($label) aria-label="{{ $label }}" @endif>
        {{ $slot }}
    </ul>
</li>
