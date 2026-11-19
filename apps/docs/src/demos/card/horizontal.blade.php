<x-jds::card class="drive-card">
    <img src="/images/card/mountain-road.jpg" alt="산맥으로 이어지는 도로" />
    <x-jds::card.content style="gap: .75rem; min-width: 0;">
        <x-jds::card.header style="padding-inline-end: 2rem; gap: .25rem;">
            <x-jds::card.title>주말 드라이브 가이드</x-jds::card.title>
            <x-jds::card.description>출발 전에 차량과 경로를 확인하세요.</x-jds::card.description>
        </x-jds::card.header>
        <x-jds::card.footer class="drive-card-actions">
            <div style="display: grid;">
                <span style="font-size: .875rem; font-weight: 500;">주말 추천 경로</span>
                <small style="font-size: .75rem; color: var(--muted);">차량 준비부터 출발까지</small>
            </div>
            <a href="/docs" class="jds-button jds-button--primary jds-button--md">가이드 보기</a>
        </x-jds::card.footer>
    </x-jds::card.content>
    <x-jds::button
        variant="ghost" size="sm" is-icon-only aria-label="배너 닫기"
        style="position: absolute; top: .75rem; right: .75rem;"
        onclick="this.closest('.drive-card').remove()"
    >×</x-jds::button>
</x-jds::card>

<style>
    .drive-card { width: 100%; }
    .drive-card > img { height: 140px; width: 100%; flex: none; object-fit: cover; border-radius: 1rem; }
    .drive-card-actions { flex-direction: column; align-items: stretch; justify-content: space-between; gap: .75rem; margin-top: auto; }
    @media (width >= 37rem) {
        .drive-card { flex-direction: row; }
        .drive-card > img { width: 120px; height: 120px; }
        .drive-card-actions { flex-direction: row; align-items: center; }
    }
</style>
