let iconsPromise

const loadIcons = () => {
    iconsPromise ??= Promise.all([
        import('../icons/iconify-bundle.js'),
        import('../icons/iconify-extra.js'),
    ]).then(([baseModule, extraModule]) => {
        const extraIcons = Object.fromEntries(
            Object.entries(extraModule.default).flatMap(([prefix, icons]) =>
                Object.entries(icons).map(([name, body]) => [
                    `${prefix}:${name}`,
                    {body, width: 24, height: 24},
                ]),
            ),
        )

        return {...baseModule.default, ...extraIcons}
    })
    return iconsPromise
}

const render = async elements => {
    const pending = elements.filter(element => !element.dataset.iconMounted)
    if (!pending.length) return

    const icons = await loadIcons()

    pending.forEach(element => {
        const key = element.dataset.icon
        const icon = icons[key]

        if (!icon) {
            console.warn(`[JDS] Icon not bundled: ${key}`)
            return
        }

        element.dataset.iconMounted = 'true'
        element.classList.add('app-icon')
        element.innerHTML = `<svg viewBox="0 0 ${icon.width} ${icon.height}" fill="currentColor" aria-hidden="true" focusable="false">${icon.body}</svg>`
    })
}

const selector = '[data-slot="icon"][data-icon]'

void render([...document.querySelectorAll(selector)])

new MutationObserver(records => records.forEach(record => record.addedNodes.forEach(node => {
    if (node.nodeType !== Node.ELEMENT_NODE) return

    void render([
        ...(node.matches?.(selector) ? [node] : []),
        ...node.querySelectorAll?.(selector) || [],
    ])
}))).observe(document.documentElement, {childList: true, subtree: true})
