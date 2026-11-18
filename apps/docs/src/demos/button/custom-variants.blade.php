@php
    $radius = 'full';
    $radii = [
        'full' => 'custom-button--radius-full',
        'lg' => 'custom-button--radius-lg',
        'md' => 'custom-button--radius-md',
        'sm' => 'custom-button--radius-sm',
    ];
@endphp

<x-jds::button :class="'custom-button '.$radii[$radius]">Custom Button</x-jds::button>

<style>
    .custom-button {
        height: 2.75rem;
        padding-inline: 1.5rem;
        font-weight: 600;
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / .1), 0 2px 4px -2px rgb(0 0 0 / .1);
        text-shadow: 0 1px 2px rgb(0 0 0 / .2);
    }
    .custom-button[data-pending="true"] { opacity: .4; }
    .custom-button--radius-full { border-radius: 9999px; }
    .custom-button--radius-lg { border-radius: var(--radius); }
    .custom-button--radius-md { border-radius: calc(var(--radius) * .75); }
    .custom-button--radius-sm { border-radius: calc(var(--radius) * .5); }
    [data-theme="dark"] .custom-button {
        --jds-button-bg: rgb(255 255 255 / .10);
        --jds-button-bg-hover: rgb(255 255 255 / .15);
    }
</style>
