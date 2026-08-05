@props(['name','description'=>null,'avatar'=>null,'href'=>null])
<div data-slot="user" {{ $attributes->class('app-user') }}>
    <x-avatar :src="$avatar" :name="$name" size="sm" />
    <span class="app-user-copy">
        @if($href)<a href="{{ $href }}" class="app-user-name">{{ $name }}</a>@else<span class="app-user-name">{{ $name }}</span>@endif
        @if($description)<span class="app-user-description">{{ $description }}</span>@endif
    </span>
</div>
