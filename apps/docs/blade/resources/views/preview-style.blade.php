html {
    color-scheme: light;
    background: hsl(var(--content1, 0 0% 100%));
}

html[data-theme="dark"] {
    color-scheme: dark;
}

body {
    min-width: 0;
    margin: 0;
    padding: clamp(1.5rem, 3vw, 2rem);
    overflow: visible;
    background: hsl(var(--content1, 0 0% 100%));
}

.jds-blade-preview-root {
    display: flex;
    width: 100%;
    min-width: 0;
    min-height: 4rem;
    flex-wrap: wrap;
    align-items: flex-start;
    gap: .75rem;
    box-sizing: border-box;
    overflow: visible;
}

.jds-example-row {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: .75rem;
}

.jds-example-stack {
    display: flex;
    min-width: 0;
    flex-direction: column;
    gap: .75rem;
}

.jds-example-grid {
    display: grid;
    width: 100%;
    min-width: 0;
    grid-template-columns: repeat(auto-fit, minmax(min(13rem, 100%), 1fr));
    gap: 1rem;
}

.jds-blade-preview-root[data-preview-name^="date-input-"] > [data-ui-component="date-input"],
.jds-blade-preview-root[data-preview-name^="date-picker-"] > [data-ui-component="date-picker"],
.jds-blade-preview-root[data-preview-name^="date-range-picker-"] > [data-ui-component="date-range-picker"],
.jds-blade-preview-root[data-preview-name^="time-input-"] > [data-ui-component="time-input"] {
    width: min(20rem, 100%);
}

.jds-blade-preview-root:is(
    [data-preview-name="date-input-variants"],
    [data-preview-name="date-input-granularity"],
    [data-preview-name="date-range-picker-variants"],
    [data-preview-name="date-range-picker-label-placements"],
    [data-preview-name="date-range-picker-granularity"],
    [data-preview-name="date-range-picker-selector-placement"],
    [data-preview-name="date-range-picker-locales"],
    [data-preview-name="date-range-picker-unavailable"],
    [data-preview-name="time-input-variants"],
    [data-preview-name="time-input-colors"],
    [data-preview-name="time-input-label-placements"]
) {
    flex-direction: column;
}

.jds-blade-preview-root:is(
    [data-preview-name="date-range-picker-time"],
    [data-preview-name="date-range-picker-granularity"],
    [data-preview-name="date-range-picker-time-zones"]
) > [data-ui-component="date-range-picker"] {
    width: min(36rem, 100%);
}

.jds-blade-preview-root[data-preview-name="date-range-picker-content"] > [data-ui-component="date-range-picker"] {
    width: min(23rem, 100%);
}

.jds-blade-preview-root[data-preview-name="date-range-picker-form"] form {
    display: flex;
    width: min(20rem, 100%);
    flex-direction: column;
    align-items: flex-start;
    gap: .75rem;
}

.jds-blade-preview-root[data-preview-name="date-range-picker-form"] form > [data-ui-component="date-range-picker"] {
    width: 100%;
}

.jds-blade-preview-root:is(
    [data-preview-name="date-input-sizes"],
    [data-preview-name="date-input-radius"]
) > [data-ui-component="date-input"] {
    width: min(11rem, 100%);
}

.jds-blade-preview-root:is(
    [data-preview-name="time-input-sizes"],
    [data-preview-name="time-input-radius"],
    [data-preview-name="time-input-granularity"]
) > [data-ui-component="time-input"] {
    width: min(12rem, 100%);
}

@media (max-width: 640px) {
    body {
        padding: 1rem;
    }

    .jds-example-grid {
        grid-template-columns: minmax(0, 1fr);
    }
}
