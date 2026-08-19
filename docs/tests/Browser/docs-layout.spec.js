import {expect, test} from '@playwright/test'

test('Docs use HeroUI-style header, sidebar, main and toc grid areas', async ({page}) => {
    await page.setViewportSize({width: 1600, height: 1000})
    await page.goto('/components/radio-group')

    const shell = page.locator('.jds-docs-shell')
    const header = page.locator('.jds-docs-header')
    const sidebar = page.locator('.jds-docs-sidebar')
    const main = page.locator('.jds-docs-main')
    const toc = page.locator('.jds-docs-page-rail')

    await expect(shell).toHaveCSS('display', 'grid')
    await expect(header).toHaveCSS('grid-area', 'header')
    await expect(sidebar).toHaveCSS('grid-area', 'sidebar')
    await expect(main).toHaveCSS('grid-area', 'main')
    await expect(toc).toHaveCSS('grid-area', 'toc')
    await expect(header).toHaveCSS('position', 'sticky')
    await expect(sidebar).toHaveCSS('position', 'sticky')
    await expect(toc).toHaveCSS('position', 'sticky')

    const boxes = await page.evaluate(() => {
        const box = selector => {
            const rect = document.querySelector(selector).getBoundingClientRect()
            return {left: rect.left, right: rect.right, top: rect.top}
        }

        return {
            header: box('.jds-docs-header'),
            sidebar: box('.jds-docs-sidebar'),
            main: box('.jds-docs-main'),
            toc: box('.jds-docs-page-rail'),
            title: box('.jds-docs-title'),
            article: box('.jds-docs-component-content'),
            sidebarTitle: box('.jds-docs-sidebar-title'),
            tocTitle: box('.jds-docs-page-index > span'),
        }
    })
    expect(boxes.sidebar.right).toBeLessThanOrEqual(boxes.main.left)
    expect(boxes.main.right).toBeLessThanOrEqual(boxes.toc.left)
    expect(boxes.header.left).toBe(boxes.sidebar.left)
    expect(boxes.header.right).toBe(boxes.toc.right)
    expect(boxes.title.left).toBe(boxes.article.left)
    expect(boxes.title.right).toBe(boxes.article.right)
    expect(Math.abs(boxes.title.top - boxes.sidebarTitle.top)).toBeLessThanOrEqual(1)
    expect(Math.abs(boxes.title.top - boxes.tocTitle.top)).toBeLessThanOrEqual(1)
})

test('Docs move toc to popover row and collapse sidebar on smaller screens', async ({page}) => {
    await page.setViewportSize({width: 1100, height: 900})
    await page.goto('/components/radio-group')
    await expect(page.locator('.jds-docs-page-rail')).toBeHidden()
    await expect(page.locator('.jds-docs-page-index-popover')).toBeVisible()
    await expect(page.locator('.jds-docs-sidebar')).toBeVisible()

    await page.setViewportSize({width: 700, height: 900})
    await expect(page.locator('.jds-docs-sidebar')).toBeHidden()
    await expect(page.locator('.jds-docs-mobile-nav')).toBeVisible()
    await expect(page.locator('.jds-docs-main')).toBeVisible()
})

test('Docs theme toggle switches and persists dark mode', async ({page}) => {
    test.setTimeout(30_000)
    await page.emulateMedia({colorScheme: 'light'})
    await page.goto('/components/radio-group')

    const toggle = page.locator('[data-docs-theme-toggle]')
    const body = page.locator('body')
    const heading = page.locator('h1').first()
    await expect(page.locator('html')).toHaveAttribute('data-theme', 'light')
    await expect(toggle).toHaveAttribute('aria-pressed', 'false')
    await expect(body).toHaveCSS('background-color', 'rgb(255, 255, 255)')
    await expect(body).toHaveCSS('color', 'rgb(17, 24, 28)')
    await expect(heading).toHaveCSS('color', 'rgb(17, 24, 28)')

    await toggle.click()
    await expect(page.locator('html')).toHaveAttribute('data-theme', 'dark')
    await expect(toggle).toHaveAttribute('aria-pressed', 'true')
    await expect(body).toHaveCSS('color-scheme', 'dark')
    await expect(body).toHaveCSS('background-color', 'rgb(0, 0, 0)')
    await expect(body).toHaveCSS('color', 'rgb(236, 237, 238)')
    await expect(heading).toHaveCSS('color', 'rgb(236, 237, 238)')

    await page.reload()
    await expect(page.locator('html')).toHaveAttribute('data-theme', 'dark')
    await expect(body).toHaveCSS('background-color', 'rgb(0, 0, 0)')
    await expect(body).toHaveCSS('color', 'rgb(236, 237, 238)')
})

test('Docs theme toggle works when a page heading id starts with a number', async ({page}) => {
    await page.emulateMedia({colorScheme: 'light'})
    await page.goto('/components/time-input')

    const toggle = page.locator('[data-docs-theme-toggle]')
    await expect(toggle).toHaveAttribute('aria-pressed', 'false')
    await toggle.click()
    await expect(page.locator('html')).toHaveAttribute('data-theme', 'dark')
    await expect(toggle).toHaveAttribute('aria-pressed', 'true')
})
