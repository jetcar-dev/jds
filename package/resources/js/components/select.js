(function (window, document) {
    'use strict'

    let openedSelect = null
    let selectSequence = 0
    let typeText = ''
    let typeTimer = null

    const getEnabledItems = function (controller) {
        return controller.items.filter(function (item) {
            return item.dataset.disabled !== 'true' && !item.hidden
        })
    }

    const restoreContent = function (controller) {
        const parent = controller.contentParent
        const nextSibling = controller.contentNextSibling

        if (!parent || controller.content.parentNode === parent) {
            return
        }

        if (nextSibling && nextSibling.parentNode === parent) {
            parent.insertBefore(controller.content, nextSibling)
        } else {
            parent.appendChild(controller.content)
        }
    }

    // Trigger 위치와 화면 여백을 기준으로 목록이 잘리지 않는 방향을 선택
    const placeContent = function (controller) {
        if (!controller.open) {
            return
        }

        if (!document.contains(controller.trigger)) {
            closeSelect(controller, false)
            return
        }

        const triggerRect = controller.trigger.getBoundingClientRect()
        const content = controller.content
        const offset = Number(content.dataset.sideOffset || 4)
        const viewportWidth = document.documentElement.clientWidth
        const viewportHeight = document.documentElement.clientHeight

        content.style.minWidth = triggerRect.width + 'px'
        content.style.maxHeight = ''

        const contentRect = content.getBoundingClientRect()
        const bottomSpace = viewportHeight - triggerRect.bottom - offset - 8
        const topSpace = triggerRect.top - offset - 8
        let side = content.dataset.preferredSide

        if (side === 'bottom' && contentRect.height > bottomSpace && topSpace > bottomSpace) {
            side = 'top'
        } else if (side === 'top' && contentRect.height > topSpace && bottomSpace > topSpace) {
            side = 'bottom'
        }

        const availableHeight = side === 'top' ? topSpace : bottomSpace
        content.style.maxHeight = Math.max(96, Math.min(384, availableHeight)) + 'px'

        const width = content.getBoundingClientRect().width
        const height = content.getBoundingClientRect().height
        let left = triggerRect.left

        if (content.dataset.align === 'center') {
            left = triggerRect.left + (triggerRect.width - width) / 2
        } else if (content.dataset.align === 'end') {
            left = triggerRect.right - width
        }

        left = Math.max(8, Math.min(left, viewportWidth - width - 8))

        let top = side === 'top'
            ? triggerRect.top - height - offset
            : triggerRect.bottom + offset

        top = Math.max(8, Math.min(top, viewportHeight - height - 8))

        content.dataset.side = side
        content.style.left = left + 'px'
        content.style.top = top + 'px'
    }

    const closeSelect = function (controller, returnFocus) {
        if (!controller || !controller.open) {
            return
        }

        controller.open = false
        controller.content.hidden = true
        controller.content.dataset.state = 'closed'
        controller.trigger.dataset.state = 'closed'
        controller.trigger.setAttribute('aria-expanded', 'false')

        restoreContent(controller)
        controller.content.removeAttribute('data-overlay-context')
        controller.content.style.removeProperty('left')
        controller.content.style.removeProperty('top')
        controller.content.style.removeProperty('min-width')
        controller.content.style.removeProperty('max-height')

        if (openedSelect === controller) {
            openedSelect = null
        }

        if (returnFocus) {
            controller.trigger.focus()
        }
    }

    const openSelect = function (controller) {
        if (controller.disabled || controller.open) {
            return
        }

        if (openedSelect && openedSelect !== controller) {
            closeSelect(openedSelect, false)
        }

        controller.open = true
        openedSelect = controller
        controller.contentParent = controller.content.parentNode
        controller.contentNextSibling = controller.content.nextSibling
        controller.modalLayer = controller.trigger.closest('[data-modal-layer]')
        document.body.appendChild(controller.content)
        if (controller.modalLayer) {
            controller.content.dataset.overlayContext = 'modal'
        }
        controller.content.hidden = false
        controller.content.dataset.state = 'open'
        controller.trigger.dataset.state = 'open'
        controller.trigger.setAttribute('aria-expanded', 'true')
        placeContent(controller)

        const items = getEnabledItems(controller)
        const selectedItem = items.find(function (item) {
            return item.dataset.state === 'checked'
        })

        requestAnimationFrame(function () {
            ;(selectedItem || items[0] || controller.content).focus()
        })
    }

    const renderValue = function (controller) {
        const selectedItems = controller.items.filter(function (item) {
            return controller.values.includes(String(item.dataset.value))
        })

        controller.items.forEach(function (item) {
            const selected = controller.values.includes(String(item.dataset.value))
            item.dataset.state = selected ? 'checked' : 'unchecked'
            item.setAttribute('aria-selected', selected ? 'true' : 'false')
        })

        controller.valueBox.replaceChildren()

        if (!selectedItems.length) {
            controller.valueBox.textContent = controller.valueBox.dataset.placeholder || ''
            controller.valueBox.classList.add('app-select-value-placeholder')
            controller.trigger.dataset.placeholder = 'true'
        } else if (!controller.multiple) {
            controller.valueBox.textContent = selectedItems[0].querySelector('[data-slot="select-item-label"]').textContent.trim()
            controller.valueBox.classList.remove('app-select-value-placeholder')
            controller.trigger.removeAttribute('data-placeholder')
        } else {
            controller.valueBox.classList.add('app-select-value-multiple')
            controller.valueBox.classList.remove('app-select-value-placeholder')
            controller.trigger.removeAttribute('data-placeholder')

            selectedItems.forEach(function (item) {
                const value = String(item.dataset.value)
                const label = item.querySelector('[data-slot="select-item-label"]').textContent.trim()
                const chip = document.createElement('span')
                const chipLabel = document.createElement('span')
                const removeButton = document.createElement('button')

                chip.className = 'app-select-chip'
                chipLabel.className = 'app-select-chip-label'
                chipLabel.textContent = label
                removeButton.type = 'button'
                removeButton.className = 'app-select-chip-remove'
                removeButton.dataset.selectRemove = value
                removeButton.setAttribute('aria-label', label + ' 선택 해제')
                removeButton.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12"/></svg>'
                chip.append(chipLabel, removeButton)
                controller.valueBox.append(chip)
            })
        }

        if (controller.multiple) {
            controller.inputContainer.replaceChildren()

            if (controller.inputName) {
                controller.values.forEach(function (value) {
                    const input = document.createElement('input')
                    input.type = 'hidden'
                    input.name = controller.inputName + '[]'
                    input.value = value
                    controller.inputContainer.append(input)
                })
            }
        } else {
            controller.input.value = controller.values[0] || ''
        }

        controller.root.dataset.value = JSON.stringify(controller.multiple
            ? controller.values
            : (controller.values[0] || ''))
    }

    const notifyChange = function (controller) {
        controller.root.dataset.invalid = 'false'
        controller.trigger.removeAttribute('aria-invalid')

        if (!controller.multiple) {
            controller.syncing = true
            controller.input.dispatchEvent(new Event('input', {bubbles: true}))
            controller.input.dispatchEvent(new Event('change', {bubbles: true}))
            controller.syncing = false
        }

        const selectedItems = controller.items.filter(function (item) {
            return controller.values.includes(String(item.dataset.value))
        })

        window.AppUI.emit(controller.root, 'select-change', {
            value: controller.multiple ? controller.values.slice() : (controller.values[0] || ''),
            values: controller.values.slice(),
            labels: selectedItems.map(function (item) {
                return item.querySelector('[data-slot="select-item-label"]').textContent.trim()
            })
        })
    }

    const selectItem = function (controller, item) {
        if (item.dataset.disabled === 'true') {
            return
        }

        const value = String(item.dataset.value)
        const selectedIndex = controller.values.indexOf(value)

        if (controller.multiple) {
            if (selectedIndex === -1) {
                controller.values.push(value)
            } else {
                controller.values.splice(selectedIndex, 1)
            }
        } else {
            controller.values = [value]
        }

        renderValue(controller)
        notifyChange(controller)

        if (!controller.multiple) {
            closeSelect(controller, true)
        }
    }

    const moveFocus = function (controller, direction) {
        const items = getEnabledItems(controller)
        if (!items.length) {
            return
        }

        const currentIndex = items.indexOf(document.activeElement)
        let nextIndex = currentIndex + direction

        if (nextIndex < 0) {
            nextIndex = items.length - 1
        } else if (nextIndex >= items.length) {
            nextIndex = 0
        }

        items[nextIndex].focus()
    }

    const findByTyping = function (controller, key) {
        clearTimeout(typeTimer)
        typeText += key.toLocaleLowerCase()
        typeTimer = setTimeout(function () {
            typeText = ''
        }, 500)

        const item = getEnabledItems(controller).find(function (option) {
            return option.textContent.trim().toLocaleLowerCase().startsWith(typeText)
        })

        if (item) {
            item.focus()
        }
    }

    window.AppUI.register('select', '[data-slot="select"][data-native="false"]', function (root) {
        const trigger = root.querySelector('[data-slot="select-trigger"]')
        const valueBox = root.querySelector('[data-slot="select-value"]')
        const content = root.querySelector('[data-slot="select-content"]')

        if (!trigger || !valueBox || !content) {
            return
        }

        selectSequence += 1

        const multiple = root.dataset.multiple === 'true'
        const input = root.querySelector('[data-select-input]')
        const inputContainer = root.querySelector('[data-select-inputs]')
        const initialValues = multiple
            ? Array.from(inputContainer?.querySelectorAll('input') || []).map(function (field) {
                return String(field.value)
            })
            : (input?.value === '' || input?.value === undefined ? [] : [String(input.value)])
        const controller = {
            root: root,
            trigger: trigger,
            valueBox: valueBox,
            content: content,
            items: Array.from(content.querySelectorAll('[data-slot="select-item"]')),
            input: input,
            inputContainer: inputContainer,
            inputName: inputContainer?.dataset.name || '',
            multiple: multiple,
            disabled: root.dataset.disabled === 'true',
            required: root.dataset.required === 'true',
            values: initialValues,
            open: false,
            syncing: false
        }

        controller.contentParent = content.parentNode
        controller.contentNextSibling = content.nextSibling
        controller.modalLayer = trigger.closest('[data-modal-layer]')

        root.appSelect = controller
        content.id = content.id || 'app-select-list-' + selectSequence
        content.dataset.preferredSide = content.dataset.side || 'bottom'
        content.setAttribute('aria-multiselectable', multiple ? 'true' : 'false')
        trigger.setAttribute('aria-controls', content.id)
        trigger.setAttribute('aria-required', controller.required ? 'true' : 'false')

        if (root.dataset.invalid === 'true') {
            trigger.setAttribute('aria-invalid', 'true')
        }

        content.querySelectorAll('[data-slot="select-group"]').forEach(function (group, index) {
            const label = group.querySelector(':scope > [data-slot="select-label"]')
            if (!label) {
                return
            }

            label.id = label.id || content.id + '-group-' + index
            group.setAttribute('aria-labelledby', label.id)
        })

        renderValue(controller)

        trigger.addEventListener('click', function () {
            controller.open ? closeSelect(controller, false) : openSelect(controller)
        })

        trigger.addEventListener('keydown', function (event) {
            if (['ArrowDown', 'ArrowUp', 'Enter', ' '].includes(event.key)) {
                event.preventDefault()
                openSelect(controller)
            }
        })

        content.addEventListener('click', function (event) {
            const item = event.target.closest('[data-slot="select-item"]')
            if (item) {
                selectItem(controller, item)
            }
        })

        content.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                event.preventDefault()
                closeSelect(controller, true)
            } else if (event.key === 'Tab') {
                closeSelect(controller, false)
            } else if (event.key === 'ArrowDown') {
                event.preventDefault()
                moveFocus(controller, 1)
            } else if (event.key === 'ArrowUp') {
                event.preventDefault()
                moveFocus(controller, -1)
            } else if (event.key === 'Home' || event.key === 'End') {
                event.preventDefault()
                const items = getEnabledItems(controller)
                const item = event.key === 'Home' ? items[0] : items[items.length - 1]
                item?.focus()
            } else if (event.key === 'Enter' || event.key === ' ') {
                const item = document.activeElement.closest('[data-slot="select-item"]')
                if (item && content.contains(item)) {
                    event.preventDefault()
                    selectItem(controller, item)
                }
            } else if (event.key.length === 1 && !event.ctrlKey && !event.metaKey && !event.altKey) {
                findByTyping(controller, event.key)
            }
        })

        valueBox.addEventListener('click', function (event) {
            const removeButton = event.target.closest('[data-select-remove]')
            if (!removeButton) {
                return
            }

            event.preventDefault()
            event.stopPropagation()
            controller.values = controller.values.filter(function (value) {
                return value !== removeButton.dataset.selectRemove
            })
            renderValue(controller)
            notifyChange(controller)
        })

        if (controller.input) {
            controller.input.addEventListener('change', function () {
                if (controller.syncing) {
                    return
                }

                controller.values = controller.input.value === '' ? [] : [String(controller.input.value)]
                renderValue(controller)
            })

            if (controller.input.id) {
                document.querySelectorAll('label[for="' + CSS.escape(controller.input.id) + '"]').forEach(function (label) {
                    label.addEventListener('click', function (event) {
                        event.preventDefault()
                        controller.open ? closeSelect(controller, false) : openSelect(controller)
                    })
                })
            }
        }

        const form = root.closest('form')
        if (form && controller.required) {
            form.addEventListener('submit', function (event) {
                if (controller.values.length) {
                    return
                }

                event.preventDefault()
                root.dataset.invalid = 'true'
                trigger.setAttribute('aria-invalid', 'true')
                trigger.focus()
                window.AppUI.emit(root, 'select-invalid')
            })
        }
        controller.modalLayer?.addEventListener('app-ui:modal-closing', function () {
            closeSelect(controller, false)
        })
    })

    document.addEventListener('pointerdown', function (event) {
        if (openedSelect
            && !openedSelect.root.contains(event.target)
            && !openedSelect.content.contains(event.target)) {
            closeSelect(openedSelect, false)
        }
    })

    window.addEventListener('resize', function () {
        if (openedSelect) {
            placeContent(openedSelect)
        }
    })

    document.addEventListener('scroll', function () {
        if (openedSelect) {
            placeContent(openedSelect)
        }
    }, true)
})(window, document)
