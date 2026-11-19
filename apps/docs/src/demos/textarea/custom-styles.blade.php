<x-jds::textarea class="notes-field" aria-label="메모" placeholder="Add a note..." />

<style>
    .notes-field {
        width: min(100%, 20rem);
        height: 7rem;
        border: 1px solid color-mix(in oklab, var(--border) 80%, transparent);
        border-radius: calc(var(--radius) * 1.5);
        background: var(--surface);
        color: oklch(0.269 0 0);
        font-size: .875rem;
        line-height: 1.25rem;
        box-shadow: 0 0 0 1px rgb(0 0 0 / 5%), 0 1px 2px rgb(0 0 0 / 5%);
        transition: box-shadow 150ms ease, border-color 150ms ease;
    }

    .notes-field::placeholder { color: oklch(0.708 0 0); }
    .notes-field:focus-visible { box-shadow: 0 0 0 2px oklch(0.708 0 0 / 25%), 0 1px 2px rgb(0 0 0 / 5%); }

    :is(.dark, [data-theme="dark"]) .notes-field {
        color: oklch(0.97 0 0);
        box-shadow: 0 0 0 1px rgb(255 255 255 / 10%), 0 1px 2px rgb(0 0 0 / 5%);
    }
    :is(.dark, [data-theme="dark"]) .notes-field::placeholder { color: oklch(0.556 0 0); }
    :is(.dark, [data-theme="dark"]) .notes-field:focus-visible { box-shadow: 0 0 0 2px oklch(0.556 0 0 / 30%), 0 1px 2px rgb(0 0 0 / 5%); }

    @media (prefers-reduced-motion: reduce) {
        .notes-field { transition-property: none; }
    }
</style>
