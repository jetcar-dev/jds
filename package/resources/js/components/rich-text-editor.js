(function (window, document) {
    'use strict'

    const AppUI = window.AppUI

    AppUI.register('rich-text-editor', '[data-slot="rich-text-editor"]', root => {
        const editor = root.querySelector('[data-rte-editor]')
        const input = root.querySelector('[data-rte-input]')
        const buttons = [...root.querySelectorAll('[data-rte-action]')]
        let savedRange = null

        if (!editor) return

        const selectionIsInside = selection => {
            const node = selection?.anchorNode
            return Boolean(node && (node === editor || editor.contains(node)))
        }

        const rememberSelection = () => {
            const selection = window.getSelection()
            if (!selection || !selection.rangeCount || !selectionIsInside(selection)) return
            savedRange = selection.getRangeAt(0).cloneRange()
        }

        const restoreSelection = () => {
            editor.focus()
            if (!savedRange) return
            const selection = window.getSelection()
            selection.removeAllRanges()
            selection.addRange(savedRange)
        }

        const refresh = () => {
            const selection = window.getSelection()
            if (!selectionIsInside(selection)) return
            buttons.forEach(button => {
                const state = button.dataset.rteState
                if (!state) return
                let active = false
                try { active = document.queryCommandState(state) } catch (_) {}
                button.setAttribute('aria-pressed', String(active))
            })
        }

        const sync = (notify = true) => {
            if (input) {
                input.value = editor.innerHTML
                if (notify) input.dispatchEvent(new Event('input', {bubbles: true}))
            }
            if (notify) AppUI.emit(root, 'rich-text-editor:change', {value: editor.innerHTML})
        }

        const clearFormatting = () => {
            const selection = window.getSelection()
            if (!selection || !selection.rangeCount) return
            const range = selection.getRangeAt(0)
            if (range.collapsed) {
                document.execCommand('removeFormat', false, null)
                document.execCommand('unlink', false, null)
                return
            }

            const wrapper = document.createElement('div')
            wrapper.appendChild(range.extractContents())
            wrapper.querySelectorAll('a,b,strong,i,em,u,s,strike,del,font,span').forEach(element => {
                const parent = element.parentNode
                while (element.firstChild) parent.insertBefore(element.firstChild, element)
                parent.removeChild(element)
            })
            const nodes = [...wrapper.childNodes]
            const fragment = document.createDocumentFragment()
            nodes.forEach(node => fragment.appendChild(node))
            range.insertNode(fragment)

            if (nodes.length) {
                range.setStartBefore(nodes[0])
                range.setEndAfter(nodes[nodes.length - 1])
                selection.removeAllRanges()
                selection.addRange(range)
            }
        }

        const run = button => {
            const action = button.dataset.rteAction
            const value = button.dataset.rteValue
            rememberSelection()
            restoreSelection()

            if (action === 'command') document.execCommand(value, false, null)
            if (action === 'block') document.execCommand('formatBlock', false, value)
            if (action === 'link') {
                const url = window.prompt('Link URL')
                if (url) document.execCommand('createLink', false, url)
            }
            if (action === 'clear') {
                clearFormatting()
            }

            rememberSelection()
            refresh()
            sync()
        }

        buttons.forEach(button => {
            button.addEventListener('click', () => run(button))
        })

        editor.addEventListener('input', () => { rememberSelection(); sync() })
        editor.addEventListener('keyup', () => { rememberSelection(); refresh() })
        editor.addEventListener('mouseup', () => { rememberSelection(); refresh() })
        editor.addEventListener('focus', refresh)
        document.addEventListener('selectionchange', () => { rememberSelection(); refresh() })

        const form = root.closest('form')
        form?.addEventListener('reset', () => window.setTimeout(() => {
            editor.innerHTML = input?.defaultValue || ''
            sync(false)
        }))

        sync(false)
    })
})(window, document)
