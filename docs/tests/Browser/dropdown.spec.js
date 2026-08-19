import {expect, test} from '@playwright/test'

test('Dropdown matches HeroUI menu geometry and restores trigger focus', async ({page}) => {
    await page.goto('/components/dropdown')

    const trigger = page.locator('[data-preview-name="dropdown-basic"]').getByRole('button', {name: '메뉴 열기'})
    await trigger.click()
    await expect(trigger).toHaveAttribute('aria-expanded', 'true')

    const menu = page.getByRole('menu', {name: '프로필 작업'})
    await expect(menu).toBeVisible()
    const content = menu.locator('..')
    const popoverContent = content.locator('..')
    await expect(popoverContent).toHaveCSS('min-width', '200px')
    await expect(popoverContent).toHaveCSS('padding', '4px')
    await expect(content).toHaveCSS('padding', '4px')
    await expect(menu).toHaveCSS('padding', '0px')

    const firstItem = menu.getByRole('menuitem', {name: '새 파일'})
    await expect(firstItem).not.toBeFocused()
    await expect(firstItem).toHaveCSS('background-color', 'rgba(0, 0, 0, 0)')
    await expect(firstItem).toHaveCSS('padding', '6px 8px')
    await expect(firstItem).toHaveCSS('font-size', '14px')
    await firstItem.click()
    await expect(menu).toBeHidden()
    await expect(trigger).toBeFocused()
})

test('Dropdown supports keyboard, disabled keys, single and multiple selection', async ({page}) => {
    await page.goto('/components/dropdown')

    const disabledTrigger = page.locator('[data-preview-name="dropdown-disabled"]').getByRole('button', {name: '파일 작업'})
    await disabledTrigger.press('ArrowDown')
    const disabledMenu = page.getByRole('menu', {name: '파일 작업'})
    await expect(disabledMenu).toBeVisible()
    await expect(disabledMenu.getByRole('menuitem', {name: '수정'})).toHaveAttribute('aria-disabled', 'true')
    const firstEnabled = disabledMenu.getByRole('menuitem', {name: '보기'})
    await expect(firstEnabled).toBeFocused()
    await expect(firstEnabled).toHaveAttribute('data-focus-visible', 'true')
    await firstEnabled.press('Escape')
    await expect(disabledMenu).toBeHidden()

    const singleTrigger = page.locator('[data-preview-name="dropdown-single-selection"]').getByRole('button', {name: '텍스트 정렬'})
    await singleTrigger.click()
    const singleMenu = page.getByRole('menu', {name: '텍스트 정렬'})
    const center = singleMenu.getByRole('menuitemradio', {name: '가운데'})
    await center.click()
    await expect(singleMenu).toBeHidden()
    const singleValue = await page.locator('[data-preview-name="dropdown-single-selection"] [data-ui-component="dropdown"]').evaluate(element => AppUI.get(element).getValue())
    expect(singleValue).toBe('center')

    const multiplePreview = page.locator('[data-preview-name="dropdown-multiple-selection"]')
    await multiplePreview.getByRole('button', {name: '표시할 열'}).click()
    const multipleMenu = page.getByRole('menu', {name: '표시할 열'})
    const owner = multipleMenu.getByRole('menuitemcheckbox', {name: '담당자'})
    await owner.click()
    await expect(owner).toHaveAttribute('aria-checked', 'true')
    await expect(multipleMenu).toBeVisible()

    const selected = await multiplePreview.locator('[data-ui-component="dropdown"]').evaluate(element => AppUI.get(element).getValue())
    expect(selected).toEqual(['name', 'status', 'owner'])
})
