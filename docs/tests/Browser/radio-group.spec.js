import {expect, test} from '@playwright/test'

test('Radio Group matches HeroUI structure, states and styles', async ({page}) => {
    await page.goto('/components/radio-group')

    const basic = page.locator('[data-preview-name="radio-group-basic"] [data-ui-component="radio-group"]')
    await expect(basic).toHaveAttribute('data-slot', 'base')
    await expect(basic.locator('input[type="radio"][name="city"]')).toHaveCount(5)
    await expect(basic.locator('[data-ui-component="radio"]').first()).toHaveAttribute('data-slot', 'base')
    await expect(basic.locator('[data-slot="control"]')).toHaveCount(5)

    const defaults = page.locator('[data-preview-name="radio-group-default-value"] [data-ui-component="radio-group"]')
    await expect(defaults.locator('input[value="london"]')).toBeChecked()

    const descriptions = page.locator('[data-preview-name="radio-group-descriptions"] [data-slot="description"]')
    await expect(descriptions).toHaveCount(4)

    const horizontal = page.locator('[data-preview-name="radio-group-horizontal"] [data-ui-component="radio-group"] > [data-slot="wrapper"]')
    await expect(horizontal).toHaveCSS('flex-direction', 'row')

    const disabled = page.locator('[data-preview-name="radio-group-disabled"] input[type="radio"]')
    expect(await disabled.evaluateAll(inputs => inputs.every(input => input.disabled))).toBe(true)

    const sizes = page.locator('[data-preview-name="radio-group-sizes"] [data-ui-component="radio-group"]')
    await expect(sizes.nth(0).locator('[data-slot="wrapper"] [data-slot="wrapper"]').first()).toHaveCSS('width', '16px')
    await expect(sizes.nth(1).locator('[data-slot="wrapper"] [data-slot="wrapper"]').first()).toHaveCSS('width', '20px')
    await expect(sizes.nth(2).locator('[data-slot="wrapper"] [data-slot="wrapper"]').first()).toHaveCSS('width', '24px')

    const custom = page.locator('[data-preview-name="radio-group-custom-styles"] .plan-radio-group')
    await expect(custom.locator('.plan-radio')).toHaveCount(3)
    await expect(custom.locator('.plan-radio[data-selected="true"]')).toHaveCount(1)
    await expect(custom.locator('.plan-radio[data-selected="true"]')).toHaveCSS('border-top-color', 'rgb(0, 111, 238)')
})

test('Radio Group controller, keyboard, form and validation stay synchronized', async ({page}) => {
    await page.goto('/components/radio-group')

    const controlled = page.locator('[data-preview-name="radio-group-controlled"] [data-ui-component="radio-group"]')
    const detail = await controlled.evaluate(root => new Promise(resolve => {
        root.addEventListener('app-ui:radio-group:change', event => resolve(event.detail), {once: true})
        AppUI.get(root).setValue('enterprise')
    }))
    expect(detail).toEqual({value: 'enterprise'})
    await expect(controlled.locator('input[value="enterprise"]')).toBeChecked()
    await expect(controlled).toHaveAttribute('data-value', 'enterprise')

    const horizontal = page.locator('[data-preview-name="radio-group-horizontal"] [data-ui-component="radio-group"]')
    const first = horizontal.locator('input').first()
    await first.focus()
    await page.keyboard.press('ArrowRight')
    await expect(horizontal.locator('input').nth(1)).toBeChecked()
    await expect(horizontal).toHaveAttribute('data-value', 'sms')

    const form = page.locator('[data-preview-name="radio-group-form"] form')
    const group = form.locator('[data-ui-component="radio-group"]')
    await form.locator('button[type="submit"]').click()
    await expect(group).toHaveAttribute('data-invalid', 'true')

    await form.locator('input[value="express"]').check()
    await expect(group).toHaveAttribute('data-invalid', 'false')
    expect(await form.evaluate(element => new FormData(element).get('delivery'))).toBe('express')
})
