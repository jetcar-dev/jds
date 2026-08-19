<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

final class CalendarTest extends TestCase
{
    public function test_calendar_renders_heroui_contract_and_config(): void
    {
        $html = Blade::render(<<<'BLADE'
            <x-calendar
                name="booking_date"
                value="2026-08-05"
                color="success"
                :visible-months="2"
                first-day-of-week="mon"
                page-behavior="single"
                show-month-and-year-pickers
                invalid
                error-message="날짜를 확인하세요."
                :unavailable-values="['2026-08-08']"
                :presets="['오늘' => 'today', '급여일' => '2026-08-25']"
            >
                <x-slot:topContent>빠른 선택</x-slot:topContent>
                <x-slot:bottomContent>도움말</x-slot:bottomContent>
            </x-calendar>
        BLADE);

        foreach ([
            'data-ui-component="calendar"',
            'data-slot="base"',
            'data-slot="content"',
            'data-slot="top-content"',
            'data-slot="bottom-content"',
            'data-slot="helper-wrapper"',
            'data-slot="error-message"',
            'data-color="success"',
            'data-visible-months="2"',
            'data-invalid="true"',
            'data-calendar-input',
            'name="booking_date"',
            '2026-08-08',
            '날짜를 확인하세요.',
            'data-slot="preset-group"',
            'data-calendar-preset="today"',
            'data-calendar-preset="2026-08-25"',
        ] as $fragment) {
            $this->assertStringContainsString($fragment, $html);
        }

        $this->assertStringContainsString('"showMonthAndYearPickers":false', $html);
    }

    public function test_calendar_boolean_states_use_attribute_presence(): void
    {
        $html = Blade::render('<x-calendar disabled read-only auto-focus hide-disabled-dates disable-animation />');

        $this->assertStringContainsString('data-disabled="true"', $html);
        $this->assertStringContainsString('data-readonly="true"', $html);
        $this->assertStringContainsString('aria-disabled="true"', $html);
        $this->assertStringContainsString('"autoFocus":true', $html);
        $this->assertStringContainsString('"hideDisabledDates":true', $html);
        $this->assertStringContainsString('data-disable-animation="true"', $html);
    }

    public function test_calendar_document_covers_supported_examples_and_api(): void
    {
        $response = $this->get('/components/calendar')->assertOk();

        foreach ([
            'calendar-basic',
            'calendar-disabled',
            'calendar-readonly',
            'calendar-colors',
            'calendar-bounds',
            'calendar-unavailable',
            'calendar-invalid',
            'calendar-month-year',
            'calendar-visible-months',
            'calendar-week-start',
            'calendar-page-behavior',
            'calendar-content-slots',
            'calendar-presets',
            'calendar-preset-overflow',
            '지원값',
            'calendar-form',
            'app-ui:calendar:change',
            'app-ui:calendar:focus-change',
            'app-ui:calendar:page-change',
        ] as $fragment) {
            $response->assertSee($fragment);
        }
    }
}
