<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

final class AutocompleteTest extends TestCase
{
    public function test_autocomplete_renders_heroui_slots_and_form_contract(): void
    {
        $html = Blade::render(<<<'BLADE'
<x-autocomplete id="animal" name="animal_id" value="cat" label="동물" placeholder="검색" variant="bordered" color="primary" size="lg" radius="full" label-placement="outside" required invalid error-message="선택하세요" :disabled-keys="['dog']" clearable>
    <x-slot:startContent>앞</x-slot:startContent>
    <x-slot:selectorIcon>열기</x-slot:selectorIcon>
    <x-autocomplete-section title="반려동물">
        <x-autocomplete-item value="cat" description="고양잇과"><x-slot:startContent>🐈</x-slot:startContent>고양이</x-autocomplete-item>
        <x-autocomplete-item value="dog">강아지</x-autocomplete-item>
    </x-autocomplete-section>
</x-autocomplete>
BLADE);

        $this->assertStringContainsString('data-slot="base"', $html);
        $this->assertStringContainsString('data-slot="input-wrapper"', $html);
        $this->assertStringContainsString('data-slot="input"', $html);
        $this->assertStringContainsString('data-slot="endContentWrapper"', $html);
        $this->assertStringContainsString('data-slot="clearButton"', $html);
        $this->assertStringContainsString('data-slot="selectorButton"', $html);
        $this->assertStringContainsString('data-slot="clearIcon"', $html);
        $this->assertStringContainsString('data-slot="popoverContent"', $html);
        $this->assertStringContainsString('data-slot="listboxWrapper"', $html);
        $this->assertStringContainsString('data-slot="listbox"', $html);
        $this->assertStringContainsString('data-slot="listbox-section"', $html);
        $this->assertStringContainsString('data-slot="listbox-item"', $html);
        $this->assertStringContainsString('data-invalid="true"', $html);
        $this->assertStringContainsString('data-variant="bordered"', $html);
        $this->assertStringContainsString('data-color="primary"', $html);
        $this->assertStringContainsString('data-size="lg"', $html);
        $this->assertStringContainsString('data-radius="full"', $html);
        $this->assertStringContainsString('name="animal_id"', $html);
        $this->assertStringContainsString('value="cat"', $html);
        $this->assertStringContainsString('aria-invalid="true"', $html);
        $this->assertStringContainsString('required', $html);
        $this->assertStringContainsString('앞', $html);
        $this->assertStringContainsString('열기', $html);
    }

    public function test_autocomplete_boolean_and_custom_value_options_render(): void
    {
        $html = Blade::render('<x-autocomplete read-only allows-custom-value :allows-empty-collection="false" :should-close-on-blur="false" menu-trigger="manual" disable-selector-icon-rotation disable-animation loading virtualized :max-listbox-height="200" :item-height="40"><x-autocomplete-item value="one">하나</x-autocomplete-item></x-autocomplete>');

        $this->assertStringContainsString('data-readonly="true"', $html);
        $this->assertStringContainsString('data-allows-custom-value="true"', $html);
        $this->assertStringContainsString('data-allows-empty-collection="false"', $html);
        $this->assertStringContainsString('data-should-close-on-blur="false"', $html);
        $this->assertStringContainsString('data-menu-trigger="manual"', $html);
        $this->assertStringContainsString('data-disable-selector-icon-rotation="true"', $html);
        $this->assertStringContainsString('data-disable-animation="true"', $html);
        $this->assertStringContainsString('data-loading="true"', $html);
        $this->assertStringContainsString('data-virtualized="true"', $html);
        $this->assertStringContainsString('data-max-listbox-height="200"', $html);
        $this->assertStringContainsString('data-item-height="40"', $html);
    }

    public function test_autocomplete_document_contains_real_examples(): void
    {
        $this->get('/components/autocomplete')
            ->assertOk()
            ->assertSee('autocomplete-dynamic')
            ->assertSee('autocomplete-disabled-items')
            ->assertSee('autocomplete-variants')
            ->assertSee('autocomplete-label-placement')
            ->assertSee('autocomplete-item-content')
            ->assertSee('autocomplete-custom-value')
            ->assertSee('autocomplete-sections')
            ->assertSee('autocomplete-virtualized')
            ->assertSee('autocomplete-controlled')
            ->assertSee('app-ui:autocomplete:input-change');
    }
}
