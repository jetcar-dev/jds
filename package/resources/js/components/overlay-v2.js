(function (window, document) {
    'use strict'

    const AppUI = window.AppUI
    const openOverlays = new Set()
    const focusableSelector = 'a[href],button:not(:disabled),input:not(:disabled),select:not(:disabled),textarea:not(:disabled),[tabindex]:not([tabindex="-1"])'

    const position = (panel, anchor, options = {}) => {
        if (!panel || !anchor || panel.hidden) return
        const offset = Number(options.offset ?? panel.dataset.offset ?? 4)
        const preferred = options.placement || panel.dataset.placement || 'bottom-start'
        const [preferredSide, align = 'start'] = preferred.split('-')
        const anchorRect = anchor.getBoundingClientRect()
        const viewport = {width: document.documentElement.clientWidth, height: document.documentElement.clientHeight}
        panel.style.minWidth = options.matchWidth === false ? '' : `${anchorRect.width}px`
        panel.style.maxHeight = `${Math.max(96, viewport.height - 16)}px`
        const rect = panel.getBoundingClientRect()
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

    const create = ({root, trigger, panel, modal = false, placement, offset = 4, matchWidth = true, dismissable = true, keyboardDismiss = true, onOpen, onClose}) => {
        const parent = panel.parentNode
        const next = panel.nextSibling
        let restoreFocus = null
        let open = false

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
            restore()
            openOverlays.delete(api)
            onClose?.()
            AppUI.emit(root, `${root.dataset.uiComponent || 'overlay'}:close`)
            if (focus && restoreFocus?.isConnected) restoreFocus.focus()
            if (modal && ![...openOverlays].some(item => item.modal)) document.body.classList.remove('app-overlay-open')
        }

        const show = source => {
            if (open || trigger?.matches(':disabled,[aria-disabled="true"]')) return
            if (!modal) [...openOverlays].filter(item => !item.modal).forEach(item => item.close(false))
            restoreFocus = source || trigger || document.activeElement
            open = true
            root.dataset.open = 'true'
            trigger?.setAttribute('aria-expanded', 'true')
            if (!modal) document.body.appendChild(panel)
            panel.hidden = false
            openOverlays.add(api)
            if (modal) document.body.classList.add('app-overlay-open')
            requestAnimationFrame(() => {
                if (!modal) position(panel, trigger, {placement, offset, matchWidth})
                const target = panel.querySelector('[autofocus]') || (modal ? panel.querySelector(focusableSelector) : null)
                target?.focus()
            })
            onOpen?.()
            AppUI.emit(root, `${root.dataset.uiComponent || 'overlay'}:open`)
        }

        const api = {root, trigger, panel, modal, open: show, close, isOpen: () => open, position: () => position(panel, trigger, {placement, offset, matchWidth}), destroy: close}
        trigger?.addEventListener('click', event => { event.preventDefault(); open ? close(false) : show(event.target.closest(focusableSelector) || trigger) })
        panel.addEventListener('keydown', event => {
            if (event.key === 'Escape' && keyboardDismiss) { event.preventDefault(); close(true); return }
            if (!modal || event.key !== 'Tab') return
            const items = [...panel.querySelectorAll(focusableSelector)].filter(item => item.offsetParent !== null)
            if (!items.length) { event.preventDefault(); panel.focus(); return }
            const first = items[0], last = items[items.length - 1]
            if (event.shiftKey && document.activeElement === first) { event.preventDefault(); last.focus() }
            else if (!event.shiftKey && document.activeElement === last) { event.preventDefault(); first.focus() }
        })
        if (dismissable) panel.addEventListener('click', event => { if (event.target.closest('[data-overlay-close]')) close(true) })
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
