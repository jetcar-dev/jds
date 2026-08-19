import {expect, test} from '@playwright/test'

test('Code matches HeroUI sizes, colors and radius', async ({page}) => {
    await page.goto('/components/code')

    const basic = page.locator('[data-preview-name="code-basic"] [data-ui-component="code"]')
    await expect(basic).toHaveAttribute('data-size', 'sm')
    await expect(basic).toHaveAttribute('data-color', 'default')
    await expect(basic).toHaveCSS('font-size', '14px')
    await expect(basic).toHaveCSS('line-height', '20px')
    await expect(basic).toHaveCSS('padding', '4px 8px')
    await expect(basic).toHaveCSS('border-radius', '8px')

    const basicPreview = page.locator('[data-preview-name="code-basic"] .jds-docs-example-ui')
    await expect(basicPreview).toHaveCSS('min-height', '160px')
    await expect(basicPreview).toHaveCSS('padding-left', '48px')
    await expect(basicPreview).toHaveCSS('padding-top', '48px')

    const sizes = page.locator('[data-preview-name="code-sizes"] [data-ui-component="code"]')
    await expect(sizes).toHaveCount(3)
    await expect(sizes.nth(0)).toHaveCSS('font-size', '14px')
    await expect(sizes.nth(1)).toHaveCSS('font-size', '16px')
    await expect(sizes.nth(2)).toHaveCSS('font-size', '18px')

    const colors = page.locator('[data-preview-name="code-colors"] [data-ui-component="code"]')
    await expect(colors).toHaveCount(6)
    await expect(colors.nth(1)).toHaveAttribute('data-color', 'primary')
    await expect(colors.nth(5)).toHaveAttribute('data-color', 'danger')

    const radii = page.locator('[data-preview-name="code-radius"] [data-ui-component="code"]')
    await expect(radii).toHaveCount(5)
    await expect(radii.nth(0)).toHaveCSS('border-radius', '0px')
    await expect(radii.nth(4)).toHaveCSS('border-radius', '9999px')
})
