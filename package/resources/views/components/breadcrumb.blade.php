@props(['items' => null, 'separator' => null, 'ariaLabel' => 'breadcrumb'])

@php
    $normalizedItems = [];
    if (is_iterable($items)) {
        foreach ($items as $label => $item) {
            if (is_array($item)) {
                $normalizedItems[] = [
                    'label' => (string)($item['label'] ?? $label),
                    'href' => $item['href'] ?? null,
                ];
            } else {
                $normalizedItems[] = [
                    'label' => (string)(is_int($label) ? $item : $label),
                    'href' => is_int($label) ? null : $item,
                ];
            }
        }
    }
@endphp

<nav aria-label="{{ $ariaLabel }}" data-slot="breadcrumb" {{ $attributes->class('app-breadcrumb') }}>
    @if(count($normalizedItems))
        <x-breadcrumb-list>
            @foreach($normalizedItems as $item)
                <x-breadcrumb-item>
                    @if(!$loop->last && filled($item['href']))
                        <x-breadcrumb-link :href="$item['href']">{{ $item['label'] }}</x-breadcrumb-link>
                    @else
                        <x-breadcrumb-page>{{ $item['label'] }}</x-breadcrumb-page>
                    @endif
                </x-breadcrumb-item>
                @unless($loop->last)
                    <x-breadcrumb-separator>{{ $separator }}</x-breadcrumb-separator>
                @endunless
            @endforeach
        </x-breadcrumb-list>
    @else
        {{ $slot }}
    @endif
</nav>
