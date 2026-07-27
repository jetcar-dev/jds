(function (window) {
    'use strict'

    const resize = function (textarea) {
        const maxRows = Number(textarea.dataset.maxRows)
        if (!maxRows) return
        textarea.style.height = 'auto'
        const style = window.getComputedStyle(textarea)
        const lineHeight = Number.parseFloat(style.lineHeight) || Number.parseFloat(style.fontSize) * 1.2
        const padding = Number.parseFloat(style.paddingTop) + Number.parseFloat(style.paddingBottom)
        const border = Number.parseFloat(style.borderTopWidth) + Number.parseFloat(style.borderBottomWidth)
        const cap = lineHeight * maxRows + padding + border
        const height = Math.min(textarea.scrollHeight, cap)
        textarea.style.height = height + 'px'
        textarea.style.overflowY = textarea.scrollHeight > cap ? 'auto' : 'hidden'
    }

    window.AppUI.register('textarea', '[data-slot="textarea"]', function (textarea) {
        if (!textarea.dataset.maxRows) return
        textarea.addEventListener('input', function () { resize(textarea) })
        resize(textarea)
    })
})(window)
