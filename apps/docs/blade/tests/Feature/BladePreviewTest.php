<?php

namespace Tests\Feature;

use Tests\TestCase;

final class BladePreviewTest extends TestCase
{
    public function test_it_renders_an_existing_blade_example_for_fumadocs(): void
    {
        $response = $this->get('/_preview/popover/popover-basic?theme=dark');

        $response
            ->assertOk()
            ->assertSee('data-theme="dark"', false)
            ->assertSee('data-ui-component="popover"', false)
            ->assertSee('data-preview-name="popover-basic"', false)
            ->assertSee('jds-blade-preview', false);
    }

    public function test_it_rejects_unknown_examples(): void
    {
        $this->get('/_preview/popover/not-found')->assertNotFound();
        $this->get('/_preview/not-found/basic')->assertNotFound();
    }

    public function test_it_serves_built_jds_assets_to_the_preview_frame(): void
    {
        $this->get('/_jds/jds.css')
            ->assertOk()
            ->assertHeader('Content-Type', 'text/css; charset=utf-8');

        $this->get('/_jds/jds.js')
            ->assertOk()
            ->assertHeader('Content-Type', 'text/javascript; charset=utf-8');

        $this->get('/_jds/../../package.json')->assertNotFound();
    }
}
