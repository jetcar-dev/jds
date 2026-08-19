import {expect, test} from '@playwright/test'

test('Divider matches the HeroUI horizontal and vertical geometry', async ({page}) => {
    await page.goto('/components/divider')

    const basic = page.locator('[data-preview-name="divider-basic"]')
    const horizontal = basic.locator('[data-ui-component="divider"][data-orientation="horizontal"]')
    const vertical = basic.locator('[data-ui-component="divider"][data-orientation="vertical"]')

    await expect(horizontal).toHaveCSS('height', '1px')
    await expect(horizontal).toHaveCSS('width', '320px')
    await expect(horizontal).toHaveCSS('background-color', 'rgba(17, 17, 17, 0.15)')
    await expect(horizontal).toHaveAttribute('role', 'separator')

    await expect(vertical).toHaveCount(2)
    await expect(vertical.first()).toHaveCSS('width', '1px')
    await expect(vertical.first()).toHaveCSS('height', '20px')
    await expect(vertical.first()).toHaveAttribute('aria-orientation', 'vertical')
})
