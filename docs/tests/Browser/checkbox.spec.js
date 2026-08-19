import {expect, test} from '@playwright/test'

test('Checkbox examples match HeroUI v2 sizes, colors and radius', async ({page}) => {
    await page.goto('/components/checkbox')

    const basic = page.locator('[data-preview-name="checkbox-basic"] [data-ui-component="checkbox"]')
    await expect(basic).toHaveAttribute('data-slot', 'base')
    await expect(basic.locator('[data-slot="hidden-input"]')).toBeChecked()
    await expect(basic.locator('[data-slot="wrapper"]')).toHaveCSS('width', '20px')
    await expect(basic.locator('[data-slot="wrapper"]')).toHaveCSS('height', '20px')
    await expect(basic.locator('[data-slot="label"]')).toHaveCSS('font-size', '16px')

    const sizes = page.locator('[data-preview-name="checkbox-sizes"] [data-ui-component="checkbox"]')
    await expect(sizes.nth(0).locator('[data-slot="wrapper"]')).toHaveCSS('width', '16px')
    await expect(sizes.nth(1).locator('[data-slot="wrapper"]')).toHaveCSS('width', '20px')
    await expect(sizes.nth(2).locator('[data-slot="wrapper"]')).toHaveCSS('width', '24px')
    await expect(sizes.nth(0).locator('[data-slot="label"]')).toHaveCSS('font-size', '14px')
    await expect(sizes.nth(2).locator('[data-slot="label"]')).toHaveCSS('font-size', '18px')

    const colors = page.locator('[data-preview-name="checkbox-colors"] [data-ui-component="checkbox"]')
    const selectedColors = await colors.evaluateAll(roots => roots.map(root =>
        getComputedStyle(root.querySelector('[data-slot="wrapper"]'), '::after').backgroundColor,
    ))
    expect(selectedColors).toEqual([
        'rgb(212, 212, 216)',
        'rgb(0, 111, 238)',
        'rgb(120, 40, 200)',
        'rgb(23, 201, 100)',
        'rgb(245, 165, 36)',
        'rgb(243, 18, 96)',
    ])

    const radii = page.locator('[data-preview-name="checkbox-radius"] [data-slot="wrapper"]')
    expect(await radii.evaluateAll(elements => elements.map(element => getComputedStyle(element).borderRadius))).toEqual([
        '9999px',
        '8.4px',
        '7.2px',
        '6px',
        '0px',
    ])

    const mixed = page.locator('[data-preview-name="checkbox-indeterminate"] [data-ui-component="checkbox"]')
    await expect(mixed).toHaveAttribute('data-indeterminate', 'true')
    await expect(mixed).toHaveAttribute('data-selected', 'true')
    await expect(mixed.locator('.app-checkbox-check')).toHaveCSS('display', 'none')
    await expect(mixed.locator('.app-checkbox-indeterminate')).toHaveCSS('display', 'block')

    const lineThrough = page.locator('[data-preview-name="checkbox-line-through"] [data-slot="label"]')
    await expect.poll(() => lineThrough.evaluate(element => getComputedStyle(element, '::before').width)).not.toBe('0px')

    const customIcon = page.locator('[data-preview-name="checkbox-custom-icon"] .app-checkbox-icon > .app-icon')
    await expect(customIcon).toHaveCSS('width', '16px')
    await expect(customIcon).toHaveCSS('height', '12px')

    const customStyles = page.locator('[data-preview-name="checkbox-custom-styles"] .plan-checkbox')
    await expect(customStyles).toHaveCSS('width', '384px')
    await expect(customStyles).toHaveCSS('padding', '16px')
    await expect(customStyles).toHaveCSS('border-top-width', '2px')
    await expect(customStyles).toHaveCSS('border-top-color', 'rgb(0, 111, 238)')
    await customStyles.locator('input').uncheck()
    await expect(customStyles).toHaveAttribute('data-selected', 'false')
})

test('Checkbox native input, states and controller stay synchronized', async ({page}) => {
    await page.goto('/components/checkbox')

    const basic = page.locator('[data-preview-name="checkbox-basic"] [data-ui-component="checkbox"]')
    const input = basic.locator('[data-slot="hidden-input"]')
    await basic.hover()
    await expect(basic).toHaveAttribute('data-hover', 'true')
    await input.uncheck()
    await expect(basic).toHaveAttribute('data-selected', 'false')
    await input.check()
    await expect(basic).toHaveAttribute('data-selected', 'true')

    const eventResult = await basic.evaluate(root => new Promise(resolve => {
        const counts = {input: 0, change: 0}
        const field = root.querySelector('input')
        field.addEventListener('input', () => counts.input++)
        field.addEventListener('change', () => counts.change++)
        root.addEventListener('app-ui:checkbox:change', event => resolve({counts, detail: event.detail}), {once: true})
        AppUI.get(root).setValue(false)
    }))
    expect(eventResult.counts).toEqual({input: 1, change: 1})
    expect(eventResult.detail).toEqual({selected: false, indeterminate: false, value: 'yes'})

    const mixed = page.locator('[data-preview-name="checkbox-indeterminate"] [data-ui-component="checkbox"]')
    await mixed.locator('input').click()
    await expect(mixed).toHaveAttribute('data-indeterminate', 'true')
    await expect(mixed).toHaveAttribute('data-selected', 'true')
    await expect(mixed.locator('.app-checkbox-check')).toHaveCSS('display', 'none')
    await expect(mixed.locator('.app-checkbox-indeterminate')).toHaveCSS('display', 'block')

    await mixed.evaluate(root => AppUI.get(root).setIndeterminate(false))
    await expect(mixed).toHaveAttribute('data-indeterminate', 'false')
    await expect(mixed.locator('.app-checkbox-check')).toBeVisible()
    await expect(mixed.locator('.app-checkbox-indeterminate')).toHaveCSS('display', 'none')

    const readonly = page.locator('[data-preview-name="checkbox-form"] [data-readonly="true"]')
    const readonlyInput = readonly.locator('input')
    await expect(readonlyInput).toBeChecked()
    await readonlyInput.click({force: true})
    await expect(readonlyInput).toBeChecked()
    await expect(readonly).toHaveAttribute('data-selected', 'true')

    const formCheckbox = page.locator('[data-preview-name="checkbox-form"] [name="terms"]')
    await formCheckbox.check()
    expect(await formCheckbox.evaluate(field => new FormData(field.form).get(field.name))).toBe('accepted')

    const notice = page.locator('[data-preview-name="checkbox-form"] [name="notice"]')
    await notice.uncheck()
    await expect(notice).not.toBeChecked()
    await notice.check()
    await expect(notice).toBeChecked()
})
