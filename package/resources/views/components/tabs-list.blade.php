<div data-slot="base" class="app-tabs-base">
    <div data-slot="tabList" class="app-tabs-list" role="tablist" {{ $attributes }}>
        <span data-slot="cursor" class="app-tabs-cursor" aria-hidden="true"></span>
        {{ $slot }}
    </div>
</div>
