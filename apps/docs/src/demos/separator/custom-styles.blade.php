<style>
    .account-divider {
        background-color: var(--separator-secondary);
    }
</style>

<div style="display: grid; max-width: min(100%, 20rem); gap: 1rem; font-size: .875rem;">
    <strong style="font-weight: 500;">Account Settings</strong>
    <x-jds::separator class="account-divider" />

    <div style="display: flex; align-items: center; gap: .75rem; color: var(--muted);">
        <span>Profile</span>
        <x-jds::separator orientation="vertical" style="height: 1rem;" />
        <span>Billing</span>
        <x-jds::separator orientation="vertical" style="height: 1rem;" />
        <span>Security</span>
    </div>
</div>
