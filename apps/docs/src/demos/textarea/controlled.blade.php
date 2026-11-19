<div style="display: grid; gap: .5rem; width: min(100%, 24rem);">
    <x-jds::textarea
        id="announcement"
        aria-label="공지사항"
        aria-describedby="announcement-description"
        placeholder="Compose an announcement..."
    />
    <p id="announcement-description" style="margin: 0; color: var(--muted); font-size: .75rem; line-height: 1rem;">
        글자 수 <span id="announcement-count">0</span> / 280
    </p>
</div>

<script>
    const announcement = document.getElementById('announcement');
    const count = document.getElementById('announcement-count');

    announcement.addEventListener('input', () => {
        count.textContent = announcement.value.length;
    });
</script>
