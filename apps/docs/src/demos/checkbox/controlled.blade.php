<div style="display: grid; gap: .75rem;">
    <x-jds::checkbox id="email-notifications" checked> Email notifications </x-jds::checkbox>
    <output for="email-notifications" style="color: var(--muted); font-size: .875rem;">
        Status: <strong id="notification-status">Enabled</strong>
    </output>
</div>

<script>
    const checkbox = document.getElementById('email-notifications');
    const status = document.getElementById('notification-status');

    checkbox.addEventListener('input', () => {
        status.textContent = checkbox.checked ? 'Enabled' : 'Disabled';
    });
</script>
