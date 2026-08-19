import {expect, test} from '@playwright/test'

test('Card examples match HeroUI v2 composition and dimensions', async ({page}) => {
    await page.goto('/components/card')

    await expect.poll(
        () => page.locator('[data-preview-name^="card-"] img').evaluateAll(images =>
            images.filter(image => image.complete && image.naturalWidth === 0).length,
        ),
        {timeout: 15_000},
    ).toBe(0)

    const basic = page.locator('[data-preview-name="card-basic"] [data-ui-component="card"]')
    await expect(basic).toHaveAttribute('data-radius', 'lg')
    await expect(basic).toHaveAttribute('data-shadow', 'md')
    await expect(basic.locator('[data-slot="body"]')).toHaveCSS('padding', '12px')

    const divider = page.locator('[data-preview-name="card-divider"] [data-ui-component="card"]')
    expect(await divider.evaluate(element => Math.round(element.getBoundingClientRect().width))).toBe(400)
    await expect(divider.locator('hr[data-slot="divider"]')).toHaveCount(2)

    const image = page.locator('[data-preview-name="card-image"] [data-ui-component="card"]')
    await expect(image.locator('img')).toHaveCSS('width', '270px')
    await expect(image.locator('[data-slot="header"]')).toHaveCSS('padding-left', '16px')

    const footerBlurred = page.locator('[data-preview-name="card-footer-blurred"] [data-ui-component="card"]')
    await expect(footerBlurred).toHaveCSS('width', '200px')
    await expect(footerBlurred).toHaveCSS('height', '200px')
    await expect(footerBlurred.locator('[data-slot="footer"]')).toHaveCSS('bottom', '4px')
    await expect(footerBlurred.locator('[data-slot="footer"]')).not.toHaveCSS('backdrop-filter', 'none')

    const composition = page.locator('[data-preview-name="card-composition"] [data-ui-component="card"]')
    expect(await composition.evaluate(element => Math.round(element.getBoundingClientRect().width))).toBe(340)

    const blurred = page.locator('[data-preview-name="card-blurred"] [data-ui-component="card"]')
    await expect(blurred).not.toHaveCSS('backdrop-filter', 'none')
    await expect(blurred.locator('img[alt="Album cover"]')).toHaveCSS('height', '200px')

    const fruits = page.locator('[data-preview-name="card-pressable"] [data-ui-component="card"]')
    await expect(fruits).toHaveCount(8)
    await expect(fruits.first().locator('img')).toHaveCSS('height', '140px')

    const covers = page.locator('[data-preview-name="card-cover-image"] [data-ui-component="card"]')
    await expect(covers).toHaveCount(5)
    await expect(covers.first()).toHaveCSS('height', '300px')
    await expect(covers.nth(3).locator('[data-slot="footer"]')).toHaveCSS('border-bottom-left-radius', '14px')
    await expect(covers.nth(4).locator('[data-slot="footer"]')).toHaveCSS('border-bottom-right-radius', '14px')
})

test('Pressable Card exposes native and AppUI interaction states', async ({page}) => {
    await page.goto('/components/card')

    const card = page.locator('[data-preview-name="card-pressable"] [data-ui-component="card"]').first()
    await expect(card).toHaveJSProperty('tagName', 'BUTTON')
    await expect(card).toHaveAttribute('type', 'button')

    await card.hover()
    await expect(card).toHaveAttribute('data-hover', 'true')

    await card.focus()
    await page.keyboard.press('Enter')
    await expect(card).toBeFocused()

    const pressed = await card.evaluate(root => new Promise(resolve => {
        root.addEventListener('app-ui:card:press', () => resolve(true), {once: true})
        AppUI.get(root).press()
    }))
    expect(pressed).toBe(true)
})
