<style>
    .account-divider {
        height: 2px;
        background-color: var(--accent);
    }
</style>

<div style="display: grid; width: min(100%, 20rem); gap: 1rem; font-size: .875rem;">
    <strong style="font-weight: 500;">Account Settings</strong>
    <x-jds::separator class="account-divider" />

    <div style="display: flex; align-items: center; gap: .75rem; color: var(--muted);">
        <span>Profile</span>
        <x-jds::separator orientation="vertical" />
        <span>Billing</span>
        <x-jds::separator orientation="vertical" />
        <span>Security</span>
    </div>
</div>
