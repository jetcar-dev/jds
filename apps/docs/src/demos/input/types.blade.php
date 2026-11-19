<div class="input-types">
    <div>
        <label for="input-type-email">Email</label>
        <x-jds::input id="input-type-email" type="email" placeholder="jane@example.com" />
    </div>
    <div>
        <label for="input-type-number">Age</label>
        <x-jds::input id="input-type-number" type="number" min="0" placeholder="30" />
    </div>
    <div>
        <label for="input-type-password">Password</label>
        <x-jds::input id="input-type-password" type="password" placeholder="••••••••" />
    </div>
</div>

<style>
    .input-types {
        display: grid;
        gap: 1rem;
        width: min(100%, 20rem);
    }

    .input-types > div { display: grid; gap: .25rem; }

    .input-types label {
        color: var(--foreground);
        font-size: .875rem;
        font-weight: 500;
        line-height: 1.25rem;
    }
</style>
