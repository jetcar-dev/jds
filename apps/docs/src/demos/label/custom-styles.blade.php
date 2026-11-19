<div style="display: flex; flex-direction: column; gap: .375rem; width: min(100%, 16rem);">
    <x-jds::label class="repository-label" for="repo">Repository</x-jds::label>
    <x-jds::input id="repo" placeholder="jetcar/jds" full-width />
</div>

<style>
    .repository-label {
        color: var(--accent);
        font-size: .75rem;
        font-weight: 600;
        line-height: 1rem;
        letter-spacing: .025em;
        text-transform: uppercase;
    }
</style>
