<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

final class TabsTest extends TestCase
{
    public function test_tabs_render_heroui_slots_and_configuration(): void
    {
        $html = Blade::render(<<<'BLADE'
<x-tabs name="period" value="week" variant="bordered" color="primary" size="lg" radius="full" placement="start" full-width :disabled-keys="['locked']">
    <x-tabs-list aria-label="기간">
        <x-tabs-trigger value="day">일간</x-tabs-trigger>
        <x-tabs-trigger value="week"><x-slot:startContent>앞</x-slot:startContent>주간</x-tabs-trigger>
        <x-tabs-trigger value="locked" disabled>잠김</x-tabs-trigger>
    </x-tabs-list>
    <x-tabs-content value="day">일간 콘텐츠</x-tabs-content>
    <x-tabs-content value="week">주간 콘텐츠</x-tabs-content>
</x-tabs>
BLADE);

        $this->assertStringContainsString('data-slot="tabWrapper"', $html);
        $this->assertStringContainsString('data-slot="base"', $html);
        $this->assertStringContainsString('data-slot="tabList"', $html);
        $this->assertStringContainsString('data-slot="cursor"', $html);
        $this->assertStringContainsString('data-slot="tab"', $html);
        $this->assertStringContainsString('data-slot="tabContent"', $html);
        $this->assertStringContainsString('data-slot="panel"', $html);
        $this->assertStringContainsString('data-variant="bordered"', $html);
        $this->assertStringContainsString('data-color="primary"', $html);
        $this->assertStringContainsString('data-size="lg"', $html);
        $this->assertStringContainsString('data-radius="full"', $html);
        $this->assertStringContainsString('data-placement="start"', $html);
        $this->assertStringContainsString('data-orientation="vertical"', $html);
        $this->assertStringContainsString('data-full-width="true"', $html);
        $this->assertStringContainsString('data-should-select-on-press-up="true"', $html);
        $this->assertStringContainsString('name="period"', $html);
        $this->assertStringContainsString('앞', $html);
    }

    public function test_tabs_support_link_and_boolean_attributes(): void
    {
        $html = Blade::render('<x-tabs disabled disable-animation :should-select-on-press-up="false" :destroy-inactive-tab-panel="false"><x-tabs-list><x-tabs-trigger value="docs" href="/docs">문서</x-tabs-trigger></x-tabs-list><x-tabs-content value="docs">내용</x-tabs-content></x-tabs>');

        $this->assertStringContainsString('data-disabled="true"', $html);
        $this->assertStringContainsString('data-disable-animation="true"', $html);
        $this->assertStringContainsString('data-should-select-on-press-up="false"', $html);
        $this->assertStringContainsString('data-destroy-inactive-tab-panel="false"', $html);
        $this->assertStringContainsString('<a', $html);
        $this->assertStringContainsString('href="/docs"', $html);
    }

    public function test_tabs_document_contains_real_examples(): void
    {
        $this->get('/components/tabs')
            ->assertOk()
            ->assertSee('tabs-variants')
            ->assertSee('tabs-colors')
            ->assertSee('tabs-icons')
            ->assertSee('tabs-placement')
            ->assertSee('tabs-dynamic')
            ->assertSee('tabs-controlled')
            ->assertSee('tabs-form')
            ->assertSee('tabs-full-width')
            ->assertSee('app-ui:tabs:change');
    }
}
