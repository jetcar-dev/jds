<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

final class ChipTest extends TestCase
{
    public function test_chip_renders_heroui_contract_and_slots(): void
    {
        $html = Blade::render(<<<'BLADE'
            <x-chip variant="dot" color="success" size="lg" radius="md" closable>
                <x-slot:avatar><x-avatar name="J" /></x-slot:avatar>
                <x-slot:startContent><x-icon name="bolt-linear" /></x-slot:startContent>
                Online
            </x-chip>
        BLADE);

        foreach ([
            'data-ui-component="chip"',
            'data-slot="base"',
            'data-slot="dot"',
            'data-slot="avatar"',
            'data-slot="start-content"',
            'data-slot="content"',
            'data-slot="close-button"',
            'data-variant="dot"',
            'data-color="success"',
            'data-size="lg"',
            'data-radius="md"',
            'data-closable="true"',
            'app-radius-md',
            'Online',
        ] as $fragment) {
            $this->assertStringContainsString($fragment, $html);
        }
    }

    public function test_chip_validates_appearance_values_and_boolean_state(): void
    {
        $html = Blade::render('<x-chip variant="invalid" color="invalid" size="xl" radius="invalid" disabled>Chip</x-chip>');

        $this->assertStringContainsString('data-variant="solid"', $html);
        $this->assertStringContainsString('data-color="default"', $html);
        $this->assertStringContainsString('data-size="md"', $html);
        $this->assertStringContainsString('data-radius="full"', $html);
        $this->assertStringContainsString('data-disabled="true"', $html);
        $this->assertStringContainsString('aria-disabled="true"', $html);
    }

    public function test_chip_document_covers_supported_examples_and_controller(): void
    {
        $response = $this->get('/components/chip')->assertOk();

        foreach ([
            'chip-basic',
            'chip-disabled',
            'chip-sizes',
            'chip-colors',
            'chip-radius',
            'chip-variants',
            'chip-content',
            'chip-close',
            'chip-avatar',
            'chip-list',
            'chip-custom-close',
            'app-ui:chip:close',
        ] as $fragment) {
            $response->assertSee($fragment);
        }

        $response->assertDontSee('app-ui:chip:change');
        $response->assertDontSee('setValue()');
    }
}
