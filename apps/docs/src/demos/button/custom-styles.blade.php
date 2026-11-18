<x-jds::button variant="ghost" class="upgrade-button">Upgrade</x-jds::button>

<style>
    .upgrade-button {
        --upgrade-border: linear-gradient(315deg, #e5e5e5, #fafafa 50%, #c4c4c4);
        --upgrade-from: #f5f5f5;
        --upgrade-to: #fff;
        border-radius: 9999px;
        padding-inline: 2.5rem;
        background: linear-gradient(to top, var(--upgrade-from), var(--upgrade-to));
        color: #262626;
        font-weight: 450;
        box-shadow: 0 1px 6px rgb(0 0 0 / .02), 0 3px 12px rgb(0 0 0 / .02),
            0 8px 24px rgb(0 0 0 / .01), 0 18px 40px rgb(0 0 0 / .02);
        transition: transform .3s cubic-bezier(.34, 1.56, .64, 1), filter .3s;
    }
    .upgrade-button:hover { filter: brightness(1.05); }
    .upgrade-button:active,
    .upgrade-button[data-pressed="true"] { transform: scale(.95); }
    .upgrade-button::before {
        content: "";
        position: absolute;
        inset: 0;
        padding: 1.5px;
        border-radius: inherit;
        background: var(--upgrade-border);
        mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
        mask-composite: exclude;
        pointer-events: none;
    }
    [data-theme="dark"] .upgrade-button {
        --upgrade-from: #171717;
        --upgrade-to: #262626;
        --upgrade-border: linear-gradient(315deg, #404040, #262626 50%, #525252);
        color: #f5f5f5;
    }
    @media (prefers-reduced-motion: reduce) {
        .upgrade-button { transition: none; }
    }
</style>
