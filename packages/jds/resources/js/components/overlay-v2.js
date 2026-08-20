(function (window, document) {
    'use strict'

    const AppUI = window.AppUI
    const openOverlays = new Set()
    const focusableSelector = 'a[href],button:not(:disabled),input:not(:disabled),select:not(:disabled),textarea:not(:disabled),[tabindex]:not([tabindex="-1"])'
    let scrollLockRestore = null

    const lockDocumentScroll = () => {
        if (scrollLockRestore) return
        const root = document.documentElement
        const properties = ['scrollbar-gutter', 'overflow']
        const previous = Object.fromEntries(properties.map(property => [property, {
            value: root.style.getPropertyValue(property),
            priority: root.style.getPropertyPriority(property),
        }]))
        root.style.setProperty('scrollbar-gutter', 'stable')
        root.style.setProperty('overflow', 'hidden')
        scrollLockRestore = () => {
            properties.forEach(property => {
                const {value, priority} = previous[property]
                if (value) root.style.setProperty(property, value, priority)
                else root.style.removeProperty(property)
            })
            scrollLockRestore = null
        }
    }

    const unlockDocumentScroll = () => scrollLockRestore?.()

    const position = (panel, anchor, options = {}) => {
        if (!panel || !anchor || panel.hidden) return
        const offset = Number(options.offset ?? panel.dataset.offset ?? 4)
        const crossOffset = Number(options.crossOffset ?? panel.dataset.crossOffset ?? 0)
        const containerPadding = Math.max(0, Number(options.containerPadding ?? panel.dataset.containerPadding ?? 8))
        const shouldFlip = options.shouldFlip ?? panel.dataset.shouldFlip !== 'false'
        const preferred = options.placement || panel.dataset.placement || 'bottom-start'
        const [preferredSide, preferredAlign] = preferred.split('-')
        const align = preferredAlign || 'center'
        const anchorRect = anchor.getBoundingClientRect()
        const viewport = {width: document.documentElement.clientWidth, height: document.documentElement.clientHeight}
        panel.style.minWidth = options.matchWidth === false ? '' : `${anchorRect.width}px`
        panel.style.maxHeight = `${Math.max(96, viewport.height - 16)}px`
        const measuredRect = panel.getBoundingClientRect()
        const rect = {
            width: panel.offsetWidth || measuredRect.width,
            height: panel.offsetHeight || measuredRect.height,
        }
        const spaces = {top: anchorRect.top - offset - containerPadding, bottom: viewport.height - anchorRect.bottom - offset - containerPadding, left: anchorRect.left - offset - containerPadding, right: viewport.width - anchorRect.right - offset - containerPadding}
        let side = preferredSide
        if (shouldFlip && (side === 'bottom' || side === 'top') && rect.height > spaces[side] && spaces[side === 'bottom' ? 'top' : 'bottom'] > spaces[side]) side = side === 'bottom' ? 'top' : 'bottom'
        if (shouldFlip && (side === 'left' || side === 'right') && rect.width > spaces[side] && spaces[side === 'left' ? 'right' : 'left'] > spaces[side]) side = side === 'left' ? 'right' : 'left'
        let top = anchorRect.bottom + offset
        let left = anchorRect.left
        if (side === 'top') top = anchorRect.top - rect.height - offset
        if (side === 'left') { left = anchorRect.left - rect.width - offset; top = anchorRect.top }
        if (side === 'right') { left = anchorRect.right + offset; top = anchorRect.top }
        if (side === 'top' || side === 'bottom') {
            if (align === 'center') left = anchorRect.left + (anchorRect.width - rect.width) / 2
            if (align === 'end') left = anchorRect.right - rect.width
            left += crossOffset
        } else {
            if (align === 'center') top = anchorRect.top + (anchorRect.height - rect.height) / 2
            if (align === 'end') top = anchorRect.bottom - rect.height
            top += crossOffset
        }
        panel.style.left = `${Math.max(containerPadding, Math.min(left, viewport.width - rect.width - containerPadding))}px`
        panel.style.top = `${Math.max(containerPadding, Math.min(top, viewport.height - rect.height - containerPadding))}px`
        panel.dataset.placementActual = `${side}${align === 'center' ? '' : `-${align}`}`
        panel.style.setProperty('--overlay-origin', side === 'top' ? 'bottom' : side === 'left' ? 'right' : side === 'right' ? 'left' : 'top')
    }

    const create = ({root, trigger, panel, modal = false, placement, offset = 4, crossOffset = 0, containerPadding = 8, shouldFlip = true, matchWidth = true, dismissable = true, keyboardDismiss = true, toggleOnTriggerClick = true, focusPanel = false, blockScroll = modal, closeOnScroll = false, closeOnBlur = false, closeDelay = 0, portalContainer = document.body, onOpen, onClosing, onClose}) => {
        const parent = panel.parentNode
        const next = panel.nextSibling
        let restoreFocus = null
        let open = false
        let closing = false
        let closeTimer = 0
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

        const finishClose = focus => {
            panel.hidden = true
            panel.dataset.state = 'closed'
            panel.dataset.focus = 'false'
            root.dataset.state = 'closed'
            closing = false
            closeTimer = 0
            setBackgroundInert(false)
            restore()
            onClose?.()
            AppUI.emit(root, `${root.dataset.uiComponent || 'overlay'}:close`)
            if (focus && restoreFocus?.isConnected) restoreFocus.focus({preventScroll: true})
            if (blockScroll && ![...openOverlays].some(item => item.blockScroll)) unlockDocumentScroll()
        }

        const close = (focus = false, immediate = false) => {
            if (!open || closing) return
            open = false
            closing = true
            root.dataset.open = 'false'
            root.dataset.state = 'closing'
            panel.dataset.open = 'false'
            panel.dataset.state = 'closing'
            trigger?.setAttribute('aria-expanded', 'false')
            openOverlays.delete(api)
            onClosing?.()
            const reducedMotion = window.matchMedia?.('(prefers-reduced-motion: reduce)')?.matches
            const delay = immediate || reducedMotion ? 0 : Math.max(0, Number(closeDelay))
            if (delay) closeTimer = window.setTimeout(() => finishClose(focus), delay)
            else finishClose(focus)
        }

        const show = source => {
            if (open || root.dataset.disabled === 'true' || trigger?.matches(':disabled,[aria-disabled="true"]')) return
            if (closing) {
                clearTimeout(closeTimer)
                closing = false
                closeTimer = 0
            }
            if (!modal) [...openOverlays].filter(item => !item.modal).forEach(item => item.close(false))
            restoreFocus = source || trigger || document.activeElement
            open = true
            root.dataset.open = 'true'
            root.dataset.state = 'open'
            panel.dataset.open = 'true'
            panel.dataset.state = 'opening'
            trigger?.setAttribute('aria-expanded', 'true')
            api.openedAt = Date.now()
            ;(portalContainer?.isConnected ? portalContainer : document.body).appendChild(panel)
            panel.hidden = false
            const overlayWrapper = panel.querySelector('[data-overlay-wrapper]')
            if (overlayWrapper) {
                overlayWrapper.scrollLeft = 0
                overlayWrapper.scrollTop = 0
            }
            if (!modal) position(panel, trigger, {placement, offset, crossOffset, containerPadding, shouldFlip, matchWidth})
            openOverlays.add(api)
            if (blockScroll) lockDocumentScroll()
            if (modal) {
                setBackgroundInert(true)
            }
            requestAnimationFrame(() => {
                panel.dataset.state = 'open'
                const target = panel.querySelector('[autofocus]') || ((modal || focusPanel) ? panel.querySelector(focusableSelector) || panel : null)
                target?.focus({preventScroll: true})
                if (target) panel.dataset.focus = 'true'
                if ((modal || focusPanel) && target) {
                    const focusVisible = root.dataset.focusVisibleOnOpen === 'true'
                    const interactionRoot = target.closest('[data-ui-interaction-ready="true"]')
                    ;[target, interactionRoot].filter(Boolean).forEach(element => {
                        if (focusVisible) element.dataset.focusVisible = 'true'
                        else delete element.dataset.focusVisible
                    })
                }
            })
            onOpen?.()
            AppUI.emit(root, `${root.dataset.uiComponent || 'overlay'}:open`)
        }

        const api = {root, trigger, panel, modal, blockScroll, closeOnScroll, dismissable, openedAt: 0, keyboardDismiss, open: show, close, isOpen: () => open, position: () => position(panel, trigger, {placement, offset, crossOffset, containerPadding, shouldFlip, matchWidth}), destroy: () => close(false, true)}
        if (toggleOnTriggerClick) trigger?.addEventListener('click', event => {
            event.preventDefault()
            root.dataset.focusVisibleOnOpen = String(event.detail === 0)
            open ? api.close(false) : api.open(event.target.closest(focusableSelector) || trigger)
        })
        panel.addEventListener('keydown', event => {
            if (event.key === 'Escape' && keyboardDismiss) { event.preventDefault(); api.close(true); return }
            if (!modal || event.key !== 'Tab') return
            const items = [...panel.querySelectorAll(focusableSelector)].filter(item => item.offsetParent !== null)
            if (!items.length) { event.preventDefault(); panel.focus(); return }
            const first = items[0], last = items[items.length - 1]
            if (event.shiftKey && document.activeElement === first) { event.preventDefault(); last.focus() }
            else if (!event.shiftKey && document.activeElement === last) { event.preventDefault(); first.focus() }
        })
        panel.addEventListener('focusout', event => {
            if (!panel.contains(event.relatedTarget)) panel.dataset.focus = 'false'
        })
        if (closeOnBlur) panel.addEventListener('focusout', event => {
            const nextTarget = event.relatedTarget
            if (nextTarget && (panel.contains(nextTarget) || trigger?.contains(nextTarget))) return
            queueMicrotask(() => {
                if (!panel.contains(document.activeElement) && !trigger?.contains(document.activeElement)) api.close(false)
            })
        })
        if (dismissable) panel.addEventListener('click', event => { if (event.target.closest('[data-overlay-close]')) api.close(true) })
        return api
    }

    document.addEventListener('pointerdown', event => {
        ;[...openOverlays].reverse().some(overlay => {
            if (overlay.modal || !overlay.dismissable || overlay.root.contains(event.target) || overlay.panel.contains(event.target)) return false
            overlay.close(false)
            return true
        })
    }, true)
    document.addEventListener('keydown', event => {
        if (event.key !== 'Escape') return
        const overlay = [...openOverlays].at(-1)
        if (!overlay?.keyboardDismiss) return
        event.preventDefault()
        event.stopPropagation()
        overlay.close(true)
    }, true)
    document.addEventListener('scroll', event => [...openOverlays].filter(item => !item.modal).forEach(item => {
        if (item.closeOnScroll && Date.now() - item.openedAt > 120 && !item.panel.contains(event.target)) item.close(false)
        else item.position()
    }), true)
    window.addEventListener('resize', () => [...openOverlays].filter(item => !item.modal).forEach(item => item.position()))

    AppUI.overlay = {create, position, focusableSelector}
})(window, document)
