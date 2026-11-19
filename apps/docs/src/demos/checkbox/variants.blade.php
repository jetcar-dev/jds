<div style="display: grid; gap: 1rem;">
    <div style="display: grid; gap: .5rem;">
        <span style="color: var(--muted); font-size: .875rem; font-weight: 500;">Primary variant</span>
        <x-jds::checkbox name="primary">
            Primary checkbox
            <x-slot:description>Standard styling with default background</x-slot:description>
        </x-jds::checkbox>
    </div>
    <div style="display: grid; gap: .5rem;">
        <span style="color: var(--muted); font-size: .875rem; font-weight: 500;">Secondary variant</span>
        <x-jds::checkbox name="secondary" variant="secondary">
            Secondary checkbox
            <x-slot:description>Lower emphasis variant for use in surfaces</x-slot:description>
        </x-jds::checkbox>
    </div>
</div>
