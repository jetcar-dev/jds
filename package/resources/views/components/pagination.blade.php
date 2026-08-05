@props(['total' => 1, 'page' => 1, 'siblings' => 1, 'boundaries' => 1, 'variant' => 'flat', 'color' => 'primary', 'size' => 'md', 'showControls' => false, 'compact' => false, 'loop' => false])
@php $total=max(1,(int)$total);$page=max(1,min($total,(int)$page)); @endphp
<nav aria-label="Pagination" data-slot="pagination" data-variant="{{ $variant }}" data-color="{{ $color }}" data-size="{{ $size }}" class="app-pagination app-color-{{ $color }} app-size-{{ $size }}">
    @if($showControls)<button type="button" class="app-pagination-item" aria-label="이전 페이지" data-page="{{ max(1,$page-1) }}" @disabled(!$loop && $page===1)>‹</button>@endif
    @for($i=1;$i<=$total;$i++)
        @if($i <= $boundaries || $i > $total-$boundaries || abs($i-$page) <= $siblings)
            <button type="button" class="app-pagination-item" data-page="{{ $i }}" data-active="{{ $i===$page?'true':'false' }}" @if($i===$page) aria-current="page" @endif>{{ $i }}</button>
        @elseif(($i===$boundaries+1 && $i<$page-$siblings)||($i===$page+$siblings+1 && $i<=$total-$boundaries))
            <span class="app-pagination-item" aria-hidden="true">…</span>
        @endif
    @endfor
    @if($showControls)<button type="button" class="app-pagination-item" aria-label="다음 페이지" data-page="{{ min($total,$page+1) }}" @disabled(!$loop && $page===$total)>›</button>@endif
</nav>
