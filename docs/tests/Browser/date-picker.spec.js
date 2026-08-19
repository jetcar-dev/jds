import {expect, test} from '@playwright/test'

test('Date Picker renders Date Input, selector button and Calendar slots', async ({page}) => {
    await page.goto('/components/date-picker')

    const picker = page.locator('[data-preview-name="date-picker-basic"] [data-ui-component="date-picker"]')
    const field = picker.locator('[data-ui-component="date-input"]')
    const selector = picker.locator('[data-slot="selector-button"]')

    await expect(picker).toHaveCSS('width', '320px')
    await expect(field.locator('[data-slot="input-wrapper"]')).toHaveCSS('height', '56px')
    await expect(field.locator('[data-slot="segment"]')).toHaveCount(6)
    await expect(selector).toHaveCSS('width', '32px')
    await expect(selector).toHaveCSS('height', '32px')
    await expect(selector.locator('[data-slot="selector-icon"]')).toHaveCSS('width', '18px')
    await expect(selector.locator('[data-icon="solar:calendar-bold"]')).toBeVisible()
    await expect(selector).toHaveAttribute('aria-expanded', 'false')
    await expect(picker.locator('[data-slot="popover-content"]')).toBeHidden()
})

test('Date Picker opens, selects a date and restores focus', async ({page}) => {
    await page.goto('/components/date-picker')

    const picker = page.locator('[data-preview-name="date-picker-bounds"] [data-ui-component="date-picker"]')
    const selector = picker.locator('[data-slot="selector-button"]')
    const popoverId = await selector.getAttribute('aria-controls')
    const popover = page.locator(`#${popoverId}`)
    const field = picker.locator('[data-ui-component="date-input"]')
    const input = picker.locator('[data-date-value]')

    await picker.evaluate(element => {
        window.__datePickerEvents = []
        element.addEventListener('app-ui:date-picker:change', event => window.__datePickerEvents.push(event.detail.value))
    })
    await selector.click()
    await expect(picker).toHaveAttribute('data-open', 'true')
    await expect(selector).toHaveAttribute('aria-expanded', 'true')
    await expect(popover).toBeVisible()
    await page.waitForTimeout(220)
    const [anchorBox, popoverBox] = await Promise.all([
        field.locator('[data-slot="inner-wrapper"]').boundingBox(),
        popover.boundingBox(),
    ])
    expect(Math.abs(popoverBox.x - (anchorBox.x + (anchorBox.width - popoverBox.width) / 2))).toBeLessThanOrEqual(1)
    await expect(popover.locator('[data-ui-component="calendar"]')).toHaveCSS('width', '256px')
    await expect(popover.locator('[data-ui-component="calendar"]')).toHaveCSS('box-shadow', 'none')

    await popover.locator('[data-value="2026-08-18"]').click()
    await expect(input).toHaveValue('2026-08-18')
    await expect(picker).toHaveAttribute('data-open', 'false')
    await expect(selector).toBeFocused()
    expect(await picker.evaluate(() => window.__datePickerEvents)).toContain('2026-08-18')
})

test('Date Picker variants, states and selector placements match the Date Input contract', async ({page}) => {
    await page.goto('/components/date-picker')

    const variants = page.locator('[data-preview-name="date-picker-variants"] [data-ui-component="date-picker"]')
    await expect(variants).toHaveCount(4)
    await expect(variants.nth(0).locator('[data-slot="input-wrapper"]')).toHaveCSS('border-top-width', '0px')
    await expect(variants.nth(1).locator('[data-slot="input-wrapper"]')).toHaveCSS('border-top-width', '2px')
    await expect(variants.nth(2).locator('[data-slot="input-wrapper"]')).toHaveCSS('border-radius', '0px')
    await expect(variants.nth(3).locator('[data-slot="input-wrapper"]')).toHaveCSS('border-top-width', '2px')

    const disabled = page.locator('[data-preview-name="date-picker-disabled"] [data-ui-component="date-picker"]')
    const readonly = page.locator('[data-preview-name="date-picker-readonly"] [data-ui-component="date-picker"]')
    await expect(disabled.locator('[data-slot="selector-button"]')).toBeDisabled()
    await expect(readonly.locator('[data-slot="selector-button"]')).toBeEnabled()

    const placements = page.locator('[data-preview-name="date-picker-selector-placement"] [data-ui-component="date-picker"]')
    await expect(placements.nth(0)).toHaveAttribute('data-selector-button-placement', 'end')
    await expect(placements.nth(1)).toHaveAttribute('data-selector-button-placement', 'start')
    await expect(placements.nth(1).locator('[data-slot="start-content"] [data-slot="selector-button"]')).toBeVisible()

    const labelPlacements = page.locator('[data-preview-name="date-picker-label-placements"] [data-ui-component="date-picker"]')
    const outsideLeft = labelPlacements.nth(2)
    await expect(outsideLeft).toHaveAttribute('data-label-placement', 'outside-left')
    await expect(outsideLeft.locator('[data-ui-component="date-input"]')).toHaveCSS('display', 'grid')
    const outsideLeftGeometry = await outsideLeft.evaluate(element => {
        const label = element.querySelector('[data-slot="label"]').getBoundingClientRect()
        const wrapper = element.querySelector('[data-slot="input-wrapper"]').getBoundingClientRect()
        return {
            labelBeforeField: label.right <= wrapper.left,
            verticallyAligned: Math.abs((label.top + label.height / 2) - (wrapper.top + wrapper.height / 2)) <= 1,
        }
    })
    expect(outsideLeftGeometry).toEqual({labelBeforeField: true, verticallyAligned: true})
})

test('Date Picker controller and manual input synchronize with Calendar', async ({page}) => {
    await page.goto('/components/date-picker')

    const picker = page.locator('[data-preview-name="date-picker-form"] [data-ui-component="date-picker"]')
    await picker.evaluate(element => AppUI.get(element).setValue('2026-08-12', true))
    await expect(picker.locator('[data-date-value]')).toHaveValue('2026-08-12')
    await expect(picker.locator('[data-type="day"]')).toHaveText('12')

    const selector = picker.locator('[data-slot="selector-button"]')
    const popover = page.locator(`#${await selector.getAttribute('aria-controls')}`)
    await selector.click()
    await expect(popover.locator('[data-value="2026-08-12"]')).toHaveAttribute('data-selected', 'true')
    await page.keyboard.press('Escape')
    await expect(picker).toHaveAttribute('data-open', 'false')

    await selector.focus()
    await page.keyboard.press('Alt+ArrowDown')
    await expect(picker).toHaveAttribute('data-open', 'true')
})

test('Date Picker preserves time segments when Calendar changes the date', async ({page}) => {
    await page.goto('/components/date-picker')

    const picker = page.locator('[data-preview-name="date-picker-time"] [data-ui-component="date-picker"]')
    const field = picker.locator('[data-date-picker-field]')
    await expect(field.locator('[data-type="hour"]')).toHaveText('15')
    await expect(field.locator('[data-type="minute"]')).toHaveText('45')
    const selector = picker.locator('[data-slot="selector-button"]')
    const popover = page.locator(`#${await selector.getAttribute('aria-controls')}`)
    await selector.click()
    const timeInput = popover.locator('[data-picker-time]')
    const bottomContent = popover.locator('[data-slot="bottom-content"]')
    await expect(timeInput).toBeVisible()
    await expect(bottomContent).toHaveCSS('background-color', 'rgb(250, 250, 250)')
    await expect(timeInput.locator('[data-slot="label"]')).toHaveText('시간')
    await expect(timeInput).toHaveCSS('display', 'flex')
    await expect(timeInput).toHaveCSS('font-family', /Pretendard/)
    await expect(timeInput).toHaveCSS('column-gap', '24px')
    await expect(timeInput.locator('[data-slot="label"]')).toHaveCSS('font-size', '12px')
    await expect(timeInput.locator('[data-slot="input-wrapper"]')).toHaveCSS('height', '32px')
    await expect(timeInput.locator('[data-slot="input"]')).toHaveCSS('font-size', '14px')
    await expect(timeInput).toHaveCSS('width', '256px')

    const hourSegment = timeInput.locator('[data-type="hour"]')
    await hourSegment.click()
    await hourSegment.press('1')
    await expect(hourSegment).toBeFocused()
    await expect(picker).toHaveAttribute('data-open', 'true')
    await expect(timeInput.locator('[data-time-value]')).toHaveValue('01:45')

    await hourSegment.press('7')
    await expect(timeInput.locator('[data-type="minute"]')).toBeFocused()
    await expect(timeInput.locator('[data-time-value]')).toHaveValue('17:45')

    await popover.locator('[data-value="2026-08-12"]').click()
    await expect(field.locator('[data-date-value]')).toHaveValue('2026-08-12T17:45')
    await expect(picker).toHaveAttribute('data-open', 'true')
    await expect(popover).toBeVisible()

    await timeInput.evaluate(element => AppUI.get(element).setValue('17:45', true))
    await expect(field.locator('[data-date-value]')).toHaveValue('2026-08-12T17:45')
})

test('Date Picker multiple months, presets and unavailable dates remain interactive', async ({page}) => {
    await page.goto('/components/date-picker')

    const multiple = page.locator('[data-preview-name="date-picker-visible-months"] [data-ui-component="date-picker"]')
    const multipleSelector = multiple.locator('[data-slot="selector-button"]')
    const multiplePopover = page.locator(`#${await multipleSelector.getAttribute('aria-controls')}`)
    await multipleSelector.click()
    await expect(multiplePopover.locator('[data-slot="grid"]')).toHaveCount(2)
    await page.keyboard.press('Escape')

    const unavailable = page.locator('[data-preview-name="date-picker-unavailable"] [data-ui-component="date-picker"]')
    const unavailableSelector = unavailable.locator('[data-slot="selector-button"]')
    const unavailablePopover = page.locator(`#${await unavailableSelector.getAttribute('aria-controls')}`)
    await unavailableSelector.click()
    await expect(unavailablePopover.locator('[data-value="2026-08-08"]')).toBeDisabled()
    await page.keyboard.press('Escape')

    const presets = page.locator('[data-preview-name="date-picker-presets"] [data-ui-component="date-picker"]')
    const presetsSelector = presets.locator('[data-slot="selector-button"]')
    const presetsPopover = page.locator(`#${await presetsSelector.getAttribute('aria-controls')}`)
    await presetsSelector.click()
    await presetsPopover.locator('[data-calendar-preset="2026-08-31"]').click()
    await expect(presets.locator('[data-date-value]')).toHaveValue('2026-08-31')
})
