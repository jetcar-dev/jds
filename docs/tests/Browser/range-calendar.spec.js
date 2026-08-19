import {expect, test} from '@playwright/test'

test('Range Calendar forwards Calendar props and renders HeroUI range segments', async ({page}) => {
    await page.goto('/components/range-calendar')

    const examples = page.locator('[data-preview-name="range-calendar-basic"] [data-ui-component="calendar"]')
    const selected = examples.nth(1)
    await expect(selected).toHaveAttribute('data-selection-mode', 'range')
    await expect(selected).toHaveCSS('width', '256px')

    const range = selected.locator('[data-range-selection="true"]')
    await expect(range).toHaveCount(8)
    await expect(selected.locator('[data-value="2026-08-11"]')).toHaveAttribute('data-selection-start', 'true')
    await expect(selected.locator('[data-value="2026-08-18"]')).toHaveAttribute('data-selection-end', 'true')
    await expect(selected.locator('[data-value="2026-08-15"]')).toHaveAttribute('data-range-end', 'true')
    await expect(selected.locator('[data-value="2026-08-16"]')).toHaveAttribute('data-range-start', 'true')
    await expect(selected.locator('[data-value="2026-08-12"]')).toHaveAttribute('data-selected', 'true')
    await expect(selected.locator('[data-value="2026-08-12"]')).toHaveAttribute('aria-pressed', 'true')

    const colors = await selected.evaluate(element => {
        const start = element.querySelector('[data-value="2026-08-11"]')
        const middle = element.querySelector('[data-value="2026-08-12"]')
        return {
            startBackground: getComputedStyle(start).backgroundColor,
            startForeground: getComputedStyle(start).color,
            rangeBackground: getComputedStyle(middle.parentElement, '::before').backgroundColor,
            middleBackground: getComputedStyle(middle).backgroundColor,
            middleForeground: getComputedStyle(middle).color,
        }
    })
    expect(colors).toEqual({
        startBackground: 'rgb(0, 111, 238)',
        startForeground: 'rgb(255, 255, 255)',
        rangeBackground: 'rgb(230, 241, 254)',
        middleBackground: 'rgba(0, 0, 0, 0)',
        middleForeground: 'rgb(0, 111, 238)',
    })

    const start = selected.locator('[data-value="2026-08-11"]')
    await start.focus()
    await expect(start).toHaveCSS('background-color', 'rgb(0, 111, 238)')
    await expect(start).toHaveCSS('z-index', '2')

    const visibleMonths = page.locator('[data-preview-name="range-calendar-visible-months"] [data-ui-component="calendar"]')
    await expect(visibleMonths).toHaveAttribute('data-visible-months', '3')
    await expect(visibleMonths.locator('[data-slot="grid"]')).toHaveCount(3)

    await page.getByRole('button', {name: '다크 모드로 전환'}).click()
    await page.waitForTimeout(200)
    const darkColors = await selected.evaluate(element => {
        const start = element.querySelector('[data-value="2026-08-11"]')
        const middle = element.querySelector('[data-value="2026-08-12"]')
        return {
            startBackground: getComputedStyle(start).backgroundColor,
            rangeBackground: getComputedStyle(middle.parentElement, '::before').backgroundColor,
            middleForeground: getComputedStyle(middle).color,
        }
    })
    expect(darkColors).toEqual({
        startBackground: 'rgb(51, 142, 247)',
        rangeBackground: 'rgb(0, 23, 49)',
        middleForeground: 'rgb(51, 142, 247)',
    })
})

test('Range Calendar previews forward and reverse ranges and clears preview outside', async ({page}) => {
    await page.goto('/components/range-calendar')
    const calendar = page.locator('[data-preview-name="range-calendar-basic"] [data-ui-component="calendar"]').first()

    await calendar.locator('[data-value="2026-08-11"]').click()
    await calendar.locator('[data-value="2026-08-18"]').hover()
    await expect(calendar.locator('[data-value="2026-08-18"]')).toHaveAttribute('data-selection-end', 'true')
    await expect(calendar.locator('[data-value="2026-08-18"]')).toHaveCSS('background-color', 'rgb(0, 111, 238)')
    await expect(calendar.locator('[data-range-selection="true"]')).toHaveCount(8)

    await calendar.locator('[data-value="2026-08-05"]').hover()
    await expect(calendar.locator('[data-value="2026-08-05"]')).toHaveAttribute('data-selection-start', 'true')
    await expect(calendar.locator('[data-value="2026-08-11"]')).toHaveAttribute('data-selection-end', 'true')

    await page.locator('h1').hover()
    await expect(calendar.locator('[data-range-selection="true"]')).toHaveCount(0)
    await expect(calendar.locator('[data-value="2026-08-11"]')).toHaveAttribute('data-selection-start', 'true')
})

test('Range Calendar blocks unavailable gaps unless non-contiguous ranges are enabled', async ({page}) => {
    await page.goto('/components/range-calendar')

    const contiguous = page.locator('[data-preview-name="range-calendar-unavailable"] [data-ui-component="calendar"]')
    await contiguous.locator('[data-value="2026-08-07"]').click()
    await contiguous.locator('[data-value="2026-08-10"]').click()
    expect(await contiguous.evaluate(element => AppUI.get(element).getValue())).toEqual({start: '2026-08-07', end: ''})

    const nonContiguous = page.locator('[data-preview-name="range-calendar-non-contiguous"] [data-ui-component="calendar"]')
    await nonContiguous.locator('[data-value="2026-08-07"]').click()
    await nonContiguous.locator('[data-value="2026-08-10"]').click()
    expect(await nonContiguous.evaluate(element => AppUI.get(element).getValue())).toEqual({start: '2026-08-07', end: '2026-08-10'})
    await expect(nonContiguous.locator('[data-value="2026-08-08"]')).toHaveAttribute('data-unavailable', 'true')
    await expect(nonContiguous.locator('[data-value="2026-08-08"]')).toHaveAttribute('data-selected', 'false')
    await expect(nonContiguous.locator('[data-value="2026-08-10"]')).toHaveAttribute('data-range-start', 'true')
})

test('Range Calendar presets, form value, events and controller stay in sync', async ({page}) => {
    await page.goto('/components/range-calendar')

    const presets = page.locator('[data-preview-name="range-calendar-presets"] [data-ui-component="calendar"]')
    await presets.locator('[data-calendar-preset="2026-08-17/2026-08-23"]').click()
    expect(await presets.evaluate(element => AppUI.get(element).getValue())).toEqual({start: '2026-08-17', end: '2026-08-23'})

    const form = page.locator('[data-preview-name="range-calendar-form"] [data-ui-component="calendar"]')
    await form.evaluate(element => {
        window.__rangeEvents = []
        element.addEventListener('app-ui:range-calendar:change', event => window.__rangeEvents.push(event.detail.value))
    })
    await form.locator('[data-value="2026-08-11"]').click()
    await form.locator('[data-value="2026-08-18"]').click()
    await expect(form.locator('[data-calendar-input]')).toHaveValue('{"start":"2026-08-11","end":"2026-08-18"}')
    expect(await form.evaluate(element => window.__rangeEvents.at(-1))).toEqual({start: '2026-08-11', end: '2026-08-18'})

    const controller = await form.evaluate(element => {
        const instance = AppUI.get(element)
        instance.setValue({start: '2026-08-20', end: '2026-08-25'}, true)
        return {
            value: instance.getValue(),
            methods: ['getValue', 'setValue', 'next', 'previous', 'focus', 'destroy'].every(method => typeof instance[method] === 'function'),
        }
    })
    expect(controller.value).toEqual({start: '2026-08-20', end: '2026-08-25'})
    expect(controller.methods).toBe(true)
})
