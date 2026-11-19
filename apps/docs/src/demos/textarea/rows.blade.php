<div class="textarea-rows-example">
    <div>
        <label for="textarea-feedback">Short feedback</label>
        <x-jds::textarea id="textarea-feedback" rows="3" placeholder="This week's highlights..." />
    </div>
    <div>
        <label for="textarea-notes">Detailed notes</label>
        <x-jds::textarea id="textarea-notes" rows="6" style="resize: vertical;" placeholder="Write out the full meeting notes..." />
    </div>
</div>

<style>
    .textarea-rows-example { display: grid; gap: 1rem; width: min(100%, 24rem); }
    .textarea-rows-example > div { display: grid; gap: .5rem; }
    .textarea-rows-example label { color: var(--foreground); font-size: .875rem; font-weight: 500; line-height: 1.25rem; }
</style>
