<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

final class CheckboxGroupTest extends TestCase
{
    public function test_checkbox_group_renders_heroui_structure_and_properties(): void
    {
        $html = Blade::render(<<<'BLADE'
            <x-checkbox-group name="cities" label="Cities" :default-value="['seoul']" orientation="horizontal" color="success" size="lg" radius="full" required>
                <x-checkbox value="seoul">Seoul</x-checkbox>
                <x-checkbox value="busan">Busan</x-checkbox>
            </x-checkbox-group>
        BLADE);

        $this->assertStringContainsString('data-ui-component="checkbox-group"', $html);
        $this->assertStringContainsString('data-slot="label"', $html);
        $this->assertStringContainsString('data-slot="wrapper"', $html);
        $this->assertStringContainsString('data-orientation="horizontal"', $html);
        $this->assertStringContainsString('data-color="success"', $html);
        $this->assertStringContainsString('data-size="lg"', $html);
        $this->assertStringContainsString('data-radius="full"', $html);
        $this->assertStringContainsString('data-required="true"', $html);
        $this->assertStringContainsString('data-name="cities"', $html);
    }

    public function test_checkbox_group_document_matches_heroui_catalog(): void
    {
        $response = $this->get('/components/checkbox-group')->assertOk();

        foreach ([
            'checkbox-group-basic', 'checkbox-group-disabled', 'checkbox-group-horizontal',
            'checkbox-group-colors', 'checkbox-group-sizes', 'checkbox-group-controlled',
            'checkbox-group-invalid', 'checkbox-group-custom-styles', 'checkbox-group-form',
        ] as $key) {
            $response->assertSee($key);
        }

        $response->assertSee('Checkbox Group')->assertSee('커스텀 스타일');
    }
}
