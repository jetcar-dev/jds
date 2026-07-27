(function (window, document) {
    'use strict'

    const AppUI = window.AppUI
    const viewportGap = 8
    let nextId = 1

    const menuItems = function (menu) {
        return Array.from(menu.querySelectorAll('[role="menuitem"], [role="menuitemcheckbox"], [role="menuitemradio"]')).filter(function (item) {
            return item.closest('[role="menu"]') === menu && !item.disabled && item.getAttribute('aria-disabled') !== 'true'
        })
    }

    const positionMenu = function (menu, anchor, options) {
        menu.hidden = false
        menu.style.visibility = 'hidden'

        const anchorRect = anchor.getBoundingClientRect()
        const menuRect = menu.getBoundingClientRect()
        const offset = Number(menu.dataset.sideOffset || 4)
        let top
        let left

        if (options.submenu) {
            const openLeft = anchorRect.right + menuRect.width + offset > window.innerWidth - viewportGap
            left = openLeft ? anchorRect.left - menuRect.width - offset : anchorRect.right + offset
            top = anchorRect.top
        } else {
            const prefersTop = menu.dataset.side === 'top'
            const fitsBelow = anchorRect.bottom + menuRect.height + offset <= window.innerHeight - viewportGap
            const fitsAbove = anchorRect.top - menuRect.height - offset >= viewportGap
            const openTop = prefersTop ? fitsAbove || !fitsBelow : !fitsBelow && fitsAbove
            top = openTop ? anchorRect.top - menuRect.height - offset : anchorRect.bottom + offset
            left = menu.dataset.align === 'end' ? anchorRect.right - menuRect.width : anchorRect.left
        }

        menu.style.top = Math.max(viewportGap, Math.min(top, window.innerHeight - menuRect.height - viewportGap)) + 'px'
        menu.style.left = Math.max(viewportGap, Math.min(left, window.innerWidth - menuRect.width - viewportGap)) + 'px'
        menu.style.visibility = ''
    }

    const cancelSubmenuClose = function (sub) {
        window.clearTimeout(Number(sub.dataset.closeTimer || 0))
        delete sub.dataset.closeTimer
    }

    const closeSubmenu = function (sub) {
        if (!sub) return
        cancelSubmenuClose(sub)
        sub.classList.remove('app-dropdown-submenu-open')
        sub.querySelector('[data-slot="dropdown-menu-sub-trigger"]')?.setAttribute('aria-expanded', 'false')
        const content = sub.querySelector(':scope > [data-slot="dropdown-menu-sub-content"]')
        if (content) content.hidden = true
    }

    const closeSubmenus = function (root) {
        root.querySelectorAll('[data-slot="dropdown-menu-sub"].app-dropdown-submenu-open').forEach(closeSubmenu)
    }

    const closeSubmenuSoon = function (sub) {
        cancelSubmenuClose(sub)
        sub.dataset.closeTimer = String(window.setTimeout(function () {
            closeSubmenu(sub)
        }, 120))
    }

    const closeMenu = function (root, returnFocus) {
        const trigger = root.querySelector(':scope > [data-slot="dropdown-menu-trigger"]')
        const control = trigger?.querySelector('button, a, [role="button"], [tabindex]:not([tabindex="-1"])')
        const content = root.querySelector(':scope > [data-slot="dropdown-menu-content"]')
        closeSubmenus(root)
        root.classList.remove('app-dropdown-open')
        if (content) content.hidden = true
        control?.setAttribute('aria-expanded', 'false')
        if (returnFocus) control?.focus()
    }

    const openMenu = function (root, focus) {
        const trigger = root.querySelector(':scope > [data-slot="dropdown-menu-trigger"]')
        const control = trigger?.querySelector('button, a, [role="button"], [tabindex]:not([tabindex="-1"])')
        const content = root.querySelector(':scope > [data-slot="dropdown-menu-content"]')
        if (!control || !content) return

        document.querySelectorAll('[data-slot="dropdown-menu"].app-dropdown-open').forEach(function (opened) {
            if (opened !== root) closeMenu(opened, false)
        })

        root.classList.add('app-dropdown-open')
        control.setAttribute('aria-expanded', 'true')
        positionMenu(content, control, {submenu: false})
        const items = menuItems(content)
        if (focus === 'first') items[0]?.focus()
        if (focus === 'last') items[items.length - 1]?.focus()
    }

    const openSubmenu = function (sub, focus) {
        const trigger = sub.querySelector(':scope > [data-slot="dropdown-menu-sub-trigger"]')
        const content = sub.querySelector(':scope > [data-slot="dropdown-menu-sub-content"]')
        if (!trigger || !content || trigger.disabled) return
        cancelSubmenuClose(sub)
        const parentMenu = sub.closest('[role="menu"]')
        parentMenu?.querySelectorAll(':scope > [data-slot="dropdown-menu-sub"]').forEach(function (sibling) {
            if (sibling !== sub) closeSubmenu(sibling)
        })
        sub.classList.add('app-dropdown-submenu-open')
        trigger.setAttribute('aria-expanded', 'true')
        positionMenu(content, trigger, {submenu: true})
        if (focus) menuItems(content)[0]?.focus()
    }

    const setRadioValue = function (group, value) {
        group.dataset.value = value
        group.querySelectorAll('[role="menuitemradio"]').forEach(function (item) {
            const checked = item.dataset.value === value
            item.setAttribute('aria-checked', String(checked))
            item.dataset.state = checked ? 'checked' : 'unchecked'
        })
    }

    const init = function (root) {
        const trigger = root.querySelector(':scope > [data-slot="dropdown-menu-trigger"]')
        const control = trigger?.querySelector('button, a, [role="button"], [tabindex]:not([tabindex="-1"])')
        const content = root.querySelector(':scope > [data-slot="dropdown-menu-content"]')
        if (!trigger || !control || !content) return

        const id = 'app-dropdown-' + nextId++
        content.id = content.id || id + '-menu'
        control.id = control.id || id + '-trigger'
        control.setAttribute('aria-haspopup', 'menu')
        control.setAttribute('aria-expanded', 'false')
        control.setAttribute('aria-controls', content.id)
        content.setAttribute('aria-labelledby', control.id)

        root.querySelectorAll('[data-slot="dropdown-menu-radio-group"]').forEach(function (group) {
            setRadioValue(group, group.dataset.value || group.querySelector('[role="menuitemradio"]')?.dataset.value || '')
        })

        root.querySelectorAll('[data-slot="dropdown-menu-sub"]').forEach(function (sub) {
            const content = sub.querySelector(':scope > [data-slot="dropdown-menu-sub-content"]')
            sub.addEventListener('mouseenter', function () { openSubmenu(sub, false) })
            sub.addEventListener('mouseleave', function () { closeSubmenuSoon(sub) })
            content?.addEventListener('mouseenter', function () { cancelSubmenuClose(sub) })
            content?.addEventListener('mouseleave', function () { closeSubmenuSoon(sub) })
        })

        trigger.addEventListener('click', function (event) {
            event.preventDefault()
            root.classList.contains('app-dropdown-open') ? closeMenu(root, false) : openMenu(root)
        })

        trigger.addEventListener('keydown', function (event) {
            if (!['Enter', ' ', 'ArrowDown', 'ArrowUp'].includes(event.key)) return
            event.preventDefault()
            openMenu(root, event.key === 'ArrowUp' ? 'last' : 'first')
        })

        root.addEventListener('click', function (event) {
            const subTrigger = event.target.closest('[data-slot="dropdown-menu-sub-trigger"]')
            if (subTrigger && root.contains(subTrigger)) {
                event.preventDefault()
                const sub = subTrigger.closest('[data-slot="dropdown-menu-sub"]')
                sub.classList.contains('app-dropdown-submenu-open') ? closeSubmenu(sub) : openSubmenu(sub, false)
                return
            }

            const item = event.target.closest('[role="menuitem"], [role="menuitemcheckbox"], [role="menuitemradio"]')
            if (!item || !root.contains(item) || item.disabled) return
            if (item.getAttribute('role') === 'menuitemcheckbox') {
                const checked = item.getAttribute('aria-checked') !== 'true'
                item.setAttribute('aria-checked', String(checked))
                item.dataset.state = checked ? 'checked' : 'unchecked'
            }
            if (item.getAttribute('role') === 'menuitemradio') {
                const group = item.closest('[data-slot="dropdown-menu-radio-group"]')
                if (group) setRadioValue(group, item.dataset.value)
            }
            if (item.dataset.closeOnSelect === 'true') closeMenu(root, false)
        })

        root.addEventListener('keydown', function (event) {
            const menu = event.target.closest('[role="menu"]')
            if (!menu || !root.contains(menu)) return
            const items = menuItems(menu)
            const current = items.indexOf(event.target)
            if (event.key === 'ArrowDown' || event.key === 'ArrowUp') {
                event.preventDefault()
                const step = event.key === 'ArrowDown' ? 1 : -1
                items[(current + step + items.length) % items.length]?.focus()
            } else if (event.key === 'Home') {
                event.preventDefault(); items[0]?.focus()
            } else if (event.key === 'End') {
                event.preventDefault(); items[items.length - 1]?.focus()
            } else if (event.key === 'ArrowRight' && event.target.matches('[data-slot="dropdown-menu-sub-trigger"]')) {
                event.preventDefault(); openSubmenu(event.target.closest('[data-slot="dropdown-menu-sub"]'), true)
            } else if (event.key === 'ArrowLeft' && menu.matches('[data-slot="dropdown-menu-sub-content"]')) {
                event.preventDefault()
                const sub = menu.closest('[data-slot="dropdown-menu-sub"]')
                closeSubmenu(sub)
                sub.querySelector('[data-slot="dropdown-menu-sub-trigger"]')?.focus()
            } else if (event.key === 'Escape') {
                event.preventDefault(); closeMenu(root, true)
            }
        })
    }

    document.addEventListener('click', function (event) {
        document.querySelectorAll('[data-slot="dropdown-menu"].app-dropdown-open').forEach(function (root) {
            if (!root.contains(event.target)) closeMenu(root, false)
        })
    })

    window.addEventListener('resize', function () {
        document.querySelectorAll('[data-slot="dropdown-menu"].app-dropdown-open').forEach(function (root) { closeMenu(root, false) })
    })

    window.addEventListener('scroll', function () {
        document.querySelectorAll('[data-slot="dropdown-menu"].app-dropdown-open').forEach(function (root) { closeMenu(root, false) })
    }, true)

    AppUI.register('dropdown-menu', '[data-slot="dropdown-menu"]', init)
})(window, document)
