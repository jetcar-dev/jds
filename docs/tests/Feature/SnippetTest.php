<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

final class SnippetTest extends TestCase
{
    public function test_snippet_renders_heroui_contract_and_multiline_content(): void
    {
        $html = Blade::render(<<<'BLADE'
            <x-snippet
                variant="solid"
                color="primary"
                size="lg"
                radius="sm"
                symbol=">"
                :code-string="['npm install', 'npm run build']"
            />
        BLADE);

        foreach ([
            'data-ui-component="snippet"',
            'data-slot="base"',
            'data-slot="content"',
            'data-slot="pre"',
            'data-slot="symbol"',
            'data-slot="copy-button"',
            'data-slot="copy-icon"',
            'data-slot="check-icon"',
            'data-variant="solid"',
            'data-color="primary"',
            'data-size="lg"',
            'app-radius-sm',
            'd="m5 12 4 4L19 6"',
            'npm install',
            'npm run build',
        ] as $fragment) {
            $this->assertStringContainsString($fragment, $html);
        }
    }

    public function test_snippet_boolean_options_remove_or_disable_controls(): void
    {
        $hidden = Blade::render('<x-snippet hide-symbol hide-copy-button>command</x-snippet>');
        $disabled = Blade::render('<x-snippet disable-copy>command</x-snippet>');

        $this->assertStringNotContainsString('data-slot="symbol"', $hidden);
        $this->assertStringNotContainsString('data-snippet-copy', $hidden);
        $this->assertStringContainsString('data-disable-copy="true"', $disabled);
        $this->assertStringContainsString('disabled', $disabled);
    }

    public function test_snippet_document_contains_only_supported_examples_and_api(): void
    {
        $response = $this->get('/components/snippet')->assertOk();

        foreach ([
            'snippet-basic',
            'snippet-sizes',
            'snippet-colors',
            'snippet-variants',
            'snippet-symbol',
            'snippet-multiline',
            'snippet-copy-options',
            'snippet-custom-icons',
            'app-ui:snippet:copy',
        ] as $fragment) {
            $response->assertSee($fragment);
        }

        $response->assertDontSee('app-ui:snippet:change');
        $response->assertDontSee('setValue()');
    }
}
