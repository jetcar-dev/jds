(function (window, document) {
    'use strict'

    const AppUI = window.AppUI
    const openOverlays = new Set()
    const focusableSelector = 'a[href],button:not(:disabled),input:not(:disabled),select:not(:disabled),textarea:not(:disabled),[tabindex]:not([tabindex="-1"])'
    let scrollLockRestore = null

    const lockDocumentScroll = () => {
        if (scrollLockRestore) return
        const root = document.documentElement
        const body = document.body
        const properties = ['scrollbar-gutter', 'overflow']
        const previous = Object.fromEntries(properties.map(property => [property, {
            value: root.style.getPropertyValue(property),
            priority: root.style.getPropertyPriority(property),
        }]))
        const previousBodyWidth = {
            value: body.style.getPropertyValue('width'),
            priority: body.style.getPropertyPriority('width'),
        }
        const bodyWidth = body.getBoundingClientRect().width
        const preserveBodyWidth = root.scrollHeight > root.clientHeight
        if (preserveBodyWidth) body.style.setProperty('width', `${bodyWidth}px`)
        root.style.setProperty('scrollbar-gutter', 'stable')
        root.style.setProperty('overflow', 'hidden')
        scrollLockRestore = () => {
            properties.forEach(property => {
                const {value, priority} = previous[property]
                if (value) root.style.setProperty(property, value, priority)
                else root.style.removeProperty(property)
            })
            if (preserveBodyWidth) {
                if (previousBodyWidth.value) body.style.setProperty('width', previousBodyWidth.value, previousBodyWidth.priority)
                else body.style.removeProperty('width')
            }
            scrollLockRestore = null
        }
    }

    const unlockDocumentScroll = () => scrollLockRestore?.()

    const position = (panel, anchor, options = {}) => {
        if (!panel || !anchor || panel.hidden) return
        const offset = Number(options.offset ?? panel.dataset.offset ?? 4)
        const preferred = options.placement || panel.dataset.placement || 'bottom-start'
        const [preferredSide, align = 'start'] = preferred.split('-')
        const anchorRect = anchor.getBoundingClientRect()
        const viewport = {width: document.documentElement.clientWidth, height: document.documentElement.clientHeight}
        panel.style.minWidth = options.matchWidth === false ? '' : `${anchorRect.width}px`
        panel.style.maxHeight = `${Math.max(96, viewport.height - 16)}px`
        const measuredRect = panel.getBoundingClientRect()
        const rect = {
            width: panel.offsetWidth || measuredRect.width,
            height: panel.offsetHeight || measuredRect.height,
        }
        const spaces = {top: anchorRect.top - offset - 8, bottom: viewport.height - anchorRect.bottom - offset - 8, left: anchorRect.left - offset - 8, right: viewport.width - anchorRect.right - offset - 8}
        let side = preferredSide
        if ((side === 'bottom' || side === 'top') && rect.height > spaces[side] && spaces[side === 'bottom' ? 'top' : 'bottom'] > spaces[side]) side = side === 'bottom' ? 'top' : 'bottom'
        if ((side === 'left' || side === 'right') && rect.width > spaces[side] && spaces[side === 'left' ? 'right' : 'left'] > spaces[side]) side = side === 'left' ? 'right' : 'left'
        let top = anchorRect.bottom + offset
        let left = anchorRect.left
        if (side === 'top') top = anchorRect.top - rect.height - offset
        if (side === 'left') { left = anchorRect.left - rect.width - offset; top = anchorRect.top }
        if (side === 'right') { left = anchorRect.right + offset; top = anchorRect.top }
        if (side === 'top' || side === 'bottom') {
            if (align === 'center') left = anchorRect.left + (anchorRect.width - rect.width) / 2
            if (align === 'end') left = anchorRect.right - rect.width
        } else {
            if (align === 'center') top = anchorRect.top + (anchorRect.height - rect.height) / 2
            if (align === 'end') top = anchorRect.bottom - rect.height
        }
        panel.style.left = `${Math.max(8, Math.min(left, viewport.width - rect.width - 8))}px`
        panel.style.top = `${Math.max(8, Math.min(top, viewport.height - rect.height - 8))}px`
        panel.dataset.placementActual = side
        panel.style.setProperty('--overlay-origin', side === 'top' ? 'bottom' : side === 'left' ? 'right' : side === 'right' ? 'left' : 'top')
    }

    const create = ({root, trigger, panel, modal = false, placement, offset = 4, matchWidth = true, dismissable = true, keyboardDismiss = true, toggleOnTriggerClick = true, blockScroll = true, portalContainer = document.body, onOpen, onClose}) => {
        const parent = panel.parentNode
        const next = panel.nextSibling
        let restoreFocus = null
        let open = false
        let inertedElements = []

        const setBackgroundInert = active => {
            if (!modal) return
            if (!active) {
                inertedElements.forEach(({element, inert}) => { element.inert = inert })
                inertedElements = []
                return
            }
            inertedElements = [...document.body.children]
                .filter(element => element !== panel && !element.contains(panel))
                .map(element => ({element, inert: element.inert}))
            inertedElements.forEach(({element}) => { element.inert = true })
        }

        const restore = () => {
            if (panel.parentNode === parent) return
            next?.parentNode === parent ? parent.insertBefore(panel, next) : parent.appendChild(panel)
            for (const property of ['left','top','min-width','max-height']) panel.style.removeProperty(property)
        }

        const close = (focus = false) => {
            if (!open) return
            open = false
            panel.hidden = true
            root.dataset.open = 'false'
            trigger?.setAttribute('aria-expanded', 'false')
            setBackgroundInert(false)
            restore()
            openOverlays.delete(api)
            onClose?.()
            AppUI.emit(root, `${root.dataset.uiComponent || 'overlay'}:close`)
            if (focus && restoreFocus?.isConnected) restoreFocus.focus()
            if (modal && blockScroll && ![...openOverlays].some(item => item.modal && item.blockScroll)) unlockDocumentScroll()
        }

        const show = source => {
            if (open || trigger?.matches(':disabled,[aria-disabled="true"]')) return
            if (!modal) [...openOverlays].filter(item => !item.modal).forEach(item => item.close(false))
            restoreFocus = source || trigger || document.activeElement
            open = true
            root.dataset.open = 'true'
            trigger?.setAttribute('aria-expanded', 'true')
            ;(portalContainer?.isConnected ? portalContainer : document.body).appendChild(panel)
            panel.hidden = false
            openOverlays.add(api)
            if (modal) {
                if (blockScroll) lockDocumentScroll()
                setBackgroundInert(true)
            }
            requestAnimationFrame(() => {
                if (!modal) position(panel, trigger, {placement, offset, matchWidth})
                const target = panel.querySelector('[autofocus]') || (modal ? panel.querySelector(focusableSelector) || panel.querySelector('[role="dialog"]') : null)
                target?.focus()
                if (modal && target) {
                    if (root.dataset.focusVisibleOnOpen === 'true') target.dataset.focusVisible = 'true'
                    else delete target.dataset.focusVisible
                }
            })
            onOpen?.()
            AppUI.emit(root, `${root.dataset.uiComponent || 'overlay'}:open`)
        }

        const api = {root, trigger, panel, modal, blockScroll, open: show, close, isOpen: () => open, position: () => position(panel, trigger, {placement, offset, matchWidth}), destroy: close}
        if (toggleOnTriggerClick) trigger?.addEventListener('click', event => { event.preventDefault(); open ? api.close(false) : api.open(event.target.closest(focusableSelector) || trigger) })
        panel.addEventListener('keydown', event => {
            if (event.key === 'Escape' && keyboardDismiss) { event.preventDefault(); api.close(true); return }
            if (!modal || event.key !== 'Tab') return
            const items = [...panel.querySelectorAll(focusableSelector)].filter(item => item.offsetParent !== null)
            if (!items.length) { event.preventDefault(); panel.focus(); return }
            const first = items[0], last = items[items.length - 1]
            if (event.shiftKey && document.activeElement === first) { event.preventDefault(); last.focus() }
            else if (!event.shiftKey && document.activeElement === last) { event.preventDefault(); first.focus() }
        })
        if (dismissable) panel.addEventListener('click', event => { if (event.target.closest('[data-overlay-close]')) api.close(true) })
        return api
    }

    document.addEventListener('pointerdown', event => {
        ;[...openOverlays].reverse().some(overlay => {
            if (overlay.modal || overlay.root.contains(event.target) || overlay.panel.contains(event.target)) return false
            overlay.close(false)
            return true
        })
    }, true)
    document.addEventListener('scroll', () => [...openOverlays].filter(item => !item.modal).forEach(item => item.position()), true)
    window.addEventListener('resize', () => [...openOverlays].filter(item => !item.modal).forEach(item => item.position()))

    AppUI.overlay = {create, position, focusableSelector}
})(window, document)
