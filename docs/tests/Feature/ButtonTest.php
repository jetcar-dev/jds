<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

final class ButtonTest extends TestCase
{
    public function test_button_renders_heroui_structure_and_states(): void
    {
        $html = Blade::render(<<<'BLADE'
<x-button color="primary" variant="flat" size="lg" loading spinner-placement="end" full-width>
    <x-slot:startContent>앞</x-slot:startContent>
    저장
    <x-slot:endContent>뒤</x-slot:endContent>
</x-button>
BLADE);

        $this->assertStringContainsString('data-slot="base"', $html);
        $this->assertStringContainsString('data-slot="start-content"', $html);
        $this->assertStringContainsString('data-slot="content"', $html);
        $this->assertStringContainsString('data-slot="end-content"', $html);
        $this->assertStringContainsString('data-slot="spinner"', $html);
        $this->assertStringContainsString('data-variant="flat"', $html);
        $this->assertStringContainsString('data-color="primary"', $html);
        $this->assertStringContainsString('data-loading="true"', $html);
        $this->assertStringContainsString('data-disabled="true"', $html);
        $this->assertStringContainsString('data-full-width="true"', $html);
        $this->assertStringContainsString('aria-busy="true"', $html);
        $this->assertStringContainsString('disabled', $html);
    }

    public function test_button_supports_link_icon_only_and_boolean_attributes(): void
    {
        $html = Blade::render('<x-button href="/orders" icon-only disabled aria-label="주문"><x-icon name="solar:bolt-bold" /></x-button>');

        $this->assertStringContainsString('<a', $html);
        $this->assertStringContainsString('href="/orders"', $html);
        $this->assertStringContainsString('data-icon-only="true"', $html);
        $this->assertStringContainsString('aria-disabled="true"', $html);
        $this->assertStringContainsString('tabindex="-1"', $html);
        $this->assertStringContainsString('aria-label="주문"', $html);
    }

    public function test_button_supports_external_link_and_anchor_icon(): void
    {
        $html = Blade::render('<x-button href="https://example.com" external show-anchor-icon>외부 문서</x-button>');

        foreach ([
            '<a',
            'data-link="true"',
            'data-external="true"',
            'target="_blank"',
            'rel="noopener noreferrer"',
            'data-slot="anchor-icon"',
            'data-icon="solar:square-top-down-linear"',
        ] as $fragment) {
            $this->assertStringContainsString($fragment, $html);
        }
    }

    public function test_button_document_contains_real_examples(): void
    {
        $this->get('/components/button')
            ->assertOk()
            ->assertSee('button-variants')
            ->assertSee('button-colors')
            ->assertSee('button-icons')
            ->assertSee('button-states')
            ->assertSee('button-link-width')
            ->assertSee('app-ui:button:press');
    }
}
