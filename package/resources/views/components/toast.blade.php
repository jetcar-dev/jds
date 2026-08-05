@props(['title' => null, 'description' => null, 'color' => 'default', 'timeout' => 5000, 'closable' => true])
<div data-slot="toast" role="status" data-color="{{ $color }}" data-timeout="{{ $timeout }}" data-dismissible="{{ $closable?'true':'false' }}" {{ $attributes->class("app-toast app-color-$color") }}>
    <span data-slot="icon">{{ $icon ?? '' }}</span><div><div data-slot="title" class="app-toast-title">{{ $title }}</div><div data-slot="description" class="app-toast-description">{{ $description ?? $slot }}</div></div>
    @if($closable)<button type="button" data-slot="close-button" data-dismiss aria-label="닫기">×</button>@endif
</div>
