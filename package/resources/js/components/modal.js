(function (window, document) {
    'use strict'

    const AppUI = window.AppUI
    const focusable = 'a[href],button:not(:disabled),input:not(:disabled),select:not(:disabled),textarea:not(:disabled),[tabindex]:not([tabindex="-1"])'
    let openCount = 0
    let modalSequence = 0

    AppUI.register('modal', '[data-slot="modal"]', root => {
        const layer = root.querySelector('[data-modal-layer]')
        if (!layer) return
        const panel = layer.querySelector('[data-modal-panel]')
        const overlay = layer.querySelector('[data-modal-overlay]')
        const id = root.dataset.modalId
        const isDismissable = root.dataset.isDismissable === 'true'
        const isKeyboardDismissDisabled = root.dataset.isKeyboardDismissDisabled === 'true'
        let restoreFocus = null
        let closeTimer = null

        overlay.dataset.backdropVariant = root.dataset.backdropVariant || 'opaque'
        layer.dataset.scroll = root.dataset.scroll || 'inside'
        layer.dataset.modalReady = 'true'

        const open = trigger => {
            if (!layer.hidden && layer.dataset.state === 'open') return
            if (closeTimer) {
                clearTimeout(closeTimer)
                closeTimer = null
            }
            const wasHidden = layer.hidden
            restoreFocus = trigger || document.activeElement
            layer.hidden = false
            layer.dataset.state = 'open'
            root.dataset.state = 'open'
            if (wasHidden) openCount += 1
            document.body.classList.add('app-modal-open')
            const title = panel.querySelector('[data-slot="modal-title"]')
            const description = panel.querySelector('[data-slot="modal-description"]')
            modalSequence += 1
            if (title) { title.id ||= `app-modal-title-${modalSequence}`; panel.setAttribute('aria-labelledby', title.id) }
            if (description) { description.id ||= `app-modal-description-${modalSequence}`; panel.setAttribute('aria-describedby', description.id) }
            requestAnimationFrame(() => (panel.querySelector(focusable) || panel).focus())
        }

        const close = () => {
            if (layer.hidden || layer.dataset.state === 'closing') return
            layer.dataset.state = 'closing'
            root.dataset.state = 'closing'
            closeTimer = window.setTimeout(() => {
                layer.hidden = true
                layer.dataset.state = 'closed'
                root.dataset.state = 'closed'
                openCount = Math.max(0, openCount - 1)
                if (!openCount) document.body.classList.remove('app-modal-open')
                if (restoreFocus && document.contains(restoreFocus)) restoreFocus.focus()
                closeTimer = null
            }, 200)
        }

        root.addEventListener('app-modal-open', event => open(event.detail?.trigger))
        root.addEventListener('click', event => {
            const trigger = event.target.closest('[data-slot="modal-trigger"]')
            if (!trigger || trigger.dataset.modalTarget || !root.contains(trigger)) return
            open(event.target.closest(focusable) || trigger)
        })
        layer.addEventListener('click', event => {
            if (event.target.closest('[data-modal-confirm]')) {
                AppUI.emit(root, 'modal-confirm')
            }
            if (event.target.closest('[data-modal-close]')) close()
        })
        layer.addEventListener('pointerdown', event => {
            if (isDismissable && !panel.contains(event.target)) close()
        }, true)
        layer.addEventListener('keydown', event => {
            if (event.key === 'Escape' && !isKeyboardDismissDisabled) {
                event.preventDefault()
                close()
                return
            }
            if (event.key !== 'Tab') return
            const items = [...panel.querySelectorAll(focusable)].filter(item => item.offsetParent !== null)
            if (!items.length) { event.preventDefault(); panel.focus(); return }
            const first = items[0], last = items[items.length - 1]
            if (event.shiftKey && document.activeElement === first) { event.preventDefault(); last.focus() }
            else if (!event.shiftKey && document.activeElement === last) { event.preventDefault(); first.focus() }
        })
        if (id) {
            window.addEventListener(`open-modal-${id}`, event => open(event.detail?.trigger))
            window.addEventListener(`close-modal-${id}`, close)
        }
        document.body.appendChild(layer)
        if (root.dataset.modalInitialOpen === 'true') open()
    })

    document.addEventListener('click', event => {
        const trigger = event.target.closest('[data-slot="modal-trigger"]')
        if (!trigger) return
        const restoreTarget = event.target.closest(focusable) || trigger
        const target = trigger.dataset.modalTarget
        if (target) {
            window.dispatchEvent(new CustomEvent(`open-modal-${target}`, {detail: {trigger: restoreTarget}}))
            return
        }
        trigger.closest('[data-slot="modal"]')?.dispatchEvent(new CustomEvent('app-modal-open', {
            detail: {trigger: restoreTarget},
        }))
    })
})(window, document)
