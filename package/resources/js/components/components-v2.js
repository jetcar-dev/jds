(function (window, document) {
    'use strict'
    const AppUI = window.AppUI

    AppUI.register('interactive', '[data-ui-interactive]', root => { AppUI.interaction(root); return {focus: () => root.focus()} })

    AppUI.register('button', '[data-ui-component="button"]', root => {
        const disabled = () => root.dataset.disabled === 'true'
        const ripple = event => {
            if (disabled() || root.dataset.disableRipple === 'true' || root.dataset.disableAnimation === 'true') return
            const rect = root.getBoundingClientRect()
            const size = Math.max(rect.width, rect.height) * 2
            const point = event.detail === 0
                ? {x: rect.width / 2, y: rect.height / 2}
                : {x: event.clientX - rect.left, y: event.clientY - rect.top}
            const wave = document.createElement('span')
            wave.className = 'app-button-ripple'
            wave.style.cssText = `width:${size}px;height:${size}px;left:${point.x - size / 2}px;top:${point.y - size / 2}px`
            wave.addEventListener('animationend', () => wave.remove(), {once: true})
            root.appendChild(wave)
        }
        root.addEventListener('pointerdown', event => {
            if (disabled()) return
            ripple(event)
            AppUI.emit(root, 'button:press-start', {pressed: true})
            AppUI.emit(root, 'button:press-change', {pressed: true})
        })
        root.addEventListener('pointerup', () => {
            if (disabled()) return
            AppUI.emit(root, 'button:press-end', {pressed: false})
            AppUI.emit(root, 'button:press-change', {pressed: false})
        })
        root.addEventListener('click', event => {
            if (disabled()) { event.preventDefault(); return }
            if (event.detail === 0) ripple(event)
            AppUI.emit(root, 'button:press', {originalEvent: event})
        })
        return {focus: () => root.focus(), press: () => { if (!disabled()) root.click() }, getValue: () => !disabled()}
    })

    AppUI.register('card', '[data-ui-component="card"]', root => {
        const pressable = () => root.dataset.pressable === 'true'
        const disabled = () => root.dataset.disabled === 'true'
        const listeners = new AbortController()
        AppUI.interaction(root)

        const ripple = event => {
            if (!pressable() || disabled() || root.dataset.disableRipple === 'true' || root.dataset.disableAnimation === 'true') return
            const rect = root.getBoundingClientRect()
            const size = Math.max(rect.width, rect.height) * 2
            const point = event.detail === 0
                ? {x: rect.width / 2, y: rect.height / 2}
                : {x: event.clientX - rect.left, y: event.clientY - rect.top}
            const wave = document.createElement('span')
            wave.className = 'app-button-ripple'
            wave.style.cssText = `width:${size}px;height:${size}px;left:${point.x - size / 2}px;top:${point.y - size / 2}px`
            wave.addEventListener('animationend', () => wave.remove(), {once: true})
            root.appendChild(wave)
        }
        root.addEventListener('pointerdown', event => {
            if (!pressable() || disabled()) return
            ripple(event)
            AppUI.emit(root, 'card:press-start', {pressed: true})
            AppUI.emit(root, 'card:press-change', {pressed: true})
        }, {signal: listeners.signal})
        root.addEventListener('pointerup', () => {
            if (!pressable() || disabled()) return
            AppUI.emit(root, 'card:press-end', {pressed: false})
            AppUI.emit(root, 'card:press-change', {pressed: false})
        }, {signal: listeners.signal})
        root.addEventListener('click', event => {
            if (!pressable() || disabled()) {
                if (disabled()) event.preventDefault()
                return
            }
            if (event.detail === 0) ripple(event)
            AppUI.emit(root, 'card:press', {originalEvent: event})
        }, {signal: listeners.signal})

        return {
            focus: () => { if (pressable() && !disabled()) root.focus() },
            press: () => { if (pressable() && !disabled()) root.click() },
            destroy: () => listeners.abort(),
        }
    })

    AppUI.register('alert', '[data-ui-component="alert"]', root => {
        const close = () => {
            if (root.dataset.visible === 'false') return
            root.dataset.visible = 'false'
            AppUI.emit(root, 'alert:visible-change', {visible: false})
            AppUI.emit(root, 'alert:close')
            const remove = () => root.remove()
            matchMedia('(prefers-reduced-motion: reduce)').matches
                ? remove()
                : root.addEventListener('transitionend', remove, {once: true})
            if (!matchMedia('(prefers-reduced-motion: reduce)').matches) setTimeout(remove, 220)
        }
        root.querySelector('[data-alert-close]')?.addEventListener('click', close)
        return {close, getValue: () => root.dataset.visible === 'true'}
    })

    AppUI.register('chip', '[data-ui-component="chip"]', root => {
        const closeButton = root.querySelector('[data-chip-close]')
        const listeners = new AbortController()
        let timer
        const getValue = () => root.querySelector('[data-slot="content"]')?.textContent?.trim() || ''
        const close = () => {
            if (root.dataset.disabled === 'true' || root.dataset.visible === 'false') return false
            root.dataset.visible = 'false'
            AppUI.emit(root, 'chip:close', {value: getValue()})
            const finish = () => { root.hidden = true }
            const reducedMotion = matchMedia('(prefers-reduced-motion: reduce)').matches
            if (root.dataset.disableAnimation === 'true' || reducedMotion) finish()
            else timer = setTimeout(finish, 180)
            return true
        }
        closeButton?.addEventListener('click', close, {signal: listeners.signal})
        return {
            close,
            getValue,
            focus: () => closeButton?.focus(),
            destroy: () => { clearTimeout(timer); listeners.abort() },
        }
    })

    AppUI.register('dismissible', '[data-dismissible="true"]', root => {
        root.querySelector('[data-dismiss]')?.addEventListener('click', () => { root.hidden = true; AppUI.emit(root, `${root.dataset.uiComponent}:close`) })
        return {close: () => { root.hidden = true }}
    })

    AppUI.register('image', '[data-slot="image-wrapper"]', root => {
        const image = root.querySelector('img')
        const fallback = root.querySelector('[data-image-fallback]')
        image?.addEventListener('load', () => { root.dataset.loaded = 'true'; if (fallback) fallback.hidden = true })
        image?.addEventListener('error', () => { root.dataset.loaded = 'false'; image.hidden = true; if (fallback) fallback.hidden = false })
        return {}
    })

    AppUI.register('snippet', '[data-ui-component="snippet"]', root => {
        const button = root.querySelector('[data-snippet-copy]')
        const copyIcon = root.querySelector('[data-slot="copy-icon"]')
        const checkIcon = root.querySelector('[data-slot="check-icon"]')
        const tooltip = button?.closest('[data-slot="tooltip-root"]')?.querySelector('[data-slot="tooltip"]')
        const tooltipLabel = tooltip?.textContent || '복사'
        const listeners = new AbortController()
        let timer
        const value = () => [...root.querySelectorAll('.app-snippet-code')].map(line => line.textContent || '').join('\n')
        const setCopied = copied => {
            root.dataset.copied = String(copied)
            copyIcon?.toggleAttribute('hidden', copied)
            checkIcon?.toggleAttribute('hidden', !copied)
            if (button) {
                button.setAttribute('aria-label', copied ? '복사됨' : '클립보드에 복사')
            }
            if (tooltip) tooltip.textContent = copied ? '복사됨' : tooltipLabel
        }
        const copy = async () => {
            if (root.dataset.disableCopy === 'true' || button?.disabled) return false
            const copiedValue = value()
            try {
                await navigator.clipboard.writeText(copiedValue)
                clearTimeout(timer)
                setCopied(true)
                AppUI.emit(root, 'snippet:copy', {value: copiedValue})
                timer = setTimeout(() => setCopied(false), Number(root.dataset.timeout || 2000))
                return true
            } catch (error) {
                AppUI.emit(root, 'snippet:copy-error', {value: copiedValue, error})
                return false
            }
        }
        button?.addEventListener('click', copy, {signal: listeners.signal})
        return {
            copy,
            getValue: value,
            focus: () => button?.focus(),
            destroy: () => {
                clearTimeout(timer)
                listeners.abort()
            },
        }
    })

    AppUI.register('scroll-shadow', '[data-slot="scroll-shadow"]', root => {
        const sync = () => { root.dataset.top = root.scrollTop > 1 ? 'true' : 'false'; root.dataset.bottom = root.scrollTop + root.clientHeight < root.scrollHeight - 1 ? 'true' : 'false' }
        root.addEventListener('scroll', sync, {passive: true}); new ResizeObserver(sync).observe(root); sync(); return {sync}
    })

    AppUI.register('avatar', '[data-ui-component="avatar"]', root => {
        const image = root.querySelector('[data-slot="img"]')
        const listeners = new AbortController()
        AppUI.interaction(root)

        const showFallback = visible => { root.dataset.fallbackVisible = String(visible) }
        const setLoaded = loaded => {
            root.dataset.loaded = String(loaded)
            if (image) image.dataset.loaded = String(loaded)
            showFallback(!loaded && (!image?.getAttribute('src') || root.dataset.showFallback === 'true'))
        }
        const sync = () => {
            if (!image?.getAttribute('src')) return setLoaded(false)
            if (!image.complete) {
                root.dataset.loaded = 'false'
                showFallback(root.dataset.showFallback === 'true')
                return
            }
            setLoaded(image.naturalWidth > 0)
        }
        const setSource = source => {
            if (!image) return
            const value = source == null ? '' : String(source)
            if (value) {
                image.hidden = false
                root.dataset.loaded = 'false'
                showFallback(root.dataset.showFallback === 'true')
                image.src = value
            } else {
                image.removeAttribute('src')
                image.hidden = true
                setLoaded(false)
            }
        }

        image?.addEventListener('load', () => setLoaded(true), {signal: listeners.signal})
        image?.addEventListener('error', () => setLoaded(false), {signal: listeners.signal})
        sync()

        return {
            getValue: () => image?.getAttribute('src') || '',
            setValue: setSource,
            setSource,
            showFallback,
            focus: () => root.focus(),
            destroy: () => listeners.abort(),
        }
    })

    AppUI.register('avatar-group', '[data-ui-component="avatar-group"]', root => {
        const count = root.querySelector('[data-slot="count"]')
        const avatars = () => [...root.querySelectorAll(':scope > [data-ui-component="avatar"]')]
        const refresh = () => {
            const items = avatars()
            const max = Math.max(1, Number(root.dataset.max || 5))
            const visibleCount = Math.min(max, items.length)
            items.forEach((avatar, index) => { avatar.hidden = index >= max })
            const total = root.dataset.total == null ? items.length : Math.max(items.length, Number(root.dataset.total || 0))
            const overflow = Math.max(0, total - visibleCount)
            if (count) {
                count.hidden = overflow === 0
                count.textContent = overflow ? `+${overflow}` : ''
                count.dataset.count = String(overflow)
                count.setAttribute('aria-label', overflow ? `${overflow}명 더 있음` : '추가 사용자 없음')
            }
            root.dataset.count = String(overflow)
            return overflow
        }
        const observer = new MutationObserver(refresh)
        observer.observe(root, {childList: true})
        refresh()
        return {getValue: refresh, refresh, destroy: () => observer.disconnect()}
    })

    AppUI.register('input', '[data-ui-component="input"], [data-ui-component="textarea"], [data-slot="number-input"] > [data-slot="input-wrapper"]', root => {
        const input = root.querySelector('input,textarea')
        const wrapper = root.querySelector('[data-slot="input-wrapper"]')
        const clear = root.querySelector('[data-input-clear]')
        const passwordToggle = root.querySelector('[data-password-toggle]')
        const hasStaticContent = root.dataset.hasStartContent === 'true' || root.dataset.hasEndContent === 'true'
        const componentName = root.dataset.uiComponent || 'input'
        const hasManagedValidation = ['input', 'textarea'].includes(componentName)
        const isTextarea = componentName === 'textarea'
        const explicitInvalid = root.dataset.invalid === 'true'
        let nativeValidationActive = false
        if (!input) return {}

        AppUI.interaction(root)
        let pointerFocus = false
        ;[clear, passwordToggle].filter(Boolean).forEach(action => {
            action.addEventListener('pointerdown', event => event.preventDefault())
        })
        wrapper?.addEventListener('pointerdown', event => {
            pointerFocus = true
            delete root.dataset.focusVisible
            if (event.target !== input && !event.target.closest('button')) {
                event.preventDefault()
                input.focus({preventScroll: true})
            }
            requestAnimationFrame(() => {
                delete root.dataset.focusVisible
                pointerFocus = false
            })
        })
        root.addEventListener('focusin', () => {
            if (pointerFocus) delete root.dataset.focusVisible
        })
        const sync = (notify = false) => {
            const filled = input.value.length > 0 || Boolean(input.placeholder) || hasStaticContent
            root.dataset.filled = String(filled)
            root.dataset.hasValue = String(input.value.length > 0)
            root.querySelector('[data-slot="input-wrapper"]')?.setAttribute('data-filled', String(filled))
            if (clear) clear.dataset.visible = String(input.value.length > 0)
            if (notify) AppUI.emit(root, `${componentName}:change`, {value: input.value})
        }
        const resizeTextarea = (notify = false) => {
            if (!isTextarea || root.dataset.disableAutosize === 'true') return
            const minRows = Math.max(1, Number(root.dataset.minRows || 3))
            const maxRows = Math.max(minRows, Number(root.dataset.maxRows || 8))
            const lineHeight = parseFloat(getComputedStyle(input).lineHeight) || 20
            const minHeight = minRows * lineHeight
            const maxHeight = maxRows * lineHeight
            input.style.height = 'auto'
            const naturalHeight = input.scrollHeight
            const height = Math.min(maxHeight, Math.max(minHeight, naturalHeight))
            input.style.height = `${height}px`
            input.style.overflowY = naturalHeight > maxHeight ? 'auto' : 'hidden'
            wrapper?.setAttribute('data-has-multiple-rows', String(height >= lineHeight * 2))
            if (notify) AppUI.emit(root, 'textarea:height-change', {height, rowHeight: lineHeight})
        }
        const notify = () => {
            input.dispatchEvent(new Event('input', {bubbles: true}))
            input.dispatchEvent(new Event('change', {bubbles: true}))
        }
        const ensureError = () => {
            let helper = root.querySelector('[data-slot="helper-wrapper"]')
            if (!helper) {
                helper = document.createElement('div')
                helper.dataset.slot = 'helper-wrapper'
                helper.className = 'app-input-helper'
                root.querySelector('[data-slot="main-wrapper"]')?.append(helper)
            }
            let error = helper.querySelector('[data-slot="error-message"]')
            if (!error) {
                error = document.createElement('div')
                error.id = `${input.id}-error`
                error.dataset.slot = 'error-message'
                error.dataset.nativeValidation = 'true'
                error.className = 'app-field-error'
                helper.append(error)
            }
            return error
        }
        const syncValidity = (notifyChange = false) => {
            if (!hasManagedValidation || (!nativeValidationActive && !explicitInvalid)) return
            const invalid = explicitInvalid || !input.validity.valid
            const wrapper = root.querySelector('[data-slot="input-wrapper"]')
            const description = root.querySelector('[data-slot="description"]')
            const error = invalid ? ensureError() : root.querySelector('[data-slot="error-message"]')

            root.dataset.invalid = String(invalid)
            wrapper?.setAttribute('data-invalid', String(invalid))
            if (description) description.hidden = invalid
            if (error) {
                if (error.dataset.nativeValidation === 'true') error.textContent = input.validationMessage
                error.hidden = !invalid
            }
            if (invalid) {
                input.setAttribute('aria-invalid', 'true')
                if (error?.id) input.setAttribute('aria-errormessage', error.id)
                input.removeAttribute('aria-describedby')
            } else {
                input.removeAttribute('aria-invalid')
                input.removeAttribute('aria-errormessage')
                if (description?.id) input.setAttribute('aria-describedby', description.id)
            }
            if (notifyChange) AppUI.emit(root, `${componentName}:validation-change`, {invalid, validationMessage: invalid ? input.validationMessage : ''})
        }

        clear?.addEventListener('click', () => {
            input.value = ''
            notify()
            input.focus()
            sync(false)
            resizeTextarea(true)
            AppUI.emit(root, `${componentName}:clear`, {value: ''})
        })
        passwordToggle?.addEventListener('click', event => {
            const visible = input.type === 'password'
            input.type = visible ? 'text' : 'password'
            event.currentTarget.dataset.visible = String(visible)
            event.currentTarget.setAttribute('aria-label', visible ? '비밀번호 숨기기' : '비밀번호 표시')
            event.currentTarget.querySelector('[data-password-visible-icon]')?.toggleAttribute('hidden', visible)
            event.currentTarget.querySelector('[data-password-hidden-icon]')?.toggleAttribute('hidden', !visible)
            input.focus()
        })
        input.addEventListener('invalid', event => {
            if (!hasManagedValidation) return
            event.preventDefault()
            nativeValidationActive = true
            syncValidity(true)
            requestAnimationFrame(() => {
                input.focus({preventScroll: true})
                delete root.dataset.focusVisible
            })
        })
        input.addEventListener('input', () => {
            sync(true)
            resizeTextarea(true)
            syncValidity(true)
        })
        sync(false)
        resizeTextarea(false)
        syncValidity(false)
        return {
            getValue: () => input.value,
            setValue: value => { input.value = value ?? ''; notify(); sync(false); resizeTextarea(true) },
            clear: () => clear?.click(),
            focus: () => input.focus(),
            destroy: () => {},
        }
    })

    AppUI.register('number-input', '[data-slot="number-input"]', root => {
        const input = root.querySelector('input[type="number"]')
        const change = delta => { input.stepUp(delta > 0 ? 1 : -1); input.dispatchEvent(new Event('input',{bubbles:true})); input.dispatchEvent(new Event('change',{bubbles:true})) }
        root.querySelector('[data-step-up]')?.addEventListener('click', () => change(1)); root.querySelector('[data-step-down]')?.addEventListener('click', () => change(-1))
        return {getValue: () => input.valueAsNumber, setValue: value => { input.value = value; input.dispatchEvent(new Event('input',{bubbles:true})) }, focus: () => input.focus()}
    })

    AppUI.register('checkbox', '[data-ui-component="checkbox"]', root => {
        const input = root.querySelector('input[type="checkbox"]')
        const listeners = new AbortController()
        const readOnly = () => root.dataset.readonly === 'true'
        let indeterminate = root.dataset.indeterminate === 'true'
        let currentChecked = input.checked
        input.indeterminate = indeterminate
        const sync = notify => {
            input.indeterminate = indeterminate
            const selected = input.checked || indeterminate
            root.dataset.selected = String(selected)
            root.dataset.indeterminate = String(indeterminate)
            if (notify) AppUI.emit(root, 'checkbox:change', {
                selected: input.checked,
                indeterminate,
                value: input.value,
            })
        }
        const dispatchChange = () => {
            input.dispatchEvent(new Event('input', {bubbles: true}))
            input.dispatchEvent(new Event('change', {bubbles: true}))
        }
        const setValue = (value, notify = true) => {
            if (value && typeof value === 'object') {
                input.checked = !!value.selected
                if (Object.hasOwn(value, 'indeterminate')) indeterminate = !!value.indeterminate
            } else {
                input.checked = !!value
            }
            currentChecked = input.checked
            sync(false)
            if (notify) dispatchChange()
        }

        input.addEventListener('click', event => {
            if (!readOnly()) return
            event.preventDefault()
            event.stopImmediatePropagation()
            input.checked = currentChecked
            sync(false)
        }, {signal: listeners.signal})
        input.addEventListener('change', () => {
            currentChecked = input.checked
            sync(true)
        }, {signal: listeners.signal})
        AppUI.interaction(root)
        sync(false)

        return {
            getValue: () => input.checked,
            setValue,
            setIndeterminate: (value, notify = true) => {
                indeterminate = !!value
                sync(false)
                if (notify) dispatchChange()
            },
            focus: () => input.focus(),
            destroy: () => listeners.abort(),
        }
    })

    AppUI.register('checkbox-group', '[data-ui-component="checkbox-group"]', root => {
        const listeners = new AbortController()
        const signal = listeners.signal
        const wrapper = root.querySelector(':scope > [data-slot="wrapper"]')
        const error = root.querySelector(':scope > [data-slot="error-message"]')
        const checkboxRoots = () => [...(wrapper?.querySelectorAll('[data-ui-component="checkbox"]') || [])]
        const inputs = () => checkboxRoots().map(checkbox => checkbox.querySelector('input[type="checkbox"]')).filter(Boolean)
        const groupName = root.dataset.name || ''
        const forcedInvalid = root.dataset.invalid === 'true'
        const required = root.dataset.required === 'true'
        let updating = false

        const parseInitialValue = () => {
            if (!root.hasAttribute('data-initial-value')) return null
            try { return JSON.parse(root.dataset.initialValue || '[]').map(String) } catch { return [] }
        }
        const getValue = () => inputs().filter(input => input.checked).map(input => input.value)
        const syncValidity = () => {
            const fields = inputs()
            const empty = getValue().length === 0
            fields.forEach(input => {
                input.required = false
                input.setCustomValidity('')
            })
            if (fields[0] && required && empty) fields[0].required = true
            if (fields[0] && forcedInvalid) fields[0].setCustomValidity(error?.textContent?.trim() || 'Invalid selection')
            const invalid = forcedInvalid || (required && empty && root.dataset.validationAttempted === 'true')
            root.dataset.invalid = String(invalid)
            if (invalid) root.setAttribute('aria-invalid', 'true')
            else root.removeAttribute('aria-invalid')
            checkboxRoots().forEach(checkbox => { checkbox.dataset.invalid = String(invalid) })
            if (error) error.hidden = !invalid
        }
        const sync = notify => {
            const value = getValue()
            root.dataset.selected = String(value.length > 0)
            root.dataset.value = JSON.stringify(value)
            syncValidity()
            if (notify) AppUI.emit(root, 'checkbox-group:change', {value})
        }
        const configureChildren = () => {
            checkboxRoots().forEach(checkbox => {
                const input = checkbox.querySelector('input[type="checkbox"]')
                if (!input) return
                if (groupName && !input.name) input.name = groupName.endsWith('[]') ? groupName : `${groupName}[]`
                input.dataset.groupInitiallyDisabled ??= String(input.disabled)
                const disabled = root.dataset.disabled === 'true' || input.dataset.groupInitiallyDisabled === 'true'
                input.disabled = disabled
                checkbox.dataset.disabled = String(disabled)
                if (root.dataset.readonly === 'true') {
                    checkbox.dataset.readonly = 'true'
                    input.setAttribute('aria-readonly', 'true')
                }
                checkbox.dataset.invalid = root.dataset.invalid
            })
        }
        const setValue = (values, notify = true) => {
            const selected = new Set((Array.isArray(values) ? values : []).map(String))
            const changed = []
            updating = true
            checkboxRoots().forEach(checkbox => {
                const input = checkbox.querySelector('input[type="checkbox"]')
                if (!input) return
                const checked = selected.has(String(input.value))
                if (input.checked !== checked) changed.push(input)
                AppUI.get(checkbox)?.setValue(checked, false)
            })
            if (notify) changed.forEach(input => {
                input.dispatchEvent(new Event('input', {bubbles: true}))
                input.dispatchEvent(new Event('change', {bubbles: true}))
            })
            updating = false
            sync(notify)
        }

        configureChildren()
        const initialValue = parseInitialValue()
        if (initialValue !== null) setValue(initialValue, false)
        else sync(false)

        wrapper?.addEventListener('change', event => {
            if (updating || !event.target.matches('input[type="checkbox"]')) return
            sync(true)
        }, {signal})
        wrapper?.addEventListener('invalid', () => {
            root.dataset.validationAttempted = 'true'
            syncValidity()
        }, {capture: true, signal})
        root.closest('form')?.addEventListener('submit', () => {
            root.dataset.validationAttempted = 'true'
            syncValidity()
        }, {signal})
        root.closest('form')?.addEventListener('reset', () => queueMicrotask(() => {
            checkboxRoots().forEach(checkbox => AppUI.get(checkbox)?.setValue(checkbox.querySelector('input').defaultChecked, false))
            sync(false)
        }), {signal})

        return {
            getValue,
            setValue,
            focus: () => inputs().find(input => !input.disabled)?.focus(),
            destroy: () => listeners.abort(),
        }
    })

    AppUI.register('radio', '[data-ui-component="radio"]', root => {
        const input = root.querySelector('input[type="radio"]')
        const listeners = new AbortController()
        const readOnly = () => root.dataset.readonly === 'true'
        let currentChecked = input.checked
        const sync = notify => {
            currentChecked = input.checked
            root.dataset.selected = String(input.checked)
            if (notify) AppUI.emit(root, 'radio:change', {selected: input.checked, value: input.value})
        }
        const setValue = (selected, notify = true) => {
            input.checked = !!selected
            sync(false)
            if (notify) {
                input.dispatchEvent(new Event('input', {bubbles: true}))
                input.dispatchEvent(new Event('change', {bubbles: true}))
            }
        }
        input.addEventListener('click', event => {
            if (!readOnly()) return
            event.preventDefault()
            event.stopImmediatePropagation()
            input.checked = currentChecked
            sync(false)
        }, {signal: listeners.signal})
        input.addEventListener('change', () => sync(true), {signal: listeners.signal})
        AppUI.interaction(root)
        sync(false)
        return {
            getValue: () => input.checked,
            setValue,
            sync: () => sync(false),
            focus: () => input.focus(),
            destroy: () => listeners.abort(),
        }
    })

    AppUI.register('radio-group', '[data-ui-component="radio-group"]', root => {
        const listeners = new AbortController()
        const signal = listeners.signal
        const wrapper = root.querySelector(':scope > [data-slot="wrapper"]')
        const error = root.querySelector(':scope > [data-slot="error-message"]')
        const radioRoots = () => [...(wrapper?.querySelectorAll('[data-ui-component="radio"]') || [])]
        const inputs = () => radioRoots().map(radio => radio.querySelector('input[type="radio"]')).filter(Boolean)
        const groupName = root.dataset.name || `radio-${Math.random().toString(36).slice(2)}`
        const forcedInvalid = root.dataset.invalid === 'true'
        const required = root.dataset.required === 'true'
        let updating = false

        const getValue = () => inputs().find(input => input.checked)?.value || ''
        const syncValidity = () => {
            const fields = inputs()
            fields.forEach(input => {
                input.required = false
                input.setCustomValidity('')
            })
            if (fields[0] && required) fields[0].required = true
            if (fields[0] && forcedInvalid) fields[0].setCustomValidity(error?.textContent?.trim() || 'Invalid selection')
            const invalid = forcedInvalid || (required && !getValue() && root.dataset.validationAttempted === 'true')
            root.dataset.invalid = String(invalid)
            if (invalid) root.setAttribute('aria-invalid', 'true')
            else root.removeAttribute('aria-invalid')
            radioRoots().forEach(radio => { radio.dataset.invalid = String(invalid) })
            if (error) error.hidden = !invalid
        }
        const sync = notify => {
            radioRoots().forEach(radio => AppUI.get(radio)?.sync())
            const value = getValue()
            root.dataset.selected = String(value !== '')
            root.dataset.value = value
            syncValidity()
            if (notify) AppUI.emit(root, 'radio-group:change', {value})
        }
        const configureChildren = () => {
            radioRoots().forEach(radio => {
                const input = radio.querySelector('input[type="radio"]')
                if (!input) return
                input.name = groupName
                input.dataset.groupInitiallyDisabled ??= String(input.disabled)
                const disabled = root.dataset.disabled === 'true' || input.dataset.groupInitiallyDisabled === 'true'
                input.disabled = disabled
                radio.dataset.disabled = String(disabled)
                if (root.dataset.readonly === 'true') {
                    radio.dataset.readonly = 'true'
                    input.setAttribute('aria-readonly', 'true')
                }
                radio.dataset.invalid = root.dataset.invalid
            })
        }
        const setValue = (value, notify = true) => {
            const selectedValue = value == null ? '' : String(value)
            const changed = []
            updating = true
            radioRoots().forEach(radio => {
                const input = radio.querySelector('input[type="radio"]')
                if (!input) return
                const selected = selectedValue !== '' && String(input.value) === selectedValue
                if (input.checked !== selected) changed.push(input)
                AppUI.get(radio)?.setValue(selected, false)
            })
            if (notify) changed.forEach(input => {
                input.dispatchEvent(new Event('input', {bubbles: true}))
                input.dispatchEvent(new Event('change', {bubbles: true}))
            })
            updating = false
            sync(notify)
        }

        configureChildren()
        if (root.hasAttribute('data-initial-value')) setValue(root.dataset.initialValue, false)
        else sync(false)

        wrapper?.addEventListener('change', event => {
            if (updating || !event.target.matches('input[type="radio"]')) return
            sync(true)
        }, {signal})
        wrapper?.addEventListener('invalid', () => {
            root.dataset.validationAttempted = 'true'
            syncValidity()
        }, {capture: true, signal})
        root.closest('form')?.addEventListener('reset', () => queueMicrotask(() => {
            radioRoots().forEach(radio => AppUI.get(radio)?.setValue(radio.querySelector('input').defaultChecked, false))
            sync(false)
        }), {signal})

        return {
            getValue,
            setValue,
            focus: () => (inputs().find(input => input.checked && !input.disabled) || inputs().find(input => !input.disabled))?.focus(),
            destroy: () => listeners.abort(),
        }
    })

    AppUI.register('switch', '[data-ui-component="switch"]', root => {
        const input = root.querySelector('input[type="checkbox"]')
        const listeners = new AbortController()
        const readonly = () => root.dataset.readonly === 'true'
        const sync = notify => {
            root.dataset.selected = String(input.checked)
            if (notify) AppUI.emit(root, 'switch:change', {selected: input.checked, value: input.value})
        }
        const setValue = (value, notify = false) => {
            input.checked = !!value
            sync(false)
            if (notify) {
                input.dispatchEvent(new Event('input', {bubbles: true}))
                input.dispatchEvent(new Event('change', {bubbles: true}))
            }
        }
        root.addEventListener('click', event => { if (readonly()) event.preventDefault() }, {capture: true, signal: listeners.signal})
        root.addEventListener('keydown', event => { if (readonly() && event.key === ' ') event.preventDefault() }, {signal: listeners.signal})
        input.addEventListener('change', () => sync(true), {signal: listeners.signal})
        root.closest('form')?.addEventListener('reset', () => queueMicrotask(() => sync(false)), {signal: listeners.signal})
        AppUI.interaction(root)
        sync(false)
        return {getValue: () => input.checked, setValue, focus: () => input.focus(), destroy: () => listeners.abort()}
    })

    AppUI.register('slider', '[data-slot="slider"]', root => {
        const inputs = [...root.querySelectorAll('input[type="range"]')], fill = root.querySelector('[data-slider-fill]'), thumbs = [...root.querySelectorAll('[data-slider-thumb]')]
        const sync = notify => { const min = Number(inputs[0]?.min || 0), max = Number(inputs[0]?.max || 100), values = inputs.map(input => Number(input.value)), percentages = values.map(value => (value-min)/(max-min)*100); root.style.setProperty('--range-start',`${Math.min(...percentages)}%`); root.style.setProperty('--range-end',`${Math.max(...percentages)}%`); thumbs.forEach((thumb,index) => thumb.style.setProperty('--thumb-position',`${percentages[index]}%`)); root.querySelector('[data-slider-output]')?.replaceChildren(document.createTextNode(values.join(' – '))); if (notify) AppUI.emit(root,'slider:change',{value:values.length === 1 ? values[0] : values}) }
        inputs.forEach(input => input.addEventListener('input',() => sync(true))); sync(false)
        return {getValue: () => inputs.length === 1 ? Number(inputs[0].value) : inputs.map(input => Number(input.value)), setValue: value => { (Array.isArray(value)?value:[value]).forEach((item,index) => { if(inputs[index]) inputs[index].value=item }); sync(false) }}
    })

    AppUI.register('input-otp', '[data-slot="input-otp"]', root => {
        const slots = [...root.querySelectorAll('[data-otp-slot]')], input = root.querySelector('[data-otp-input]')
        const sync = notify => { input.value = slots.map(slot => slot.value).join(''); if (notify) { input.dispatchEvent(new Event('input',{bubbles:true})); input.dispatchEvent(new Event('change',{bubbles:true})); AppUI.emit(root,'input-otp:change',{value:input.value}) } }
        slots.forEach((slot,index) => { slot.addEventListener('input',() => { slot.value = slot.value.slice(-1); if(slot.value) slots[index+1]?.focus(); sync(true) }); slot.addEventListener('keydown',event => { if(event.key==='Backspace'&&!slot.value) slots[index-1]?.focus(); if(event.key==='ArrowLeft') slots[index-1]?.focus(); if(event.key==='ArrowRight') slots[index+1]?.focus() }); slot.addEventListener('paste',event => { event.preventDefault(); [...event.clipboardData.getData('text').slice(0,slots.length)].forEach((character,i)=>slots[i].value=character); sync(true); slots[Math.min(event.clipboardData.getData('text').length,slots.length)-1]?.focus() }) }); sync(false)
        return {getValue:()=>input.value,setValue:value=>{ [...String(value||'')].forEach((character,index)=>{if(slots[index])slots[index].value=character});sync(false)},focus:()=>slots[0]?.focus()}
    })

    AppUI.register('accordion', '[data-slot="accordion"]', root => {
        const items = () => [...root.querySelectorAll(':scope > [data-ui-part="accordion-item"]')]
        const mode = root.dataset.selectionMode || 'single'
        const behavior = root.dataset.selectionBehavior || 'toggle'
        const disabledKeys = new Set(JSON.parse(root.dataset.disabledKeys || '[]').map(String))
        const listeners = new AbortController()
        const inherited = (item, name) => item.dataset[name] === 'inherit' ? root.dataset[name] === 'true' : item.dataset[name] === 'true'
        const selected = () => items().filter(item => item.dataset.open === 'true')
        const emit = () => AppUI.emit(root, 'accordion:change', {keys: selected().map(item => item.dataset.value)})
        const setOpen = (item, open, notify = true, force = false, animate = true) => {
            if (!item || (!force && item.dataset.disabled === 'true') || mode === 'none') return false
            if (open && (mode === 'single' || behavior === 'replace')) items().forEach(other => { if (other !== item) setOpen(other, false, false, true) })
            if (!force && !open && root.dataset.disallowEmptySelection === 'true' && selected().length === 1 && item.dataset.open === 'true') return false
            const trigger = item.querySelector('[data-slot="trigger"]')
            const heading = item.querySelector('[data-slot="heading"]')
            const motion = item.querySelector('[data-slot="content-motion"]')
            const content = item.querySelector('[data-slot="content"]')
            const stateParts = item.querySelectorAll('[data-slot="title"], [data-slot="subtitle"], [data-slot="indicator"]')
            const fromHeight = motion?.hidden ? 0 : motion?.getBoundingClientRect().height || 0
            const fromOpacity = motion?.hidden ? 0 : Number.parseFloat(getComputedStyle(motion).opacity) || 0
            motion?._accordionAnimation?.cancel()
            item.dataset.open = String(open)
            trigger?.setAttribute('data-open', String(open))
            trigger?.setAttribute('aria-expanded', String(open))
            heading?.setAttribute('data-open', String(open))
            motion?.setAttribute('data-open', String(open))
            content?.setAttribute('data-open', String(open))
            stateParts.forEach(part => part.setAttribute('data-open', String(open)))
            content?.setAttribute('aria-hidden', String(!open))
            if (content) content.inert = !open
            const keepMounted = inherited(item, 'keepContentMounted')
            const noAnimation = inherited(item, 'disableAnimation') || matchMedia('(prefers-reduced-motion: reduce)').matches
            if (motion) {
                if (open) motion.hidden = false
                if (noAnimation || !animate) {
                    motion.hidden = !open && !keepMounted
                    motion.style.removeProperty('height')
                    motion.style.removeProperty('opacity')
                    motion.style.removeProperty('overflow')
                } else {
                    const targetHeight = open ? content?.scrollHeight || 0 : 0
                    const animation = motion.animate([
                        {height: `${fromHeight}px`, opacity: fromOpacity, overflow: 'hidden'},
                        {height: `${targetHeight}px`, opacity: open ? 1 : 0, overflow: 'hidden'},
                    ], {duration: 250, easing: 'cubic-bezier(.32,.72,0,1)', fill: 'forwards'})
                    motion._accordionAnimation = animation
                    animation.onfinish = () => {
                        if (motion._accordionAnimation !== animation) return
                        motion._accordionAnimation = null
                        motion.hidden = item.dataset.open !== 'true' && !keepMounted
                        motion.style.removeProperty('height')
                        motion.style.removeProperty('opacity')
                        motion.style.removeProperty('overflow')
                        animation.cancel()
                    }
                }
            }
            if (notify) emit()
            return true
        }
        const initialRaw = JSON.parse(root.dataset.selectedKeys || '[]')
        const initialKeys = initialRaw === 'all' ? new Set(items().map(item => String(item.dataset.value))) : new Set((Array.isArray(initialRaw) ? initialRaw : [initialRaw]).map(String))
        items().forEach(item => {
            const trigger = item.querySelector('[data-slot="trigger"]')
            const itemDisabled = root.dataset.disabled === 'true' || item.dataset.itemDisabled === 'true' || disabledKeys.has(String(item.dataset.value))
            item.dataset.disabled = String(itemDisabled)
            trigger.dataset.disabled = String(itemDisabled)
            item.querySelector('[data-slot="heading"]')?.setAttribute('data-disabled', String(itemDisabled))
            item.querySelectorAll('[data-slot="title"], [data-slot="subtitle"], [data-slot="indicator"], [data-slot="content"]').forEach(part => part.setAttribute('data-disabled', String(itemDisabled)))
            trigger.disabled = itemDisabled
            trigger.setAttribute('aria-disabled', String(itemDisabled))
            ;['compact', 'hideIndicator', 'disableAnimation', 'disableIndicatorAnimation', 'keepContentMounted'].forEach(name => {
                if (item.dataset[name] === 'inherit') item.dataset[name] = root.dataset[name]
            })
            AppUI.interaction(trigger)
            trigger.addEventListener('click', () => setOpen(item, item.dataset.open !== 'true'), {signal: listeners.signal})
            const shouldOpen = initialKeys.size ? initialKeys.has(String(item.dataset.value)) : item.dataset.open === 'true'
            setOpen(item, shouldOpen, false, true, false)
        })
        root.addEventListener('keydown', event => {
            if (!['ArrowDown', 'ArrowUp', 'Home', 'End'].includes(event.key)) return
            const triggers = items().filter(item => item.dataset.disabled !== 'true').map(item => item.querySelector('[data-slot="trigger"]')).filter(Boolean)
            const index = triggers.indexOf(document.activeElement)
            if (index < 0 || !triggers.length) return
            event.preventDefault()
            const next = event.key === 'Home' ? triggers[0] : event.key === 'End' ? triggers.at(-1) : triggers[(index + (event.key === 'ArrowDown' ? 1 : -1) + triggers.length) % triggers.length]
            next.focus()
        }, {signal: listeners.signal})
        const setValue = values => {
            const keys = values === 'all' ? new Set(items().map(item => String(item.dataset.value))) : new Set((Array.isArray(values) ? values : [values]).filter(value => value != null).map(String))
            items().forEach(item => setOpen(item, keys.has(String(item.dataset.value)), false, true))
        }
        return {
            getValue: () => selected().map(item => item.dataset.value),
            setValue,
            open: value => setOpen(items().find(item => String(item.dataset.value) === String(value)), true),
            close: value => setOpen(items().find(item => String(item.dataset.value) === String(value)), false),
            toggle: value => { const item = items().find(item => String(item.dataset.value) === String(value)); return setOpen(item, item?.dataset.open !== 'true') },
            focus: () => items().find(item => item.dataset.disabled !== 'true')?.querySelector('[data-slot="trigger"]')?.focus(),
            destroy: () => listeners.abort()
        }
    })

    AppUI.register('tabs', '[data-ui-component="tabs"]', root => {
        const list = root.querySelector(':scope > [data-slot="base"] > [data-slot="tabList"]')
        const cursor = list?.querySelector(':scope > [data-slot="cursor"]')
        const tabs = () => list ? [...list.querySelectorAll(':scope > [data-slot="tab"]')] : []
        const input = root.querySelector('[data-tabs-input]')
        const disabledKeys = new Set(JSON.parse(root.dataset.disabledKeys || '[]').map(String))
        const listeners = new AbortController()
        const panelRecords = [...root.querySelectorAll(':scope > [data-slot="panel"]')].map(panel => ({panel, marker: document.createComment(`tab-panel:${panel.dataset.value}`)}))
        let resizeObserver
        const enabledTabs = () => tabs().filter(tab => tab.dataset.disabled !== 'true')
        const updateCursor = () => {
            if (!cursor || root.dataset.disableAnimation === 'true' || root.dataset.disableCursorAnimation === 'true') return
            const tab = tabs().find(tab => tab.dataset.selected === 'true')
            if (!tab) { cursor.removeAttribute('data-initialized'); return }
            const underlined = root.dataset.variant === 'underlined'
            cursor.style.left = `${tab.offsetLeft + (underlined ? tab.offsetWidth * .1 : 0)}px`
            cursor.style.top = `${tab.offsetTop + (underlined ? tab.offsetHeight - 2 : 0)}px`
            cursor.style.width = `${tab.offsetWidth * (underlined ? .8 : 1)}px`
            cursor.style.height = underlined ? '2px' : `${tab.offsetHeight}px`
            cursor.dataset.initialized = 'true'
            requestAnimationFrame(() => { cursor.dataset.animated = 'true' })
        }
        const syncPanels = value => panelRecords.forEach(record => {
            const selected = String(record.panel.dataset.value) === String(value)
            record.panel.dataset.selected = String(selected)
            record.panel.dataset.inert = String(!selected)
            record.panel.hidden = !selected
            record.panel.inert = !selected
            if (selected) {
                if (record.marker.isConnected) record.marker.replaceWith(record.panel)
            } else if (root.dataset.destroyInactiveTabPanel === 'true' && record.panel.isConnected) {
                record.panel.replaceWith(record.marker)
            }
        })
        const setValue = (value, notify = false) => {
            const next = tabs().find(tab => String(tab.dataset.value) === String(value) && tab.dataset.disabled !== 'true') || enabledTabs()[0]
            if (!next) return
            value = next.dataset.value
            tabs().forEach((tab, index) => {
                const selected = tab === next
                tab.dataset.selected = String(selected)
                tab.setAttribute('aria-selected', String(selected))
                tab.tabIndex = selected ? 0 : -1
                const tabId = `${root.id}-tab-${index}`
                const panel = panelRecords.find(record => String(record.panel.dataset.value) === String(tab.dataset.value))?.panel
                tab.id = tabId
                if (panel) {
                    const panelId = `${root.id}-panel-${index}`
                    panel.id = panelId
                    panel.setAttribute('aria-labelledby', tabId)
                    tab.setAttribute('aria-controls', panelId)
                } else tab.removeAttribute('aria-controls')
            })
            root.dataset.value = value
            syncPanels(value)
            if (input) {
                input.value = value
                if (notify) {
                    input.dispatchEvent(new Event('input', {bubbles: true}))
                    input.dispatchEvent(new Event('change', {bubbles: true}))
                }
            }
            requestAnimationFrame(updateCursor)
            if (notify && list) list.scrollTo({left: Math.max(0, next.offsetLeft - list.clientWidth / 2 + next.offsetWidth / 2), top: Math.max(0, next.offsetTop - list.clientHeight / 2 + next.offsetHeight / 2), behavior: 'smooth'})
            if (notify) AppUI.emit(root, 'tabs:change', {value})
        }
        tabs().forEach(tab => {
            const disabled = root.dataset.disabled === 'true' || tab.dataset.itemDisabled === 'true' || disabledKeys.has(String(tab.dataset.value))
            tab.dataset.disabled = String(disabled)
            tab.setAttribute('aria-disabled', String(disabled))
            if (tab.tagName === 'BUTTON') tab.disabled = disabled
            if (disabled) tab.tabIndex = -1
            AppUI.interaction(tab)
            const selectionEvent = root.dataset.shouldSelectOnPressUp === 'false' ? 'pointerdown' : 'click'
            tab.addEventListener(selectionEvent, event => {
                if (disabled) { event.preventDefault(); return }
                setValue(tab.dataset.value, true)
            }, {signal: listeners.signal})
        })
        list?.setAttribute('aria-orientation', root.dataset.orientation)
        root.addEventListener('keydown', event => {
            const vertical = root.dataset.orientation === 'vertical'
            const allowed = vertical ? ['ArrowUp', 'ArrowDown', 'Home', 'End'] : ['ArrowLeft', 'ArrowRight', 'Home', 'End']
            if (!allowed.includes(event.key)) return
            const all = enabledTabs(), current = all.indexOf(document.activeElement)
            if (current < 0 || !all.length) return
            event.preventDefault()
            const forward = event.key === (vertical ? 'ArrowDown' : 'ArrowRight')
            const next = event.key === 'Home' ? all[0] : event.key === 'End' ? all.at(-1) : all[(current + (forward ? 1 : -1) + all.length) % all.length]
            next.focus()
            if (root.dataset.keyboardActivation !== 'manual') setValue(next.dataset.value, true)
        }, {signal: listeners.signal})
        resizeObserver = new ResizeObserver(updateCursor)
        if (list) resizeObserver.observe(list)
        tabs().forEach(tab => resizeObserver.observe(tab))
        setValue(root.dataset.value || tabs().find(tab => tab.dataset.selected === 'true')?.dataset.value || enabledTabs()[0]?.dataset.value)
        return {
            getValue: () => root.dataset.value,
            setValue: value => setValue(value, true),
            focus: () => tabs().find(tab => tab.dataset.selected === 'true')?.focus(),
            destroy: () => { listeners.abort(); resizeObserver?.disconnect() }
        }
    })

    AppUI.register('listbox', '[data-ui-component="listbox"]', base => {
        const root = base.querySelector('[data-slot="list"]')
        const name = base.dataset.name
        const valuesBox = base.querySelector('[data-listbox-form-values]')
        const proxy = base.querySelector('[data-listbox-input]')
        if (!root) return {}

        const disabledKeys = new Set(JSON.parse(base.dataset.disabledKeys || '[]').map(String))
        root.style.setProperty('--listbox-max-height', `${Math.max(1, Number(base.dataset.maxListboxHeight || 256))}px`)
        root.querySelectorAll('[role="option"]').forEach(item => {
            AppUI.interaction(item)
            item.dataset.selectable = String(base.dataset.selectionMode !== 'none' || base.dataset.shouldHighlightOnFocus === 'true')
            if (disabledKeys.has(String(item.dataset.value))) {
                item.dataset.disabled = 'true'
                item.setAttribute('aria-disabled', 'true')
            }
            if (base.dataset.virtualized === 'true') item.style.containIntrinsicSize = `0 ${Math.max(1, Number(base.dataset.itemHeight || 40))}px`
        })

        const sync = selected => {
            const values = selected.map(item => item.dataset.value)
            const value = base.dataset.selectionMode === 'multiple' ? values : (values[0] || '')
            base.dataset.values = JSON.stringify(values)
            if (valuesBox && name) {
                valuesBox.replaceChildren(...values.map(selectedValue => {
                    const input = document.createElement('input')
                    input.type = 'hidden'
                    input.name = name + (base.dataset.selectionMode === 'multiple' ? '[]' : '')
                    input.value = selectedValue
                    return input
                }))
            }
            if (proxy) {
                proxy.value = Array.isArray(value) ? JSON.stringify(value) : value
                proxy.dispatchEvent(new Event('input', {bubbles: true}))
                proxy.dispatchEvent(new Event('change', {bubbles: true}))
            }
            AppUI.emit(base, 'listbox:change', {value, values})
        }

        const collection = AppUI.collection(root, {
            selectionMode: base.dataset.selectionMode,
            selectionBehavior: base.dataset.selectionBehavior,
            disallowEmptySelection: base.dataset.disallowEmptySelection === 'true',
            shouldFocusWrap: base.dataset.shouldFocusWrap === 'true',
            onSelectionChange: sync,
        })
        const initialValues = JSON.parse(base.dataset.values || '[]').map(String)
        if (initialValues.length) collection.setValue(initialValues)

        root.addEventListener('click', event => {
            const item = event.target.closest('[role="option"]')
            if (!item || item.dataset.disabled === 'true' || item.dataset.readonly === 'true') return
            AppUI.emit(base, 'listbox:action', {key: item.dataset.value})
        })

        return {
            getValue: () => base.dataset.selectionMode === 'multiple' ? collection.getValue() : (collection.getValue()[0] || ''),
            setValue: value => { collection.setValue(value, true) },
            focus: () => collection.focus(collection.selected()[0] || collection.items()[0]),
            destroy: collection.destroy,
        }
    })

    AppUI.register('select', '[data-ui-component="select"]', root => {
        const trigger = root.querySelector('[data-slot="trigger"]')
        const popover = root.querySelector('[data-select-popover]')
        const listbox = popover?.querySelector('[data-slot="listbox"]')
        const input = root.querySelector('[data-select-input]')
        const valueBox = root.querySelector('[data-slot="value"]')
        const clearButton = root.querySelector('[data-select-clear]')
        const validation = root.querySelector('[data-select-validation]')
        const listboxWrapper = popover?.querySelector('[data-slot="listboxWrapper"]')
        const emptyContent = popover?.querySelector('[data-slot="emptyContent"]')
        const formValues = root.querySelector('[data-select-form-values]')
        const multiple = root.dataset.selectionMode === 'multiple'
        const controlledInvalid = root.dataset.invalid === 'true'
        const name = formValues?.dataset.name
        if (!trigger || !popover || !listbox || !input || !valueBox) return {}

        AppUI.interaction(root)
        const listeners = new AbortController()
        const disabledKeys = new Set(JSON.parse(root.dataset.disabledKeys || '[]').map(String))
        const itemText = item => String(item?.dataset.textValue || item?.querySelector('[data-label]')?.textContent || '').trim()
        const allItems = () => [...listbox.querySelectorAll('[role="option"]')]
        const enabledItems = () => allItems().filter(item => item.dataset.disabled !== 'true' && !item.hidden)
        let overlay
        let selectedValues = []

        const syncScrollIndicators = () => {
            if (!listboxWrapper) return
            listboxWrapper.dataset.top = String(listboxWrapper.scrollTop > 1)
            listboxWrapper.dataset.bottom = String(listboxWrapper.scrollTop + listboxWrapper.clientHeight < listboxWrapper.scrollHeight - 1)
        }
        const syncEmpty = () => {
            if (!emptyContent) return
            emptyContent.hidden = allItems().length > 0 || root.dataset.hideEmptyContent === 'true'
        }
        const prepareItems = () => {
            const virtualized = root.dataset.virtualized === 'true' || (root.dataset.virtualized === 'auto' && allItems().length > 50)
            listbox.dataset.virtualize = String(virtualized)
            allItems().forEach(item => {
                const disabled = item.dataset.itemDisabled === 'true' || item.dataset.disabled === 'true' || disabledKeys.has(String(item.dataset.value))
                item.dataset.itemDisabled ??= item.dataset.disabled
                item.dataset.disabled = String(disabled)
                item.setAttribute('aria-disabled', String(disabled))
                if (virtualized) {
                    item.style.contentVisibility = 'auto'
                    item.style.containIntrinsicSize = `0 ${Number(root.dataset.itemHeight || 36)}px`
                }
            })
            syncEmpty()
        }
        const syncForm = values => {
            const value = multiple ? values : (values[0] || '')
            input.value = multiple ? JSON.stringify(values) : value
            if (validation) validation.value = values.length ? 'selected' : ''
            if (values.length && !controlledInvalid) {
                root.dataset.invalid = 'false'
                trigger.removeAttribute('aria-invalid')
            }
            root.dataset.value = multiple ? JSON.stringify(values) : value
            if (multiple && formValues && name) {
                formValues.replaceChildren(...values.map(entry => {
                    const field = document.createElement('input')
                    field.type = 'hidden'
                    field.name = `${name}[]`
                    field.value = entry
                    return field
                }))
            }
        }
        const syncDisplay = selected => {
            const hasValue = selected.length > 0
            valueBox.textContent = selected.map(itemText).join(', ') || valueBox.dataset.placeholderText || ''
            valueBox.dataset.placeholder = String(!hasValue)
            root.dataset.hasValue = String(hasValue)
            root.dataset.filled = String(hasValue || Boolean(valueBox.dataset.placeholderText) || root.dataset.hasLabel !== 'true' || Boolean(root.querySelector('[data-slot="startContent"], [data-slot="endContent"]')))
            if (clearButton) clearButton.dataset.visible = String(hasValue)
        }
        const emitValue = values => {
            input.dispatchEvent(new Event('input', {bubbles: true}))
            input.dispatchEvent(new Event('change', {bubbles: true}))
            AppUI.emit(root, 'select:change', {value: multiple ? values : (values[0] || ''), values})
        }
        const syncSelection = (selected, notify = false) => {
            let values = selected.map(item => String(item.dataset.value))
            if (!values.length && root.dataset.disallowEmptySelection === 'true' && selectedValues.length) {
                collection.setValue(selectedValues, false)
                values = [...selectedValues]
                selected = collection.selected()
            }
            selectedValues = values
            syncForm(values)
            syncDisplay(selected)
            if (notify) emitValue(values)
        }

        const collection = AppUI.collection(listbox, {
            selectionMode: multiple ? 'multiple' : 'single',
            onSelectionChange: selected => {
                syncSelection(selected, true)
                if (!multiple) overlay.close(true)
            },
        })

        const open = source => {
            if (root.dataset.disabled === 'true') return
            overlay.open(source || trigger)
        }
        const close = (restoreFocus = false) => overlay.close(restoreFocus)
        overlay = AppUI.overlay.create({
            root,
            trigger,
            panel: popover,
            placement: popover.dataset.placement || 'bottom-start',
            offset: 5,
            matchWidth: true,
            onOpen: () => {
                const selected = collection.selected()[0]
                collection.focus(selected || enabledItems()[0])
                requestAnimationFrame(syncScrollIndicators)
            },
        })

        const parseInitial = () => {
            if (!multiple) return input.value ? [input.value] : []
            try { return JSON.parse(input.value || '[]').map(String) } catch { return [] }
        }
        const setValue = (value, notify = true) => {
            const values = (multiple ? (Array.isArray(value) ? value : [value]) : [value])
                .filter(entry => entry !== null && entry !== undefined && String(entry) !== '')
                .map(String)
            collection.setValue(values, false)
            syncSelection(collection.selected(), notify)
        }
        const clear = (notify = true) => {
            if (root.dataset.disallowEmptySelection === 'true' || root.dataset.disabled === 'true') return
            collection.setValue([], false)
            syncSelection([], notify)
            AppUI.emit(root, 'select:clear')
        }

        trigger.addEventListener('keydown', event => {
            if (['ArrowDown', 'ArrowUp', 'Enter', ' '].includes(event.key)) {
                event.preventDefault()
                open(trigger)
            } else if (event.key === 'Escape') close(false)
        }, {signal: listeners.signal})
        clearButton?.addEventListener('pointerdown', event => { event.preventDefault(); event.stopPropagation() }, {signal: listeners.signal})
        clearButton?.addEventListener('click', event => {
            event.preventDefault()
            event.stopPropagation()
            clear(true)
            trigger.focus()
        }, {signal: listeners.signal})
        validation?.addEventListener('invalid', event => {
            event.preventDefault()
            root.dataset.invalid = 'true'
            trigger.setAttribute('aria-invalid', 'true')
            trigger.focus()
        }, {signal: listeners.signal})
        listboxWrapper?.addEventListener('scroll', syncScrollIndicators, {signal: listeners.signal, passive: true})

        prepareItems()
        const initialValues = parseInitial()
        if (!initialValues.length) {
            const marked = allItems().filter(item => item.dataset.selected === 'true').map(item => item.dataset.value)
            initialValues.push(...(multiple ? marked : marked.slice(0, 1)))
        }
        collection.setValue(initialValues, false)
        syncSelection(collection.selected(), false)
        const observer = new MutationObserver(() => { prepareItems(); collection.setValue(selectedValues, false); syncSelection(collection.selected(), false) })
        observer.observe(listbox, {childList: true, subtree: true})
        if (root.dataset.defaultOpen === 'true') requestAnimationFrame(() => open(trigger))

        return {
            getValue: () => multiple ? [...selectedValues] : (selectedValues[0] || ''),
            setValue,
            clear,
            open,
            close,
            focus: () => trigger.focus(),
            destroy: () => { listeners.abort(); observer.disconnect(); collection.destroy(); overlay.destroy() },
        }
    })

    AppUI.register('autocomplete', '[data-ui-component="autocomplete"]', root => {
        const field = root.querySelector('[data-autocomplete-input]')
        const hidden = root.querySelector('[data-autocomplete-value]')
        const wrapper = root.querySelector('[data-slot="input-wrapper"]')
        const popover = root.querySelector('[data-autocomplete-popover]')
        const listbox = popover?.querySelector('[data-slot="listbox"]')
        const clearButton = root.querySelector('[data-autocomplete-clear]')
        const selectorButton = root.querySelector('[data-autocomplete-selector]')
        const emptyContent = popover?.querySelector('[data-slot="emptyContent"]')
        const listboxWrapper = popover?.querySelector('[data-slot="listboxWrapper"]')
        const hasInputContent = Boolean(root.querySelector('[data-slot="start-content"], [data-slot="end-content"]'))
        if (!field || !hidden || !wrapper || !popover || !listbox) return {}

        AppUI.interaction(root)
        const listeners = new AbortController()
        const disabledKeys = new Set(JSON.parse(root.dataset.disabledKeys || '[]').map(String))
        const allItems = () => [...listbox.querySelectorAll('[role="option"]')]
        const text = item => String(item?.dataset.textValue || item?.querySelector('[data-label]')?.textContent || item?.textContent || '').trim()
        const normalize = value => String(value || '').normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLocaleLowerCase()
        const visibleItems = () => allItems().filter(item => !item.hidden && item.dataset.disabled !== 'true')
        let activeItem = null
        let overlay
        let observer
        const focusFromPointer = () => {
            field.focus({preventScroll: true})
            const clearPointerRing = () => {
                delete root.dataset.focusVisible
                delete wrapper.dataset.focusVisible
            }
            clearPointerRing()
            requestAnimationFrame(clearPointerRing)
        }

        const syncFilled = () => {
            const filled = field.value.length > 0 || Boolean(field.placeholder) || hasInputContent
            root.dataset.filled = String(filled)
            wrapper.dataset.filled = String(filled)
            if (clearButton) clearButton.dataset.visible = String(field.value.length > 0)
        }
        const syncSections = () => listbox.querySelectorAll('[data-slot="listbox-section"]').forEach(section => {
            section.hidden = !section.querySelector('[role="option"]:not([hidden])')
        })
        const syncEmpty = () => {
            const hasVisible = allItems().some(item => !item.hidden)
            if (emptyContent) emptyContent.hidden = hasVisible
            return hasVisible
        }
        const syncScrollIndicators = () => {
            if (!listboxWrapper) return
            listboxWrapper.dataset.top = String(listboxWrapper.scrollTop > 1)
            listboxWrapper.dataset.bottom = String(listboxWrapper.scrollTop + listboxWrapper.clientHeight < listboxWrapper.scrollHeight - 1)
        }
        const setActive = item => {
            allItems().forEach(option => {
                const active = option === item
                option.dataset.focus = String(active)
                option.dataset.focusVisible = String(active)
            })
            activeItem = item || null
            if (activeItem) {
                field.setAttribute('aria-activedescendant', activeItem.id)
                activeItem.scrollIntoView({block: 'nearest'})
            } else field.removeAttribute('aria-activedescendant')
        }
        const prepareItems = () => {
            const virtualized = root.dataset.virtualized === 'true' || (root.dataset.virtualized === 'auto' && allItems().length > 50)
            listbox.dataset.virtualize = String(virtualized)
            allItems().forEach(item => {
                const disabled = root.dataset.readonly === 'true' || item.dataset.itemDisabled === 'true' || item.dataset.disabled === 'true' || disabledKeys.has(String(item.dataset.value))
                item.dataset.itemDisabled ??= item.dataset.disabled
                item.dataset.disabled = String(disabled)
                item.setAttribute('aria-disabled', String(disabled))
                item.tabIndex = -1
                if (virtualized) {
                    item.style.contentVisibility = 'auto'
                    item.style.containIntrinsicSize = `0 ${Number(root.dataset.itemHeight || 32)}px`
                }
                AppUI.interaction(item)
            })
        }
        const updateSelection = value => allItems().forEach(item => {
            const selected = value !== '' && String(item.dataset.value) === String(value)
            item.dataset.selected = String(selected)
            item.setAttribute('aria-selected', String(selected))
        })
        const emitValue = () => {
            hidden.dispatchEvent(new Event('input', {bubbles: true}))
            hidden.dispatchEvent(new Event('change', {bubbles: true}))
            AppUI.emit(root, 'autocomplete:change', {value: hidden.value, label: field.value})
        }
        const close = (restoreFocus = false) => {
            setActive(null)
            overlay.close(restoreFocus)
        }
        const open = () => {
            if (root.dataset.disabled === 'true') return
            const hasVisible = syncEmpty()
            if (!hasVisible && root.dataset.allowsEmptyCollection !== 'true') return
            overlay.open(field)
        }
        const select = (item, notify = true, refocus = true) => {
            if (!item || item.dataset.disabled === 'true' || root.dataset.readonly === 'true') return
            hidden.value = item.dataset.value || ''
            field.value = text(item)
            root.dataset.value = hidden.value
            updateSelection(hidden.value)
            syncFilled()
            if (notify) {
                emitValue()
                AppUI.emit(root, 'autocomplete:input-change', {inputValue: field.value})
            }
            close(false)
            if (refocus) field.focus()
        }
        const clear = (notify = true) => {
            hidden.value = ''
            field.value = ''
            root.dataset.value = ''
            updateSelection('')
            syncFilled()
            filter(false)
            if (notify) {
                emitValue()
                AppUI.emit(root, 'autocomplete:input-change', {inputValue: ''})
                AppUI.emit(root, 'autocomplete:clear')
            }
        }
        const filter = (openMenu = true) => {
            const query = normalize(field.value.trim())
            allItems().forEach(item => { item.hidden = Boolean(query) && !normalize(text(item)).includes(query) })
            syncSections()
            const hasVisible = syncEmpty()
            setActive(null)
            if (openMenu && root.dataset.menuTrigger !== 'manual') {
                if (hasVisible || root.dataset.allowsEmptyCollection === 'true') open()
                else close(false)
            }
            if (overlay?.isOpen()) overlay.position()
        }
        const setValue = (value, notify = true) => {
            const nextValue = value == null ? '' : String(value)
            const item = allItems().find(option => String(option.dataset.value) === nextValue)
            hidden.value = item ? nextValue : (root.dataset.allowsCustomValue === 'true' ? nextValue : '')
            field.value = item ? text(item) : (root.dataset.allowsCustomValue === 'true' ? nextValue : '')
            root.dataset.value = hidden.value
            updateSelection(hidden.value)
            filter(false)
            syncFilled()
            if (notify) emitValue()
        }
        const commitInput = () => {
            if (root.dataset.allowsCustomValue === 'true') {
                hidden.value = field.value
                root.dataset.value = hidden.value
                updateSelection('')
                emitValue()
                return
            }
            const exact = allItems().find(item => normalize(text(item)) === normalize(field.value))
            if (exact && exact.dataset.disabled !== 'true') select(exact, false, false)
            else {
                const selected = allItems().find(item => item.dataset.selected === 'true')
                field.value = selected ? text(selected) : ''
                syncFilled()
            }
        }

        overlay = AppUI.overlay.create({
            root,
            trigger: wrapper,
            panel: popover,
            placement: popover.dataset.placement || 'bottom-start',
            offset: 5,
            matchWidth: true,
            toggleOnTriggerClick: false,
            onOpen: () => {
                field.setAttribute('aria-expanded', 'true')
                selectorButton?.setAttribute('aria-expanded', 'true')
                if (selectorButton) selectorButton.dataset.open = 'true'
                const selected = allItems().find(item => item.dataset.selected === 'true' && !item.hidden && item.dataset.disabled !== 'true')
                setActive(selected || visibleItems()[0] || null)
                requestAnimationFrame(syncScrollIndicators)
            },
            onClose: () => {
                field.setAttribute('aria-expanded', 'false')
                selectorButton?.setAttribute('aria-expanded', 'false')
                if (selectorButton) selectorButton.dataset.open = 'false'
                setActive(null)
            },
        })

        field.addEventListener('focus', () => { if (root.dataset.menuTrigger === 'focus') open() }, {signal: listeners.signal})
        field.addEventListener('click', () => { if (!overlay.isOpen() && root.dataset.menuTrigger !== 'manual') open() }, {signal: listeners.signal})
        field.addEventListener('input', () => {
            const oldValue = hidden.value
            if (!allItems().some(item => item.dataset.selected === 'true' && text(item) === field.value)) {
                hidden.value = ''
                root.dataset.value = ''
                updateSelection('')
                if (oldValue) emitValue()
            }
            syncFilled()
            filter(true)
            AppUI.emit(root, 'autocomplete:input-change', {inputValue: field.value})
        }, {signal: listeners.signal})
        field.addEventListener('keydown', event => {
            const items = visibleItems()
            if (event.key === 'ArrowDown' || event.key === 'ArrowUp') {
                event.preventDefault()
                if (!overlay.isOpen()) open()
                if (!items.length) return
                const index = items.indexOf(activeItem)
                setActive(items[(index + (event.key === 'ArrowDown' ? 1 : -1) + items.length) % items.length])
            } else if (event.key === 'Home' && overlay.isOpen() && items.length) {
                event.preventDefault(); setActive(items[0])
            } else if (event.key === 'End' && overlay.isOpen() && items.length) {
                event.preventDefault(); setActive(items.at(-1))
            } else if (event.key === 'Enter' && overlay.isOpen() && activeItem) {
                event.preventDefault(); select(activeItem)
            } else if (event.key === 'Escape' && overlay.isOpen()) {
                event.preventDefault(); close(false)
            }
        }, {signal: listeners.signal})
        field.addEventListener('blur', () => setTimeout(() => {
            if (root.contains(document.activeElement) || popover.contains(document.activeElement)) return
            commitInput()
            if (root.dataset.shouldCloseOnBlur === 'true') close(false)
        }), {signal: listeners.signal})
        wrapper.addEventListener('click', event => {
            if (event.target.closest('button')) return
            focusFromPointer()
        }, {signal: listeners.signal})
        selectorButton?.addEventListener('pointerdown', event => event.preventDefault(), {signal: listeners.signal})
        selectorButton?.addEventListener('click', () => {
            const wasOpen = overlay.isOpen()
            focusFromPointer()
            wasOpen ? close(false) : open()
        }, {signal: listeners.signal})
        clearButton?.addEventListener('pointerdown', event => event.preventDefault(), {signal: listeners.signal})
        clearButton?.addEventListener('click', () => {
            clear(true)
            focusFromPointer()
            open()
        }, {signal: listeners.signal})
        listbox.addEventListener('pointermove', event => {
            const item = event.target.closest('[role="option"]')
            if (item && item.dataset.disabled !== 'true') setActive(item)
        }, {signal: listeners.signal})
        listbox.addEventListener('pointerdown', event => { if (event.target.closest('[role="option"]')) event.preventDefault() }, {signal: listeners.signal})
        listbox.addEventListener('click', event => select(event.target.closest('[role="option"]')), {signal: listeners.signal})
        listboxWrapper?.addEventListener('scroll', syncScrollIndicators, {signal: listeners.signal, passive: true})

        prepareItems()
        observer = new MutationObserver(() => { prepareItems(); filter(false) })
        observer.observe(listbox, {childList: true, subtree: true})
        root.dataset.uiComponent = 'autocomplete'
        const initialInputValue = field.value
        const initiallySelected = allItems().find(item => item.dataset.selected === 'true')
        if (hidden.value || initiallySelected) setValue(hidden.value || initiallySelected.dataset.value, false)
        else {
            field.value = initialInputValue
            root.dataset.value = ''
            updateSelection('')
            filter(false)
            syncFilled()
        }

        return {
            getValue: () => hidden.value,
            getInputValue: () => field.value,
            setValue,
            setInputValue: value => { field.value = value ?? ''; syncFilled(); filter(false); AppUI.emit(root, 'autocomplete:input-change', {inputValue: field.value}) },
            clear,
            open,
            close,
            focus: () => field.focus(),
            destroy: () => { listeners.abort(); observer?.disconnect(); overlay.destroy() },
        }
    })

    AppUI.register('dropdown', '[data-ui-component="dropdown"]', root => {
        const triggerSlot = root.querySelector('[data-slot="dropdown-trigger"]')
        const trigger = triggerSlot?.firstElementChild || triggerSlot
        const panel = root.querySelector('[data-slot="dropdown-content"]')
        const list = panel?.querySelector('[data-slot="list"]')
        if (!trigger || !panel || !list) return {}

        const listeners = new AbortController()
        const selectionMode = panel.dataset.selectionMode || 'none'
        const selectionBehavior = panel.dataset.selectionBehavior || 'toggle'
        const disabledKeys = new Set(JSON.parse(panel.dataset.disabledKeys || '[]').map(String))
        const selectedKeys = new Set(JSON.parse(panel.dataset.selectedKeys || '[]').map(String))
        let focusLastOnOpen = false
        let focusVisibleOnOpen = false

        trigger.setAttribute('aria-haspopup', 'menu')
        trigger.setAttribute('aria-expanded', 'false')
        if (!trigger.getAttribute('aria-controls')) trigger.setAttribute('aria-controls', list.id)
        if (root.dataset.disabled === 'true') {
            trigger.setAttribute('aria-disabled', 'true')
            if ('disabled' in trigger) trigger.disabled = true
        }

        const allItems = () => [...list.querySelectorAll('[data-slot="dropdown-item"]')]
        const syncItems = (applyInitialSelection = false) => {
            allItems().forEach(item => {
                const key = String(item.dataset.key)
                if (disabledKeys.has(key)) {
                    item.dataset.disabled = 'true'
                    item.setAttribute('aria-disabled', 'true')
                }
                if (applyInitialSelection && selectedKeys.has(key)) item.dataset.selected = 'true'
                const role = selectionMode === 'single' ? 'menuitemradio' : selectionMode === 'multiple' ? 'menuitemcheckbox' : 'menuitem'
                item.setAttribute('role', role)
                item.removeAttribute('aria-selected')
                if (selectionMode === 'none') item.removeAttribute('aria-checked')
                else item.setAttribute('aria-checked', item.dataset.selected === 'true' ? 'true' : 'false')
                AppUI.interaction(item)
            })
        }
        syncItems(true)

        let collection
        const emitSelection = selected => {
            syncItems()
            const keys = selected.map(item => String(item.dataset.key))
            AppUI.emit(root, 'dropdown:selection-change', {
                selectedKeys: selectionMode === 'single' ? (keys[0] ?? null) : keys,
            })
        }
        collection = AppUI.collection(list, {
            selector: '[data-slot="dropdown-item"]',
            selectionMode,
            selectionBehavior,
            disallowEmptySelection: panel.dataset.disallowEmptySelection === 'true',
            onSelectionChange: emitSelection,
        })

        const overlay = AppUI.overlay.create({
            root,
            trigger,
            panel,
            placement: panel.dataset.placement || root.dataset.placement || 'bottom-start',
            offset: panel.dataset.offset ?? root.dataset.offset ?? 8,
            matchWidth: false,
            onOpen: () => requestAnimationFrame(() => {
                if (!focusVisibleOnOpen) return
                const enabled = collection.items()
                const target = focusLastOnOpen ? enabled.at(-1) : collection.selected()[0] || enabled[0]
                focusLastOnOpen = false
                collection.focus(target)
                focusVisibleOnOpen = false
            }),
        })

        const shouldClose = item => {
            if (item.dataset.closeOnSelect === 'true') return true
            if (item.dataset.closeOnSelect === 'false') return false
            if (panel.dataset.closeOnSelect === 'true') return true
            if (panel.dataset.closeOnSelect === 'false') return false
            return selectionMode !== 'multiple'
        }
        const action = item => {
            if (!item || item.dataset.disabled === 'true' || item.dataset.readonly === 'true') return
            AppUI.emit(root, 'dropdown:action', {key: item.dataset.key})
            if (shouldClose(item)) overlay.close(true)
        }
        list.addEventListener('click', event => action(event.target.closest('[data-slot="dropdown-item"]')), {signal: listeners.signal})
        list.addEventListener('keydown', event => {
            if (event.key === 'Enter' || event.key === ' ') action(document.activeElement?.closest('[data-slot="dropdown-item"]'))
        }, {signal: listeners.signal})
        trigger.addEventListener('keydown', event => {
            if (!['ArrowDown', 'ArrowUp', 'Enter', ' '].includes(event.key)) return
            event.preventDefault()
            focusLastOnOpen = event.key === 'ArrowUp'
            focusVisibleOnOpen = true
            overlay.open(trigger)
        }, {signal: listeners.signal})

        const getValue = () => {
            const keys = collection.selected().map(item => String(item.dataset.key))
            return selectionMode === 'single' ? (keys[0] ?? null) : keys
        }
        const setValue = (value, notify = false) => {
            collection.setValue(Array.isArray(value) ? value : value == null ? [] : [value], notify)
            syncItems()
        }
        return {
            ...overlay,
            collection,
            getValue,
            setValue,
            focus: () => trigger.focus(),
            destroy: () => { listeners.abort(); collection.destroy(); overlay.destroy() },
        }
    })
    const registerMenuOverlay=(name,root)=>{const triggerSlot=root.querySelector(`[data-slot="${name}-trigger"]`),trigger=triggerSlot?.firstElementChild||triggerSlot,panel=root.querySelector(`[data-slot="${name}-content"]`);if(!trigger||!panel)return{};const overlay=AppUI.overlay.create({root,trigger,panel,placement:panel.dataset.placement||'bottom-start',matchWidth:false});root.dataset.uiComponent=name;return overlay}
    AppUI.register('popover','[data-slot="popover"]',root=>registerMenuOverlay('popover',root))

    AppUI.register('tooltip','[data-slot="tooltip-root"]',root=>{const trigger=root.querySelector('[data-slot="tooltip-trigger"]'),tip=root.querySelector('[data-slot="tooltip"]');let timer;const show=()=>{clearTimeout(timer);timer=setTimeout(()=>{document.body.appendChild(tip);tip.hidden=false;AppUI.overlay.position(tip,trigger,{placement:tip.dataset.placement||'top',matchWidth:false,offset:6})},Number(root.dataset.delay||500))};const hide=()=>{clearTimeout(timer);tip.hidden=true;root.appendChild(tip)};trigger.addEventListener('pointerenter',show);trigger.addEventListener('focusin',show);trigger.addEventListener('pointerleave',hide);trigger.addEventListener('focusout',hide);return{open:show,close:hide}})

    const registerModal=(name,root)=>{
        const triggerSlot=root.querySelector(`[data-slot="${name}-trigger"]`)
        const trigger=triggerSlot?.firstElementChild||triggerSlot
        const layer=root.querySelector('[data-overlay-layer]')
        const wrapper=layer?.querySelector('[data-overlay-wrapper]')||layer
        const panel=layer?.querySelector(`[data-slot="${name}-content"]`)
        const backdrop=layer?.querySelector('[data-overlay-backdrop]')
        if(!layer||!panel)return{}

        const isModal=name==='modal'
        const dismissable=root.dataset.dismissable==='true'
        const disableAnimation=root.dataset.disableAnimation==='true'
        const reducedMotion=()=>matchMedia('(prefers-reduced-motion: reduce)').matches
        const parseMotion=()=>{try{return root.dataset.motion?JSON.parse(root.dataset.motion):null}catch{return null}}
        const motion=parseMotion()
        let runningAnimations=[]
        let dragX=0,dragY=0
        if(backdrop)backdrop.dataset.backdrop=root.dataset.backdrop||'opaque'
        for(const property of ['size','radius','shadow','placement','scrollBehavior','hideCloseButton','disableAnimation','draggable','draggableOverflow']){
            if(root.dataset[property]!=null)wrapper.dataset[property]=root.dataset[property]
        }
        if(!isModal)panel.dataset.placement=root.dataset.placement||'right'
        panel.dataset.dismissable=String(dismissable)
        panel.dataset.open='false'

        const title=panel.querySelector('[data-slot="modal-title"]')
        const description=panel.querySelector('[data-slot="modal-description"]')
        if(title){title.id||=`${root.id||name}-${Math.random().toString(36).slice(2)}-title`;panel.setAttribute('aria-labelledby',title.id)}
        if(description){description.id||=`${root.id||name}-${Math.random().toString(36).slice(2)}-description`;panel.setAttribute('aria-describedby',description.id)}
        trigger?.setAttribute('aria-haspopup','dialog')
        trigger?.setAttribute('aria-expanded','false')

        const cancelAnimations=()=>{runningAnimations.forEach(animation=>animation.cancel());runningAnimations=[]}
        const motionTarget=isModal?wrapper:panel
        const defaultModalFrames=open=>{
            const mobile=matchMedia('(max-width: 639px)').matches
            if(open){
                return mobile
                    ? [{opacity:0,transform:'translateY(80px) scale(1)'},{opacity:1,transform:'translateY(0) scale(1)'}]
                    : [{opacity:0,transform:'translateY(0) scale(1.03)'},{opacity:1,transform:'translateY(0) scale(1)'}]
            }
            return mobile
                ? [{opacity:1,transform:'translateY(0) scale(1)'},{opacity:0,transform:'translateY(80px) scale(1)'}]
                : [{opacity:1,transform:'translateY(0) scale(1)'},{opacity:0,transform:'translateY(0) scale(1.03)'}]
        }
        const animateOpen=()=>{
            layer.dataset.open='true'
            panel.dataset.open='true'
            AppUI.emit(root,`${name}:change`,{open:true})
            if(disableAnimation||reducedMotion())return
            cancelAnimations()
            const keyframes=Array.isArray(motion?.enter)?motion.enter:defaultModalFrames(true)
            const options={duration:matchMedia('(max-width: 639px)').matches?600:400,easing:'cubic-bezier(.36,.66,.04,1)',fill:'both',...(motion?.options||{}),...(motion?.enterOptions||{})}
            const animations=[motionTarget.animate(keyframes,options),backdrop?.animate([{opacity:0},{opacity:1}],{duration:250,easing:'linear',fill:'both'})].filter(Boolean)
            runningAnimations=animations
            Promise.allSettled(animations.map(animation=>animation.finished)).then(()=>{animations.forEach(animation=>animation.cancel());if(runningAnimations===animations)runningAnimations=[]})
        }
        const finishClose=()=>{
            cancelAnimations()
            layer.dataset.open='false'
            panel.dataset.open='false'
            panel.style.removeProperty('translate')
            dragX=0;dragY=0
            layer.hidden=true
            AppUI.emit(root,`${name}:change`,{open:false})
        }

        let portalContainer=document.body
        if(root.dataset.portalTarget){
            try{portalContainer=document.getElementById(root.dataset.portalTarget)||document.querySelector(root.dataset.portalTarget)||document.body}catch{portalContainer=document.body}
        }

        const overlay=AppUI.overlay.create({
            root,
            trigger,
            panel:layer,
            modal:true,
            dismissable:false,
            keyboardDismiss:root.dataset.keyboardDismissDisabled!=='true'&&root.dataset.keyboardDismiss!=='false',
            toggleOnTriggerClick:false,
            blockScroll:root.dataset.shouldBlockScroll!=='false',
            portalContainer,
            onOpen:animateOpen,
            onClose:finishClose,
        })
        const originalOpen=overlay.open,originalClose=overlay.close
        let closing=false
        overlay.open=source=>{if(closing)return;originalOpen(source)}
        overlay.close=focus=>{
            if(closing||!overlay.isOpen())return
            if(disableAnimation||reducedMotion()){originalClose(focus);return}
            closing=true
            layer.dataset.state='closing'
            cancelAnimations()
            const keyframes=Array.isArray(motion?.exit)?motion.exit:defaultModalFrames(false)
            const options={duration:300,easing:'cubic-bezier(.36,.66,.04,1)',fill:'both',...(motion?.options||{}),...(motion?.exitOptions||{})}
            const animations=[
                motionTarget.animate(keyframes,options),
                backdrop?.animate([{opacity:1},{opacity:0}],{duration:250,easing:'linear',fill:'both'}),
            ].filter(Boolean)
            Promise.allSettled(animations.map(animation=>animation.finished)).then(()=>{animations.forEach(animation=>animation.cancel());delete layer.dataset.state;closing=false;originalClose(focus)})
        }
        if(isModal&&root.dataset.draggable==='true'){
            const handle=panel.querySelector('[data-slot="modal-header"]')
            if(handle){
                handle.dataset.draggableHandle='true'
                handle.addEventListener('pointerdown',event=>{
                    if(event.button!==0||event.target.closest('button,a,input,select,textarea,[contenteditable="true"]'))return
                    event.preventDefault()
                    const startX=event.clientX,startY=event.clientY,startDragX=dragX,startDragY=dragY,startRect=panel.getBoundingClientRect()
                    handle.setPointerCapture?.(event.pointerId)
                    const move=moveEvent=>{
                        let nextX=startDragX+moveEvent.clientX-startX,nextY=startDragY+moveEvent.clientY-startY
                        if(root.dataset.draggableOverflow!=='true'){
                            const padding=8,deltaX=nextX-startDragX,deltaY=nextY-startDragY
                            if(startRect.left+deltaX<padding)nextX+=padding-(startRect.left+deltaX)
                            if(startRect.right+deltaX>innerWidth-padding)nextX-=startRect.right+deltaX-(innerWidth-padding)
                            if(startRect.top+deltaY<padding)nextY+=padding-(startRect.top+deltaY)
                            if(startRect.bottom+deltaY>innerHeight-padding)nextY-=startRect.bottom+deltaY-(innerHeight-padding)
                        }
                        dragX=nextX;dragY=nextY;panel.style.translate=`${dragX}px ${dragY}px`
                    }
                    const end=()=>{handle.removeEventListener('pointermove',move);handle.removeEventListener('pointerup',end);handle.removeEventListener('pointercancel',end)}
                    handle.addEventListener('pointermove',move);handle.addEventListener('pointerup',end);handle.addEventListener('pointercancel',end)
                })
            }
        }
        if(dismissable){
            backdrop?.addEventListener('pointerdown',()=>overlay.close(true))
            wrapper?.addEventListener('pointerdown',event=>{if(event.target===wrapper)overlay.close(true)})
        }
        trigger?.addEventListener('click',event=>{event.preventDefault();root.dataset.focusVisibleOnOpen=String(event.detail===0);overlay.isOpen()?overlay.close(false):overlay.open(trigger)})
        layer.addEventListener('click',event=>{if(event.target.closest('[data-overlay-close],[data-modal-close],[data-drawer-close]'))overlay.close(true)})
        root.dataset.uiComponent=name
        overlay.getValue=()=>overlay.isOpen()
        overlay.setValue=value=>value?overlay.open():overlay.close(false)
        overlay.focus=()=>panel.focus()
        if(root.dataset.defaultOpen==='true')queueMicrotask(()=>overlay.open())
        return overlay
    }
    AppUI.register('modal','[data-slot="modal"]',root=>registerModal('modal',root))
    AppUI.register('drawer','[data-slot="drawer"]',root=>registerModal('drawer',root))
    document.addEventListener('click',event=>{const trigger=event.target.closest('[data-overlay-target]');if(!trigger)return;const target=document.getElementById(trigger.dataset.overlayTarget);const source=event.target.closest(AppUI.overlay.focusableSelector)||trigger.querySelector(AppUI.overlay.focusableSelector)||trigger;if(target)target.dataset.focusVisibleOnOpen=String(event.detail===0);target?.appUI?.open(source)})

    AppUI.register('badge', '[data-ui-component="badge"]', root => {
        const badge = root.querySelector(':scope > [data-slot="badge"]')
        const setValue = value => { if (badge && root.dataset.dot !== 'true') badge.textContent = value ?? '' }
        const setVisible = visible => { root.dataset.invisible = String(!visible) }
        return {getValue: () => badge?.textContent ?? '', setValue, setVisible, show: () => setVisible(true), hide: () => setVisible(false)}
    })

    AppUI.register('breadcrumbs', '[data-ui-component="breadcrumbs"]', root => {
        const list = root.querySelector(':scope > [data-slot="list"]')
        const separatorTemplate = root.querySelector(':scope > [data-breadcrumb-separator-template]')
        const ellipsisTemplate = root.querySelector(':scope > [data-breadcrumb-ellipsis-template]')
        const items = () => [...list.querySelectorAll(':scope > [data-slot="base"]:not([data-generated="true"])')]
        const listeners = new AbortController()

        const syncCurrent = preferred => {
            const all = items()
            let current = preferred
                ? all.find(item => String(item.dataset.key ?? item.textContent.trim()) === String(preferred))
                : all.find(item => item.dataset.current === 'true')
            current ||= all.at(-1)
            all.forEach(item => {
                const active = item === current
                item.dataset.current = String(active)
                const link = item.querySelector(':scope > [data-slot="item"]')
                if (!link) return
                active ? link.setAttribute('aria-current', 'page') : link.removeAttribute('aria-current')
                if (active) link.setAttribute('aria-disabled', 'true')
                else if (item.dataset.disabled !== 'true' && root.dataset.disabled !== 'true') link.removeAttribute('aria-disabled')
                link.tabIndex = active || item.dataset.disabled === 'true' || root.dataset.disabled === 'true' ? -1 : 0
            })
            return current
        }

        const applySeparator = () => {
            if (!separatorTemplate) return
            items().forEach(item => {
                if (item.dataset.customSeparator === 'true') return
                const separator = item.querySelector(':scope > [data-slot="separator"]')
                if (separator) separator.replaceChildren(separatorTemplate.content.cloneNode(true))
            })
        }

        const collapse = () => {
            list.querySelector(':scope > [data-generated="true"]')?.remove()
            const all = items()
            all.forEach(item => { item.hidden = false })
            const max = Number(root.dataset.maxItems || 0)
            if (!max || all.length <= max) return
            let before = Math.max(0, Number(root.dataset.itemsBeforeCollapse || 1))
            let after = Math.max(0, Number(root.dataset.itemsAfterCollapse || 1))
            if (before + after >= all.length) return
            const collapsed = all.slice(before, all.length - after)
            collapsed.forEach(item => { item.hidden = true })
            const ellipsis = document.createElement('li')
            ellipsis.className = 'app-breadcrumb-ellipsis'
            ellipsis.dataset.slot = 'ellipsis'
            ellipsis.dataset.generated = 'true'
            const first = collapsed[0]?.querySelector(':scope > [data-slot="item"]')
            const control = document.createElement(first?.getAttribute('href') ? 'a' : 'span')
            control.className = 'app-breadcrumb-link'
            control.dataset.slot = 'item'
            control.setAttribute('aria-label', 'Show more breadcrumbs')
            control.tabIndex = 0
            if (first?.getAttribute('href')) control.setAttribute('href', first.getAttribute('href'))
            else control.setAttribute('role', 'link')
            if (ellipsisTemplate) control.append(ellipsisTemplate.content.cloneNode(true))
            else control.textContent = '…'
            ellipsis.append(control)
            const separator = document.createElement('span')
            separator.className = 'app-breadcrumb-separator'
            separator.dataset.slot = 'separator'
            separator.setAttribute('aria-hidden', 'true')
            if (separatorTemplate) {
                separator.append(separatorTemplate.content.cloneNode(true))
            } else {
                const sourceSeparator = all.find(item => item.dataset.customSeparator !== 'true')
                    ?.querySelector(':scope > [data-slot="separator"]')
                if (sourceSeparator) {
                    separator.append(...[...sourceSeparator.childNodes].map(node => node.cloneNode(true)))
                }
            }
            ellipsis.append(separator)
            list.insertBefore(ellipsis, all[all.length - after] || null)
            AppUI.interaction(control)
        }

        const prepare = () => {
            applySeparator()
            syncCurrent()
            items().forEach(item => {
                const link = item.querySelector(':scope > [data-slot="item"]')
                if (link && !link.dataset.breadcrumbPrepared) {
                    link.dataset.breadcrumbPrepared = 'true'
                    AppUI.interaction(link)
                }
            })
            collapse()
        }

        list.addEventListener('click', event => {
            const link = event.target.closest('[data-slot="item"]')
            const item = link?.closest('[data-slot="base"]')
            if (!link || !item) return
            if (item.dataset.disabled === 'true' || item.dataset.current === 'true' || root.dataset.disabled === 'true') {
                event.preventDefault()
                return
            }
            AppUI.emit(root, 'breadcrumbs:action', {key: item.dataset.key ?? link.textContent.trim(), href: link.getAttribute('href')})
        }, {signal: listeners.signal})
        list.addEventListener('keydown', event => {
            const link = event.target.closest('[data-slot="item"]')
            if (link?.tagName === 'SPAN' && (event.key === 'Enter' || event.key === ' ')) {
                event.preventDefault()
                link.click()
            }
        }, {signal: listeners.signal})

        prepare()
        return {
            getValue: () => { const item = items().find(entry => entry.dataset.current === 'true'); return item?.dataset.key ?? item?.querySelector('[data-slot="item"]')?.textContent.trim() ?? null },
            setValue: value => { const current = syncCurrent(value); collapse(); AppUI.emit(root, 'breadcrumbs:change', {value: current?.dataset.key ?? current?.textContent.trim() ?? null}) },
            focus: () => list.querySelector('[data-slot="item"]:not([aria-disabled="true"])')?.focus(),
            destroy: () => listeners.abort(),
        }
    })

    AppUI.register('navbar','[data-slot="navbar"]',root=>{const button=root.querySelector('[data-navbar-toggle]'),menu=root.querySelector('[data-navbar-menu]');const setOpen=open=>{root.dataset.open=String(open);button?.setAttribute('aria-expanded',String(open));if(menu)menu.hidden=!open};button?.addEventListener('click',()=>setOpen(root.dataset.open!=='true'));setOpen(false);return{open:()=>setOpen(true),close:()=>setOpen(false)}})
    AppUI.register('table','[data-slot="table"]',root=>{root.querySelectorAll('tbody tr').forEach(row=>{AppUI.interaction(row);if(row.dataset.selectable==='true')row.addEventListener('click',()=>{if(root.dataset.selectionMode==='single')root.querySelectorAll('tbody tr').forEach(item=>item.dataset.selected='false');row.dataset.selected=String(row.dataset.selected!=='true');AppUI.emit(root,'table:change',{keys:[...root.querySelectorAll('tbody tr[data-selected="true"]')].map(item=>item.dataset.key)})})});return{getValue:()=>[...root.querySelectorAll('tbody tr[data-selected="true"]')].map(item=>item.dataset.key)}})
    AppUI.register('pagination','[data-slot="pagination"]',root=>{let page=Number(root.querySelector('[aria-current="page"]')?.dataset.page||1);const setValue=(value,notify=false)=>{page=Number(value);root.querySelectorAll('[data-page]').forEach(item=>{const active=Number(item.dataset.page)===page;item.dataset.active=String(active);active?item.setAttribute('aria-current','page'):item.removeAttribute('aria-current')});if(notify)AppUI.emit(root,'pagination:change',{page})};root.addEventListener('click',event=>{const item=event.target.closest('[data-page]');if(item&&!item.disabled)setValue(item.dataset.page,true)});return{getValue:()=>page,setValue,focus:()=>root.querySelector('[aria-current="page"]')?.focus()}})

    const toastRegions=new Map()
    AppUI.toast=(message,options={})=>{const placement=options.placement||'top-right';let region=toastRegions.get(placement);if(!region){region=document.createElement('div');region.className='app-toast-region';region.dataset.placement=placement;region.setAttribute('aria-live','polite');document.body.appendChild(region);toastRegions.set(placement,region)}const toast=document.createElement('div');toast.className='app-toast app-color-'+(options.color||'default');toast.setAttribute('role',options.color==='danger'?'alert':'status');toast.innerHTML=`<span>${options.icon||''}</span><span><span class="app-toast-title"></span><span class="app-toast-description"></span></span><button type="button" data-toast-close aria-label="Close">×</button>`;toast.querySelector('.app-toast-title').textContent=options.title||message;toast.querySelector('.app-toast-description').textContent=options.description||(!options.title?message:'');const close=()=>toast.remove();toast.querySelector('[data-toast-close]').addEventListener('click',close);region.appendChild(toast);if((options.timeout??5000)>0)setTimeout(close,options.timeout);return{close,element:toast}}
    AppUI.register('toast','[data-slot="toast"]',root=>{const close=()=>{root.hidden=true;AppUI.emit(root,'toast:close')},timeout=Number(root.dataset.timeout||0);if(timeout>0)setTimeout(close,timeout);return{close}})
})(window, document)
