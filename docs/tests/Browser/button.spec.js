import {expect, test} from '@playwright/test'

test('Button variants, states and controller work', async ({page}) => {
    await page.goto('/component-test#button-test')

    const variants = page.locator('#button-test [data-button-variants] [data-ui-component="button"]')
    await expect(variants).toHaveCount(7)
    await expect(variants.nth(0)).toHaveAttribute('data-variant', 'solid')
    await expect(variants.nth(3)).toHaveAttribute('data-variant', 'flat')
    await expect(variants.nth(6)).toHaveAttribute('data-variant', 'ghost')

    const sizes = page.locator('#button-test [data-button-sizes] [data-ui-component="button"]')
    await expect(sizes.nth(0)).toHaveCSS('height', '32px')
    await expect(sizes.nth(1)).toHaveCSS('height', '40px')
    await expect(sizes.nth(2)).toHaveCSS('height', '48px')

    const loading = page.locator('#button-test [data-loading="true"]')
    await expect(loading).toBeDisabled()
    await expect(loading).toHaveAttribute('aria-busy', 'true')

    const solid = variants.first()
    await solid.dispatchEvent('pointerdown', {clientX: 10, clientY: 10})
    await expect(solid.locator('.app-button-ripple')).toHaveCount(1)

    await page.evaluate(() => {
        window.buttonPresses = 0
        const button = document.querySelector('#button-test [data-button-variants] [data-ui-component="button"]')
        button.addEventListener('app-ui:button:press', () => window.buttonPresses++)
        AppUI.get(button).press()
    })
    await expect.poll(() => page.evaluate(() => window.buttonPresses)).toBe(1)
})

test('Button renders internal and external links', async ({page}) => {
    await page.goto('/components/button')

    const links = page.locator('[data-preview-name="button-link-width"] [data-ui-component="button"][data-link="true"]')
    await expect(links).toHaveCount(3)
    await expect(links.nth(0)).toHaveAttribute('href', '/components/button')

    const external = links.nth(1)
    await expect(external).toHaveAttribute('target', '_blank')
    await expect(external).toHaveAttribute('rel', 'noopener noreferrer')
    await expect(external.locator('[data-slot="anchor-icon"] .app-icon')).toHaveAttribute('data-icon', 'solar:square-top-down-linear')
    await expect(external.locator('[data-slot="anchor-icon"] .app-icon')).toHaveCSS('width', '14px')

    await expect(links.nth(2).locator('[data-slot="anchor-icon"] .app-icon')).toHaveAttribute('data-icon', 'solar:arrow-right-linear')
})
