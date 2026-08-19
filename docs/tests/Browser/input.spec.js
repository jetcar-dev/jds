import {expect, test} from '@playwright/test'

test('Input uses HeroUI sizing, label and native field contract', async ({page}) => {
    await page.goto('/components/input')

    const root = page.locator('[data-preview-name="input-basic"] [data-ui-component="input"]')
    const wrapper = root.locator('[data-slot="input-wrapper"]')
    const input = root.locator('[data-slot="input"]')
    const label = root.locator('[data-slot="label"]')

    await expect(root).toHaveAttribute('data-label-placement', 'inside')
    await expect(wrapper).toHaveCSS('height', '56px')
    await expect(wrapper).toHaveCSS('background-color', 'rgb(244, 244, 245)')
    await expect(wrapper).toHaveCSS('border-radius', '12px')
    await expect(input).toHaveAttribute('type', 'email')
    await expect(input).toHaveAttribute('name', 'email')

    const initialPosition = await root.evaluate(element => {
        const label = element.querySelector('[data-slot="label"]').getBoundingClientRect()
        const input = element.querySelector('[data-slot="input"]').getBoundingClientRect()
        return {labelTop: label.top, labelBottom: label.bottom, inputTop: input.top}
    })
    expect(initialPosition.inputTop - initialPosition.labelBottom).toBeGreaterThanOrEqual(4)

    await input.click()
    await expect(root).toHaveAttribute('data-focus', 'true')
    await expect(root).not.toHaveAttribute('data-focus-visible')
    await expect(wrapper).toHaveCSS('box-shadow', 'rgba(0, 0, 0, 0.05) 0px 1px 2px 0px')
    await expect.poll(async () => label.evaluate(element => element.getBoundingClientRect().top)).toBeCloseTo(initialPosition.labelTop, 0)

    const readonlyInput = page.locator('[data-preview-name="input-readonly"] [data-ui-component="input"] [data-slot="input"]')
    await readonlyInput.click()
    await wrapper.click({position: {x: 6, y: 6}})
    await expect(input).toBeFocused()
    await expect(root).not.toHaveAttribute('data-focus-visible')
})

test('Input variants, colors, sizes and invalid state match HeroUI values', async ({page}) => {
    await page.goto('/components/input')

    const sizes = page.locator('[data-preview-name="input-sizes"] [data-slot="input-wrapper"]')
    await expect(sizes.nth(0)).toHaveCSS('height', '48px')
    await expect(sizes.nth(1)).toHaveCSS('height', '56px')
    await expect(sizes.nth(2)).toHaveCSS('height', '64px')
    await expect(sizes.nth(0)).toHaveCSS('padding', '6px 12px')
    await expect(sizes.nth(1)).toHaveCSS('padding', '8px 12px')
    await expect(sizes.nth(2)).toHaveCSS('padding', '10px 12px')
    await expect(sizes.nth(0).locator('[data-slot="label"]')).toHaveCSS('font-size', '14px')
    await expect(sizes.nth(1).locator('[data-slot="label"]')).toHaveCSS('font-size', '14px')
    await expect(sizes.nth(2).locator('[data-slot="label"]')).toHaveCSS('font-size', '16px')
    await expect(sizes.nth(2).locator('[data-slot="input"]')).toHaveCSS('line-height', '24px')

    const colors = page.locator('[data-preview-name="input-colors"] [data-ui-component="input"]')
    await expect(colors.nth(1).locator('[data-slot="input-wrapper"]')).toHaveCSS('background-color', 'rgb(204, 227, 253)')
    await expect(colors.nth(1).locator('[data-slot="input"]')).toHaveCSS('color', 'rgb(0, 111, 238)')
    await expect(colors.nth(3).locator('[data-slot="input"]')).toHaveCSS('color', 'rgb(18, 161, 80)')
    await expect(colors.nth(4).locator('[data-slot="input"]')).toHaveCSS('color', 'rgb(196, 132, 29)')

    const variants = page.locator('[data-preview-name="input-variants"] [data-ui-component="input"]')
    await variants.nth(0).locator('[data-slot="input-wrapper"]').hover()
    await expect(variants.nth(0).locator('[data-slot="input-wrapper"]')).toHaveCSS('background-color', 'rgb(228, 228, 231)')
    await variants.nth(1).locator('[data-slot="input"]').click()
    await expect(variants.nth(1).locator('[data-slot="input-wrapper"]')).toHaveCSS('border-color', 'rgb(0, 0, 0)')
    await variants.nth(2).locator('[data-slot="input"]').click()
    await expect.poll(async () => variants.nth(2).locator('[data-slot="input-wrapper"]').evaluate(wrapper => Math.round(Number.parseFloat(getComputedStyle(wrapper, '::after').width)))).toBeGreaterThan(200)

    const invalid = page.locator('[data-preview-name="input-invalid"] [data-ui-component="input"]')
    await expect(invalid.locator('[data-slot="input-wrapper"]')).toHaveCSS('border-color', 'rgb(243, 18, 96)')
    await expect(invalid.locator('[data-slot="input"]')).toHaveCSS('color', 'rgb(243, 18, 96)')
    await expect(invalid.locator('[data-slot="label"]')).toHaveCSS('color', 'rgb(243, 18, 96)')
    await expect(invalid.locator('[data-slot="error-message"]')).toHaveCSS('line-height', '16px')
})

test('Input clear, password toggle, slots and controller work', async ({page}) => {
    await page.goto('/components/input')

    const clearable = page.locator('[data-preview-name="input-clearable"] [data-ui-component="input"]').first()
    const clearInput = clearable.locator('[data-slot="input"]')
    await clearInput.click()
    await expect(clearable.locator('[data-input-clear]')).toHaveAttribute('data-visible', 'true')
    await clearable.locator('[data-input-clear]').click()
    await expect(clearInput).toHaveValue('')
    await expect(clearable).toHaveAttribute('data-filled', 'false')
    await expect(clearInput).toBeFocused()
    await expect(clearable).not.toHaveAttribute('data-focus-visible')

    const password = page.locator('[data-preview-name="input-password"] [data-ui-component="input"]')
    const passwordInput = password.locator('[data-slot="input"]')
    await expect(passwordInput).toHaveAttribute('type', 'password')
    await password.locator('[data-password-toggle]').click()
    await expect(passwordInput).toHaveAttribute('type', 'text')
    await expect(password.locator('[data-password-toggle]')).toHaveAttribute('aria-label', '비밀번호 숨기기')
    await expect(passwordInput).toBeFocused()
    await expect(password).not.toHaveAttribute('data-focus-visible')
    await password.locator('[data-password-toggle]').click()
    await expect(passwordInput).toHaveAttribute('type', 'password')

    const content = page.locator('[data-preview-name="input-content"] [data-ui-component="input"]').last()
    const alignment = await content.evaluate(root => {
        const box = selector => root.querySelector(selector).getBoundingClientRect()
        return {
            input: box('[data-slot="input"]'),
            start: box('[data-slot="start-content"]'),
            end: box('[data-slot="end-content"]'),
        }
    })
    expect(Math.abs(alignment.input.top - alignment.start.top)).toBeLessThan(1)
    expect(Math.abs(alignment.input.bottom - alignment.end.bottom)).toBeLessThan(1)
    await expect(content.locator('[data-slot="start-content"]')).toHaveCSS('font-size', '14px')
    await expect(content.locator('[data-slot="start-content"]')).toHaveCSS('line-height', '20px')
    await expect(content.locator('[data-slot="end-content"]')).toHaveCSS('font-size', '14px')
    await expect(content.locator('[data-slot="end-content"]')).toHaveCSS('line-height', '20px')

    const controlled = page.locator('[data-preview-name="input-basic"] [data-ui-component="input"]')
    const eventValue = await controlled.evaluate(root => new Promise(resolve => {
        root.addEventListener('app-ui:input:change', event => resolve(event.detail.value), {once: true})
        AppUI.get(root).setValue('updated@example.com')
    }))
    expect(eventValue).toBe('updated@example.com')
    await expect(controlled.locator('[data-slot="input"]')).toHaveValue('updated@example.com')
})

test('Input converts native form validation into the HeroUI error state', async ({page}) => {
    await page.goto('/components/input')

    const form = page.locator('[data-preview-name="input-form"] form')
    const root = form.locator('[data-ui-component="input"]')
    const input = root.locator('[data-slot="input"]')
    const wrapper = root.locator('[data-slot="input-wrapper"]')

    await form.getByRole('button', {name: '저장'}).click()
    await expect(input).toBeFocused()
    await expect(input).toHaveAttribute('aria-invalid', 'true')
    await expect(root).toHaveAttribute('data-invalid', 'true')
    await expect(root).not.toHaveAttribute('data-focus-visible')
    await expect(root.locator('[data-slot="error-message"]')).toBeVisible()
    await expect(root.locator('[data-slot="error-message"]')).not.toBeEmpty()
    await expect(wrapper).toHaveCSS('background-color', 'rgb(254, 231, 239)')
    await expect(wrapper).toHaveCSS('box-shadow', 'rgba(0, 0, 0, 0.05) 0px 1px 2px 0px')

    await input.fill('user@example.com')
    await expect(input).not.toHaveAttribute('aria-invalid')
    await expect(root).toHaveAttribute('data-invalid', 'false')
    await expect(root.locator('[data-slot="error-message"]')).toBeHidden()
})
