<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

final class AccordionTest extends TestCase
{
    public function test_accordion_renders_heroui_configuration(): void
    {
        $html = Blade::render(<<<'BLADE'
<x-accordion variant="splitted" selection-mode="multiple" compact :disabled-keys="['locked']" :default-selected-keys="['one']" keep-content-mounted>
    <x-accordion-item value="one" title="첫 번째" subtitle="부제목" open>
        <x-slot:startContent>앞</x-slot:startContent>
        <x-slot:indicator>표시</x-slot:indicator>
        본문
    </x-accordion-item>
    <x-accordion-item value="locked" title="잠김">잠긴 본문</x-accordion-item>
</x-accordion>
BLADE);

        $this->assertStringContainsString('data-slot="accordion"', $html);
        $this->assertStringContainsString('data-variant="splitted"', $html);
        $this->assertStringContainsString('data-selection-mode="multiple"', $html);
        $this->assertStringContainsString('data-compact="true"', $html);
        $this->assertStringContainsString('data-keep-content-mounted="true"', $html);
        $this->assertStringContainsString('data-ui-part="accordion-item"', $html);
        $this->assertStringContainsString('data-slot="heading"', $html);
        $this->assertStringContainsString('data-slot="trigger"', $html);
        $this->assertStringContainsString('data-slot="titleWrapper"', $html);
        $this->assertStringContainsString('data-slot="subtitle"', $html);
        $this->assertStringContainsString('data-slot="indicator"', $html);
        $this->assertStringContainsString('data-slot="content"', $html);
        $this->assertStringContainsString('aria-controls=', $html);
        $this->assertStringContainsString('aria-labelledby=', $html);
        $this->assertStringContainsString('앞', $html);
        $this->assertStringContainsString('표시', $html);
    }

    public function test_accordion_boolean_attributes_use_presence_syntax(): void
    {
        $html = Blade::render('<x-accordion disabled hide-indicator disable-animation :full-width="false"><x-accordion-item value="one" title="제목">본문</x-accordion-item></x-accordion>');

        $this->assertStringContainsString('data-disabled="true"', $html);
        $this->assertStringContainsString('data-hide-indicator="true"', $html);
        $this->assertStringContainsString('data-disable-animation="true"', $html);
        $this->assertStringContainsString('data-full-width="false"', $html);
    }

    public function test_accordion_document_contains_real_examples(): void
    {
        $this->get('/components/accordion')
            ->assertOk()
            ->assertSee('accordion-multiple')
            ->assertSee('accordion-compact')
            ->assertSee('accordion-variant-light')
            ->assertSee('accordion-variant-shadow')
            ->assertSee('accordion-variant-bordered')
            ->assertSee('accordion-variant-splitted')
            ->assertSee('accordion-start-content')
            ->assertSee('accordion-custom-indicator')
            ->assertSee('accordion-animation')
            ->assertSee('app-ui:accordion:change');
    }
}
