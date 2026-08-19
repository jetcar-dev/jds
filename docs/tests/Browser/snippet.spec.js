import {expect, test} from '@playwright/test'

test('Snippet matches HeroUI sizes, variants and multiline behavior', async ({page}) => {
    await page.goto('/components/snippet')

    const basic = page.locator('[data-preview-name="snippet-basic"] [data-ui-component="snippet"]')
    await expect(basic).toHaveAttribute('data-size', 'md')
    await expect(basic).toHaveAttribute('data-variant', 'flat')
    await expect(basic).toHaveCSS('font-size', '14px')
    await expect(basic).toHaveCSS('line-height', '20px')
    await expect(basic).toHaveCSS('padding', '6px 12px')
    await expect(basic).toHaveCSS('border-radius', '14px')
    await basic.locator('[data-snippet-copy]').hover()
    await expect(page.locator('body > [data-slot="tooltip"]', {hasText: '복사'})).toBeVisible()

    const sizes = page.locator('[data-preview-name="snippet-sizes"] [data-ui-component="snippet"]')
    await expect(sizes).toHaveCount(3)
    await expect(sizes.nth(0)).toHaveCSS('font-size', '12px')
    await expect(sizes.nth(1)).toHaveCSS('font-size', '14px')
    await expect(sizes.nth(2)).toHaveCSS('font-size', '16px')

    const variants = page.locator('[data-preview-name="snippet-variants"] [data-ui-component="snippet"]')
    await expect(variants).toHaveCount(4)
    await expect(variants.nth(1)).toHaveCSS('border-top-width', '2px')
    await expect(variants.nth(2)).toHaveAttribute('data-variant', 'solid')
    await expect(variants.nth(3)).toHaveAttribute('data-variant', 'shadow')

    const multiline = page.locator('[data-preview-name="snippet-multiline"] [data-ui-component="snippet"]')
    await expect(multiline.locator('[data-slot="pre"]')).toHaveCount(3)
    await expect(multiline).toContainText('php artisan test')
})

test('Snippet copies its exact value and exposes the controller API', async ({page}) => {
    await page.goto('/components/snippet')
    await page.evaluate(() => {
        Object.defineProperty(navigator, 'clipboard', {
            configurable: true,
            value: {writeText: async value => { window.__snippetCopied = value }},
        })
    })

    const snippet = page.locator('[data-preview-name="snippet-multiline"] [data-ui-component="snippet"]')
    await snippet.locator('[data-snippet-copy]').click()
    await expect(snippet).toHaveAttribute('data-copied', 'true')
    await expect(snippet.locator('[data-snippet-copy]')).toHaveAttribute('aria-label', '복사됨')
    await expect(snippet.locator('[data-slot="check-icon"]')).toBeVisible()

    const result = await snippet.evaluate(element => ({
        copied: window.__snippetCopied,
        value: AppUI.get(element).getValue(),
        methods: ['copy', 'getValue', 'focus', 'destroy'].every(method => typeof AppUI.get(element)[method] === 'function'),
    }))
    expect(result.copied).toBe('npm install @jetcar/jds\nnpm run build\nphp artisan test')
    expect(result.value).toBe(result.copied)
    expect(result.methods).toBe(true)
})
