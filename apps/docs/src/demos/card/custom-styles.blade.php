<x-jds::card class="offer-card" variant="secondary">
    <x-jds::card.header class="offer-heading">
        <span class="offer-label">추천</span>
        <div class="offer-heading-row">
            <span class="offer-icon" aria-hidden="true">☆</span>
            <div>
                <x-jds::card.title>장기 이용 플랜</x-jds::card.title>
                <x-jds::card.description>장기 이용 시 선택할 수 있는 계약 기간과 보험, 정비 범위, 월별 비용을 비교해 보세요.</x-jds::card.description>
            </div>
        </div>
    </x-jds::card.header>
    <x-jds::card.content>
        <ul>
            <li><span aria-hidden="true">✓</span> 차량별 이용 가능 기간</li>
            <li><span aria-hidden="true">✓</span> 보험과 정비 범위</li>
            <li><span aria-hidden="true">✓</span> 월별 이용 요금</li>
        </ul>
    </x-jds::card.content>
    <x-jds::card.footer class="offer-actions">
        <a href="/docs" class="jds-button jds-button--primary jds-button--md">이용 안내</a>
        <a href="/docs/components/card#variants" class="jds-button jds-button--secondary jds-button--md">플랜 비교</a>
    </x-jds::card.footer>
</x-jds::card>

<style>
    .offer-card {
        --offer-highlight: color-mix(in oklab, var(--accent) 12%, var(--surface));
        --offer-border: color-mix(in oklab, var(--accent) 20%, transparent);
        --offer-spot: color-mix(in oklab, var(--accent) 20%, transparent);
        width: min(100%, 28rem);
        isolation: isolate;
        overflow: hidden;
        border: 1px solid var(--offer-border);
        box-shadow: 0 10px 15px -3px color-mix(in oklab, var(--accent) 10%, transparent);
        background: linear-gradient(135deg, var(--offer-highlight), var(--surface), var(--surface-secondary));
    }
    :is(.dark, [data-theme="dark"]) .offer-card {
        --offer-highlight: color-mix(in oklab, var(--accent) 20%, var(--surface));
        --offer-border: color-mix(in oklab, var(--accent) 30%, transparent);
        --offer-spot: color-mix(in oklab, var(--accent) 30%, transparent);
    }
    .offer-card::before, .offer-card::after { content: ''; position: absolute; z-index: -1; pointer-events: none; border-radius: 50%; }
    .offer-card::before { top: -3rem; right: -3rem; width: 10rem; height: 10rem; background: var(--offer-spot); filter: blur(40px); }
    .offer-card::after { bottom: -2rem; left: -2rem; width: 7rem; height: 7rem; background: var(--offer-border); filter: blur(28px); }
    .offer-heading { gap: .75rem; }
    .offer-label, .offer-icon { color: var(--accent-soft-foreground); background: var(--offer-border); }
    .offer-label { align-self: start; border-radius: 9999px; padding: .125rem .625rem; font-size: .75rem; font-weight: 500; }
    .offer-heading-row { display: flex; gap: .75rem; }
    .offer-icon { display: grid; place-items: center; flex-shrink: 0; width: 2.5rem; height: 2.5rem; border-radius: .75rem; font-size: 1.25rem; }
    .offer-card ul { display: grid; gap: .5rem; margin: 0; padding: 0; list-style: none; color: var(--muted); font-size: .875rem; }
    .offer-card li { display: flex; gap: .5rem; }
    .offer-card li span { color: var(--accent); }
    .offer-actions { flex-direction: column; gap: .5rem; }
    .offer-actions .jds-button { width: 100%; }
    @media (width >= 34rem) { .offer-actions { flex-direction: row; } }
</style>
