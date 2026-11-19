<div class="card-gallery">
    <x-jds::card class="gallery-banner">
        <img class="gallery-banner-image" src="/images/card/mountain-road.jpg" alt="산맥으로 이어지는 도로" />
        <x-jds::card.content class="gallery-banner-content">
            <x-jds::card.header>
                <x-jds::card.title>주말 드라이브 프로그램</x-jds::card.title>
                <x-jds::card.description>추천 경로와 출발 전 차량 준비 사항을 확인하세요.</x-jds::card.description>
            </x-jds::card.header>
            <x-jds::card.footer class="gallery-actions">
                <div><strong>주말 출발</strong><small>예약 현황 확인</small></div>
                <a class="jds-button jds-button--primary jds-button--md" href="/docs">가이드 보기</a>
            </x-jds::card.footer>
        </x-jds::card.content>
        <x-jds::button class="gallery-dismiss" size="sm" variant="ghost" is-icon-only aria-label="배너 닫기" onclick="this.closest('.jds-card').remove()">×</x-jds::button>
    </x-jds::card>

    <div class="gallery-featured">
        <div class="gallery-clubs">
            <x-jds::card>
                <x-jds::card.header class="gallery-notice">
                    <span class="gallery-info" aria-hidden="true">₩</span>
                    <div>
                        <small>PAYMENT</small>
                        <x-jds::card.title>정기 결제 수단 관리</x-jds::card.title>
                        <x-jds::card.description>다음 청구에 사용할 결제 수단을 확인하세요.</x-jds::card.description>
                    </div>
                </x-jds::card.header>
                <x-jds::card.footer><a class="gallery-link" href="/docs">결제 안내 <span aria-hidden="true">↗</span></a></x-jds::card.footer>
                <x-jds::button class="gallery-dismiss" size="sm" variant="ghost" is-icon-only aria-label="결제 알림 닫기" onclick="this.closest('.jds-card').remove()">×</x-jds::button>
            </x-jds::card>

            <div class="gallery-club-pair">
                @foreach ([['ioniq-5.jpg', '전기차 드라이버', '148명', '서', '서윤'], ['mountain-road.jpg', '주말 드라이브', '362명', '지', '지원']] as [$image, $name, $members, $initial, $host])
                    <x-jds::card class="gallery-club">
                        <img class="gallery-club-image" src="/images/card/{{ $image }}" alt="" />
                        <x-jds::card.header>
                            <x-jds::card.title>{{ $name }}</x-jds::card.title>
                            <x-jds::card.description>멤버 {{ $members }}</x-jds::card.description>
                        </x-jds::card.header>
                        <x-jds::card.footer class="gallery-host"><span aria-hidden="true">{{ $initial }}</span> {{ $host }}</x-jds::card.footer>
                    </x-jds::card>
                @endforeach
            </div>
        </div>

        <x-jds::card class="gallery-cover gallery-cover--tall">
            <img src="/images/card/ioniq-5.jpg" alt="아이오닉 5" />
            <x-jds::card.header>
                <x-jds::card.title>IONIQ 5</x-jds::card.title>
                <x-jds::card.description>전기차 시승</x-jds::card.description>
            </x-jds::card.header>
            <x-jds::card.footer class="gallery-cover-footer">
                <div><strong>시승 안내</strong><small>예약과 이용 시간</small></div>
                <a class="jds-button jds-button--tertiary jds-button--sm" href="/docs">알아보기</a>
            </x-jds::card.footer>
        </x-jds::card>
    </div>

    <div class="gallery-bottom">
        <x-jds::card class="gallery-cover gallery-cover--wide">
            <img src="/images/card/mountain-road.jpg" alt="산과 평원을 잇는 도로" />
            <x-jds::card.footer class="gallery-cover-footer">
                <div><strong>주말 로드 트립</strong><small>산과 평원을 지나는 코스</small></div>
                <a class="jds-button jds-button--tertiary jds-button--sm" href="/docs">경로 보기</a>
            </x-jds::card.footer>
        </x-jds::card>

        <div class="gallery-list">
            @foreach ([['ioniq-5.jpg', '전기차 첫 시승', '토요일 오전 11시'], ['mountain-road.jpg', '주말 드라이브', '일요일 오전 9시'], ['ioniq-5.jpg', '차량 점검 클래스', '수요일 오후 6시']] as [$image, $title, $time])
                <x-jds::card class="gallery-list-item" variant="transparent">
                    <img src="/images/card/{{ $image }}" alt="" />
                    <x-jds::card.header>
                        <x-jds::card.title>{{ $title }}</x-jds::card.title>
                        <x-jds::card.description>{{ $time }}</x-jds::card.description>
                    </x-jds::card.header>
                </x-jds::card>
            @endforeach
        </div>
    </div>
</div>

<style>
    .card-gallery { box-sizing: border-box; width: min(100%, 42rem); padding: 1rem; display: grid; gap: 1rem; }
    .gallery-featured, .gallery-clubs, .gallery-club-pair, .gallery-bottom, .gallery-list { display: grid; gap: 1rem; }
    .card-gallery .jds-card { min-width: 0; }
    .card-gallery strong { font-size: .875rem; font-weight: 500; }
    .card-gallery small { display: block; color: var(--muted); font-size: .75rem; }
    .gallery-banner { min-height: 152px; }
    .gallery-banner-image { width: 100%; height: 140px; border-radius: 1rem; object-fit: cover; }
    .gallery-banner-content { gap: .75rem; }
    .gallery-banner-content .jds-card__header { gap: .25rem; padding-inline-end: 2rem; }
    .gallery-actions { flex-direction: column; align-items: stretch; justify-content: space-between; gap: .75rem; margin-top: auto; }
    .gallery-dismiss { position: absolute; inset-block-start: .75rem; inset-inline-end: .75rem; z-index: 1; }
    .gallery-notice { gap: .75rem; padding-inline-end: 2rem; }
    .gallery-info { display: grid; width: 2rem; height: 2rem; box-sizing: border-box; place-items: center; border: 2px solid currentColor; border-radius: 50%; }
    .gallery-link { color: var(--foreground); font-weight: 500; text-decoration: none; }
    .gallery-link:hover { text-decoration: underline; }
    .gallery-club { gap: .5rem; }
    .gallery-club-image { width: 3.5rem; height: 3.5rem; border-radius: .75rem; object-fit: cover; }
    .gallery-club .jds-card__header { margin-top: .25rem; }
    .gallery-club .jds-card__title { line-height: 1rem; }
    .gallery-club .jds-card__description { font-size: .75rem; line-height: 1.125rem; }
    .gallery-host { gap: .5rem; color: var(--muted); font-size: .75rem; }
    .gallery-host span { display: grid; place-items: center; width: 1rem; height: 1rem; border-radius: 50%; background: var(--default); font-size: .625rem; }
    .gallery-cover { position: relative; isolation: isolate; overflow: hidden; color: white; }
    .gallery-cover > img { position: absolute; z-index: -2; inset: 0; width: 100%; height: 100%; object-fit: cover; }
    .gallery-cover::after { position: absolute; z-index: -1; inset: 0; content: ''; background: linear-gradient(rgb(0 0 0 / 55%), transparent 40%, transparent 60%, rgb(0 0 0 / 80%)); }
    .gallery-cover :is(.jds-card__title, .jds-card__description, small) { color: var(--white); }
    .gallery-cover--tall { min-height: 230px; }
    .gallery-cover--wide { height: 250px; overflow: visible; }
    .gallery-cover-footer { justify-content: space-between; gap: .5rem; margin-top: auto; }
    .gallery-cover-footer .jds-button { --jds-button-bg: var(--white); --jds-button-bg-hover: var(--white); --jds-button-fg: var(--eclipse); }
    .gallery-list { gap: .5rem; align-content: space-between; }
    .gallery-list-item { flex-direction: row; align-items: center; padding: .25rem; }
    .gallery-list-item img { flex: none; width: 4rem; height: 4rem; object-fit: cover; border-radius: .75rem; }
    .gallery-list-item .jds-card__description { font-size: .75rem; }

    @media (width >= 34rem) {
        .gallery-banner { flex-direction: row; }
        .gallery-banner-image { flex: none; width: 120px; height: 120px; }
        .gallery-actions { flex-direction: row; align-items: center; }
        .gallery-club-pair { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }

    @media (width >= 46rem) {
        .gallery-featured { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .gallery-clubs { grid-template-rows: 208px 160px; }
        .gallery-bottom { grid-template-columns: repeat(12, minmax(0, 1fr)); }
        .gallery-cover--wide { grid-column: span 8; height: 350px; }
        .gallery-list { grid-column: span 4; padding-block: .5rem; }
        .gallery-list-item img { width: 5rem; height: 5rem; }
    }
</style>
