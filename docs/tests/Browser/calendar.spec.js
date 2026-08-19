import {expect, test} from '@playwright/test'

test('Calendar matches HeroUI layout, slots and selected state', async ({page}) => {
    await page.goto('/components/calendar')

    const calendar = page.locator('[data-preview-name="calendar-basic"] [data-ui-component="calendar"]').first()
    await expect(calendar).toHaveCSS('width', '256px')
    await expect(calendar.locator('[data-slot="header-wrapper"]')).toHaveCSS('height', '48px')
    await expect(calendar.locator('[data-slot="grid-wrapper"]')).toHaveCSS('height', '260px')
    await expect(calendar.locator('[data-slot="prev-button"] svg')).toBeVisible()
    await expect(calendar.locator('[data-slot="next-button"] svg')).toBeVisible()
    await expect(calendar.locator('[data-slot="grid-header-cell"]')).toHaveCount(7)
    await expect(calendar.locator('[data-slot="cell-button"]')).toHaveCount(42)
    await expect(calendar.locator('[data-slot="grid-header-row"]')).toHaveCSS('padding', '0px 16px 8px')
    await expect(calendar.locator('[data-slot="grid-header-cell"]').first()).toHaveCSS('width', '32px')
    await expect(calendar.locator('[data-slot="grid-body-row"]').first()).toHaveCSS('height', '36px')
    await expect(calendar.locator('[data-slot="cell"]').first()).toHaveCSS('padding', '2px 0px')
    await expect(calendar.locator('[data-slot="cell-button"][data-outside-month="true"]').first()).toBeDisabled()

    const selectedCalendar = page.locator('[data-preview-name="calendar-basic"] [data-ui-component="calendar"]').nth(1)
    await expect(selectedCalendar.locator('[data-value="2026-08-05"]')).toHaveAttribute('data-selected', 'true')
})

test('Calendar selects, emits native and custom events, and exposes its controller', async ({page}) => {
    await page.goto('/components/calendar')
    const calendar = page.locator('[data-preview-name="calendar-form"] [data-ui-component="calendar"]')
    await calendar.evaluate(element => {
        window.__calendarEvents = []
        element.querySelector('[data-calendar-input]').addEventListener('change', event => window.__calendarEvents.push(['native', event.target.value]))
        element.addEventListener('app-ui:calendar:change', event => window.__calendarEvents.push(['custom', event.detail.value]))
    })
    await calendar.locator('[data-value="2026-08-12"]').click()
    await expect(calendar.locator('[data-value="2026-08-12"]')).toHaveAttribute('data-selected', 'true')
    await expect(calendar.locator('[data-calendar-input]')).toHaveValue('2026-08-12')

    const result = await calendar.evaluate(element => ({
        value: AppUI.get(element).getValue(),
        events: window.__calendarEvents,
        methods: ['getValue', 'setValue', 'next', 'previous', 'focus', 'destroy'].every(method => typeof AppUI.get(element)[method] === 'function'),
    }))
    expect(result.value).toBe('2026-08-12')
    expect(result.events).toEqual([['native', '2026-08-12'], ['custom', '2026-08-12']])
    expect(result.methods).toBe(true)
})

test('Calendar disabled, readonly, unavailable and month picker states behave correctly', async ({page}) => {
    await page.goto('/components/calendar')

    const disabled = page.locator('[data-preview-name="calendar-disabled"] [data-ui-component="calendar"]')
    await expect(disabled).toHaveCSS('opacity', '1')
    await expect(disabled.locator('[data-slot="prev-button"]')).toBeDisabled()
    await expect(disabled.locator('[data-slot="cell-button"]:not([data-outside-month="true"])').first()).toBeDisabled()

    const readonly = page.locator('[data-preview-name="calendar-readonly"] [data-ui-component="calendar"]')
    const readonlyDate = readonly.locator('[data-value="2026-08-12"]')
    await readonlyDate.hover()
    await expect(readonlyDate).toHaveCSS('background-color', 'rgba(0, 0, 0, 0)')
    await readonly.locator('[data-value="2026-08-12"]').click()
    expect(await readonly.evaluate(element => AppUI.get(element).getValue())).toBe('2026-08-05')

    const unavailable = page.locator('[data-preview-name="calendar-unavailable"] [data-ui-component="calendar"]')
    const unavailableDate = unavailable.locator('[data-value="2026-08-08"]')
    await expect(unavailableDate).toHaveAttribute('data-unavailable', 'true')
    await expect(unavailableDate).toHaveCSS('text-decoration-line', 'line-through')
    expect(await unavailableDate.evaluate(element => getComputedStyle(element).color)).toBe(
        await unavailable.locator('[data-outside-month="true"]').first().evaluate(element => getComputedStyle(element).color),
    )
    expect(await unavailableDate.evaluate(element => element.disabled)).toBe(false)
    await unavailableDate.focus()
    await expect(unavailableDate).toBeFocused()
    await unavailableDate.dispatchEvent('click')
    expect(await unavailable.evaluate(element => AppUI.get(element).getValue())).toBe('')

    const picker = page.locator('[data-preview-name="calendar-month-year"] [data-ui-component="calendar"]')
    await picker.locator('[data-slot="title"]').click()
    await expect(picker.locator('[data-slot="picker-wrapper"]')).toBeVisible()
    await expect(picker).toHaveCSS('height', '272px')
    await expect(picker.locator('[data-slot="grid"]')).toHaveCSS('opacity', '0')
    await expect(picker.locator('[data-slot="picker-month-list"]')).toBeVisible()
    await expect(picker.locator('[data-slot="picker-year-list"]')).toBeVisible()
    expect(await picker.locator('[data-slot="picker-wrapper"] > [data-slot$="-list"]').evaluateAll(elements => elements.map(element => element.dataset.slot))).toEqual([
        'picker-year-list',
        'picker-month-list',
    ])
    await expect(picker.locator('[data-slot="picker-highlight"]')).toHaveCSS('height', '32px')

    const invalid = page.locator('[data-preview-name="calendar-invalid"] [data-ui-component="calendar"]')
    const invalidSelected = invalid.locator('[data-selected="true"]')
    await expect(invalidSelected).toHaveAttribute('aria-invalid', 'true')
    await expect(invalidSelected).toHaveCSS('background-color', 'rgb(0, 111, 238)')
    await expect(invalid.locator('[data-slot="error-message"]')).toBeVisible()
})

test('Calendar keyboard navigation and page behavior are stable', async ({page}) => {
    await page.goto('/components/calendar')
    const calendar = page.locator('[data-preview-name="calendar-week-start"] [data-ui-component="calendar"]')
    const day = calendar.locator('[data-value="2026-08-10"]')
    await day.focus()
    await page.keyboard.press('ArrowRight')
    await expect(calendar.locator('[data-value="2026-08-11"]')).toBeFocused()
    await page.keyboard.press('ArrowDown')
    await expect(calendar.locator('[data-value="2026-08-18"]')).toBeFocused()

    const paged = page.locator('[data-preview-name="calendar-page-behavior"] [data-ui-component="calendar"]')
    await expect(paged.locator('[data-slot="header-wrapper"]')).toHaveCount(1)
    await expect(paged.locator('[data-slot="header"]')).toHaveCount(2)
    await expect(paged.locator('[data-slot="grid"]')).toHaveCount(2)
    await paged.locator('[data-slot="next-button"]:visible').click()
    await expect(paged.locator('[data-slot="title"]').first()).toContainText('9월')
    expect(await paged.evaluate(element => element.getAnimations({subtree: true}).length)).toBeGreaterThan(0)
})

test('Calendar hover and custom presets match the HeroUI state contract', async ({page}) => {
    await page.goto('/components/calendar')

    const colored = page.locator('[data-preview-name="calendar-colors"] [data-ui-component="calendar"]').first()
    const hoverDate = colored.locator('[data-value="2026-08-12"]')
    const expectedBackground = await colored.evaluate(element => {
        const probe = document.createElement('span')
        probe.style.color = 'hsl(var(--primary-50))'
        element.appendChild(probe)
        const color = getComputedStyle(probe).color
        probe.remove()
        return color
    })
    const expectedForeground = await colored.evaluate(element => {
        const probe = document.createElement('span')
        probe.style.color = 'hsl(var(--primary-400))'
        element.appendChild(probe)
        const color = getComputedStyle(probe).color
        probe.remove()
        return color
    })
    await hoverDate.hover()
    await expect(hoverDate).toHaveCSS('background-color', expectedBackground)
    await expect(hoverDate).toHaveCSS('color', expectedForeground)

    const presets = page.locator('[data-preview-name="calendar-presets"] [data-ui-component="calendar"]').nth(1)
    await expect(presets.locator('[data-slot="top-content"]')).toHaveCSS('padding', '12px 12px 8px')
    await presets.locator('[data-calendar-preset="2026-08-25"]').click()
    await expect(presets.locator('[data-value="2026-08-25"]')).toHaveAttribute('data-selected', 'true')
    await expect(presets.locator('[data-calendar-preset="2026-08-25"]')).not.toHaveAttribute('aria-pressed', /.+/)
    await expect(presets.locator('[data-calendar-preset="2026-08-25"]')).not.toHaveAttribute('data-selected', /.+/)

    const bottom = page.locator('[data-preview-name="calendar-presets"] [data-ui-component="calendar"]').nth(2)
    await expect(bottom.locator('[data-slot="bottom-content"]')).toHaveCSS('min-height', '55px')
    await bottom.locator('[data-calendar-preset="+1 month"]').click()
    expect(await bottom.evaluate(element => AppUI.get(element).getValue())).toMatch(/^\d{4}-\d{2}-\d{2}$/)

    const overflowCalendars = page.locator('[data-preview-name="calendar-preset-overflow"] [data-ui-component="calendar"]')
    const topOverflow = overflowCalendars.nth(0).locator('[data-slot="top-content"]')
    const bottomOverflow = overflowCalendars.nth(1).locator('[data-slot="bottom-content"]')
    expect(await topOverflow.evaluate(element => element.scrollWidth > element.clientWidth)).toBe(true)
    expect(await bottomOverflow.evaluate(element => element.scrollWidth > element.clientWidth)).toBe(true)
})
