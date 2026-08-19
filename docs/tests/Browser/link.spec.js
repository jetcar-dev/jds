import {expect, test} from '@playwright/test'

test('Link matches HeroUI v2 appearance and states', async ({page}) => {
    await page.goto('/components/link')

    const basic = page.locator('[data-preview-name="link-basic"] [data-ui-component="link"]')
    await expect(basic).toHaveAttribute('data-size', 'md')
    await expect(basic).toHaveAttribute('data-underline', 'none')
    // The preview is a flex container, so CSS blockifies inline-flex to flex.
    await expect(basic).toHaveCSS('display', 'flex')
    await expect(basic).toHaveCSS('font-size', '16px')
    await expect(basic).toHaveCSS('text-decoration-line', 'none')

    const sizes = page.locator('[data-preview-name="link-sizes"] [data-ui-component="link"]')
    await expect(sizes.nth(0)).toHaveCSS('font-size', '14px')
    await expect(sizes.nth(1)).toHaveCSS('font-size', '16px')
    await expect(sizes.nth(2)).toHaveCSS('font-size', '18px')

    const always = page.locator('[data-preview-name="link-underlines"] [data-underline="always"]')
    await expect(always).toHaveCSS('text-decoration-line', 'underline')

    const hover = page.locator('[data-preview-name="link-underlines"] [data-underline="hover"]')
    await hover.hover()
    await expect(hover).toHaveCSS('text-decoration-line', 'underline')

    const external = page.locator('[data-preview-name="link-external"] [data-external="true"]').nth(1)
    await expect(external).toHaveAttribute('target', '_blank')
    await expect(external).toHaveAttribute('rel', 'noopener noreferrer')
    await expect(external.locator('[data-slot="anchor-icon"] .app-icon')).toHaveAttribute('data-icon', 'solar:square-top-down-linear')
    await expect(external.locator('[data-slot="anchor-icon"] .app-icon')).toHaveCSS('width', '16px')
    await expect(external.locator('[data-slot="anchor-icon"] .app-icon')).toHaveCSS('height', '16px')

    const blockLinks = page.locator('[data-preview-name="link-block"] [data-block="true"]')
    await expect(blockLinks).toHaveCount(6)
    await expect(blockLinks.locator('[data-slot="anchor-icon"]')).toHaveCount(6)
    const block = blockLinks.nth(1)
    await expect(block).toHaveCSS('padding', '4px 8px')
    expect(await block.evaluate(element => getComputedStyle(element, '::after').borderRadius)).toBe('12px')
    expect(await block.evaluate(element => getComputedStyle(element, '::after').backgroundColor)).toBe('rgba(0, 0, 0, 0)')
    await block.hover()
    await page.waitForTimeout(180)
    expect(await block.evaluate(element => getComputedStyle(element, '::after').opacity)).toBe('1')

    const disabled = page.locator('[data-preview-name="link-disabled"] [data-ui-component="link"]')
    await expect(disabled).toHaveAttribute('aria-disabled', 'true')
    await expect(disabled).not.toHaveAttribute('href')
})
