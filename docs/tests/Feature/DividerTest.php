<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

final class DividerTest extends TestCase
{
    public function test_divider_renders_horizontal_and_vertical_contracts(): void
    {
        $horizontal = Blade::render('<x-divider />');
        $vertical = Blade::render('<x-divider orientation="vertical" />');

        foreach ([
            '<hr',
            'data-ui-component="divider"',
            'data-slot="base"',
            'data-orientation="horizontal"',
            'role="separator"',
            'aria-orientation="horizontal"',
        ] as $fragment) {
            $this->assertStringContainsString($fragment, $horizontal);
        }

        foreach ([
            '<div',
            'data-orientation="vertical"',
            'aria-orientation="vertical"',
        ] as $fragment) {
            $this->assertStringContainsString($fragment, $vertical);
        }
    }

    public function test_divider_document_covers_supported_api(): void
    {
        $this->get('/components/divider')
            ->assertOk()
            ->assertSee('divider-basic')
            ->assertSee('divider-orientations')
            ->assertSee('orientation')
            ->assertDontSee('app-ui:divider:change');
    }
}
