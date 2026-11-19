<x-jds::surface class="example-surface">
    <h3>Surface Content</h3>
    <p>기본 표면은 테마의 --surface 토큰을 배경색에 사용합니다.</p>
</x-jds::surface>

<style>
    .example-surface {
        display: flex;
        flex-direction: column;
        width: min(100%, 27.3125rem);
        gap: .75rem;
        padding: 1.5rem;
        border-radius: calc(var(--radius) * 3);
    }

    .example-surface h3 { font-size: 1rem; line-height: 1.5rem; font-weight: 600; }
    .example-surface p { font-size: .875rem; line-height: 1.25rem; color: var(--muted); }

    @media (width < 34rem) {
        .example-surface { width: min(100%, 20rem); }
    }
</style>
