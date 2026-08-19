<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

final class CodeTest extends TestCase
{
    public function test_code_renders_heroui_size_color_and_radius_contract(): void
    {
        $html = Blade::render('<x-code size="lg" color="success" radius="full">artisan test</x-code>');

        foreach ([
            'data-ui-component="code"',
            'data-slot="base"',
            'data-size="lg"',
            'data-color="success"',
            'app-color-success',
            'app-size-lg',
            'app-radius-full',
            'artisan test',
        ] as $fragment) {
            $this->assertStringContainsString($fragment, $html);
        }
    }

    public function test_code_document_contains_real_supported_examples(): void
    {
        $response = $this->get('/components/code')->assertOk();

        foreach (['code-basic', 'code-sizes', 'code-colors', 'code-radius'] as $key) {
            $response->assertSee($key);
        }

        $response->assertDontSee('app-ui:code:change');
    }
}
