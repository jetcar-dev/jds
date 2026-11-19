<div style="display: grid; gap: .5rem; width: min(100%, 20rem);">
    <x-jds::input
        id="input-domain"
        aria-label="도메인"
        placeholder="domain"
        value="example.com"
        oninput="document.getElementById('input-domain-url').textContent = 'https://' + (this.value || 'your-domain')"
    />
    <span id="input-domain-url" style="padding-inline: .25rem; color: var(--muted); font-size: .875rem; line-height: 1.25rem;">
        https://example.com
    </span>
</div>
