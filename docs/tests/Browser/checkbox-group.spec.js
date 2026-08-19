import {expect, test} from '@playwright/test'

test('Checkbox Group matches HeroUI structure, inheritance and layout', async ({page}) => {
    await page.goto('/components/checkbox-group')

    const basic = page.locator('[data-preview-name="checkbox-group-basic"] [data-ui-component="checkbox-group"]')
    await expect(basic).toHaveAttribute('data-slot', 'base')
    await expect(basic.locator(':scope > [data-slot="label"]')).toHaveText('방문할 도시를 선택하세요')
    await expect(basic.locator(':scope > [data-slot="wrapper"]')).toHaveCSS('flex-direction', 'column')
    await expect(basic.locator('input[name="cities[]"]')).toHaveCount(5)
    await expect(basic.locator('input[value="buenos-aires"]')).toBeChecked()
    await expect(basic.locator('input[value="london"]')).toBeChecked()

    const horizontal = page.locator('[data-preview-name="checkbox-group-horizontal"] [data-ui-component="checkbox-group"]')
    await expect(horizontal.locator(':scope > [data-slot="wrapper"]')).toHaveCSS('flex-direction', 'row')

    const disabled = page.locator('[data-preview-name="checkbox-group-disabled"] [data-ui-component="checkbox-group"]')
    await expect(disabled.locator('input')).toHaveCount(5)
    expect(await disabled.locator('input').evaluateAll(inputs => inputs.every(input => input.disabled))).toBe(true)

    const sizes = page.locator('[data-preview-name="checkbox-group-sizes"] [data-ui-component="checkbox-group"]')
    await expect(sizes.nth(0).locator('[data-slot="wrapper"] [data-slot="wrapper"]').first()).toHaveCSS('width', '16px')
    await expect(sizes.nth(1).locator('[data-slot="wrapper"] [data-slot="wrapper"]').first()).toHaveCSS('width', '20px')
    await expect(sizes.nth(2).locator('[data-slot="wrapper"] [data-slot="wrapper"]').first()).toHaveCSS('width', '24px')

    const custom = page.locator('[data-preview-name="checkbox-group-custom-styles"] .employee-group')
    await expect(custom.locator('.employee-choice')).toHaveCount(4)
    await expect(custom.locator('.employee-choice').first()).toHaveCSS('border-top-color', 'rgb(0, 111, 238)')
})

test('Checkbox Group value, events, form and validation stay synchronized', async ({page}) => {
    await page.goto('/components/checkbox-group')

    const controlled = page.locator('[data-preview-name="checkbox-group-controlled"] [data-ui-component="checkbox-group"]')
    const result = await controlled.evaluate(root => new Promise(resolve => {
        root.addEventListener('app-ui:checkbox-group:change', event => resolve(event.detail), {once: true})
        AppUI.get(root).setValue(['buenos-aires', 'sydney'])
    }))
    expect(result).toEqual({value: ['buenos-aires', 'sydney']})
    await expect(controlled.locator('input[value="buenos-aires"]')).toBeChecked()
    await expect(controlled.locator('input[value="sydney"]')).toBeChecked()
    await expect(controlled.locator('input[value="san-francisco"]')).not.toBeChecked()

    await controlled.locator('input[value="sydney"]').uncheck()
    expect(await controlled.evaluate(root => AppUI.get(root).getValue())).toEqual(['buenos-aires'])

    const form = page.locator('[data-preview-name="checkbox-group-form"] form')
    const fields = form.locator('input[name="notifications[]"]')
    await expect(fields).toHaveCount(3)
    await fields.nth(0).check()
    await fields.nth(2).check()
    expect(await form.evaluate(element => new FormData(element).getAll('notifications[]'))).toEqual(['email', 'push'])

    await fields.nth(0).uncheck()
    await fields.nth(2).uncheck()
    await form.locator('button[type="submit"]').click()
    await expect(form.locator('[data-ui-component="checkbox-group"]')).toHaveAttribute('data-invalid', 'true')
})
