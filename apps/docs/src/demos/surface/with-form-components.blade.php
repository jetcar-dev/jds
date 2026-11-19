<x-jds::surface class="example-form-surface">
    <x-jds::input variant="secondary" full-width aria-label="제목" placeholder="Input with secondary variant" />
    <x-jds::textarea variant="secondary" full-width aria-label="내용" placeholder="TextArea with secondary variant" />
</x-jds::surface>

<style>
    .example-form-surface {
        display: flex;
        flex-direction: column;
        width: min(100%, 20rem);
        gap: 1rem;
        padding: 1.5rem;
        border-radius: calc(var(--radius) * 3);
    }

</style>
