<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

final class DateRangePickerTest extends TestCase
{
    public function test_date_range_picker_renders_heroui_contract(): void
    {
        $html = Blade::render(<<<'BLADE'
            <x-date-range-picker
                name="period"
                label="기간"
                variant="bordered"
                color="secondary"
                size="lg"
                radius="lg"
                label-placement="outside-left"
                :visible-months="2"
                page-behavior="single"
                min-value="2026-08-01"
                max-value="2026-09-30"
                :value="['start' => '2026-08-05', 'end' => '2026-08-12']"
                required
                clearable
                show-month-and-year-pickers
            />
        BLADE);

        foreach ([
            'data-ui-component="date-range-picker"',
            'data-slot="base"',
            'data-slot="input-wrapper"',
            'data-slot="inner-wrapper"',
            'data-slot="start-input"',
            'data-slot="separator"',
            'data-slot="end-input"',
            'data-slot="clear-button"',
            'data-slot="selector-button"',
            'data-slot="popover-content"',
            'data-ui-component="calendar"',
            'data-variant="bordered"',
            'data-color="secondary"',
            'data-size="lg"',
            'data-required="true"',
            'name="period[start]"',
            'name="period[end]"',
            '>~</span>',
            '"granularity":"day"',
        ] as $fragment) {
            $this->assertStringContainsString($fragment, $html);
        }
    }

    public function test_date_range_picker_supports_time_and_boolean_states(): void
    {
        $html = Blade::render(<<<'BLADE'
            <x-date-range-picker
                label="회의 기간"
                granularity="minute"
                :value="['start' => '2026-08-05T09:30', 'end' => '2026-08-05T11:00']"
                disabled
                read-only
                invalid
                disable-animation
            />
        BLADE);

        foreach ([
            'data-disabled="true"',
            'data-readonly="true"',
            'data-invalid="true"',
            'data-disable-animation="true"',
            'data-range-time="start"',
            'data-range-time="end"',
            '시작 시간',
            '종료 시간',
        ] as $fragment) {
            $this->assertStringContainsString($fragment, $html);
        }
    }

    public function test_date_range_picker_localizes_time_field_labels(): void
    {
        $html = Blade::render(<<<'BLADE'
            <x-date-range-picker
                locale="en-US"
                granularity="minute"
                :hour-cycle="12"
                :value="['start' => '2026-08-05T09:30', 'end' => '2026-08-05T11:00']"
            />
        BLADE);

        $this->assertStringContainsString('Start time', $html);
        $this->assertStringContainsString('End time', $html);
        $this->assertStringNotContainsString('시작 시간', $html);
        $this->assertStringNotContainsString('종료 시간', $html);
    }

    public function test_date_range_picker_document_covers_public_api(): void
    {
        $response = $this->get('/components/date-range-picker')->assertOk();

        foreach ([
            'date-range-picker-basic',
            'date-range-picker-disabled',
            'date-range-picker-readonly-required',
            'date-range-picker-variants',
            'date-range-picker-colors-sizes',
            'date-range-picker-visible-months',
            'date-range-picker-label-placements',
            'date-range-picker-invalid',
            'date-range-picker-month-year',
            'date-range-picker-bounds',
            'date-range-picker-time',
            'date-range-picker-granularity',
            'date-range-picker-time-zones',
            'date-range-picker-selector-icon',
            'date-range-picker-selector-placement',
            'date-range-picker-content',
            'date-range-picker-locales',
            'date-range-picker-unavailable',
            'date-range-picker-presets',
            'date-range-picker-form',
            'app-ui:date-range-picker:change',
            'calendarBottomContent',
            'period[start]',
            'period[end]',
        ] as $fragment) {
            $response->assertSee($fragment);
        }
    }
}
