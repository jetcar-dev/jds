<div style="display: flex; flex-wrap: wrap; gap: 1rem;">
    <x-jds::checkbox name="heart" checked>
        <x-slot:content>
            <x-jds::checkbox.control>
                <x-jds::checkbox.indicator class="demo-indicator">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 21S3 15.4 3 8.8A4.8 4.8 0 0 1 12 6.5a4.8 4.8 0 0 1 9 2.3C21 15.4 12 21 12 21Z" />
                    </svg>
                </x-jds::checkbox.indicator>
            </x-jds::checkbox.control>
            Heart
        </x-slot:content>
    </x-jds::checkbox>
    <x-jds::checkbox name="plus" checked>
        <x-slot:content>
            <x-jds::checkbox.control>
                <x-jds::checkbox.indicator class="demo-indicator">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round">
                        <path d="M6 12h12M12 6v12" />
                    </svg>
                </x-jds::checkbox.indicator>
            </x-jds::checkbox.control>
            Plus
        </x-slot:content>
    </x-jds::checkbox>
    <x-jds::checkbox name="indeterminate" is-indeterminate>
        <x-slot:content>
            <x-jds::checkbox.control>
                <x-jds::checkbox.indicator class="demo-indicator demo-indicator--mixed">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                        <path d="M3 12h18" />
                    </svg>
                </x-jds::checkbox.indicator>
            </x-jds::checkbox.control>
            Indeterminate
        </x-slot:content>
    </x-jds::checkbox>
</div>

<style>
    .demo-indicator { visibility: hidden; }
    [data-selected="true"] .demo-indicator:not(.demo-indicator--mixed),
    [data-indeterminate="true"] .demo-indicator--mixed { visibility: visible; }
</style>
