import {expect, test} from '@playwright/test'

test('Listbox matches HeroUI structure and item dimensions', async ({page}) => {
    await page.goto('/components/listbox')

    const root = page.locator('[data-preview-name="listbox-basic"] [data-ui-component="listbox"]')
    const frame = page.locator('[data-preview-name="listbox-basic"] .jds-listbox-frame')
    const list = root.locator('[role="listbox"]')
    const first = root.getByRole('option').first()
    await expect(root).toHaveCSS('padding', '4px')
    await expect(root).toHaveCSS('gap', '4px')
    await expect(root).toHaveCSS('height', '142px')
    await expect(frame).toHaveCSS('width', '260px')
    await expect(frame).toHaveCSS('padding', '8px 4px')
    await expect(frame).toHaveCSS('border-radius', '8px')
    await expect(list).toHaveCSS('gap', '2px')
    await expect(first).toHaveCSS('height', '32px')
    await expect(first).toHaveCSS('padding', '6px 8px')
    await expect(first).toHaveCSS('gap', '8px')
    await expect(first).toHaveCSS('border-radius', '8px')
    await expect(first.locator('[data-slot="title"]')).toHaveCSS('font-size', '14px')
    await expect(first.locator('[data-slot="title"]')).toHaveCSS('line-height', '20px')

    const described = page.locator('[data-preview-name="listbox-description"] [role="option"]').first()
    await expect(described).toHaveCSS('height', '48px')
    await expect(described.locator('[data-slot="description"]')).toHaveCSS('font-size', '12px')
    await expect(described.locator('[data-slot="description"]')).toHaveCSS('line-height', '16px')

    const icon = page.locator('[data-preview-name="listbox-icons"] [data-slot="start-content"] .app-icon').first()
    await expect(icon).toHaveCSS('width', '20px')
    await expect(icon).toHaveCSS('height', '20px')

    const basicItems = root.getByRole('option')
    const regularColor = await basicItems.first().evaluate(item => getComputedStyle(item).color)
    const dangerColor = await basicItems.last().evaluate(item => getComputedStyle(item).color)
    expect(dangerColor).not.toBe(regularColor)

    const variantDemo = page.locator('[data-preview-name="listbox-variants"] [data-listbox-demo]')
    const variantListbox = variantDemo.locator('[data-ui-component="listbox"]')
    const previewItem = variantListbox.getByRole('option', {name: '새 파일'})
    const idleForeground = await previewItem.evaluate(item => getComputedStyle(item).color)
    await previewItem.hover()
    expect(await previewItem.evaluate(item => getComputedStyle(item).backgroundColor)).not.toBe('rgba(0, 0, 0, 0)')

    await variantDemo.getByRole('radio', {name: 'bordered'}).check()
    await previewItem.hover()
    await expect(previewItem).toHaveCSS('border-width', '2px')
    await expect(previewItem).toHaveCSS('color', idleForeground)

    await variantDemo.getByRole('radio', {name: 'flat'}).check()
    await expect(variantListbox).toHaveAttribute('data-variant', 'flat')
    await variantDemo.getByRole('radio', {name: 'primary'}).check()
    await expect(variantListbox).toHaveAttribute('data-color', 'primary')
    await expect(variantListbox).toHaveClass(/app-color-primary/)

    await previewItem.hover()
    expect(await previewItem.evaluate(item => getComputedStyle(item).backgroundColor)).not.toBe('rgba(0, 0, 0, 0)')

    await variantDemo.getByRole('radio', {name: 'bordered'}).check()
    await previewItem.hover()
    await expect(previewItem).toHaveCSS('border-width', '2px')
    expect(await previewItem.evaluate(item => getComputedStyle(item).borderColor)).not.toBe('rgba(0, 0, 0, 0)')

    await variantDemo.getByRole('radio', {name: 'light'}).check()
    await previewItem.hover()
    await expect(previewItem).toHaveCSS('background-color', 'rgba(0, 0, 0, 0)')

    await variantDemo.getByRole('radio', {name: 'faded'}).check()
    await previewItem.hover()
    await expect(previewItem).toHaveCSS('border-width', '1px')
    expect(await previewItem.evaluate(item => getComputedStyle(item).backgroundColor)).not.toBe('rgba(0, 0, 0, 0)')

    await variantDemo.getByRole('radio', {name: 'shadow'}).check()
    await previewItem.hover()
    expect(await previewItem.evaluate(item => getComputedStyle(item).boxShadow)).not.toBe('none')

    await variantDemo.getByRole('radio', {name: 'flat'}).check()

    const variantGroup = variantDemo.locator('[data-ui-component="radio-group"]').first()
    await expect(variantGroup.locator(':scope > [data-slot="label"]')).toHaveCSS('font-size', '16px')
    await expect(variantGroup.locator(':scope > [data-slot="label"]')).toHaveCSS('line-height', '28px')
    await expect(variantGroup.getByRole('radio', {name: 'flat'})).toBeChecked()

    const scrollList = page.locator('[data-preview-name="listbox-scroll"] [role="listbox"]')
    expect(await scrollList.evaluate(list => list.scrollHeight > list.clientHeight)).toBe(true)
    await expect(scrollList).toHaveCSS('max-height', '240px')
    await expect(scrollList.getByRole('option').first()).toHaveCSS('height', '48px')

    const iconItem = page.locator('[data-preview-name="listbox-icons"] [role="option"]').first()
    await expect(iconItem.locator('[data-slot="start-content"] .app-icon')).toHaveCSS('width', '20px')
    await expect(iconItem.locator('[data-slot="start-content"] .app-icon')).toHaveCSS('height', '20px')
    await expect(iconItem.locator('[data-slot="shortcut"] [data-ui-component="kbd"]')).toHaveCount(1)
    await expect(iconItem.locator('[data-slot="shortcut"] [data-ui-component="kbd"]')).toHaveCSS('height', '24px')
})

test('Listbox supports selection, disabled keys and keyboard navigation', async ({page}) => {
    await page.goto('/components/listbox')

    const single = page.locator('[data-preview-name="listbox-single"] [data-ui-component="listbox"]')
    await expect(single.getByRole('option', {name: '텍스트'})).toHaveAttribute('aria-selected', 'true')
    await single.getByRole('option', {name: '숫자'}).click()
    await expect(single.getByRole('option', {name: '숫자'})).toHaveAttribute('aria-selected', 'true')
    await expect(single.locator('[data-listbox-input]')).toHaveValue('number')
    await page.waitForTimeout(180)
    expect(await single.getByRole('option', {name: '숫자'}).evaluate(item => getComputedStyle(item).backgroundColor)).not.toBe('rgba(0, 0, 0, 0)')

    const multiple = page.locator('[data-preview-name="listbox-multiple"] [data-ui-component="listbox"]')
    await expect(multiple.getByRole('option', {name: '조회'})).toHaveAttribute('aria-selected', 'true')
    await expect(multiple.getByRole('option', {name: '수정'})).toHaveAttribute('aria-selected', 'true')
    await multiple.getByRole('option', {name: '조회'}).click()
    await multiple.getByRole('option', {name: '수정'}).click()
    await expect(multiple.getByRole('option', {name: '수정'})).toHaveAttribute('aria-selected', 'true')

    const disabled = page.locator('[data-preview-name="listbox-disabled"] [data-ui-component="listbox"]')
    await expect(disabled.getByRole('option', {name: '파일 편집'})).toHaveAttribute('aria-disabled', 'true')
    await disabled.getByRole('option', {name: '새 파일'}).focus()
    await disabled.getByRole('option', {name: '새 파일'}).press('End')
    await expect(disabled.getByRole('option', {name: '링크 복사'})).toBeFocused()
})

test('Listbox controller and form values stay synchronized', async ({page}) => {
    await page.goto('/components/listbox')

    const formListbox = page.locator('[data-preview-name="listbox-form"] [data-ui-component="listbox"]')
    const value = await formListbox.evaluate(root => {
        const controller = window.AppUI.get(root)
        controller.setValue('done')
        return controller.getValue()
    })
    expect(value).toBe('done')
    await expect(formListbox.locator('input[name="status"]')).toHaveValue('done')
    await expect(formListbox.getByRole('option', {name: '완료'})).toHaveAttribute('aria-selected', 'true')
})
