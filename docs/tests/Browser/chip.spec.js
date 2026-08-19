import {expect, test} from '@playwright/test'

test('Chip matches HeroUI sizes, variants, colors and slots', async ({page}) => {
    await page.goto('/components/chip')

    const basic = page.locator('[data-preview-name="chip-basic"] [data-ui-component="chip"]')
    await expect(basic).toHaveAttribute('data-size', 'md')
    await expect(basic).toHaveAttribute('data-variant', 'solid')
    await expect(basic).toHaveCSS('height', '28px')
    await expect(basic).toHaveCSS('font-size', '14px')
    await expect(basic).toHaveCSS('border-radius', '9999px')

    const sizes = page.locator('[data-preview-name="chip-sizes"] [data-ui-component="chip"]')
    await expect(sizes).toHaveCount(3)
    await expect(sizes.nth(0)).toHaveCSS('height', '24px')
    await expect(sizes.nth(1)).toHaveCSS('height', '28px')
    await expect(sizes.nth(2)).toHaveCSS('height', '32px')

    const variants = page.locator('[data-preview-name="chip-variants"] [data-ui-component="chip"]')
    await expect(variants).toHaveCount(7)
    await expect(variants.nth(1)).toHaveCSS('border-top-width', '2px')
    await expect(variants.nth(4)).toHaveCSS('border-top-width', '2px')
    await expect(variants.nth(6).locator('[data-slot="dot"]')).toBeVisible()

    const content = page.locator('[data-preview-name="chip-content"] [data-ui-component="chip"]')
    await expect(content.nth(0).locator('[data-slot="start-content"]')).toBeVisible()
    await expect(content.nth(1).locator('[data-slot="end-content"]')).toBeVisible()
    await expect(page.locator('[data-preview-name="chip-avatar"] [data-slot="avatar"]')).toHaveCount(2)
})

test('Closable Chip emits its value and exposes the controller', async ({page}) => {
    await page.goto('/components/chip')
    const chip = page.locator('[data-preview-name="chip-close"] [data-ui-component="chip"]').first()

    await chip.evaluate(element => {
        window.__chipClosed = null
        element.addEventListener('app-ui:chip:close', event => { window.__chipClosed = event.detail.value })
    })
    await chip.locator('[data-chip-close]').click()
    await expect(chip).toHaveAttribute('data-visible', 'false')
    await expect(chip).toBeHidden()
    expect(await page.evaluate(() => window.__chipClosed)).toBe('Chip')

    const controller = await page.locator('[data-preview-name="chip-close"] [data-ui-component="chip"]').nth(1).evaluate(element => ({
        value: AppUI.get(element).getValue(),
        methods: ['close', 'getValue', 'focus', 'destroy'].every(method => typeof AppUI.get(element)[method] === 'function'),
    }))
    expect(controller.value).toBe('Primary')
    expect(controller.methods).toBe(true)
})

test('Disabled Chip cannot be closed', async ({page}) => {
    await page.goto('/components/chip')
    const chip = page.locator('[data-preview-name="chip-disabled"] [data-ui-component="chip"]').nth(1)
    await expect(chip.locator('[data-chip-close]')).toBeDisabled()
    expect(await chip.evaluate(element => AppUI.get(element).close())).toBe(false)
    await expect(chip).toHaveAttribute('data-visible', 'true')
})
