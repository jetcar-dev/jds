import {expect, test} from '@playwright/test'

test('Badge matches HeroUI sizes, variants, placement and visibility', async ({page}) => {
    await page.goto('/components/badge')

    const sizes = page.locator('[data-preview-name="badge-sizes"] [data-slot="badge"]')
    await expect(sizes.nth(0)).toHaveCSS('min-width', '16px')
    await expect(sizes.nth(1)).toHaveCSS('min-width', '20px')
    await expect(sizes.nth(2)).toHaveCSS('min-width', '24px')

    const variants = page.locator('[data-preview-name="badge-variants"] [data-ui-component="badge"]')
    await expect(variants.nth(0).locator('[data-slot="badge"]')).toHaveCSS('background-color', 'rgb(245, 165, 36)')
    await expect(variants.nth(1).locator('[data-slot="badge"]')).not.toHaveCSS('background-color', 'rgb(245, 165, 36)')
    await expect(variants.nth(2).locator('[data-slot="badge"]')).toHaveCSS('border-width', '2px')
    await expect(variants.nth(3).locator('[data-slot="badge"]')).not.toHaveCSS('box-shadow', 'none')

    const placements = page.locator('[data-preview-name="badge-placements"] [data-ui-component="badge"]')
    await expect(placements.nth(0).locator('[data-slot="badge"]')).toHaveCSS('top', '4px')
    await expect(placements.nth(1).locator('[data-slot="badge"]')).toHaveCSS('bottom', '4px')
    await expect(placements.nth(2).locator('[data-slot="badge"]')).toHaveCSS('left', '4px')

    const hidden = page.locator('[data-preview-name="badge-states"] [data-ui-component="badge"]').first()
    await expect(hidden).toHaveAttribute('data-invisible', 'true')
    await expect(hidden.locator('[data-slot="badge"]')).toHaveCSS('opacity', '0')
    await hidden.evaluate(root => AppUI.get(root).show())
    await expect(hidden).toHaveAttribute('data-invisible', 'false')
})

test('Breadcrumbs match HeroUI variants, state and collapse behavior', async ({page}) => {
    await page.goto('/components/breadcrumbs')

    const basic = page.locator('[data-preview-name="breadcrumbs-basic"] [data-ui-component="breadcrumbs"]')
    await expect(basic).toHaveAttribute('data-variant', 'light')
    await expect(basic).toHaveAttribute('data-underline', 'none')
    await expect(basic.locator(':scope > [data-slot="list"]')).toHaveCSS('background-color', 'rgba(0, 0, 0, 0)')
    await expect(basic.locator('[data-slot="base"]').last()).toHaveAttribute('data-current', 'true')
    await expect(basic.locator('[data-slot="item"]').last()).toHaveAttribute('aria-current', 'page')
    await expect(basic.locator('[data-slot="separator"] svg').first()).toBeVisible()

    const variants = page.locator('[data-preview-name="breadcrumbs-variants"] [data-ui-component="breadcrumbs"]')
    await expect(variants.nth(0).locator(':scope > [data-slot="list"]')).toHaveCSS('background-color', 'rgb(244, 244, 245)')
    await expect(variants.nth(1).locator(':scope > [data-slot="list"]')).toHaveCSS('border-width', '2px')
    await expect(variants.nth(2).locator(':scope > [data-slot="list"]')).toHaveCSS('background-color', 'rgba(0, 0, 0, 0)')

    const disabled = page.locator('[data-preview-name="breadcrumbs-disabled"] [data-ui-component="breadcrumbs"]').first()
    await expect(disabled.locator('[data-slot="item"]').first()).toHaveAttribute('tabindex', '-1')
    await expect(disabled.locator('[data-slot="item"]').first()).toHaveCSS('opacity', '0.5')

    const collapsed = page.locator('[data-preview-name="breadcrumbs-collapse"] [data-ui-component="breadcrumbs"]')
    await expect(collapsed.locator('[data-slot="ellipsis"]')).toHaveCount(1)
    await expect(collapsed.locator('[data-slot="base"][hidden]')).toHaveCount(2)
    await expect(collapsed.locator('[data-slot="ellipsis"] [data-slot="item"]')).toHaveAttribute('href', '/music')
    await expect(collapsed.locator('[data-slot="ellipsis"] [data-slot="item"] svg')).toHaveCSS('width', '16px')
    await expect(collapsed.locator('[data-slot="ellipsis"] [data-slot="separator"] svg')).toHaveCSS('width', '16px')
    await expect(collapsed.locator('[data-slot="ellipsis"] [data-slot="separator"] svg')).toHaveCSS('height', '16px')

    const controlled = page.locator('[data-preview-name="breadcrumbs-controlled"] [data-ui-component="breadcrumbs"]')
    await controlled.evaluate(root => AppUI.get(root).setValue('security'))
    await expect(controlled.locator('[data-key="security"]')).toHaveAttribute('data-current', 'true')
    await expect(controlled.locator('[data-key="security"] [data-slot="item"]')).toHaveAttribute('aria-current', 'page')
    await expect(controlled.locator('[data-key="profile"] [data-slot="separator"]')).toBeVisible()
    await expect(controlled.locator('[data-key="security"] [data-slot="separator"]')).toBeVisible()
    await expect(controlled.locator('[data-key="billing"] [data-slot="separator"]')).toBeHidden()
})
