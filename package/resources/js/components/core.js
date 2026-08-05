(function (window, document) {
    'use strict'

    const components = new Map()
    const controllers = new WeakMap()
    const pendingRoots = new Set()
    let started = false
    let scheduled = false

    const find = (root, selector) => {
        const result = []
        if (root instanceof Element && root.matches(selector)) result.push(root)
        if (root?.querySelectorAll) result.push(...root.querySelectorAll(selector))
        return result
    }

    const mount = (definition, root) => {
        find(root, definition.selector).forEach(element => {
            if (definition.mounted.has(element)) return
            const controller = definition.init(element) || {}
            controller.name ||= definition.name
            controller.root ||= element
            controllers.set(element, controller)
            element.appUI = controller
            definition.mounted.add(element)
            element.dataset.uiReady = 'true'
            element.dispatchEvent(new CustomEvent('app-ui:mounted', {bubbles: true, detail: {name: definition.name}}))
        })
    }

    const flush = () => {
        scheduled = false
        const roots = [...pendingRoots]
        pendingRoots.clear()
        roots.filter((root, index) => !roots.some((candidate, i) => i !== index && candidate.contains(root)))
            .forEach(root => components.forEach(definition => mount(definition, root)))
    }

    const schedule = node => {
        if (node?.nodeType !== Node.ELEMENT_NODE) return
        pendingRoots.add(node)
        if (!scheduled) { scheduled = true; queueMicrotask(flush) }
    }

    const syncInteractionState = root => {
        root.addEventListener('pointerenter', () => { if (!root.matches(':disabled,[aria-disabled="true"]')) root.dataset.hover = 'true' })
        root.addEventListener('pointerleave', () => { delete root.dataset.hover; delete root.dataset.pressed })
        root.addEventListener('pointerdown', () => { if (!root.matches(':disabled,[aria-disabled="true"]')) root.dataset.pressed = 'true' })
        root.addEventListener('pointerup', () => delete root.dataset.pressed)
        root.addEventListener('focusin', event => {
            root.dataset.focus = 'true'
            if (event.target.matches(':focus-visible')) root.dataset.focusVisible = 'true'
        })
        root.addEventListener('focusout', event => {
            if (event.relatedTarget && root.contains(event.relatedTarget)) return
            delete root.dataset.focus
            delete root.dataset.focusVisible
        })
    }

    const start = () => {
        if (started) return
        started = true
        components.forEach(definition => mount(definition, document))
        new MutationObserver(mutations => mutations.forEach(mutation => mutation.addedNodes.forEach(schedule)))
            .observe(document.body, {childList: true, subtree: true})
    }

    window.AppUI = {
        register(name, selector, init) {
            if (!name || !selector || typeof init !== 'function') throw new TypeError('AppUI.register(name, selector, init)')
            const definition = {name, selector, init, mounted: new WeakSet()}
            components.set(name, definition)
            if (started) mount(definition, document)
        },
        init(root = document, name = null) {
            if (name) { const definition = components.get(name); if (definition) mount(definition, root); return }
            components.forEach(definition => mount(definition, root))
        },
        get(element) { return controllers.get(element) || element?.appUI || null },
        emit(element, name, detail = {}) { element.dispatchEvent(new CustomEvent(`app-ui:${name}`, {bubbles: true, detail})) },
        interaction: syncInteractionState,
        registered() { return [...components.keys()] }
    }

    document.readyState === 'loading'
        ? document.addEventListener('DOMContentLoaded', start, {once: true})
        : start()
})(window, document)
