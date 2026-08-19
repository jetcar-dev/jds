<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

final class RadioGroupTest extends TestCase
{
    public function test_radio_group_and_radio_render_native_heroui_structure(): void
    {
        $html = Blade::render(<<<'BLADE'
            <x-radio-group name="plan" label="Plan" default-value="pro" orientation="horizontal" color="success" size="lg" required>
                <x-radio value="free" label="Free" description="Basic" />
                <x-radio value="pro">Pro</x-radio>
            </x-radio-group>
        BLADE);

        foreach ([
            'data-ui-component="radio-group"', 'data-slot="label"', 'data-slot="wrapper"',
            'data-orientation="horizontal"', 'data-color="success"', 'data-size="lg"',
            'data-required="true"', 'data-name="plan"', 'data-initial-value="pro"',
            'data-ui-component="radio"', 'data-slot="hidden-input"', 'type="radio"',
            'value="free"', 'data-slot="control"', 'data-slot="label-wrapper"',
            'data-slot="description"', 'Basic',
        ] as $fragment) {
            $this->assertStringContainsString($fragment, $html);
        }
    }

    public function test_radio_group_document_matches_heroui_catalog(): void
    {
        $response = $this->get('/components/radio-group')->assertOk();

        foreach ([
            'radio-group-basic', 'radio-group-disabled', 'radio-group-default-value',
            'radio-group-descriptions', 'radio-group-horizontal', 'radio-group-colors',
            'radio-group-sizes', 'radio-group-controlled', 'radio-group-invalid',
            'radio-group-custom-styles', 'radio-group-form',
        ] as $key) {
            $response->assertSee($key);
        }

        $response->assertSee('Radio Group')->assertSee('커스텀 스타일');
    }
}
