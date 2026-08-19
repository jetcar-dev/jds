import {expect, test} from '@playwright/test'

test('Autocomplete filters, keeps input focus and submits the selected key', async ({page}) => {
    await page.goto('/components/autocomplete')

    const preview = page.locator('[data-preview-name="autocomplete-basic"]')
    const autocomplete = preview.locator('[data-ui-component="autocomplete"]')
    const input = autocomplete.getByRole('combobox')

    await input.click()
    await expect(autocomplete).toHaveAttribute('data-open', 'true')
    await expect(input).toHaveAttribute('aria-expanded', 'true')

    const popover = page.locator('[data-autocomplete-popover]:visible')
    await expect(popover).toBeVisible()
    await expect.poll(async () => Math.round((await popover.boundingBox()).width)).toBe(Math.round((await autocomplete.locator('[data-slot="input-wrapper"]').boundingBox()).width))

    await input.fill('강')
    await expect(popover.getByRole('option', {name: /강아지/})).toBeVisible()
    await expect(popover.getByRole('option', {name: /고양이/})).toBeHidden()

    await input.press('ArrowDown')
    await expect(input).toBeFocused()
    await expect(input).toHaveAttribute('aria-activedescendant', /autocomplete-option-/)
    await input.press('Enter')

    await expect(input).toHaveValue('강아지')
    await expect(autocomplete.locator('[data-autocomplete-value]')).toHaveValue('dog')
    await expect(autocomplete).toHaveAttribute('data-open', 'false')
})

test('Autocomplete selector, full wrapper hit area and label placements work', async ({page}) => {
    await page.goto('/components/autocomplete')

    const preview = page.locator('[data-preview-name="autocomplete-basic"]')
    const autocomplete = preview.locator('[data-ui-component="autocomplete"]')
    const input = autocomplete.getByRole('combobox')
    const wrapper = autocomplete.locator('[data-slot="input-wrapper"]')
    const selector = autocomplete.locator('[data-autocomplete-selector]')

    await selector.click()
    await expect(autocomplete).toHaveAttribute('data-open', 'true')
    await expect(page.locator('[data-autocomplete-popover]:visible')).toBeVisible()
    await expect(selector).toHaveAttribute('data-open', 'true')

    await selector.click()
    await expect(autocomplete).toHaveAttribute('data-open', 'false')

    await wrapper.click({position: {x: 80, y: 8}})
    await expect(input).toBeFocused()
    await expect(autocomplete).not.toHaveAttribute('data-focus-visible', 'true')

    const outsideLeft = page.locator('[data-preview-name="autocomplete-label-placement"] [data-label-placement="outside-left"]').first()
    await expect(outsideLeft).toHaveCSS('display', 'grid')
    const [labelBox, fieldBox] = await Promise.all([
        outsideLeft.locator(':scope > [data-slot="label"]').boundingBox(),
        outsideLeft.locator(':scope > [data-slot="mainWrapper"]').boundingBox(),
    ])
    expect(Math.abs(labelBox.y + labelBox.height / 2 - (fieldBox.y + 20))).toBeLessThan(2)
})

test('Autocomplete clear, custom value, invalid and sections work', async ({page}) => {
    await page.goto('/components/autocomplete')

    const controlled = page.locator('#controlled-autocomplete')
    await expect(controlled.getByRole('combobox')).toHaveValue('고양이')
    await controlled.getByRole('combobox').focus()
    await controlled.locator('[data-autocomplete-clear]').click()
    await expect(controlled.getByRole('combobox')).toHaveValue('')
    await expect(controlled.locator('[data-autocomplete-value]')).toHaveValue('')

    const custom = page.locator('[data-preview-name="autocomplete-custom-value"] [data-ui-component="autocomplete"]')
    const customInput = custom.getByRole('combobox')
    await customInput.fill('수달')
    await page.getByRole('heading', {name: '사용자 입력값 허용'}).click()
    await expect(custom.locator('[data-autocomplete-value]')).toHaveValue('수달')

    const invalid = page.locator('[data-preview-name="autocomplete-help"] [data-ui-component="autocomplete"][data-invalid="true"]')
    await expect(invalid.getByRole('combobox')).toHaveAttribute('aria-invalid', 'true')
    await expect(invalid.locator('[data-slot="errorMessage"]')).toContainText('고양이를 선택해 주세요.')

    const sections = page.locator('[data-preview-name="autocomplete-sections"] [data-ui-component="autocomplete"]')
    await sections.getByRole('combobox').click()
    const visiblePopover = page.locator('[data-autocomplete-popover]:visible')
    await expect(visiblePopover.getByRole('group')).toHaveCount(2)
})

test('Autocomplete colors, variants, invalid state and content slots match HeroUI states', async ({page}) => {
    await page.goto('/components/autocomplete')

    const colors = page.locator('[data-preview-name="autocomplete-colors"] [data-ui-component="autocomplete"]')
    const primary = colors.nth(1)
    await expect(primary.locator('[data-slot="input-wrapper"]')).toHaveCSS('background-color', 'rgb(204, 227, 253)')
    await expect(primary.getByRole('combobox')).toHaveCSS('color', 'rgb(0, 111, 238)')
    await expect(primary.locator('[data-slot="selectorButton"]')).toHaveCSS('color', 'rgb(0, 111, 238)')
    await expect(primary.locator('[data-slot="clearButton"]')).toHaveCSS('color', 'rgb(113, 113, 122)')

    await primary.getByRole('combobox').click()
    const selected = page.locator('[data-autocomplete-popover]:visible [role="option"][data-selected="true"]')
    await expect(selected).toHaveCSS('background-color', 'rgb(212, 212, 216)')
    await expect(selected).toHaveCSS('color', 'rgb(0, 0, 0)')
    const check = selected.locator('.app-listbox-check')
    await expect(check).toHaveCSS('width', '12px')
    await expect(check).toHaveCSS('height', '12px')
    await expect(check.locator('polyline')).toHaveCSS('stroke-dashoffset', '44px')
    await primary.getByRole('combobox').press('Escape')

    const variants = page.locator('[data-preview-name="autocomplete-variants"] [data-ui-component="autocomplete"]')
    const flat = variants.nth(0)
    await flat.locator('[data-slot="input-wrapper"]').hover()
    await expect(flat.locator('[data-slot="input-wrapper"]')).toHaveCSS('background-color', 'rgb(228, 228, 231)')

    const bordered = variants.nth(1)
    await bordered.getByRole('combobox').click()
    await expect(bordered.locator('[data-slot="input-wrapper"]')).toHaveCSS('border-color', 'rgb(0, 0, 0)')
    await bordered.getByRole('combobox').press('Escape')

    const underlined = variants.nth(2)
    await underlined.getByRole('combobox').click()
    await expect.poll(async () => underlined.locator('[data-slot="input-wrapper"]').evaluate((wrapper) => ({
        wrapper: Math.round(wrapper.getBoundingClientRect().width),
        indicator: Math.round(Number.parseFloat(getComputedStyle(wrapper, '::after').width)),
    }))).toEqual({wrapper: 320, indicator: 320})
    await underlined.getByRole('combobox').press('Escape')

    const invalid = page.locator('[data-preview-name="autocomplete-help"] [data-invalid="true"]')
    await expect(invalid.locator('[data-slot="input-wrapper"]')).toHaveCSS('border-color', 'rgb(243, 18, 96)')
    await expect(invalid.locator('[data-slot="input-wrapper"]')).toHaveCSS('border-width', '2px')
    await expect(invalid.getByRole('combobox')).toHaveCSS('color', 'rgb(243, 18, 96)')
    await expect(invalid.locator('[data-slot="label"]')).toHaveCSS('color', 'rgb(243, 18, 96)')
    await expect(invalid.locator('[data-slot="clearButton"]')).toHaveCSS('color', 'rgb(243, 18, 96)')
    await expect(invalid.locator('[data-slot="selectorButton"]')).toHaveCSS('color', 'rgb(243, 18, 96)')
    await expect(invalid.locator('[data-slot="errorMessage"]')).toHaveCSS('line-height', '16px')

    const content = page.locator('[data-preview-name="autocomplete-content"] [data-ui-component="autocomplete"]')
    const slots = await content.evaluate((root) => {
        const box = (selector) => {
            const rect = root.querySelector(selector).getBoundingClientRect()
            return {top: rect.top, bottom: rect.bottom, height: rect.height}
        }
        return {
            wrapper: box('[data-slot="input-wrapper"]'),
            start: box('[data-slot="start-content"]'),
            input: box('[data-slot="input"]'),
            end: box('[data-slot="end-content"]'),
        }
    })
    expect(slots.wrapper.height).toBe(56)
    expect(Math.abs(slots.start.top - slots.input.top)).toBeLessThan(1)
    expect(Math.abs(slots.end.bottom - slots.input.bottom)).toBeLessThan(1)

    await content.getByRole('combobox').click()
    await content.locator('[data-slot="clearButton"]').click()
    await page.locator('[data-preview-name="autocomplete-basic"] [data-slot="input"]').click()
    const emptyContentSlots = await content.evaluate((root) => {
        const label = root.querySelector('[data-slot="label"]').getBoundingClientRect()
        const start = root.querySelector('[data-slot="start-content"]').getBoundingClientRect()
        return {filled: root.dataset.filled, labelBottom: label.bottom, startTop: start.top}
    })
    expect(emptyContentSlots.filled).toBe('true')
    expect(emptyContentSlots.startTop - emptyContentSlots.labelBottom).toBeGreaterThanOrEqual(2)
})
