import {expect, test} from '@playwright/test'

test('Date Range Picker renders one HeroUI field with two editable date groups', async ({page}) => {
    await page.goto('/components/date-range-picker')

    const picker = page.locator('[data-preview-name="date-range-picker-basic"] [data-ui-component="date-range-picker"]')
    const wrapper = picker.locator(':scope > [data-slot="input-wrapper"]')

    await expect(picker).toHaveCSS('width', '320px')
    await expect(wrapper).toHaveCSS('height', '56px')
    await expect(wrapper).toHaveCSS('border-radius', '12px')
    await expect(wrapper).toHaveCSS('font-size', '16px')
    await expect(wrapper).toHaveCSS('line-height', '28px')
    await expect(picker.locator(':scope > [data-slot="input-wrapper"] [data-range-part]')).toHaveCount(2)
    await expect(picker.locator(':scope > [data-slot="input-wrapper"] [data-slot="separator"]')).toHaveText('~')
    await expect(picker.locator(':scope > [data-slot="input-wrapper"] [data-slot="selector-button"]')).toBeVisible()
    await expect(picker.locator(':scope > [data-slot="input-wrapper"] [data-slot="selector-icon"] .app-icon')).toHaveAttribute('data-icon', 'solar:calendar-bold')
    await expect(picker.locator(':scope > [data-slot="input-wrapper"] [data-slot="selector-icon"]')).toHaveCSS('font-size', '18px')
    await expect(picker.locator(':scope > [data-slot="input-wrapper"] [data-slot="selector-button"]')).toHaveCSS('font-size', '12px')
    await expect(picker.locator('[data-slot="segment"]').first()).toHaveCSS('border-radius', '6px')
    await expect(picker.locator('[data-range-part="start"] [data-slot="input-wrapper"]')).toHaveCSS('background-color', 'rgba(0, 0, 0, 0)')
    await picker.locator('[data-range-part="start"] [data-slot="segment"][data-editable="true"]').first().focus()
    await expect(picker).toHaveCSS('outline-style', 'none')
    const geometry = await picker.evaluate(element => {
        const wrapperRect = element.querySelector(':scope > [data-slot="input-wrapper"]').getBoundingClientRect()
        const endRect = element.querySelector('[data-range-part="end"]').getBoundingClientRect()
        const buttonRect = element.querySelector('[data-slot="selector-button"]').getBoundingClientRect()
        return {
            selectorRightInset: Math.round(wrapperRect.right - buttonRect.right),
            endFillsRemainingSpace: Math.round(buttonRect.left - endRect.right),
        }
    })
    expect(geometry).toEqual({selectorRightInset: 4, endFillsRemainingSpace: 0})
})

test('Date Range Picker opens the Range Calendar and keeps it open until the end date is selected', async ({page}) => {
    await page.goto('/components/date-range-picker')

    const picker = page.locator('[data-preview-name="date-range-picker-form"] [data-ui-component="date-range-picker"]')
    const selector = picker.locator('[data-slot="selector-button"]')
    const popover = page.locator(`#${await selector.getAttribute('aria-controls')}`)
    await selector.click()

    await expect(picker).toHaveAttribute('data-open', 'true')
    await expect(popover).toBeVisible()
    await expect(popover).toHaveCSS('background-color', 'rgb(255, 255, 255)')
    await expect(popover.locator('[data-ui-component="calendar"]')).toHaveCSS('background-color', 'rgb(255, 255, 255)')
    await expect(popover.locator('[data-ui-component="calendar"]')).toBeVisible()
    await expect(popover.locator('[data-ui-component="calendar"] [data-slot="grid"]')).toHaveCount(1)

    const calendar = popover.locator('[data-ui-component="calendar"]')
    await calendar.locator('[data-value="2026-08-10"]').click()
    await expect(picker).toHaveAttribute('data-open', 'true')
    await calendar.locator('[data-value="2026-08-12"]').click()
    await expect(picker).toHaveAttribute('data-open', 'false')
})

test('Date Range Picker form values, variants and controller stay synchronized', async ({page}) => {
    await page.goto('/components/date-range-picker')

    const picker = page.locator('[data-preview-name="date-range-picker-basic"] [data-ui-component="date-range-picker"]')
    await expect(picker.locator('[name="period[start]"]')).toHaveValue('')
    await expect(picker.locator('[name="period[end]"]')).toHaveValue('')

    await picker.evaluate(element => {
        window.__dateRangeEvents = []
        element.addEventListener('app-ui:date-range-picker:change', event => window.__dateRangeEvents.push(event.detail.value))
        AppUI.get(element).setValue({start: '2026-08-10', end: '2026-08-18'}, true)
    })

    await expect(picker.locator('[name="period[start]"]')).toHaveValue('2026-08-10')
    await expect(picker.locator('[name="period[end]"]')).toHaveValue('2026-08-18')
    expect(await picker.evaluate(() => window.__dateRangeEvents.at(-1))).toEqual({start: '2026-08-10', end: '2026-08-18'})

    const variants = page.locator('[data-preview-name="date-range-picker-variants"] [data-ui-component="date-range-picker"]')
    await expect(variants).toHaveCount(4)
    await expect(variants.nth(1).locator(':scope > [data-slot="input-wrapper"]')).toHaveCSS('border-top-width', '2px')
    await expect(variants.nth(2).locator(':scope > [data-slot="input-wrapper"]')).toHaveCSS('border-radius', '0px')

    const colors = page.locator('[data-preview-name="date-range-picker-colors-sizes"] [data-ui-component="date-range-picker"]')
    await expect(colors.nth(0).locator(':scope > [data-slot="input-wrapper"]')).toHaveCSS('background-color', 'rgb(230, 241, 254)')
    await expect(colors.nth(0).locator('[data-slot="segment"][data-editable="true"]').first()).toHaveCSS('color', 'rgb(0, 111, 238)')
    await expect(colors.nth(2).locator('[data-range-part="start"]')).toHaveCSS('font-size', '16px')

    const outsideLeft = page.locator('[data-preview-name="date-range-picker-label-placements"] [data-ui-component="date-range-picker"]').nth(2)
    await expect(outsideLeft).toHaveCSS('display', 'grid')
    const outsideLeftGeometry = await outsideLeft.evaluate(element => {
        const label = element.querySelector(':scope > [data-slot="label"]').getBoundingClientRect()
        const wrapper = element.querySelector(':scope > [data-slot="input-wrapper"]').getBoundingClientRect()
        return {
            labelBeforeField: label.right <= wrapper.left,
            verticallyAligned: Math.abs((label.top + label.height / 2) - (wrapper.top + wrapper.height / 2)) <= 1,
        }
    })
    expect(outsideLeftGeometry).toEqual({labelBeforeField: true, verticallyAligned: true})
})

test('Date Range Picker supports invalid, disabled and time field states', async ({page}) => {
    await page.goto('/components/date-range-picker')

    const invalid = page.locator('[data-preview-name="date-range-picker-invalid"] [data-ui-component="date-range-picker"]')
    await expect(invalid).toHaveAttribute('data-invalid', 'true')
    await expect(invalid.locator(':scope > [data-slot="helper-wrapper"] [data-slot="error-message"]')).toBeVisible()
    await expect(invalid.locator(':scope > [data-slot="input-wrapper"]')).toHaveCSS('background-color', 'rgba(0, 0, 0, 0)')
    await expect(invalid.locator(':scope > [data-slot="input-wrapper"]')).toHaveCSS('border-color', 'rgb(243, 18, 96)')

    const disabled = page.locator('[data-preview-name="date-range-picker-disabled"] [data-ui-component="date-range-picker"]')
    await expect(disabled).toHaveAttribute('data-disabled', 'true')
    await expect(disabled.locator('[data-slot="selector-button"]')).toBeDisabled()
    await expect(disabled.locator('[data-range-part="start"] [data-slot="segment"]:not([data-type="literal"])').first()).toHaveCSS('color', 'rgb(17, 24, 28)')

    const readonly = page.locator('[data-preview-name="date-range-picker-readonly-required"] [data-ui-component="date-range-picker"]').first()
    await expect(readonly).toHaveAttribute('data-readonly', 'true')
    await expect(readonly.locator('[data-slot="selector-button"]')).toBeDisabled()

    const timed = page.locator('[data-preview-name="date-range-picker-time"] [data-ui-component="date-range-picker"]')
    const timedSelector = timed.locator('[data-slot="selector-button"]')
    const timedPopover = page.locator(`#${await timedSelector.getAttribute('aria-controls')}`)
    await timedSelector.click()
    await expect(timedPopover.locator('[data-slot="grid"]')).toHaveCount(2)
    await expect(timedPopover.locator('[data-range-time="start"]')).toBeVisible()
    await expect(timedPopover.locator('[data-range-time="end"]')).toBeVisible()
    await expect(timedPopover.locator('[data-range-time="start"] > [data-slot="label"]')).toHaveText('시작 시간')
    await expect(timedPopover.locator('[data-range-time="end"] > [data-slot="label"]')).toHaveText('종료 시간')
    await expect(timedPopover.locator('[data-range-time="start"] [data-type="hour"]')).toHaveText('09')
    await expect(timedPopover.locator('[data-range-time="end"] [data-type="hour"]')).toHaveText('11')
    await page.setViewportSize({width: 560, height: 900})
    const timeRows = await timedPopover.locator('[data-range-time]').evaluateAll(elements => elements.map(element => {
        const rect = element.getBoundingClientRect()
        return {top: Math.round(rect.top), width: Math.round(rect.width)}
    }))
    expect(timeRows[0].top).toBe(timeRows[1].top)
    expect(timeRows[0].width).toBe(timeRows[1].width)

    const monthYear = page.locator('[data-preview-name="date-range-picker-month-year"] [data-ui-component="date-range-picker"]')
    const monthYearSelector = monthYear.locator('[data-slot="selector-button"]')
    const monthYearPopover = page.locator(`#${await monthYearSelector.getAttribute('aria-controls')}`)
    await monthYearSelector.click()
    await monthYearPopover.getByRole('button', {name: '월과 연도 선택으로 전환'}).click()
    await expect(monthYearPopover.locator('.app-calendar-picker')).toBeVisible()
    await expect(monthYearPopover.locator('.app-calendar-picker-list')).toHaveCount(2)
})
