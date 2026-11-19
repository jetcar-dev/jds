<x-jds::input
    class="project-search"
    aria-label="프로젝트 검색"
    placeholder="Search projects..."
/>

<style>
    .project-search {
        width: min(100%, 16rem);
        border: 1px solid color-mix(in oklab, var(--border) 80%, transparent);
        border-radius: calc(var(--radius) * 1.5);
        background-color: var(--default);
        color: var(--foreground);
    }

    .project-search::placeholder { color: var(--muted); }
</style>
