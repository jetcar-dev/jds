<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

final class BadgeBreadcrumbsTest extends TestCase
{
    public function test_badge_renders_heroui_api_and_slots(): void
    {
        $html = Blade::render(<<<'BLADE'
<x-badge content="9" variant="flat" color="danger" size="lg" shape="circle" placement="bottom-left" one-char disable-outline invisible>
    <x-avatar name="J" />
</x-badge>
BLADE);

        foreach ([
            'data-slot="base"',
            'data-ui-component="badge"',
            'data-slot="badge"',
            'data-variant="flat"',
            'data-color="danger"',
            'data-size="lg"',
            'data-shape="circle"',
            'data-placement="bottom-left"',
            'data-one-char="true"',
            'data-show-outline="false"',
            'data-invisible="true"',
        ] as $fragment) {
            $this->assertStringContainsString($fragment, $html);
        }
    }

    public function test_breadcrumbs_render_heroui_structure_and_controls(): void
    {
        $html = Blade::render(<<<'BLADE'
<x-breadcrumbs variant="bordered" color="primary" size="lg" radius="full" underline="hover" separator="/" :max-items="3" disabled>
    <x-breadcrumb-item href="/" item-key="home">Home</x-breadcrumb-item>
    <x-breadcrumb-item href="/music" disabled>Music</x-breadcrumb-item>
    <x-breadcrumb-item current>Song</x-breadcrumb-item>
</x-breadcrumbs>
BLADE);

        foreach ([
            '<nav',
            '<ol',
            'data-ui-component="breadcrumbs"',
            'data-slot="list"',
            'data-variant="bordered"',
            'data-color="primary"',
            'data-size="lg"',
            'data-radius="full"',
            'data-underline="hover"',
            'data-max-items="3"',
            'data-disabled="true"',
            'data-key="home"',
            'aria-current="page"',
            'data-breadcrumb-separator-template',
            'data-breadcrumb-ellipsis-template',
        ] as $fragment) {
            $this->assertStringContainsString($fragment, $html);
        }
    }

    public function test_badge_and_breadcrumbs_documents_contain_complete_examples(): void
    {
        $this->get('/components/badge')
            ->assertOk()
            ->assertSee('badge-sizes')
            ->assertSee('badge-variants')
            ->assertSee('badge-placements')
            ->assertSee('badge-states');

        $this->get('/components/breadcrumbs')
            ->assertOk()
            ->assertSee('breadcrumbs-variants')
            ->assertSee('breadcrumbs-separator')
            ->assertSee('breadcrumbs-collapse')
            ->assertSee('breadcrumbs-controlled');
    }
}
