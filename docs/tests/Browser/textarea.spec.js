import {expect, test} from '@playwright/test'

test('Textarea renders HeroUI dimensions, variants and states', async ({page}) => {
    await page.goto('/components/textarea')

    const basic = page.locator('[data-preview-name="textarea-basic"] [data-ui-component="textarea"]')
    const wrapper = basic.locator('[data-slot="input-wrapper"]')
    const input = basic.locator('textarea')
    await expect(basic).toHaveAttribute('data-label-placement', 'inside')
    await expect(wrapper).toHaveCSS('padding', '8px 12px')
    await expect(wrapper).toHaveCSS('border-radius', '12px')
    await expect(wrapper).toHaveCSS('height', '100px')
    await expect(input).toHaveCSS('font-size', '14px')
    await expect(input).toHaveCSS('line-height', '20px')
    await expect(input).toHaveCSS('resize', 'none')

    const variants = page.locator('[data-preview-name="textarea-variants"] [data-ui-component="textarea"]')
    await expect(variants.nth(1).locator('[data-slot="input-wrapper"]')).toHaveCSS('border-width', '2px')
    await expect(variants.nth(2).locator('[data-slot="input-wrapper"]')).toHaveCSS('border-width', '2px')
    await expect(variants.nth(3).locator('[data-slot="input-wrapper"]')).toHaveCSS('border-bottom-width', '2px')

    const labelBox = await basic.locator('[data-slot="label"]').evaluate(element => element.getBoundingClientRect())
    expect(labelBox.height).toBeGreaterThan(18)
    expect(labelBox.height).toBeLessThan(19)

    const disabled = page.locator('[data-preview-name="textarea-disabled"] [data-ui-component="textarea"]')
    await expect(disabled).toHaveCSS('opacity', '0.5')
    await expect(disabled.locator('[data-slot="input-wrapper"]')).toHaveCSS('opacity', '1')

    const invalid = page.locator('[data-preview-name="textarea-invalid"] [data-ui-component="textarea"]')
    await expect(invalid).toHaveAttribute('data-invalid', 'true')
    await expect(invalid.locator('[data-slot="error-message"]')).toBeVisible()
    await expect(invalid.locator('textarea')).toHaveAttribute('aria-invalid', 'true')
})

test('Textarea clear, autosize and controller behavior work', async ({page}) => {
    await page.goto('/components/textarea')

    const clearable = page.locator('[data-preview-name="textarea-clearable"] [data-ui-component="textarea"]')
    const clearInput = clearable.locator('textarea')
    await expect(clearInput).not.toHaveValue('')
    await clearable.locator('[data-input-clear]').click()
    await expect(clearInput).toHaveValue('')
    await expect(clearInput).toBeFocused()

    const autosize = page.locator('[data-preview-name="textarea-autosize"] [data-ui-component="textarea"]').nth(1)
    const autoInput = autosize.locator('textarea')
    const before = await autoInput.evaluate(element => element.getBoundingClientRect().height)
    await autoInput.fill('첫 줄\n둘째 줄\n셋째 줄\n넷째 줄')
    const after = await autoInput.evaluate(element => element.getBoundingClientRect().height)
    expect(after).toBeGreaterThan(before)

    const manual = page.locator('[data-preview-name="textarea-disable-autosize"] textarea')
    await expect(manual).toHaveCSS('resize', 'vertical')

    const controlled = await page.locator('[data-preview-name="textarea-form"] [data-ui-component="textarea"]').evaluate(root => {
        const controller = window.AppUI.get(root)
        controller.setValue('컨트롤러 값')
        return controller.getValue()
    })
    expect(controlled).toBe('컨트롤러 값')
})
