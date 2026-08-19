<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

final class DatePickerTest extends TestCase
{
    public function test_date_picker_renders_heroui_contract(): void
    {
        $html = Blade::render(<<<'BLADE'
            <x-date-picker
                name="scheduled_at"
                value="2026-08-05T15:45"
                label="방문 일시"
                variant="bordered"
                color="primary"
                size="lg"
                radius="lg"
                granularity="minute"
                :hour-cycle="24"
                :visible-months="2"
                first-day-of-week="mon"
                page-behavior="single"
                min-value="2026-08-01"
                max-value="2026-08-31"
                :unavailable-values="['2026-08-09']"
                required
            >
                <x-slot:selectorIcon><x-icon name="solar:calendar-search-linear" /></x-slot:selectorIcon>
            </x-date-picker>
        BLADE);

        foreach ([
            'data-ui-component="date-picker"',
            'data-slot="selector-button"',
            'data-slot="selector-icon"',
            'data-slot="popover-content"',
            'data-slot="calendar"',
            'data-picker-time',
            'data-ui-component="time-input"',
            'data-ui-component="date-input"',
            'data-ui-component="calendar"',
            'aria-haspopup="dialog"',
            'aria-expanded="false"',
            'data-required="true"',
            'name="scheduled_at"',
            'value="2026-08-05T15:45"',
            'data-visible-months="2"',
            '"pageBehavior":"single"',
            '"unavailableValues":["2026-08-09"]',
        ] as $fragment) {
            $this->assertStringContainsString($fragment, $html);
        }
    }

    public function test_date_picker_boolean_states_use_attribute_presence(): void
    {
        $html = Blade::render('<x-date-picker label="날짜" disabled read-only required invalid show-month-and-year-pickers disable-animation />');

        $this->assertStringContainsString('data-disabled="true"', $html);
        $this->assertStringContainsString('data-readonly="true"', $html);
        $this->assertStringContainsString('data-required="true"', $html);
        $this->assertStringContainsString('data-invalid="true"', $html);
        $this->assertStringContainsString('data-disable-animation="true"', $html);
        $this->assertStringContainsString('disabled', $html);
        $this->assertStringContainsString('"showMonthAndYearPickers":true', $html);

        $default = Blade::render('<x-date-picker />');
        $this->assertStringContainsString('data-icon="solar:calendar-bold"', $default);
    }

    public function test_date_picker_document_covers_supported_examples_and_api(): void
    {
        $response = $this->get('/components/date-picker')->assertOk();

        foreach ([
            'date-picker-basic',
            'date-picker-disabled',
            'date-picker-readonly',
            'date-picker-required',
            'date-picker-variants',
            'date-picker-label-placements',
            'date-picker-feedback',
            'date-picker-month-year',
            'date-picker-time',
            'date-picker-selector-icon',
            'date-picker-selector-placement',
            'date-picker-bounds',
            'date-picker-unavailable',
            'date-picker-visible-months',
            'date-picker-week-start',
            'date-picker-presets',
            'date-picker-form',
            'app-ui:date-picker:change',
            'calendarBottomContent',
            'selector-button-placement',
        ] as $fragment) {
            $response->assertSee($fragment);
        }
    }
}
