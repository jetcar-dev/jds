@props(['emptyContent' => null])
<tbody data-slot="tbody" {{ $attributes }}>{{ $slot }}@if(trim((string)$slot)==='' && $emptyContent)<tr><td data-slot="empty-wrapper">{{ $emptyContent }}</td></tr>@endif</tbody>
