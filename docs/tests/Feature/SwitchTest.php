<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

final class SwitchTest extends TestCase
{
    public function test_switch_renders_heroui_slots_and_states(): void
    {
        $html = Blade::render(<<<'BLADE'
<x-switch name="theme" value="dark" checked readonly color="secondary" size="lg" label="다크 모드" description="어두운 화면을 사용합니다.">
    <x-slot:startContent><x-icon name="solar:sun-bold" /></x-slot:startContent>
    <x-slot:endContent><x-icon name="solar:moon-bold" /></x-slot:endContent>
    <x-slot:thumbIconOn><x-icon name="solar:sun-bold" /></x-slot:thumbIconOn>
    <x-slot:thumbIconOff><x-icon name="solar:moon-bold" /></x-slot:thumbIconOff>
</x-switch>
BLADE);

        foreach (['data-ui-component="switch"', 'data-slot="base"', 'data-slot="hidden-input"', 'data-slot="wrapper"', 'data-slot="thumb"', 'data-slot="thumb-icon"', 'data-slot="thumb-icon-selected"', 'data-slot="thumb-icon-unselected"', 'data-slot="start-content"', 'data-slot="end-content"', 'data-slot="label"', 'data-slot="description"', 'data-selected="true"', 'data-readonly="true"', 'data-color="secondary"', 'data-size="lg"', 'role="switch"', 'name="theme"', 'value="dark"'] as $fragment) {
            $this->assertStringContainsString($fragment, $html);
        }
    }

    public function test_switch_document_contains_complete_examples(): void
    {
        $content = file_get_contents(base_path('content/components/switch.mdx'));

        foreach (['switch-basic', 'switch-label', 'switch-disabled', 'switch-sizes', 'switch-colors', 'switch-thumb-icon', 'switch-content-icons', 'switch-custom-styles', 'switch-form'] as $example) {
            $this->assertStringContainsString($example, $content);
        }
    }
}
