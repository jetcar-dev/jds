@props(['title'=>null])
<section data-slot="dropdown-section">@if($title)<div class="app-listbox-section-label">{{ $title }}</div>@endif{{ $slot }}</section>
