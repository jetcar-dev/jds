import {expect, test} from '@playwright/test'

test('Date Input matches the HeroUI structure and default geometry', async ({page}) => {
    await page.goto('/components/date-input')

    const dateInput = page.locator('[data-preview-name="date-input-basic"] [data-ui-component="date-input"]').first()
    const wrapper = dateInput.locator('[data-slot="input-wrapper"]')
    const field = dateInput.locator('[data-slot="input-field"]')

    await expect(wrapper).toHaveCSS('height', '56px')
    await expect(wrapper).toHaveCSS('padding', '8px 12px')
    await expect(wrapper).toHaveCSS('border-radius', '12px')
    await expect(wrapper).toHaveCSS('width', '320px')
    await expect(field).toHaveCSS('font-size', '14px')
    await expect(field).toHaveCSS('column-gap', '2px')
    await expect(dateInput.locator('[data-slot="label"]')).toHaveCSS('font-size', '12px')
    await expect(dateInput.locator('[data-slot="segment"]')).toHaveCount(6)
    expect(await dateInput.locator('[data-slot="segment"]').evaluateAll(elements => elements.map(element => element.dataset.type))).toEqual([
        'year', 'literal', 'month', 'literal', 'day', 'literal',
    ])
})

test('Date Input variants, states and label placements are stable', async ({page}) => {
    await page.goto('/components/date-input')

    const variants = page.locator('[data-preview-name="date-input-variants"] [data-ui-component="date-input"]')
    await expect(variants).toHaveCount(4)
    await expect(variants.nth(0).locator('[data-slot="input-wrapper"]')).toHaveCSS('border-top-width', '0px')
    await expect(variants.nth(1).locator('[data-slot="input-wrapper"]')).toHaveCSS('border-top-width', '2px')
    await expect(variants.nth(1).locator('[data-slot="inner-wrapper"]')).toHaveCSS('height', '20px')
    await expect(variants.nth(2).locator('[data-slot="input-wrapper"]')).toHaveCSS('border-radius', '0px')
    await expect(variants.nth(3).locator('[data-slot="input-wrapper"]')).toHaveCSS('border-top-width', '2px')
    await expect(variants.nth(3).locator('[data-slot="inner-wrapper"]')).toHaveCSS('height', '20px')

    const disabled = page.locator('[data-preview-name="date-input-disabled"] [data-ui-component="date-input"]')
    const readonly = page.locator('[data-preview-name="date-input-readonly"] [data-ui-component="date-input"]')
    await expect(disabled).toHaveAttribute('data-disabled', 'true')
    await expect(disabled.locator('[data-editable="false"]')).toHaveCount(3)
    await expect(readonly).toHaveAttribute('data-readonly', 'true')
    await expect(readonly.locator('[data-editable="false"]')).toHaveCount(3)
    await expect(readonly.locator('[role="spinbutton"]').first()).toHaveAttribute('tabindex', '0')
    await expect(readonly.locator('[role="spinbutton"]').first()).toHaveAttribute('aria-readonly', 'true')

    const invalid = page.locator('[data-preview-name="date-input-invalid"] [data-ui-component="date-input"]')
    await expect(invalid).toHaveAttribute('data-invalid', 'true')
    await expect(invalid.locator('[data-slot="error-message"]')).toBeVisible()
    await expect(invalid.locator('[data-slot="description"]')).toHaveCount(0)

    const placements = page.locator('[data-preview-name="date-input-label-placements"] [data-ui-component="date-input"]')
    await expect(placements).toHaveCount(4)
    await expect(placements.nth(0).locator('[data-slot="input-wrapper"]')).toHaveCSS('height', '56px')
    await expect(placements.nth(1).locator('[data-slot="input-wrapper"]')).toHaveCSS('height', '40px')
    await expect(placements.nth(1).locator('[data-slot="label"]')).toHaveCSS('font-size', '14px')
    await expect(placements.nth(2)).toHaveCSS('grid-template-columns', /.+ .+/)
})

test('Date Input keyboard editing, form value and controller work together', async ({page}) => {
    await page.goto('/components/date-input')

    const formInput = page.locator('[data-preview-name="date-input-form"] [data-ui-component="date-input"]')
    await formInput.evaluate(element => {
        window.__dateInputEvents = []
        element.querySelector('[data-date-value]').addEventListener('change', event => window.__dateInputEvents.push(['native', event.target.value]))
        element.addEventListener('app-ui:date-input:change', event => window.__dateInputEvents.push(['custom', event.detail.value]))
    })

    const year = formInput.locator('[data-type="year"]')
    const month = formInput.locator('[data-type="month"]')
    const day = formInput.locator('[data-type="day"]')
    await year.click()
    await year.press('2')
    await year.press('0')
    await year.press('2')
    await year.press('6')
    await month.press('0')
    await month.press('8')
    await day.press('0')
    await day.press('5')

    await expect(formInput.locator('[data-date-value]')).toHaveValue('2026-08-05')
    await expect(formInput).toHaveAttribute('data-invalid', 'false')
    expect(await formInput.evaluate(element => AppUI.get(element).getValue())).toBe('2026-08-05')
    expect(await formInput.evaluate(() => window.__dateInputEvents.some(event => event[0] === 'custom' && event[1] === '2026-08-05'))).toBe(true)

    await formInput.evaluate(element => AppUI.get(element).setValue('2026-08-12', true))
    await expect(formInput.locator('[data-date-value]')).toHaveValue('2026-08-12')
    await expect(formInput.locator('[data-type="day"]')).toHaveText('12')
})

test('Date Input content, clearing, bounds and time granularity work', async ({page}) => {
    await page.goto('/components/date-input')

    const content = page.locator('[data-preview-name="date-input-content"] [data-ui-component="date-input"]')
    await expect(content.nth(0).locator('[data-slot="start-content"]')).toBeVisible()
    await expect(content.nth(0).locator('[data-slot="start-content"] .app-icon')).toHaveCSS('width', '24px')
    await expect(content.nth(1).locator('[data-slot="end-content"] .app-icon')).toHaveCSS('width', '24px')

    const clearable = page.locator('[data-preview-name="date-input-clearable"] [data-ui-component="date-input"]')
    await clearable.locator('[data-slot="clear-button"]').click()
    await expect(clearable.locator('[data-date-value]')).toHaveValue('')
    await expect(clearable.locator('[data-slot="clear-button"]')).not.toBeVisible()

    const bounds = page.locator('[data-preview-name="date-input-bounds"] [data-ui-component="date-input"]')
    await bounds.evaluate(element => AppUI.get(element).setValue('2026-08-25', true))
    await expect(bounds).toHaveAttribute('data-invalid', 'true')
    await expect(bounds.locator('[data-slot="error-message"]')).toBeVisible()

    const granular = page.locator('[data-preview-name="date-input-granularity"] [data-ui-component="date-input"]')
    await expect(granular.nth(0).locator('[data-type="hour"]')).toHaveText('15')
    await expect(granular.nth(0).locator('[data-type="minute"]')).toHaveText('45')
    await expect(granular.nth(1).locator('[data-type="second"]')).toHaveText('22')
    await expect(granular.nth(1).locator('[data-type="dayPeriod"]')).toHaveText('오전')
    await expect(granular.nth(1).locator('[data-type="timeZoneName"]')).toBeVisible()
    await expect(granular.nth(1).locator('[data-date-value]')).toHaveValue(/\+09:00\[Asia\/Seoul]$/)

    const focusedSegment = granular.nth(0).locator('[data-type="day"]')
    const wrapper = granular.nth(0).locator('[data-slot="input-wrapper"]')
    const before = await wrapper.evaluate(element => getComputedStyle(element).boxShadow)
    await focusedSegment.click()
    await expect(granular.nth(0)).toHaveAttribute('data-focus', 'true')
    await expect(wrapper).toHaveCSS('box-shadow', before)
    await expect(granular.nth(0)).toHaveCSS('outline-style', 'none')
})

test('Date Input dark theme, hover and form semantics match HeroUI', async ({page}) => {
    await page.goto('/components/date-input')
    await page.locator('html').evaluate(element => { element.dataset.theme = 'dark' })

    const invalid = page.locator('[data-preview-name="date-input-invalid"] [data-ui-component="date-input"]')
    const primary = page.locator('[data-preview-name="date-input-colors"] [data-color="primary"]')
    await expect(invalid.locator('[data-slot="input-wrapper"]')).toHaveCSS('background-color', 'rgb(49, 4, 19)')
    await expect(primary.locator('[data-slot="input-wrapper"]')).toHaveCSS('background-color', 'rgb(0, 46, 98)')
    await expect(primary.locator('[data-slot="label"]')).toHaveCSS('color', 'rgb(51, 142, 247)')

    const variants = page.locator('[data-preview-name="date-input-variants"] [data-ui-component="date-input"]')
    await variants.nth(0).hover()
    await expect(variants.nth(0).locator('[data-slot="input-wrapper"]')).toHaveCSS('background-color', 'rgb(63, 63, 70)')
    await variants.nth(1).hover()
    await expect(variants.nth(1).locator('[data-slot="input-wrapper"]')).toHaveCSS('border-color', 'rgb(113, 113, 122)')

    const required = page.locator('[data-preview-name="date-input-form"] [data-ui-component="date-input"]')
    const formInput = required.locator('[data-date-value]')
    await expect(formInput).toHaveAttribute('type', 'text')
    await expect(formInput).toHaveAttribute('required', '')
    expect(await formInput.evaluate(element => element.checkValidity())).toBe(false)
})

test('Date Input placeholder and focus events follow the public API', async ({page}) => {
    await page.goto('/components/date-input')
    const dateInput = page.locator('[data-preview-name="date-input-placeholder"] [data-ui-component="date-input"]')
    const hidden = dateInput.locator('[data-date-value]')
    const year = dateInput.locator('[data-type="year"]')

    await expect(hidden).toHaveValue('')
    await expect(year).toHaveText('2026')
    await expect(year).toHaveAttribute('data-placeholder', 'true')

    await dateInput.evaluate(element => {
        window.__dateInputFocusEvents = []
        for (const name of ['focus', 'blur', 'focus-change', 'key-down', 'key-up']) {
            element.addEventListener(`app-ui:date-input:${name}`, event => window.__dateInputFocusEvents.push([name, event.detail]))
        }
    })
    await year.click()
    await year.press('2')
    await page.locator('[data-docs-copy]').first().click()
    await expect.poll(() => page.evaluate(() => window.__dateInputFocusEvents.map(event => event[0]))).toEqual(expect.arrayContaining(['focus', 'focus-change', 'key-down', 'key-up', 'blur']))
})
