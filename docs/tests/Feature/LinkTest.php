<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

final class LinkTest extends TestCase
{
    public function test_link_renders_heroui_contract(): void
    {
        $html = Blade::render('<x-link href="https://example.com" size="lg" color="success" underline="always" external show-anchor-icon block>문서</x-link>');

        foreach ([
            'data-ui-component="link"',
            'data-slot="base"',
            'data-size="lg"',
            'data-color="success"',
            'data-underline="always"',
            'data-block="true"',
            'target="_blank"',
            'rel="noopener noreferrer"',
            'data-slot="anchor-icon"',
            'data-icon="solar:square-top-down-linear"',
        ] as $fragment) {
            $this->assertStringContainsString($fragment, $html);
        }
    }

    public function test_disabled_link_is_not_navigable(): void
    {
        $html = Blade::render('<x-link href="/private" disabled>비활성</x-link>');

        $this->assertStringContainsString('aria-disabled="true"', $html);
        $this->assertStringContainsString('tabindex="-1"', $html);
        $this->assertStringNotContainsString('href="/private"', $html);
    }

    public function test_link_document_contains_complete_examples(): void
    {
        $content = file_get_contents(base_path('content/components/link.mdx'));

        foreach (['link-basic', 'link-disabled', 'link-sizes', 'link-colors', 'link-underlines', 'link-external', 'link-custom-icon', 'link-block', 'link-html-attributes'] as $example) {
            $this->assertStringContainsString($example, $content);
        }
    }
}
