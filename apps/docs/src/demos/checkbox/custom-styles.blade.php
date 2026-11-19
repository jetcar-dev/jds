<x-jds::checkbox id="custom">
    <x-slot:content class="success-checkbox">
        <x-jds::checkbox.control>
            <x-jds::checkbox.indicator />
        </x-jds::checkbox.control>
        Custom styled checkbox
    </x-slot:content>
</x-jds::checkbox>

<style>
    .success-checkbox {
        --checkbox-success: var(--success, oklch(.7329 .1935 150.81));
        --checkbox-success-opacity: 15%;
    }

    [data-theme="dark"] .success-checkbox { --checkbox-success-opacity: 12%; }

    .success-checkbox .jds-checkbox__control {
        background-color: var(--success-soft, color-mix(in oklab, var(--checkbox-success) var(--checkbox-success-opacity), transparent));
    }

    .success-checkbox .jds-checkbox__control::before { background-color: var(--checkbox-success); }
    .success-checkbox [data-slot="checkbox-default-indicator--checkmark"] { color: var(--eclipse); }
</style>
