@props([
    'name' => null,
    'total' => 1,
    'page' => null,
    'initialPage' => 1,
    'siblings' => 1,
    'boundaries' => 1,
    'dotsJump' => 5,
    'variant' => 'flat',
    'color' => 'primary',
    'size' => 'md',
    'radius' => 'md',
    'showControls' => false,
    'compact' => false,
    'loop' => false,
    'showShadow' => false,
    'disabled' => false,
    'disableCursorAnimation' => false,
    'disableAnimation' => false,
    'ariaLabel' => 'pagination navigation',
    'previousLabel' => '이전 페이지',
    'nextLabel' => '다음 페이지',
])

@php
    $variants = ['flat', 'bordered', 'faded', 'light'];
    $colors = ['default', 'primary', 'secondary', 'success', 'warning', 'danger'];
    $sizes = ['sm', 'md', 'lg'];
    $radii = ['none', 'sm', 'md', 'lg', 'full'];

    $variant = in_array($variant, $variants, true) ? $variant : 'flat';
    $color = in_array($color, $colors, true) ? $color : 'primary';
    $size = in_array($size, $sizes, true) ? $size : 'md';
    $radius = in_array($radius, $radii, true) ? $radius : 'md';
    $total = max(1, (int) $total);
    $siblings = max(0, (int) $siblings);
    $boundaries = max(0, (int) $boundaries);
    $dotsJump = max(1, (int) $dotsJump);
    $page = max(1, min($total, (int) ($page ?? $initialPage)));

    $range = static fn (int $from, int $to): array => $from <= $to ? range($from, $to) : [];
    $totalPageNumbers = ($siblings * 2) + 3 + ($boundaries * 2);

    if ($totalPageNumbers >= $total) {
        $items = $range(1, $total);
    } else {
        $startPages = $range(1, min($boundaries, $total));
        $endPages = $range(max($total - $boundaries + 1, $boundaries + 1), $total);
        $siblingsStart = max(min($page - $siblings, $total - $boundaries - ($siblings * 2) - 1), $boundaries + 2);
        $siblingsEnd = min(max($page + $siblings, $boundaries + ($siblings * 2) + 2), $total - $boundaries - 1);
        $startGap = $siblingsStart > $boundaries + 2
            ? ['before']
            : ($boundaries + 1 < $total - $boundaries ? [$boundaries + 1] : []);
        $endGap = $siblingsEnd < $total - $boundaries - 1
            ? ['after']
            : ($total - $boundaries > $boundaries ? [$total - $boundaries] : []);
        $items = [...$startPages, ...$startGap, ...$range($siblingsStart, $siblingsEnd), ...$endGap, ...$endPages];
    }
@endphp

<nav
    role="navigation"
    aria-label="{{ $ariaLabel }}"
    data-ui-component="pagination"
    data-slot="base"
    data-total="{{ $total }}"
    data-active-page="{{ $page }}"
    data-initial-page="{{ $page }}"
    data-siblings="{{ $siblings }}"
    data-boundaries="{{ $boundaries }}"
    data-dots-jump="{{ $dotsJump }}"
    data-variant="{{ $variant }}"
    data-color="{{ $color }}"
    data-size="{{ $size }}"
    data-radius="{{ $radius }}"
    data-show-controls="{{ $showControls ? 'true' : 'false' }}"
    data-compact="{{ $compact ? 'true' : 'false' }}"
    data-loop="{{ $loop ? 'true' : 'false' }}"
    data-show-shadow="{{ $showShadow ? 'true' : 'false' }}"
    data-disabled="{{ $disabled ? 'true' : 'false' }}"
    data-disable-cursor-animation="{{ $disableCursorAnimation ? 'true' : 'false' }}"
    data-disable-animation="{{ $disableAnimation ? 'true' : 'false' }}"
    data-previous-label="{{ $previousLabel }}"
    data-next-label="{{ $nextLabel }}"
    {{ $attributes->except('aria-label')->class(["app-pagination app-color-$color app-radius-$radius"]) }}
>
    <ul data-slot="wrapper" class="app-pagination-wrapper">
        <span data-slot="cursor" class="app-pagination-cursor" aria-hidden="true">{{ $page }}</span>

        @if($showControls)
            <li role="button" tabindex="{{ !$loop && $page === 1 ? '-1' : '0' }}" aria-label="{{ $previousLabel }}"
                @if(!$loop && $page === 1) aria-disabled="true" @endif
                data-disabled="{{ !$loop && $page === 1 ? 'true' : 'false' }}" data-slot="prev" data-control="prev"
                data-page="{{ $page === 1 ? ($loop ? $total : 1) : $page - 1 }}" class="app-pagination-item app-pagination-control"
            ><x-icon name="solar:alt-arrow-left-linear" /></li>
        @endif

        @foreach($items as $item)
            @if(is_int($item))
                <li role="button" tabindex="0" aria-label="{{ $item === $page ? "페이지 {$item}, 현재 페이지" : "페이지 {$item}" }}"
                    @if($item === $page) aria-current="page" @endif
                    data-slot="item" data-page="{{ $item }}" data-active="{{ $item === $page ? 'true' : 'false' }}" class="app-pagination-item"
                >{{ $item }}</li>
            @else
                @php
                    $before = $item === 'before';
                    $target = $before ? max(1, $page - $dotsJump) : min($total, $page + $dotsJump);
                @endphp
                <li role="button" tabindex="0" aria-label="{{ $before ? '이전 페이지 묶음' : '다음 페이지 묶음' }}"
                    data-slot="item" data-kind="dots" data-direction="{{ $item }}" data-page="{{ $target }}"
                    class="app-pagination-item app-pagination-dots"
                >
                    <svg data-slot="ellipsis" aria-hidden="true" viewBox="0 0 24 24"><circle cx="5" cy="12" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="19" cy="12" r="1"/></svg>
                    <svg data-slot="forward-icon" data-before="{{ $before ? 'true' : 'false' }}" aria-hidden="true" viewBox="0 0 24 24"><path d="m13 17 5-5-5-5"/><path d="m6 17 5-5-5-5"/></svg>
                </li>
            @endif
        @endforeach

        @if($showControls)
            <li role="button" tabindex="{{ !$loop && $page === $total ? '-1' : '0' }}" aria-label="{{ $nextLabel }}"
                @if(!$loop && $page === $total) aria-disabled="true" @endif
                data-disabled="{{ !$loop && $page === $total ? 'true' : 'false' }}" data-slot="next" data-control="next"
                data-page="{{ $page === $total ? ($loop ? 1 : $total) : $page + 1 }}" class="app-pagination-item app-pagination-control app-pagination-next"
            ><x-icon name="solar:alt-arrow-left-linear" /></li>
        @endif
    </ul>

    @if($name)
        <input type="hidden" name="{{ $name }}" value="{{ $page }}" data-pagination-input>
    @endif
</nav>
