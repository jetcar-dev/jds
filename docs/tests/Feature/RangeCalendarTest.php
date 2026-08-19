<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

final class RangeCalendarTest extends TestCase
{
    public function test_range_calendar_forwards_calendar_props_slots_and_range_config(): void
    {
        $html = Blade::render(<<<'BLADE'
            <x-range-calendar
                name="period"
                :value="['start' => '2026-08-11', 'end' => '2026-08-18']"
                visible-month="2026-08-01"
                :visible-months="2"
                color="success"
                disabled
                read-only
                allows-non-contiguous-ranges
                :unavailable-values="['2026-08-15']"
                :presets="[
                    '이번 주' => ['start' => '2026-08-10', 'end' => '2026-08-16'],
                ]"
            >
                <x-slot:topContent>범위 안내</x-slot:topContent>
                <x-slot:bottomContent>범위 도움말</x-slot:bottomContent>
            </x-range-calendar>
        BLADE);

        foreach ([
            'data-ui-component="calendar"',
            'data-selection-mode="range"',
            'data-visible-months="2"',
            'data-color="success"',
            'data-disabled="true"',
            'data-readonly="true"',
            'name="period"',
            'data-slot="top-content"',
            'data-slot="bottom-content"',
            '범위 안내',
            '범위 도움말',
            'data-calendar-preset="2026-08-10/2026-08-16"',
            '"allowsNonContiguousRanges":true',
        ] as $fragment) {
            $this->assertStringContainsString($fragment, $html);
        }
    }

    public function test_range_calendar_document_covers_heroui_usage_and_api(): void
    {
        $response = $this->get('/components/range-calendar')->assertOk();

        foreach ([
            'range-calendar-basic',
            'range-calendar-disabled',
            'range-calendar-readonly',
            'range-calendar-bounds',
            'range-calendar-unavailable',
            'range-calendar-non-contiguous',
            'range-calendar-focused',
            'range-calendar-invalid',
            'range-calendar-month-year',
            'range-calendar-visible-months',
            'range-calendar-week-start',
            'range-calendar-page-behavior',
            'range-calendar-presets',
            'range-calendar-content',
            'range-calendar-form',
            'app-ui:range-calendar:change',
        ] as $fragment) {
            $response->assertSee($fragment);
        }
    }
}
