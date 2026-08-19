<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

final class AlertTest extends TestCase
{
    public function test_alert_renders_heroui_slots_and_states(): void
    {
        $html = Blade::render(<<<'BLADE'
<x-alert title="저장 완료" description="변경 사항을 반영했습니다." color="success" variant="faded" closable>
    <x-slot:startContent>앞</x-slot:startContent>
    <x-slot:endContent>뒤</x-slot:endContent>
</x-alert>
BLADE);

        $this->assertStringContainsString('role="alert"', $html);
        $this->assertStringContainsString('data-slot="base"', $html);
        $this->assertStringContainsString('data-slot="main-wrapper"', $html);
        $this->assertStringContainsString('data-slot="icon-wrapper"', $html);
        $this->assertStringContainsString('data-slot="close-button"', $html);
        $this->assertStringContainsString('data-visible="true"', $html);
        $this->assertStringContainsString('data-closeable="true"', $html);
        $this->assertStringContainsString('data-variant="faded"', $html);
        $this->assertStringContainsString('앞', $html);
        $this->assertStringContainsString('뒤', $html);
    }

    public function test_alert_can_hide_icon_and_itself(): void
    {
        $withoutIcon = Blade::render('<x-alert hide-icon title="텍스트만" />');
        $hidden = Blade::render('<x-alert :visible="false" title="숨김" />');

        $this->assertStringNotContainsString('data-slot="icon-wrapper"', $withoutIcon);
        $this->assertSame('', trim($hidden));
    }

    public function test_alert_document_renders_four_real_examples(): void
    {
        $this->get('/components/alert')
            ->assertOk()
            ->assertSee('alert-colors')
            ->assertSee('alert-variants')
            ->assertSee('alert-content')
            ->assertSee('app-ui:alert:close');
    }
}
