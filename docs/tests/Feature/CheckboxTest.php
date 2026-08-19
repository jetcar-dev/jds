<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

final class CheckboxTest extends TestCase
{
    public function test_checkbox_renders_heroui_v2_structure_and_native_input(): void
    {
        $html = Blade::render(<<<'BLADE'
<x-checkbox
    id="terms"
    name="terms"
    value="accepted"
    checked
    required
    readonly
    invalid
    color="danger"
    size="lg"
    radius="full"
    line-through
    disable-animation
>
    <x-slot:icon><span data-test-icon>♥</span></x-slot:icon>
    이용약관
</x-checkbox>
BLADE);

        foreach ([
            'data-slot="base"',
            'data-ui-component="checkbox"',
            'data-selected="true"',
            'data-invalid="true"',
            'data-readonly="true"',
            'data-color="danger"',
            'data-size="lg"',
            'data-radius="full"',
            'data-line-through="true"',
            'data-disable-animation="true"',
            'data-slot="hidden-input"',
            'type="checkbox"',
            'id="terms"',
            'name="terms"',
            'value="accepted"',
            'checked',
            'required',
            'aria-invalid="true"',
            'aria-readonly="true"',
            'data-slot="wrapper"',
            'data-slot="icon"',
            'data-test-icon',
            'data-slot="label"',
            '이용약관',
        ] as $fragment) {
            $this->assertStringContainsString($fragment, $html);
        }
    }

    public function test_indeterminate_checkbox_starts_in_mixed_visual_state(): void
    {
        $html = Blade::render('<x-checkbox indeterminate>Option</x-checkbox>');

        $this->assertStringContainsString('data-selected="true"', $html);
        $this->assertStringContainsString('data-indeterminate="true"', $html);
        $this->assertStringContainsString('class="app-checkbox-indeterminate"', $html);
        $this->assertStringNotContainsString(' checked', $html);
    }

    public function test_checkbox_document_matches_heroui_example_catalog(): void
    {
        $this->get('/components/checkbox')
            ->assertOk()
            ->assertSee('checkbox-basic')
            ->assertSee('checkbox-disabled')
            ->assertSee('checkbox-sizes')
            ->assertSee('checkbox-colors')
            ->assertSee('checkbox-radius')
            ->assertSee('checkbox-indeterminate')
            ->assertSee('checkbox-line-through')
            ->assertSee('checkbox-custom-icon')
            ->assertSee('checkbox-controlled')
            ->assertSee('checkbox-form')
            ->assertSee('none, sm, md, lg, full 중에서 모서리 크기를 선택합니다.')
            ->assertDontSee('isSelected')
            ->assertDontSee('isDisabled');
    }
}
