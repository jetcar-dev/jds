import {expect, test} from '@playwright/test'

test('Select opens, selects and submits a value', async ({page}) => {
    await page.goto('/components/select')

    const select = page.locator('[data-preview-name="select-basic"] [data-ui-component="select"]').first()
    const trigger = select.getByRole('button')
    await trigger.click()
    await expect(select).toHaveAttribute('data-open', 'true')
    await expect(trigger).toHaveAttribute('aria-expanded', 'true')

    const popover = page.locator('[data-select-popover]:visible')
    await expect(popover).toBeVisible()
    await expect.poll(async () => Math.round((await popover.boundingBox()).width)).toBe(Math.round((await trigger.boundingBox()).width))
    await popover.getByRole('option', {name: '강아지'}).click()

    await expect(select.locator('[data-select-input]')).toHaveValue('dog')
    await expect(select.locator('[data-slot="value"]')).toHaveText('강아지')
    await expect(select).toHaveAttribute('data-open', 'false')
})

test('Select multiple selection, disabled keys and clear work', async ({page}) => {
    await page.goto('/components/select')

    const multiple = page.locator('[data-preview-name="select-multiple"] [data-ui-component="select"]')
    await multiple.locator('[data-slot="trigger"]').click()
    const popover = page.locator('[data-select-popover]:visible')
    await popover.getByRole('option', {name: '개발팀'}).click()
    await expect(multiple).toHaveAttribute('data-open', 'true')
    await expect(multiple.locator('[data-select-input]')).toHaveValue('["sales","support","development"]')
    await multiple.locator('[data-select-clear]').click()
    await expect(multiple.locator('[data-select-input]')).toHaveValue('[]')

    const disabledItems = page.locator('[data-preview-name="select-disabled"] [data-ui-component="select"]').nth(1)
    await disabledItems.getByRole('button').click()
    await expect(page.locator('[data-select-popover]:visible').getByRole('option', {name: /퀵서비스/})).toHaveAttribute('aria-disabled', 'true')
})

test('Select variants, colors, sizes and invalid state match the field system', async ({page}) => {
    await page.goto('/components/select')

    const sizes = page.locator('[data-preview-name="select-sizes"] [data-ui-component="select"] [data-slot="trigger"]')
    await expect(sizes.nth(0)).toHaveCSS('height', '48px')
    await expect(sizes.nth(1)).toHaveCSS('height', '56px')
    await expect(sizes.nth(2)).toHaveCSS('height', '64px')

    const noLabel = page.locator('[data-preview-name="select-basic"] [data-ui-component="select"]').nth(2)
    await expect(noLabel.locator('[data-slot="trigger"]')).toHaveCSS('height', '40px')

    const primary = page.locator('[data-preview-name="select-colors"] [data-ui-component="select"]').nth(1)
    await expect(primary.locator('[data-slot="trigger"]')).toHaveCSS('background-color', 'rgb(204, 227, 253)')
    await expect(primary.locator('[data-slot="value"]')).toHaveCSS('color', 'rgb(0, 111, 238)')

    const bordered = page.locator('[data-preview-name="select-variants"] [data-ui-component="select"]').nth(1)
    await expect(bordered.locator('[data-slot="trigger"]')).toHaveCSS('border-width', '2px')
    await bordered.getByRole('button').focus()
    await expect(bordered.locator('[data-slot="trigger"]')).toHaveCSS('border-color', 'rgb(0, 0, 0)')

    const invalid = page.locator('[data-preview-name="select-help"] [data-invalid="true"]')
    await expect(invalid.locator('[data-slot="trigger"]')).toHaveCSS('background-color', 'rgb(254, 231, 239)')
    await expect(invalid.locator('[data-slot="error-message"]')).toContainText('처리 상태를 선택해 주세요.')
})

test('Select supports label placement, sections and keyboard navigation', async ({page}) => {
    await page.goto('/components/select')

    const outsideLeft = page.locator('[data-preview-name="select-label-placement"] [data-label-placement="outside-left"]')
    await expect(outsideLeft).toHaveCSS('display', 'grid')

    const sections = page.locator('[data-preview-name="select-sections"] [data-ui-component="select"]')
    const trigger = sections.getByRole('button')
    await trigger.focus()
    await trigger.press('ArrowDown')
    const popover = page.locator('[data-select-popover]:visible')
    await expect(popover.getByRole('group')).toHaveCount(2)
    await expect(popover.getByRole('option').first()).toBeFocused()
    await popover.getByRole('option').first().press('ArrowDown')
    await expect(popover.getByRole('option').nth(1)).toBeFocused()
    await popover.getByRole('option').nth(1).press('Enter')
    await expect(sections.locator('[data-slot="value"]')).toHaveText('SUV')
})

test('Select label and placeholder follow HeroUI filled states', async ({page}) => {
    await page.goto('/components/select')

    const withoutPlaceholder = page.locator('[data-preview-name="select-without-placeholder"] [data-ui-component="select"]')
    const withoutTrigger = withoutPlaceholder.locator('[data-slot="trigger"]')
    const withoutLabel = withoutPlaceholder.locator('[data-slot="label"]')
    const withoutValue = withoutPlaceholder.locator('[data-slot="value"]')
    await expect(withoutPlaceholder).toHaveAttribute('data-filled', 'false')
    await expect(withoutValue).toHaveText('')
    const centered = await Promise.all([withoutTrigger.boundingBox(), withoutLabel.boundingBox()])
    expect(Math.abs((centered[0].y + centered[0].height / 2) - (centered[1].y + centered[1].height / 2))).toBeLessThan(2)

    await withoutTrigger.click()
    await expect(withoutPlaceholder).toHaveAttribute('data-open', 'true')
    await expect.poll(async () => {
        const [trigger, label] = await Promise.all([withoutTrigger.boundingBox(), withoutLabel.boundingBox()])
        return Math.round(label.y - trigger.y)
    }).toBe(8)
    const opened = await Promise.all([withoutTrigger.boundingBox(), withoutLabel.boundingBox()])
    await page.keyboard.press('Escape')

    const withPlaceholder = page.locator('[data-preview-name="select-with-placeholder"] [data-ui-component="select"]')
    await expect(withPlaceholder).toHaveAttribute('data-filled', 'true')
    await expect(withPlaceholder.locator('[data-slot="value"]')).toHaveText('동물을 선택하세요')
    const filled = await Promise.all([
        withPlaceholder.locator('[data-slot="trigger"]').boundingBox(),
        withPlaceholder.locator('[data-slot="label"]').boundingBox(),
        withPlaceholder.locator('[data-slot="value"]').boundingBox(),
    ])
    expect(filled[1].y - filled[0].y).toBeGreaterThanOrEqual(6)
    expect(filled[2].y).toBeGreaterThan(filled[1].y + filled[1].height)

    const selectMetrics = {
        labelOffset: Math.round(opened[1].y - opened[0].y),
        placeholderOffset: Math.round(filled[2].y - filled[0].y),
        height: Math.round(filled[0].height),
        labelFont: await withPlaceholder.locator('[data-slot="label"]').evaluate(node => getComputedStyle(node).fontSize),
        valueFont: await withPlaceholder.locator('[data-slot="value"]').evaluate(node => getComputedStyle(node).fontSize),
    }

    expect(selectMetrics).toEqual({
        labelOffset: 8,
        placeholderOffset: 26,
        height: 56,
        labelFont: '14px',
        valueFont: '14px',
    })
})
