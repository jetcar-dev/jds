import {expect, test} from '@playwright/test'

test('Time Input matches the HeroUI structure and default geometry', async ({page}) => {
    await page.goto('/components/time-input')

    const timeInput = page.locator('[data-preview-name="time-input-basic"] [data-ui-component="time-input"]').first()
    const wrapper = timeInput.locator('[data-slot="input-wrapper"]')

    await expect(timeInput).toHaveCSS('width', '320px')
    await expect(wrapper).toHaveCSS('height', '56px')
    await expect(wrapper).toHaveCSS('padding', '8px 12px')
    await expect(wrapper).toHaveCSS('border-radius', '12px')
    await expect(timeInput).toHaveCSS('font-family', /Pretendard/)
    await expect(timeInput.locator('[data-slot="label"]')).toHaveCSS('font-size', '12px')
    await expect(timeInput.locator('[data-slot="input"]')).toHaveCSS('font-size', '14px')
    await expect(timeInput.locator('[data-slot="input"]')).toHaveCSS('font-family', /Pretendard/)
    await expect(timeInput.locator('[data-type="hour"]')).toHaveText('––')
    await expect(timeInput.locator('[data-type="minute"]')).toHaveText('––')
    await expect(timeInput.locator('[data-time-value]')).toHaveValue('')
})

test('Time Input variants, sizes and label placements match HeroUI', async ({page}) => {
    await page.goto('/components/time-input')

    const variants = page.locator('[data-preview-name="time-input-variants"] [data-ui-component="time-input"]')
    await expect(variants).toHaveCount(4)
    await expect(variants.nth(0).locator('[data-slot="input-wrapper"]')).toHaveCSS('border-top-width', '0px')
    await expect(variants.nth(1).locator('[data-slot="input-wrapper"]')).toHaveCSS('border-top-width', '2px')
    await expect(variants.nth(2).locator('[data-slot="input-wrapper"]')).toHaveCSS('border-top-width', '2px')
    await expect(variants.nth(3).locator('[data-slot="input-wrapper"]')).toHaveCSS('border-radius', '0px')

    const sizes = page.locator('[data-preview-name="time-input-sizes"] [data-ui-component="time-input"]')
    await expect(sizes.nth(0).locator('[data-slot="input-wrapper"]')).toHaveCSS('height', '48px')
    await expect(sizes.nth(1).locator('[data-slot="input-wrapper"]')).toHaveCSS('height', '56px')
    await expect(sizes.nth(2).locator('[data-slot="input-wrapper"]')).toHaveCSS('height', '64px')
    await expect(sizes.nth(0).locator('[data-slot="label"]')).toHaveCSS('font-size', '12px')
    await expect(sizes.nth(1).locator('[data-slot="label"]')).toHaveCSS('font-size', '12px')
    await expect(sizes.nth(2).locator('[data-slot="label"]')).toHaveCSS('font-size', '16px')
    await expect(sizes.nth(0).locator('[data-slot="input"]')).toHaveCSS('font-size', '14px')
    await expect(sizes.nth(1).locator('[data-slot="input"]')).toHaveCSS('font-size', '14px')
    await expect(sizes.nth(2).locator('[data-slot="input"]')).toHaveCSS('font-size', '16px')

    const placements = page.locator('[data-preview-name="time-input-label-placements"] [data-ui-component="time-input"]')
    await expect(placements).toHaveCount(4)
    await expect(placements.nth(0).locator('[data-slot="input-wrapper"]')).toHaveCSS('height', '56px')
    await expect(placements.nth(1).locator('[data-slot="input-wrapper"]')).toHaveCSS('height', '40px')
    await expect(placements.nth(2)).toHaveCSS('grid-template-columns', /.+ .+/)

    const withoutLabel = page.locator('[data-preview-name="time-input-without-label"] [data-ui-component="time-input"]')
    await expect(withoutLabel.locator('[data-slot="input-wrapper"]')).toHaveCSS('height', '40px')
})

test('Time Input keyboard editing, controller and events stay synchronized', async ({page}) => {
    await page.goto('/components/time-input')

    const timeInput = page.locator('[data-preview-name="time-input-hour-cycle"] [data-ui-component="time-input"]').nth(1)
    await timeInput.evaluate(element => {
        window.__timeInputEvents = []
        element.querySelector('[data-time-value]').addEventListener('change', event => window.__timeInputEvents.push(['native', event.target.value]))
        element.addEventListener('app-ui:time-input:change', event => window.__timeInputEvents.push(['custom', event.detail.value]))
    })

    const hour = timeInput.locator('[data-type="hour"]')
    const minute = timeInput.locator('[data-type="minute"]')
    await hour.click()
    await hour.press('1')
    await hour.press('8')
    await minute.press('3')
    await minute.press('0')

    await expect(timeInput.locator('[data-time-value]')).toHaveValue('18:30')
    expect(await timeInput.evaluate(element => AppUI.get(element).getValue())).toBe('18:30')
    expect(await timeInput.evaluate(() => window.__timeInputEvents.some(event => event[0] === 'custom' && event[1] === '18:30'))).toBe(true)

    await timeInput.evaluate(element => AppUI.get(element).setValue('09:15', true))
    await expect(hour).toHaveText('09')
    await expect(minute).toHaveText('15')
    await expect(timeInput.locator('[data-time-value]')).toHaveValue('09:15')

    await hour.press('ArrowUp')
    await expect(timeInput.locator('[data-time-value]')).toHaveValue('10:15')
    await hour.press('Delete')
    await expect(timeInput.locator('[data-time-value]')).toHaveValue('')
})

test('Time Input granularity, hour cycle, time zone and content work', async ({page}) => {
    await page.goto('/components/time-input')

    const granular = page.locator('[data-preview-name="time-input-granularity"] [data-ui-component="time-input"]')
    await expect(granular.nth(0).locator('[data-type="minute"]')).toHaveCount(0)
    await expect(granular.nth(1).locator('[data-type="minute"]')).toHaveText('45')
    await expect(granular.nth(2).locator('[data-type="second"]')).toHaveText('22')

    const hourCycles = page.locator('[data-preview-name="time-input-hour-cycle"] [data-ui-component="time-input"]')
    await expect(hourCycles.nth(0).locator('[data-type="dayPeriod"]')).toHaveText('PM')
    await expect(hourCycles.nth(0).locator('[data-type="hour"]')).toHaveText('03')
    await expect(hourCycles.nth(1).locator('[data-type="dayPeriod"]')).toHaveCount(0)
    await expect(hourCycles.nth(1).locator('[data-type="hour"]')).toHaveText('15')

    const zones = page.locator('[data-preview-name="time-input-time-zone"] [data-ui-component="time-input"]')
    await expect(zones.nth(0).locator('[data-type="timeZoneName"]')).toBeVisible()
    await expect(zones.nth(1).locator('[data-type="timeZoneName"]')).toHaveCount(0)

    const content = page.locator('[data-preview-name="time-input-content"] [data-ui-component="time-input"]')
    await expect(content.nth(0).locator('[data-slot="start-content"] .app-icon')).toHaveCSS('width', '24px')
    await expect(content.nth(1).locator('[data-slot="end-content"]')).toHaveText('KST')
})

test('Time Input validation, form semantics and readonly states work', async ({page}) => {
    await page.goto('/components/time-input')

    const bounds = page.locator('[data-preview-name="time-input-bounds"] [data-ui-component="time-input"]')
    await expect(bounds).toHaveAttribute('data-invalid', 'true')
    await expect(bounds.locator('[data-slot="error-message"]')).toBeVisible()
    await bounds.evaluate(element => AppUI.get(element).setValue('10:30', true))
    await expect(bounds).toHaveAttribute('data-invalid', 'false')
    await expect(bounds.locator('[data-slot="error-message"]')).toBeHidden()

    const required = page.locator('[data-preview-name="time-input-form"] [data-ui-component="time-input"]')
    const formInput = required.locator('[data-time-value]')
    await expect(formInput).toHaveAttribute('type', 'text')
    await expect(formInput).toHaveAttribute('required', '')
    expect(await formInput.evaluate(element => element.checkValidity())).toBe(false)

    const disabled = page.locator('[data-preview-name="time-input-disabled"] [data-ui-component="time-input"]')
    const readonly = page.locator('[data-preview-name="time-input-readonly"] [data-ui-component="time-input"]')
    await expect(disabled.locator('[role="spinbutton"]').first()).toHaveAttribute('aria-disabled', 'true')
    await expect(disabled.locator('[role="spinbutton"]').first()).not.toHaveAttribute('tabindex', '0')
    await expect(readonly.locator('[role="spinbutton"]').first()).toHaveAttribute('tabindex', '0')
    await expect(readonly.locator('[role="spinbutton"]').first()).toHaveAttribute('aria-readonly', 'true')
})

test('Time Input colors, dark mode and focus state follow the shared theme', async ({page}) => {
    await page.goto('/components/time-input')

    const colors = page.locator('[data-preview-name="time-input-colors"] [data-ui-component="time-input"]')
    const primary = colors.nth(1)
    await expect(primary.locator('[data-slot="input-wrapper"]')).toHaveCSS('background-color', 'rgb(204, 227, 253)')
    await primary.hover()
    await expect(primary.locator('[data-slot="input-wrapper"]')).toHaveCSS('background-color', 'rgb(230, 241, 254)')
    await primary.locator('[data-type="hour"]').click()
    await expect(primary).toHaveAttribute('data-focus', 'true')

    await page.locator('html').evaluate(element => { element.dataset.theme = 'dark' })
    const invalid = page.locator('[data-preview-name="time-input-invalid"] [data-ui-component="time-input"]')
    await expect(invalid.locator('[data-slot="input-wrapper"]')).toHaveCSS('background-color', 'rgb(49, 4, 19)')
    await expect(primary.locator('[data-slot="input-wrapper"]')).toHaveCSS('background-color', 'rgb(0, 23, 49)')
})
