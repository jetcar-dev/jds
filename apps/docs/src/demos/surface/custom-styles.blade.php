<x-jds::surface class="billing-surface">
    <h3>Billing overview</h3>
    <p>청구서를 확인하고 등록한 결제 수단을 한 곳에서 관리하세요.</p>
</x-jds::surface>

<style>
    .billing-surface {
        width: min(100%, 24rem);
        padding: 1rem;
        border: 1px solid color-mix(in oklab, var(--accent) 15%, transparent);
        border-radius: calc(var(--radius) * 1.5);
        background-image: linear-gradient(
            to bottom right in oklab,
            color-mix(in oklab, var(--accent) 8%, transparent),
            var(--surface),
            var(--surface-secondary)
        );
    }

    .billing-surface h3,
    .billing-surface p { font-size: .875rem; line-height: 1.25rem; }
    .billing-surface h3 { font-weight: 600; }
    .billing-surface p { color: var(--muted); }
</style>
