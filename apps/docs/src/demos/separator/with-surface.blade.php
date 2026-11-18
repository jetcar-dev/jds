<style>
    .surface-example {
        display: grid;
        gap: .75rem;
        padding: 1.5rem;
        border-radius: calc(var(--radius) * 4);
        background: var(--surface);
        color: var(--surface-foreground);
    }

    .surface-example[data-surface="secondary"] { background: var(--surface-secondary); }
    .surface-example[data-surface="tertiary"] { background: var(--surface-tertiary); }
    .surface-example[data-surface="transparent"] { border: 1px solid var(--border); background: transparent; }
    .surface-example h4 { margin: 0; font-size: 1rem; font-weight: 600; }
    .surface-example p { margin: 0; font-size: .875rem; color: var(--muted); }
</style>

<div style="display: grid; width: min(100%, 20rem); gap: 1rem;">
    @foreach (['default', 'secondary', 'tertiary', 'transparent'] as $surface)
        <section class="surface-example" data-surface="{{ $surface }}">
            <h4>{{ ucfirst($surface) }} Surface</h4>
            <x-jds::separator :variant="$surface === 'transparent' ? 'default' : $surface" />
            <p>구분선 아래에 내용을 배치합니다.</p>
        </section>
    @endforeach
</div>
