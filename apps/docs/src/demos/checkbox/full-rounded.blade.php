<div style="display: grid; gap: 1.5rem;">
    <span class="jds-label" style="color: var(--muted);">Rounded checkboxes</span>
    @foreach ([12 => 'Small', 16 => 'Default', 20 => 'Large', 24 => 'Extra large'] as $size => $label)
        <x-jds::checkbox name="rounded-{{ $size }}">
            <x-slot:content>
                <x-jds::checkbox.control class="rounded-control" style="--control-size: {{ $size }}px; --checkmark-size: {{ [12 => 8, 16 => 10, 20 => 10, 24 => 16][$size] }}px;">
                    <x-jds::checkbox.indicator />
                </x-jds::checkbox.control>
                {{ $label }} size
            </x-slot:content>
        </x-jds::checkbox>
    @endforeach
</div>

<style>
    .rounded-control {
        width: var(--control-size);
        height: var(--control-size);
        border-radius: 9999px;
    }

    .rounded-control::before { border-radius: inherit; }
    .rounded-control [data-slot="checkbox-default-indicator--checkmark"] {
        width: var(--checkmark-size);
        height: var(--checkmark-size);
    }
</style>
