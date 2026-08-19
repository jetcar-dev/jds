import {expect, test} from '@playwright/test'

test('Tabs variants, cursor, form and keyboard behavior work', async ({page}) => {
    await page.goto('/component-test#tabs-test')

    const variants = page.locator('#tabs-test [data-tabs-variants] > [data-ui-component="tabs"]')
    await expect(variants).toHaveCount(4)
    await expect(variants.nth(0)).toHaveAttribute('data-variant', 'solid')
    await expect(variants.nth(3)).toHaveAttribute('data-variant', 'light')

    const solid = variants.first()
    const solidTabs = solid.getByRole('tab')
    await expect(solidTabs.nth(0)).toHaveAttribute('aria-selected', 'true')
    await expect(solid.locator('[data-slot="panel"]')).toHaveCount(1)
    await solidTabs.nth(1).click()
    await expect(solidTabs.nth(1)).toHaveAttribute('aria-selected', 'true')
    await expect(solid.getByRole('tabpanel')).toContainText('두 번째 패널')
    await expect(solid.locator('[data-slot="cursor"]')).toHaveAttribute('data-initialized', 'true')
    await expect(solidTabs.nth(2)).toBeDisabled()

    const formTabs = page.locator('#tabs-test [data-form-tabs]')
    const formList = formTabs.locator('[data-slot="tabList"]')
    await expect(formList).toHaveCSS('width', await formTabs.evaluate(element => `${element.clientWidth}px`))
    await formTabs.getByRole('tab', {name: '주간'}).click()
    await expect(formTabs.locator('input[name="period"]')).toHaveValue('week')
    await expect(formTabs).toHaveAttribute('data-value', 'week')

    const vertical = page.locator('#tabs-test [data-vertical-tabs]')
    const verticalTabs = vertical.getByRole('tab')
    await verticalTabs.first().focus()
    await page.keyboard.press('ArrowDown')
    await expect(verticalTabs.nth(1)).toBeFocused()
    await expect(verticalTabs.first()).toHaveAttribute('aria-selected', 'true')
    await page.keyboard.press('Enter')
    await expect(verticalTabs.nth(1)).toHaveAttribute('aria-selected', 'true')

    await page.evaluate(() => {
        const tabs = document.querySelector('#tabs-test [data-form-tabs]')
        AppUI.get(tabs).setValue('month')
    })
    await expect(formTabs).toHaveAttribute('data-value', 'month')
})

test('Tabs inside Preview tabs remain independent', async ({page}) => {
    await page.goto('/components/tabs')

    const preview = page.locator('[data-preview-name="tabs-basic"]')
    const docsTabs = preview.locator(':scope > [data-ui-component="tabs"]')
    const exampleTabs = preview.locator('.jds-docs-rendered-example > [data-ui-component="tabs"]')

    await expect(docsTabs.getByRole('tab', {name: '미리보기'})).toHaveAttribute('aria-selected', 'true')
    await expect(exampleTabs.getByRole('tabpanel')).toContainText('사진 콘텐츠입니다.')

    await exampleTabs.getByRole('tab', {name: '음악'}).click()
    await expect(exampleTabs.getByRole('tabpanel')).toContainText('음악 콘텐츠입니다.')
    await expect(docsTabs.getByRole('tab', {name: '미리보기'})).toHaveAttribute('aria-selected', 'true')
})
