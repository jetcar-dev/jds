import {expect, test} from '@playwright/test'

test('Switch matches HeroUI v2 dimensions, colors and slots', async ({page}) => {
    await page.goto('/components/switch')

    const sizes = page.locator('[data-preview-name="switch-sizes"] [data-ui-component="switch"]')
    await expect(sizes).toHaveCount(3)
    for (const [index, dimensions] of [[0, ['40px', '24px', '16px']], [1, ['48px', '28px', '20px']], [2, ['56px', '32px', '24px']]]) {
        const wrapper = sizes.nth(index).locator('[data-slot="wrapper"]')
        const thumb = sizes.nth(index).locator('[data-slot="thumb"]')
        await expect(wrapper).toHaveCSS('width', dimensions[0])
        await expect(wrapper).toHaveCSS('height', dimensions[1])
        await expect(thumb).toHaveCSS('width', dimensions[2])
        await expect(thumb).toHaveCSS('height', dimensions[2])
    }

    const iconSwitch = page.locator('[data-preview-name="switch-thumb-icon"] [data-ui-component="switch"]').first()
    await expect(iconSwitch.locator('[data-slot="thumb-icon-selected"] .app-icon')).toHaveAttribute('data-icon', 'solar:sun-bold')
    await expect(iconSwitch.locator('[data-slot="thumb-icon-unselected"] .app-icon')).toHaveAttribute('data-icon', 'solar:moon-bold')
    await expect(iconSwitch.locator('[data-slot="thumb-icon-selected"]')).toHaveCSS('opacity', '1')
    await expect(iconSwitch.locator('[data-slot="thumb-icon-unselected"]')).toHaveCSS('opacity', '0')
    await iconSwitch.click()
    await expect(iconSwitch.locator('[data-slot="thumb-icon-selected"]')).toHaveCSS('opacity', '0')
    await expect(iconSwitch.locator('[data-slot="thumb-icon-unselected"]')).toHaveCSS('opacity', '1')

    const content = page.locator('[data-preview-name="switch-content-icons"] [data-ui-component="switch"]')
    await expect(content.locator('[data-slot="start-content"]')).toHaveCSS('opacity', '1')
    await expect(content.locator('[data-slot="end-content"]')).toHaveCSS('opacity', '0')

    const colors = page.locator('[data-preview-name="switch-colors"] [data-ui-component="switch"]')
    await expect(colors).toHaveCount(6)
    expect(await colors.nth(1).locator('[data-slot="wrapper"]').evaluate(element => getComputedStyle(element).backgroundColor)).not.toBe('rgba(0, 0, 0, 0)')

    const custom = page.locator('[data-preview-name="switch-custom-styles"] [data-ui-component="switch"]')
    await expect(custom).toHaveCSS('width', '448px')
    await expect(custom).toHaveCSS('height', '80px')
    await expect(custom).toHaveCSS('border-radius', '8px')
    await expect(custom).toHaveCSS('border-top-color', 'rgb(0, 111, 238)')
    await expect(custom.locator('[data-slot="wrapper"]')).toHaveCSS('height', '16px')
    const selectedPosition = await custom.evaluate(element => {
        const wrapper = element.querySelector('[data-slot="wrapper"]').getBoundingClientRect()
        const thumb = element.querySelector('[data-slot="thumb"]').getBoundingClientRect()
        return Math.round(wrapper.right - thumb.right)
    })
    expect(selectedPosition).toBe(0)
    await custom.click()
    await expect(custom).toHaveAttribute('data-selected', 'false')
    await page.waitForTimeout(200)
    const unselectedPosition = await custom.evaluate(element => {
        const wrapper = element.querySelector('[data-slot="wrapper"]').getBoundingClientRect()
        const thumb = element.querySelector('[data-slot="thumb"]').getBoundingClientRect()
        return Math.round(thumb.left - wrapper.left)
    })
    expect(unselectedPosition).toBe(0)
})

test('Switch supports native input, readonly and controller changes', async ({page}) => {
    await page.goto('/components/switch')

    const basic = page.locator('[data-preview-name="switch-basic"] [data-ui-component="switch"]')
    const basicInput = basic.getByRole('switch')
    await expect(basicInput).toBeChecked()
    await basic.click()
    await expect(basicInput).not.toBeChecked()
    await expect(basic).toHaveAttribute('data-selected', 'false')

    const readonly = page.locator('[data-preview-name="switch-disabled"] [data-readonly="true"]')
    await expect(readonly.getByRole('switch')).toBeChecked()
    await readonly.click()
    await expect(readonly.getByRole('switch')).toBeChecked()

    await page.evaluate(() => {
        const root = document.querySelector('[data-preview-name="switch-basic"] [data-ui-component="switch"]')
        window.switchChanges = 0
        root.addEventListener('app-ui:switch:change', () => window.switchChanges++)
        AppUI.get(root).setValue(true, true)
    })
    await expect(basicInput).toBeChecked()
    await expect.poll(() => page.evaluate(() => window.switchChanges)).toBe(1)
})
