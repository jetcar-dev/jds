<div class="surface-variants">
    @foreach ([
        'default' => '기본 표면은 테마의 --surface 토큰을 배경색에 사용합니다.',
        'secondary' => '한 단계 강조한 표면에 --surface-secondary 토큰을 적용합니다.',
        'tertiary' => '더 강조한 표면에 --surface-tertiary 토큰을 적용합니다.',
        'transparent' => '오버레이나 직접 배경을 지정한 카드 안에서 뒤의 배경이 보이도록 표면을 투명하게 표시합니다.',
    ] as $variant => $description)
        <div class="surface-variant">
            <p class="surface-variant-label">{{ ucfirst($variant) }}</p>
            <x-jds::surface :variant="$variant" class="surface-variant-panel">
                <h3>Surface Content</h3>
                <p>{{ $description }}</p>
            </x-jds::surface>
        </div>
    @endforeach
</div>

<style>
    .surface-variants,
    .surface-variant,
    .surface-variant-panel {
        display: flex;
        flex-direction: column;
    }

    .surface-variants { width: 100%; gap: 1rem; }
    .surface-variant { gap: .5rem; }

    .surface-variant-panel {
        gap: .75rem;
        padding: 1.5rem;
        border-radius: calc(var(--radius) * 3);
    }

    .surface-variant-panel[data-surface-variant="transparent"] { border: 1px solid var(--border); }
    .surface-variant-panel[data-surface-variant="transparent"] p { max-width: 25rem; }
    .surface-variant-panel h3 { font-size: 1rem; line-height: 1.5rem; font-weight: 600; }
    .surface-variants p { margin: 0; font-size: .875rem; line-height: 1.25rem; color: var(--muted); }
    .surface-variant-label { font-weight: 500; }

    @media (width < 34rem) {
        .surface-variants { width: min(100%, 20rem); }
    }
</style>
