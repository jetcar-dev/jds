import {expect, test} from '@playwright/test'

test('Avatar sizes, radii, colors and states match HeroUI values', async ({page}) => {
    await page.goto('/components/avatar')

    const sizes = page.locator('[data-preview-name="avatar-sizes"] [data-ui-component="avatar"]')
    await expect(sizes.nth(0)).toHaveCSS('width', '32px')
    await expect(sizes.nth(1)).toHaveCSS('width', '40px')
    await expect(sizes.nth(2)).toHaveCSS('width', '56px')

    const radii = page.locator('[data-preview-name="avatar-radius"] [data-ui-component="avatar"]')
    await expect(radii.nth(0)).toHaveCSS('border-radius', '0px')
    await expect(radii.nth(1)).toHaveCSS('border-radius', '8px')
    await expect(radii.nth(2)).toHaveCSS('border-radius', '12px')
    await expect(radii.nth(3)).toHaveCSS('border-radius', '14px')
    await expect(radii.nth(4)).toHaveCSS('border-radius', '9999px')

    const colors = page.locator('[data-preview-name="avatar-colors"] [data-ui-component="avatar"]')
    await expect(colors.nth(1)).toHaveCSS('background-color', 'rgb(0, 111, 238)')
    await expect(colors.nth(3)).toHaveCSS('background-color', 'rgb(23, 201, 100)')
    await expect(colors.nth(5)).toHaveCSS('background-color', 'rgb(243, 18, 96)')

    const disabled = page.locator('[data-preview-name="avatar-disabled"] [data-ui-component="avatar"]').first()
    await expect(disabled).toHaveCSS('opacity', '0.5')
    await expect(disabled).toHaveAttribute('aria-disabled', 'true')

    const focusable = page.locator('[data-preview-name="avatar-focusable"] [data-ui-component="avatar"]').first()
    await expect(focusable).toHaveAttribute('tabindex', '0')
})

test('Avatar image loading, fallback slots and controller work', async ({page}) => {
    await page.goto('/components/avatar')

    const fallback = page.locator('[data-preview-name="avatar-fallbacks"] [data-ui-component="avatar"]').nth(1)
    await expect(fallback).toHaveAttribute('data-show-fallback', 'true')
    await expect(fallback).toHaveAttribute('data-fallback-visible', 'true')
    await expect(fallback.locator('[data-slot="name"]')).toHaveText('이')

    const withoutFallback = page.locator('[data-preview-name="avatar-fallbacks"] [data-ui-component="avatar"]').nth(2)
    await expect(withoutFallback).toHaveAttribute('data-fallback-visible', 'false')

    const custom = page.locator('[data-preview-name="avatar-custom-fallback"] [data-ui-component="avatar"]')
    await expect(custom.nth(0).locator('[data-slot="fallback"] .app-icon')).toHaveCSS('width', '24px')
    await expect(custom.nth(1).locator('[data-slot="name"]')).toHaveText('J')
    await expect(custom.nth(2)).toHaveAttribute('data-fallback-visible', 'false')

    const customIcons = page.locator('[data-preview-name="avatar-custom-icon"] [data-ui-component="avatar"]')
    await expect(customIcons).toHaveCount(2)
    await expect(customIcons.nth(0).locator('[data-slot="fallback"] .app-icon')).toHaveCSS('width', '24px')
    await expect(customIcons.nth(1).locator('[data-slot="fallback"] .app-icon')).toHaveCSS('width', '24px')

    const defaultIcon = page.locator('[data-preview-name="avatar-fallbacks"] [data-ui-component="avatar"]').nth(3)
    await expect(defaultIcon.locator('[data-default-avatar-icon="true"] .app-icon')).toHaveCSS('width', '32px')

    const basic = page.locator('[data-preview-name="avatar-basic"] [data-ui-component="avatar"]').first()
    const value = await basic.evaluate(root => {
        AppUI.get(root).setSource('')
        return AppUI.get(root).getValue()
    })
    expect(value).toBe('')
    await expect(basic).toHaveAttribute('data-fallback-visible', 'true')
})

test('Avatar Group applies max, total, disabled and grid behavior', async ({page}) => {
    await page.goto('/components/avatar')

    const groups = page.locator('[data-preview-name="avatar-group-count"] [data-ui-component="avatar-group"]')
    const localCount = groups.nth(0)
    await expect(localCount.locator('[data-slot="count"]')).toHaveText('+2')
    await expect(localCount).toHaveAttribute('data-count', '2')
    expect(await localCount.locator(':scope > [data-ui-component="avatar"]:not([hidden])').count()).toBe(3)

    const totalCount = groups.nth(1)
    await expect(totalCount.locator('[data-slot="count"]')).toHaveText('+7')
    await expect(totalCount.locator('[data-slot="count"]')).toHaveCSS('background-color', 'rgb(0, 111, 238)')

    const disabledGroup = page.locator('[data-preview-name="avatar-group-disabled"] [data-ui-component="avatar-group"]')
    await expect(disabledGroup.locator(':scope > [data-ui-component="avatar"]').first()).toHaveCSS('opacity', '0.5')

    const gridGroup = page.locator('[data-preview-name="avatar-group-grid"] [data-ui-component="avatar-group"]')
    await expect(gridGroup).toHaveCSS('display', 'grid')
    await expect(gridGroup).toHaveCSS('column-gap', '12px')
    await expect(gridGroup).toHaveCSS('row-gap', '12px')
    await expect(gridGroup.locator(':scope > [data-ui-component="avatar"]').first()).toHaveCSS('border-radius', '9999px')
    await expect(gridGroup.locator('[data-slot="count"]')).toHaveText('+1')
})
