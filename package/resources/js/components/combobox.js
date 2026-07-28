(function (window, document) {
    'use strict'

    let openedCombobox = null

    const enabledOptions = function (controller) {
        return controller.options.filter(function (option) {
            return option.dataset.disabled !== 'true' && !option.hidden
        })
    }

    // Trigger 위치를 기준으로 목록이 화면 밖으로 나가지 않게 배치
    const placeContent = function (controller) {
        if (!controller || !controller.open) {
            return
        }

        const triggerRect = controller.trigger.getBoundingClientRect()
        const content = controller.content
        const offset = Number(content.dataset.sideOffset || 4)
        const viewportWidth = document.documentElement.clientWidth
        const viewportHeight = document.documentElement.clientHeight
        const bottomSpace = viewportHeight - triggerRect.bottom - offset - 8
        const topSpace = triggerRect.top - offset - 8

        content.style.minWidth = triggerRect.width + 'px'
        content.style.maxHeight = ''

        let side = content.dataset.preferredSide || 'bottom'
        const contentHeight = content.getBoundingClientRect().height

        if (side === 'bottom' && contentHeight > bottomSpace && topSpace > bottomSpace) {
            side = 'top'
        }

        if (side === 'top' && contentHeight > topSpace && bottomSpace > topSpace) {
            side = 'bottom'
        }

        content.style.maxHeight = Math.max(96, Math.min(384, side === 'top' ? topSpace : bottomSpace)) + 'px'

        const contentRect = content.getBoundingClientRect()
        let left = triggerRect.left

        left = Math.max(8, Math.min(left, viewportWidth - contentRect.width - 8))

        let top = side === 'top'
            ? triggerRect.top - contentRect.height - offset
            : triggerRect.bottom + offset

        top = Math.max(8, Math.min(top, viewportHeight - contentRect.height - 8))

        content.dataset.side = side
        content.style.left = left + 'px'
        content.style.top = top + 'px'
    }

    const closeCombobox = function (controller, returnFocus) {
        if (!controller || !controller.open) {
            return
        }

        controller.open = false
        controller.content.hidden = true
        controller.content.dataset.state = 'closed'
        controller.root.dataset.state = 'closed'
        controller.trigger.setAttribute('aria-expanded', 'false')
        controller.trigger.removeAttribute('aria-activedescendant')

        if (openedCombobox === controller) {
            openedCombobox = null
        }

        if (returnFocus) {
            controller.trigger.focus()
        }
    }

    const updateInputs = function (controller) {
        if (controller.multiple) {
            controller.inputContainer.innerHTML = ''

            controller.values.forEach(function (value) {
                const input = document.createElement('input')
                input.type = 'hidden'
                input.name = controller.inputName ? controller.inputName + '[]' : ''
                input.value = value
                controller.inputContainer.appendChild(input)
            })

            controller.inputContainer.dispatchEvent(new Event('input', {bubbles: true}))
            controller.inputContainer.dispatchEvent(new Event('change', {bubbles: true}))
            return
        }

        controller.input.value = controller.values[0] || ''
        controller.input.dispatchEvent(new Event('input', {bubbles: true}))
        controller.input.dispatchEvent(new Event('change', {bubbles: true}))
    }

    const selectedOptions = function (controller) {
        return controller.options.filter(function (option) {
            return controller.values.includes(String(option.dataset.value))
        })
    }

    const render = function (controller) {
        const selected = selectedOptions(controller)

        controller.options.forEach(function (option) {
            const isSelected = controller.values.includes(String(option.dataset.value))

            option.dataset.state = isSelected ? 'checked' : 'unchecked'
            option.setAttribute('aria-selected', isSelected ? 'true' : 'false')
        })

        if (controller.display) {
            controller.display.textContent = selected.length
                ? selected.map(function (option) {
                    return option.querySelector('[data-combobox-option-label]').textContent.trim()
                }).join(', ')
                : controller.display.dataset.placeholder
        }

        if (controller.chips) {
            controller.chips.innerHTML = ''

            selected.forEach(function (option) {
                const chip = document.createElement('span')
                const remove = document.createElement('button')

                chip.className = 'app-combobox-chip'
                chip.textContent = option.querySelector('[data-combobox-option-label]').textContent.trim()
                remove.type = 'button'
                remove.className = 'app-combobox-chip-remove'
                remove.dataset.comboboxRemove = option.dataset.value
                remove.setAttribute('aria-label', chip.textContent + ' 제거')
                remove.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="m6 6 12 12M18 6 6 18"/></svg>'
                chip.appendChild(remove)
                controller.chips.appendChild(chip)
            })
        }

        controller.root.dataset.invalid = controller.invalid ? 'true' : 'false'
    }

    const filterOptions = function (controller, query) {
        const text = String(query || '').trim().toLowerCase()
        let count = 0

        controller.options.forEach(function (option) {
            const label = option.querySelector('[data-combobox-option-label]').textContent.toLowerCase()
            const visible = !text || label.includes(text)

            option.hidden = !visible

            if (visible) {
                count += 1
            }
        })

        controller.empty.hidden = count > 0
        controller.activeIndex = -1
        controller.trigger.removeAttribute('aria-activedescendant')
        placeContent(controller)
    }

    const removeValue = function (controller, value) {
        controller.values = controller.values.filter(function (item) {
            return item !== String(value)
        })

        updateInputs(controller)
        render(controller)
    }

    const chooseOption = function (controller, option) {
        if (option.dataset.disabled === 'true') {
            return
        }

        const value = String(option.dataset.value)
        const selected = controller.values.includes(value)

        if (controller.multiple) {
            controller.values = selected
                ? controller.values.filter(function (item) {
                    return item !== value
                })
                : controller.values.concat(value)

            updateInputs(controller)
            render(controller)

            if (controller.inputTrigger) {
                controller.inputTrigger.value = ''
                filterOptions(controller, '')
                controller.inputTrigger.focus()
            }

            return
        }

        controller.values = selected && controller.triggerType === 'button' ? [] : [value]
        controller.invalid = false
        updateInputs(controller)
        render(controller)

        if (controller.inputTrigger) {
            controller.inputTrigger.value = selected && controller.triggerType === 'button'
                ? ''
                : option.querySelector('[data-combobox-option-label]').textContent.trim()
        }

        closeCombobox(controller, controller.triggerType === 'button')
    }

    const moveActiveOption = function (controller, direction) {
        const options = enabledOptions(controller)

        if (!options.length) {
            return
        }

        const nextIndex = (controller.activeIndex + direction + options.length) % options.length
        setActiveOption(controller, options, nextIndex)
    }

    const setActiveOption = function (controller, options, index) {
        controller.activeIndex = index

        options.forEach(function (option, optionIndex) {
            option.dataset.active = optionIndex === index ? 'true' : 'false'
        })

        const activeOption = options[index]
        controller.trigger.setAttribute('aria-activedescendant', activeOption.id)
        activeOption.scrollIntoView({block: 'nearest'})
    }

    const openCombobox = function (controller) {
        if (controller.disabled || controller.open) {
            return
        }

        if (openedCombobox && openedCombobox !== controller) {
            closeCombobox(openedCombobox, false)
        }

        controller.open = true
        openedCombobox = controller
        controller.content.hidden = false
        controller.content.dataset.state = 'open'
        controller.root.dataset.state = 'open'
        controller.trigger.setAttribute('aria-expanded', 'true')

        if (controller.searchInput && controller.triggerType === 'button') {
            controller.searchInput.value = ''
            filterOptions(controller, '')
        }

        placeContent(controller)

        requestAnimationFrame(function () {
            const target = controller.searchInput || controller.trigger
            target.focus()
        })
    }

    const init = function (root) {
        const controller = {
            root: root,
            trigger: root.querySelector('[data-combobox-trigger]'),
            content: root.querySelector('[data-combobox-content]'),
            options: Array.from(root.querySelectorAll('[data-combobox-option]')),
            empty: root.querySelector('[data-combobox-empty]'),
            input: root.querySelector('[data-combobox-value]'),
            inputContainer: root.querySelector('[data-combobox-inputs]'),
            display: root.querySelector('[data-combobox-display]'),
            chips: root.querySelector('[data-combobox-chips]'),
            inputWrapper: root.querySelector('.app-combobox-input-wrap'),
            inputTrigger: root.dataset.trigger === 'input' ? root.querySelector('[data-combobox-trigger]') : null,
            searchInput: root.dataset.trigger === 'button' ? root.querySelector('[data-combobox-search]') : null,
            triggerType: root.dataset.trigger,
            multiple: root.dataset.multiple === 'true',
            searchable: root.dataset.searchable === 'true',
            disabled: root.dataset.disabled === 'true',
            invalid: root.dataset.invalid === 'true',
            required: root.dataset.required === 'true',
            open: false,
            activeIndex: -1,
        }

        if (!controller.trigger || !controller.content || !controller.empty) {
            return
        }

        controller.inputName = controller.inputContainer ? controller.inputContainer.dataset.name : ''
        controller.values = controller.multiple
            ? Array.from(controller.inputContainer.querySelectorAll('input')).map(function (input) {
                return String(input.value)
            })
            : (controller.input && controller.input.value ? [String(controller.input.value)] : [])

        render(controller)

        controller.trigger.addEventListener('click', function () {
            if (controller.open && controller.triggerType === 'button') {
                closeCombobox(controller, false)
                return
            }

            openCombobox(controller)
        })

        controller.trigger.addEventListener('focus', function () {
            if (controller.triggerType === 'input') {
                openCombobox(controller)
            }
        })

        controller.trigger.addEventListener('input', function () {
            if (controller.triggerType !== 'input') {
                return
            }

            controller.invalid = false

            if (!controller.multiple && controller.values.length) {
                controller.values = []
                updateInputs(controller)
                render(controller)
            }

            openCombobox(controller)
            filterOptions(controller, controller.trigger.value)
        })

        controller.trigger.addEventListener('keydown', function (event) {
            if (event.key === 'ArrowDown') {
                event.preventDefault()
                openCombobox(controller)
                moveActiveOption(controller, 1)
                return
            }

            if (event.key === 'ArrowUp') {
                event.preventDefault()
                openCombobox(controller)
                moveActiveOption(controller, -1)
                return
            }

            if (event.key === 'Enter' && controller.open) {
                const options = enabledOptions(controller)
                const option = options[controller.activeIndex]

                if (option) {
                    event.preventDefault()
                    chooseOption(controller, option)
                }

                return
            }

            if ((event.key === 'Enter' || event.key === ' ') && controller.triggerType === 'button') {
                event.preventDefault()
                openCombobox(controller)
                return
            }

            if (event.key === 'Home' || event.key === 'End') {
                event.preventDefault()
                openCombobox(controller)
                const options = enabledOptions(controller)

                if (options.length) {
                    setActiveOption(controller, options, event.key === 'Home' ? 0 : options.length - 1)
                }

                return
            }

            if (event.key === 'Escape') {
                closeCombobox(controller, true)
                return
            }

            if (event.key === 'Backspace' && controller.multiple && controller.triggerType === 'input' && !controller.trigger.value) {
                removeValue(controller, controller.values[controller.values.length - 1])
            }
        })

        if (controller.searchInput) {
            controller.searchInput.addEventListener('input', function () {
                filterOptions(controller, controller.searchInput.value)
            })

            controller.searchInput.addEventListener('keydown', function (event) {
                if (event.key === 'ArrowDown') {
                    event.preventDefault()
                    moveActiveOption(controller, 1)
                }

                if (event.key === 'ArrowUp') {
                    event.preventDefault()
                    moveActiveOption(controller, -1)
                }

                if (event.key === 'Home' || event.key === 'End') {
                    event.preventDefault()
                    const options = enabledOptions(controller)

                    if (options.length) {
                        setActiveOption(controller, options, event.key === 'Home' ? 0 : options.length - 1)
                    }
                }

                if (event.key === 'Enter') {
                    const options = enabledOptions(controller)
                    const option = options[controller.activeIndex]

                    if (option) {
                        event.preventDefault()
                        chooseOption(controller, option)
                    }
                }

                if (event.key === 'Escape') {
                    closeCombobox(controller, true)
                }
            })
        }

        controller.options.forEach(function (option) {
            option.addEventListener('click', function () {
                chooseOption(controller, option)
            })

            option.addEventListener('mousemove', function () {
                const options = enabledOptions(controller)
                controller.activeIndex = options.indexOf(option)

                options.forEach(function (visibleOption, index) {
                    visibleOption.dataset.active = index === controller.activeIndex ? 'true' : 'false'
                })
            })
        })

        if (controller.chips) {
            controller.chips.addEventListener('click', function (event) {
                const button = event.target.closest('[data-combobox-remove]')

                if (!button) {
                    return
                }

                removeValue(controller, button.dataset.comboboxRemove)
            })
        }

        if (controller.inputWrapper && controller.inputTrigger) {
            controller.inputWrapper.addEventListener('click', function (event) {
                if (event.target.closest('[data-combobox-remove]')) {
                    return
                }

                controller.inputTrigger.focus()
            })
        }

        const form = root.closest('form')

        if (form && controller.required) {
            form.addEventListener('submit', function (event) {
                if (controller.values.length) {
                    return
                }

                event.preventDefault()
                controller.invalid = true
                render(controller)
                controller.trigger.focus()
            })
        }
    }

    document.addEventListener('click', function (event) {
        if (openedCombobox && !openedCombobox.root.contains(event.target) && !openedCombobox.content.contains(event.target)) {
            closeCombobox(openedCombobox, false)
        }
    })

    window.addEventListener('resize', function () {
        placeContent(openedCombobox)
    })

    window.addEventListener('scroll', function () {
        placeContent(openedCombobox)
    }, true)

    window.AppUI.register('combobox', '[data-slot="combobox"]', init)
})(window, document)
