(function (window, document) {
    'use strict'

    const AppUI = window.AppUI
    const pad = value => String(value).padStart(2, '0')
    const iso = date => `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}`
    const parse = value => {
        if (!/^\d{4}-\d{2}-\d{2}$/.test(value || '')) return null
        const [year, month, day] = value.split('-').map(Number)
        const date = new Date(year, month - 1, day)
        return date.getFullYear() === year && date.getMonth() === month - 1 && date.getDate() === day ? date : null
    }
    const firstOfMonth = date => new Date(date.getFullYear(), date.getMonth(), 1)
    const addMonths = (date, amount) => new Date(date.getFullYear(), date.getMonth() + amount, 1)
    const addPresetMonths = (date, amount) => {
        const result = new Date(date.getFullYear(), date.getMonth() + amount, 1)
        result.setDate(Math.min(date.getDate(), new Date(result.getFullYear(), result.getMonth() + 1, 0).getDate()))
        return result
    }
    const dayNames = {sun: 0, mon: 1, tue: 2, wed: 3, thu: 4, fri: 5, sat: 6}
    const chevron = direction => `<svg aria-hidden="true" viewBox="0 0 16 16"><path d="${direction === 'previous' ? 'M10 3.333 6 8l4 4.667' : 'M6 3.333 10 8l-4 4.667'}" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/></svg>`

    AppUI.register('date-input', '[data-ui-component="date-input"]', root => {
        const config = JSON.parse(root.dataset.dateInputConfig || '{}')
        const field = root.querySelector('[data-date-field]')
        const hidden = root.querySelector('[data-date-value]')
        const wrapper = root.querySelector('[data-slot="input-wrapper"]')
        const description = root.querySelector('[data-slot="description"]')
        const errorMessage = root.querySelector('[data-slot="error-message"]')
        const clearButton = root.querySelector('[data-date-clear]')
        const listeners = new AbortController()
        const locale = config.locale || document.documentElement.lang || 'ko-KR'
        const granularity = ['day', 'hour', 'minute', 'second'].includes(config.granularity) ? config.granularity : 'day'
        const resolvedHourCycle = new Intl.DateTimeFormat(locale, {hour: 'numeric'}).resolvedOptions().hourCycle
        const hourCycle = Number(config.hourCycle) === 12 || Number(config.hourCycle) === 24
            ? Number(config.hourCycle)
            : /^h1[12]$/.test(resolvedHourCycle || '') ? 12 : 24
        const focusable = config.disabled !== true
        const editable = focusable && config.readOnly !== true
        const initialValue = String(config.value || '')
        const typeSettings = {
            year: {min: 1, max: 9999, digits: 4, placeholder: 'yyyy'},
            month: {min: 1, max: 12, digits: 2, placeholder: 'mm'},
            day: {min: 1, max: 31, digits: 2, placeholder: 'dd'},
            hour: {min: hourCycle === 12 ? 1 : 0, max: hourCycle === 12 ? 12 : 23, digits: 2, placeholder: '––'},
            minute: {min: 0, max: 59, digits: 2, placeholder: '––'},
            second: {min: 0, max: 59, digits: 2, placeholder: '––'},
        }
        const ariaNames = {year: '연도', month: '월', day: '일', hour: '시', minute: '분', second: '초', dayPeriod: '오전 또는 오후'}
        const dayPeriodLabel = period => {
            try {
                return new Intl.DateTimeFormat(locale, {hour: 'numeric', hourCycle: 'h12'})
                    .formatToParts(new Date(2001, 10, 22, period === 'PM' ? 15 : 9))
                    .find(part => part.type === 'dayPeriod')?.value || period
            } catch {
                return period
            }
        }
        let state = {}
        let segments = []

        const parseValue = value => {
            const match = String(value || '').match(/^(\d{4})-(\d{2})-(\d{2})(?:T(\d{2}):(\d{2})(?::(\d{2}))?)?/)
            if (!match) return {}
            const parsed = {year: match[1], month: match[2], day: match[3], hour: match[4] || '', minute: match[5] || '', second: match[6] || ''}
            if (hourCycle === 12 && parsed.hour !== '') {
                const hour = Number(parsed.hour)
                parsed.dayPeriod = hour >= 12 ? 'PM' : 'AM'
                parsed.hour = String(hour % 12 || 12).padStart(2, '0')
            }
            return parsed
        }
        const placeholderState = parseValue(config.placeholderValue || '')
        const parts = () => {
            const options = {year: 'numeric', month: '2-digit', day: '2-digit'}
            if (granularity !== 'day') {
                options.hour = '2-digit'
                options.hourCycle = hourCycle === 12 ? 'h12' : 'h23'
                if (['minute', 'second'].includes(granularity)) options.minute = '2-digit'
                if (granularity === 'second') options.second = '2-digit'
                if (config.timeZone) options.timeZone = config.timeZone
                if (config.timeZone && !config.hideTimeZone) options.timeZoneName = 'short'
            }
            try {
                return new Intl.DateTimeFormat(locale, options)
                    .formatToParts(new Date(Date.UTC(2001, 10, 22, 15, 45, 22)))
                    .filter(part => ['year', 'month', 'day', 'hour', 'minute', 'second', 'dayPeriod', 'timeZoneName', 'literal'].includes(part.type))
                    .map(part => ({type: part.type, value: part.value}))
            } catch {
                return new Intl.DateTimeFormat('en-US', options)
                    .formatToParts(new Date(Date.UTC(2001, 10, 22, 15, 45, 22)))
                    .filter(part => ['year', 'month', 'day', 'hour', 'minute', 'second', 'dayPeriod', 'timeZoneName', 'literal'].includes(part.type))
                    .map(part => ({type: part.type, value: part.value}))
            }
        }
        const valueForType = type => state[type] || ''
        const displayValue = type => {
            if (type === 'dayPeriod') return dayPeriodLabel(state.dayPeriod || 'AM')
            const settings = typeSettings[type]
            const value = valueForType(type)
            if (value && config.shouldForceLeadingZeros === false && type !== 'year') return String(Number(value))
            const placeholder = placeholderState[type]
            if (placeholder && config.shouldForceLeadingZeros === false && type !== 'year') return String(Number(placeholder))
            return value || placeholder || settings?.placeholder || ''
        }
        const createSegment = part => {
            const segment = document.createElement('div')
            segment.dataset.slot = 'segment'
            segment.dataset.type = part.type
            segment.dataset.dateSegment = ''
            segment.className = 'app-date-segment'
            if (part.type === 'literal' || part.type === 'timeZoneName') {
                segment.setAttribute('aria-hidden', 'true')
                segment.textContent = part.value || ''
                return segment
            }
            const settings = typeSettings[part.type]
            segment.setAttribute('role', 'spinbutton')
            segment.setAttribute('aria-label', `${ariaNames[part.type]}, ${config.label || '날짜'}`)
            segment.setAttribute('inputmode', part.type === 'dayPeriod' ? 'text' : 'numeric')
            segment.setAttribute('enterkeyhint', 'next')
            segment.setAttribute('spellcheck', 'false')
            segment.setAttribute('contenteditable', String(editable))
            segment.dataset.editable = String(editable)
            if (focusable) segment.tabIndex = 0
            if (config.disabled) segment.setAttribute('aria-disabled', 'true')
            if (config.readOnly) segment.setAttribute('aria-readonly', 'true')
            if (config.required) segment.setAttribute('aria-required', 'true')
            if (config.invalid) segment.setAttribute('aria-invalid', 'true')
            if (settings) {
                segment.setAttribute('aria-valuemin', String(settings.min))
                segment.setAttribute('aria-valuemax', String(settings.max))
            }
            return segment
        }
        const updateSegment = segment => {
            const type = segment.dataset.type
            if (!type || type === 'literal' || type === 'timeZoneName') return
            const value = valueForType(type)
            segment.textContent = displayValue(type)
            segment.dataset.placeholder = String(!value)
            if (value) {
                segment.setAttribute('aria-valuenow', type === 'dayPeriod' ? (value === 'PM' ? '1' : '0') : String(Number(value)))
                segment.setAttribute('aria-valuetext', displayValue(type))
            } else {
                segment.removeAttribute('aria-valuenow')
                segment.setAttribute('aria-valuetext', '비어 있음')
            }
        }
        const dateIsValid = () => {
            if (!state.year || !state.month || !state.day) return false
            const date = new Date(Number(state.year), Number(state.month) - 1, Number(state.day))
            return date.getFullYear() === Number(state.year)
                && date.getMonth() === Number(state.month) - 1
                && date.getDate() === Number(state.day)
        }
        const compose = () => {
            if (!dateIsValid()) return ''
            const date = `${String(state.year).padStart(4, '0')}-${pad(state.month)}-${pad(state.day)}`
            if (granularity === 'day') return date
            if (!state.hour || (['minute', 'second'].includes(granularity) && !state.minute) || (granularity === 'second' && !state.second)) return ''
            let hour = Number(state.hour)
            if (hourCycle === 12) {
                hour %= 12
                if ((state.dayPeriod || 'AM') === 'PM') hour += 12
            }
            let value = `${date}T${pad(hour)}`
            if (['minute', 'second'].includes(granularity)) value += `:${pad(state.minute)}`
            if (granularity === 'second') value += `:${pad(state.second)}`
            if (config.timeZone && !config.hideTimeZone) {
                try {
                    const instant = new Date(Date.UTC(Number(state.year), Number(state.month) - 1, Number(state.day), hour, Number(state.minute || 0), Number(state.second || 0)))
                    const zoneName = new Intl.DateTimeFormat('en-US', {timeZone: config.timeZone, timeZoneName: 'longOffset'})
                        .formatToParts(instant)
                        .find(part => part.type === 'timeZoneName')?.value || 'GMT'
                    const offset = zoneName === 'GMT' ? '+00:00' : zoneName.replace('GMT', '')
                    value += `${offset}[${config.timeZone}]`
                } catch {
                    value += `[${config.timeZone}]`
                }
            }
            return value
        }
        const valueIsInRange = value => {
            const comparable = String(value || '').replace(/([+-]\d{2}:\d{2})?(\[.*])$/, '')
            return (!config.minValue || comparable >= String(config.minValue).replace(/([+-]\d{2}:\d{2})?(\[.*])$/, ''))
                && (!config.maxValue || comparable <= String(config.maxValue).replace(/([+-]\d{2}:\d{2})?(\[.*])$/, ''))
        }
        const syncState = (notify = false) => {
            segments.forEach(updateSegment)
            const value = compose()
            hidden.value = value
            const completeButInvalid = Boolean(state.year && state.month && state.day) && (!dateIsValid() || !valueIsInRange(value))
            const invalid = config.invalid === true || completeButInvalid
            hidden.setCustomValidity(completeButInvalid ? (errorMessage?.textContent || '유효한 날짜를 입력해 주세요.') : '')
            root.dataset.invalid = String(invalid)
            field.setAttribute('aria-invalid', String(invalid))
            segments.forEach(segment => {
                if (!['literal', 'timeZoneName'].includes(segment.dataset.type)) {
                    segment.dataset.invalid = String(invalid)
                    segment.setAttribute('aria-invalid', String(invalid))
                }
            })
            const daySegment = segments.find(segment => segment.dataset.type === 'day')
            if (daySegment) {
                const year = Number(state.year || 2000)
                const month = Number(state.month || 1)
                daySegment.setAttribute('aria-valuemax', String(new Date(year, month, 0).getDate()))
            }
            if (description) description.hidden = invalid
            if (errorMessage) errorMessage.hidden = !invalid
            if (clearButton) clearButton.hidden = !Object.values(state).some(Boolean)
            if (notify) {
                hidden.dispatchEvent(new Event('input', {bubbles: true}))
                hidden.dispatchEvent(new Event('change', {bubbles: true}))
                AppUI.emit(root, 'date-input:change', {value, invalid})
            }
        }
        const editableSegments = () => segments.filter(segment => !['literal', 'timeZoneName'].includes(segment.dataset.type))
        const focusAdjacent = (segment, offset) => {
            const available = editableSegments()
            const index = available.indexOf(segment)
            available[index + offset]?.focus()
        }
        const setNumericValue = (segment, raw, notify = true) => {
            const type = segment.dataset.type
            const settings = typeSettings[type]
            if (!settings) return
            let numeric = Number(raw || settings.min)
            if (numeric > settings.max) numeric = settings.min
            if (numeric < settings.min) numeric = settings.max
            state[type] = String(numeric)
            syncState(notify)
        }
        const bindSegment = segment => {
            const type = segment.dataset.type
            if (['literal', 'timeZoneName'].includes(type)) return
            segment.addEventListener('beforeinput', event => event.preventDefault(), {signal: listeners.signal})
            segment.addEventListener('focus', () => {
                const entered = root.dataset.focus !== 'true'
                root.dataset.focus = 'true'
                root.dataset.focusVisible = String(segment.matches(':focus-visible'))
                segment.dataset.focused = 'true'
                segment.dataset.editBuffer = ''
                if (entered) {
                    AppUI.emit(root, 'date-input:focus', {value: hidden.value})
                    AppUI.emit(root, 'date-input:focus-change', {focused: true})
                }
            }, {signal: listeners.signal})
            segment.addEventListener('blur', () => {
                segment.dataset.focused = 'false'
                const settings = typeSettings[type]
                if (settings && state[type]) state[type] = String(state[type]).padStart(settings.digits, '0')
                syncState(false)
                requestAnimationFrame(() => {
                    if (!root.contains(document.activeElement)) {
                        root.dataset.focus = 'false'
                        root.dataset.focusVisible = 'false'
                        AppUI.emit(root, 'date-input:blur', {value: hidden.value})
                        AppUI.emit(root, 'date-input:focus-change', {focused: false})
                    }
                })
            }, {signal: listeners.signal})
            segment.addEventListener('keydown', event => {
                AppUI.emit(root, 'date-input:key-down', {key: event.key, value: hidden.value})
                if (!editable) return
                if (type === 'dayPeriod') {
                    if (/^[ap]$/i.test(event.key) || ['ArrowUp', 'ArrowDown'].includes(event.key)) {
                        event.preventDefault()
                        state.dayPeriod = /^p$/i.test(event.key) ? 'PM' : /^a$/i.test(event.key) ? 'AM' : state.dayPeriod === 'PM' ? 'AM' : 'PM'
                        syncState(true)
                    }
                } else if (/^\d$/.test(event.key)) {
                    event.preventDefault()
                    const settings = typeSettings[type]
                    const now = Date.now()
                    const stale = now - Number(segment.dataset.editTime || 0) > 1000
                    let buffer = stale ? '' : String(segment.dataset.editBuffer || '')
                    buffer = `${buffer}${event.key}`.slice(-settings.digits)
                    if (Number(buffer) > settings.max) buffer = event.key
                    segment.dataset.editBuffer = buffer
                    segment.dataset.editTime = String(now)
                    state[type] = buffer
                    syncState(true)
                    if (buffer.length >= settings.digits || (Number(buffer) > 0 && Number(buffer) * 10 > settings.max)) {
                        state[type] = String(Math.max(settings.min, Math.min(settings.max, Number(buffer)))).padStart(settings.digits, '0')
                        syncState(true)
                        focusAdjacent(segment, 1)
                    }
                }
                if (event.key === 'ArrowUp' || event.key === 'ArrowDown') {
                    if (type !== 'dayPeriod') {
                        event.preventDefault()
                        const settings = typeSettings[type]
                        const amount = event.key === 'ArrowUp' ? 1 : -1
                        setNumericValue(segment, Number(state[type] || settings.min) + amount)
                    }
                }
                if (event.key === 'ArrowLeft') { event.preventDefault(); focusAdjacent(segment, -1) }
                if (event.key === 'ArrowRight' || event.key === '/') { event.preventDefault(); focusAdjacent(segment, 1) }
                if (event.key === 'Home' && typeSettings[type]) { event.preventDefault(); setNumericValue(segment, typeSettings[type].min) }
                if (event.key === 'End' && typeSettings[type]) { event.preventDefault(); setNumericValue(segment, typeSettings[type].max) }
                if (event.key === 'Backspace' || event.key === 'Delete') {
                    event.preventDefault()
                    state[type] = ''
                    segment.dataset.editBuffer = ''
                    syncState(true)
                }
            }, {signal: listeners.signal})
            segment.addEventListener('keyup', event => {
                AppUI.emit(root, 'date-input:key-up', {key: event.key, value: hidden.value})
            }, {signal: listeners.signal})
        }
        const renderSegments = () => {
            field.replaceChildren()
            segments = parts().map(createSegment)
            segments.forEach(segment => {
                field.appendChild(segment)
                bindSegment(segment)
            })
            syncState(false)
        }
        const setValue = (value, notify = false) => {
            state = parseValue(value)
            renderSegments()
            if (notify) syncState(true)
        }
        const clear = (notify = true) => {
            state = {}
            syncState(notify)
            editableSegments()[0]?.focus()
        }

        root.addEventListener('pointerenter', () => { if (!config.disabled) root.dataset.hover = 'true' }, {signal: listeners.signal})
        root.addEventListener('pointerleave', () => { root.dataset.hover = 'false' }, {signal: listeners.signal})
        wrapper.addEventListener('click', event => {
            if (!focusable || event.target.closest('[data-date-segment],[data-date-clear],button,a')) return
            ;(editableSegments().find(segment => !state[segment.dataset.type]) || editableSegments()[0])?.focus()
        }, {signal: listeners.signal})
        field.addEventListener('paste', event => {
            if (!editable) return
            const pasted = event.clipboardData?.getData('text') || ''
            const match = pasted.match(/(\d{4})\D(\d{1,2})\D(\d{1,2})/)
            if (!match) return
            event.preventDefault()
            setValue(`${match[1]}-${pad(match[2])}-${pad(match[3])}`, true)
        }, {signal: listeners.signal})
        clearButton?.addEventListener('click', () => clear(true), {signal: listeners.signal})
        hidden.addEventListener('invalid', event => {
            event.preventDefault()
            root.dataset.invalid = 'true'
            field.setAttribute('aria-invalid', 'true')
            segments.forEach(segment => {
                if (!['literal', 'timeZoneName'].includes(segment.dataset.type)) segment.setAttribute('aria-invalid', 'true')
            })
            if (description) description.hidden = true
            if (errorMessage) errorMessage.hidden = false
            editableSegments()[0]?.focus()
        }, {signal: listeners.signal})
        const form = hidden.form
        form?.addEventListener('reset', () => requestAnimationFrame(() => setValue(initialValue, false)), {signal: listeners.signal})

        setValue(initialValue, false)
        if (config.autoFocus && editable) requestAnimationFrame(() => editableSegments()[0]?.focus())
        if (config.disableAnimation || matchMedia('(prefers-reduced-motion: reduce)').matches) root.dataset.disableAnimation = 'true'

        return {
            getValue: () => hidden.value,
            setValue,
            clear,
            focus: () => editableSegments()[0]?.focus(),
            destroy: () => listeners.abort(),
        }
    })

    AppUI.register('time-input', '[data-ui-component="time-input"]', root => {
        const config = JSON.parse(root.dataset.timeInputConfig || '{}')
        const field = root.querySelector('[data-time-field]')
        const hidden = root.querySelector('[data-time-value]')
        const wrapper = root.querySelector('[data-slot="input-wrapper"]')
        const description = root.querySelector('[data-slot="description"]')
        const errorMessage = root.querySelector('[data-slot="error-message"]')
        if (!field || !hidden || !wrapper) return {}

        const listeners = new AbortController()
        const locale = config.locale || document.documentElement.lang || 'ko-KR'
        const granularity = ['hour', 'minute', 'second'].includes(config.granularity) ? config.granularity : 'minute'
        let resolvedHourCycle = 'h23'
        try {
            resolvedHourCycle = new Intl.DateTimeFormat(locale, {hour: 'numeric'}).resolvedOptions().hourCycle || 'h23'
        } catch {}
        const hourCycle = Number(config.hourCycle) === 12 || Number(config.hourCycle) === 24
            ? Number(config.hourCycle)
            : /^h1[12]$/.test(resolvedHourCycle) ? 12 : 24
        const focusable = config.disabled !== true
        const editable = focusable && config.readOnly !== true
        const initialValue = String(config.value || '')
        const typeSettings = {
            hour: {min: hourCycle === 12 ? 1 : 0, max: hourCycle === 12 ? 12 : 23, digits: 2, placeholder: '––'},
            minute: {min: 0, max: 59, digits: 2, placeholder: '––'},
            second: {min: 0, max: 59, digits: 2, placeholder: '––'},
        }
        const ariaNames = {hour: '시', minute: '분', second: '초', dayPeriod: '오전 또는 오후', timeZoneName: '시간대'}
        let state = {}
        let segments = []

        const dayPeriodLabel = period => {
            try {
                return new Intl.DateTimeFormat(locale, {hour: 'numeric', hourCycle: 'h12'})
                    .formatToParts(new Date(2001, 10, 22, period === 'PM' ? 15 : 9))
                    .find(part => part.type === 'dayPeriod')?.value || period
            } catch {
                return period
            }
        }
        const parseValue = value => {
            const match = String(value || '').match(/^(\d{1,2})(?::(\d{2}))?(?::(\d{2}))?((?:Z|[+-]\d{2}:?\d{2})?(?:\[[^\]]+\])?)$/)
            if (!match) return {}
            const canonicalHour = Number(match[1])
            const parsed = {
                hour: String(canonicalHour),
                minute: match[2] || '',
                second: match[3] || '',
                zoneSuffix: match[4] || '',
            }
            if (hourCycle === 12) {
                parsed.dayPeriod = canonicalHour >= 12 ? 'PM' : 'AM'
                parsed.hour = String(canonicalHour % 12 || 12)
            }
            return parsed
        }
        const placeholderState = parseValue(config.placeholderValue || '')
        const timeZoneName = () => {
            if (!config.timeZone || config.hideTimeZone) return ''
            try {
                return new Intl.DateTimeFormat(locale, {timeZone: config.timeZone, timeZoneName: 'short'})
                    .formatToParts(new Date(2026, 7, 5, 12))
                    .find(part => part.type === 'timeZoneName')?.value || config.timeZone
            } catch {
                return config.timeZone
            }
        }
        const formatParts = () => {
            const options = {hour: '2-digit', hourCycle: hourCycle === 12 ? 'h12' : 'h23'}
            if (['minute', 'second'].includes(granularity)) options.minute = '2-digit'
            if (granularity === 'second') options.second = '2-digit'
            if (config.timeZone && !config.hideTimeZone) {
                options.timeZone = config.timeZone
                options.timeZoneName = 'short'
            }
            try {
                return new Intl.DateTimeFormat(locale, options).formatToParts(new Date(2026, 7, 5, 15, 45, 22))
                    .filter(part => ['hour', 'minute', 'second', 'dayPeriod', 'timeZoneName', 'literal'].includes(part.type))
            } catch {
                return new Intl.DateTimeFormat('en-US', options).formatToParts(new Date(2026, 7, 5, 15, 45, 22))
                    .filter(part => ['hour', 'minute', 'second', 'dayPeriod', 'timeZoneName', 'literal'].includes(part.type))
            }
        }
        const displayValue = type => {
            if (type === 'dayPeriod') return dayPeriodLabel(state.dayPeriod || placeholderState.dayPeriod || 'AM')
            if (type === 'timeZoneName') return timeZoneName()
            const settings = typeSettings[type]
            const value = state[type] || placeholderState[type] || ''
            if (value && config.shouldForceLeadingZeros !== false) return String(value).padStart(settings.digits, '0')
            return value || settings.placeholder
        }
        const createSegment = part => {
            const segment = document.createElement('div')
            segment.dataset.slot = 'segment'
            segment.dataset.type = part.type
            segment.className = 'app-date-segment'
            if (part.type === 'literal') {
                segment.setAttribute('aria-hidden', 'true')
                segment.textContent = part.value || ''
                return segment
            }
            if (part.type === 'timeZoneName') {
                segment.setAttribute('role', 'textbox')
                segment.setAttribute('aria-label', `${ariaNames.timeZoneName}, ${config.label || '시간'}`)
                segment.setAttribute('aria-readonly', 'true')
                segment.dataset.editable = 'false'
                if (focusable) segment.tabIndex = 0
                return segment
            }
            const settings = typeSettings[part.type]
            segment.setAttribute('role', 'spinbutton')
            segment.setAttribute('aria-label', `${ariaNames[part.type]}, ${config.label || '시간'}`)
            segment.setAttribute('inputmode', part.type === 'dayPeriod' ? 'text' : 'numeric')
            segment.setAttribute('enterkeyhint', 'next')
            segment.setAttribute('spellcheck', 'false')
            segment.setAttribute('contenteditable', String(editable))
            segment.dataset.editable = String(editable)
            if (focusable) segment.tabIndex = 0
            if (config.disabled) segment.setAttribute('aria-disabled', 'true')
            if (config.readOnly) segment.setAttribute('aria-readonly', 'true')
            if (config.required) segment.setAttribute('aria-required', 'true')
            if (settings) {
                segment.setAttribute('aria-valuemin', String(settings.min))
                segment.setAttribute('aria-valuemax', String(settings.max))
            } else {
                segment.setAttribute('aria-valuemin', '0')
                segment.setAttribute('aria-valuemax', '1')
            }
            return segment
        }
        const updateSegment = segment => {
            const type = segment.dataset.type
            if (!type || type === 'literal') return
            const hasValue = type === 'dayPeriod' ? Boolean(state.hour) : type === 'timeZoneName' ? true : Boolean(state[type])
            segment.textContent = displayValue(type)
            segment.dataset.placeholder = String(!hasValue)
            if (type === 'timeZoneName') return
            if (hasValue) {
                const numeric = type === 'dayPeriod' ? (state.dayPeriod === 'PM' ? 1 : 0) : Number(state[type])
                segment.setAttribute('aria-valuenow', String(numeric))
                segment.setAttribute('aria-valuetext', displayValue(type))
            } else {
                segment.removeAttribute('aria-valuenow')
                segment.setAttribute('aria-valuetext', '비어 있음')
            }
        }
        const compose = () => {
            if (!state.hour) return ''
            if (['minute', 'second'].includes(granularity) && !state.minute) return ''
            if (granularity === 'second' && !state.second) return ''
            let hour = Number(state.hour)
            if (hourCycle === 12) {
                hour %= 12
                if ((state.dayPeriod || 'AM') === 'PM') hour += 12
            }
            let value = pad(hour)
            if (['minute', 'second'].includes(granularity)) value += `:${pad(state.minute)}`
            if (granularity === 'second') value += `:${pad(state.second)}`
            return value + (state.zoneSuffix || '')
        }
        const seconds = value => {
            const match = String(value || '').match(/^(\d{1,2})(?::(\d{2}))?(?::(\d{2}))?/)
            return match ? Number(match[1]) * 3600 + Number(match[2] || 0) * 60 + Number(match[3] || 0) : null
        }
        const valueIsInRange = value => {
            const current = seconds(value)
            if (current === null) return true
            const minimum = seconds(config.minValue)
            const maximum = seconds(config.maxValue)
            return (minimum === null || current >= minimum) && (maximum === null || current <= maximum)
        }
        const syncState = (notify = false) => {
            segments.forEach(updateSegment)
            const value = compose()
            hidden.value = value
            const completeButInvalid = Boolean(value) && !valueIsInRange(value)
            const invalid = config.invalid === true || completeButInvalid
            hidden.setCustomValidity(completeButInvalid ? (errorMessage?.textContent || '유효한 시간을 입력해 주세요.') : '')
            root.dataset.invalid = String(invalid)
            field.setAttribute('aria-invalid', String(invalid))
            segments.forEach(segment => {
                if (segment.dataset.type !== 'literal') {
                    segment.dataset.invalid = String(invalid)
                    segment.setAttribute('aria-invalid', String(invalid))
                }
            })
            if (description) description.hidden = invalid
            if (errorMessage) errorMessage.hidden = !invalid
            if (notify) {
                hidden.dispatchEvent(new Event('input', {bubbles: true}))
                hidden.dispatchEvent(new Event('change', {bubbles: true}))
                AppUI.emit(root, 'time-input:change', {value, invalid})
            }
        }
        const focusableSegments = () => segments.filter(segment => segment.dataset.type !== 'literal')
        const focusAdjacent = (segment, offset) => {
            const available = focusableSegments()
            available[available.indexOf(segment) + offset]?.focus()
        }
        const setNumericValue = (segment, raw, notify = true) => {
            const settings = typeSettings[segment.dataset.type]
            if (!settings) return
            let numeric = Number(raw || settings.min)
            if (numeric > settings.max) numeric = settings.min
            if (numeric < settings.min) numeric = settings.max
            state[segment.dataset.type] = String(numeric)
            syncState(notify)
        }
        const bindSegment = segment => {
            const type = segment.dataset.type
            if (type === 'literal') return
            if (type !== 'timeZoneName') segment.addEventListener('beforeinput', event => event.preventDefault(), {signal: listeners.signal})
            segment.addEventListener('focus', () => {
                const entered = root.dataset.focus !== 'true'
                root.dataset.focus = 'true'
                root.dataset.focusVisible = String(segment.matches(':focus-visible'))
                segment.dataset.focused = 'true'
                segment.dataset.editBuffer = ''
                if (entered) {
                    AppUI.emit(root, 'time-input:focus', {value: hidden.value})
                    AppUI.emit(root, 'time-input:focus-change', {focused: true})
                }
            }, {signal: listeners.signal})
            segment.addEventListener('blur', () => {
                segment.dataset.focused = 'false'
                const settings = typeSettings[type]
                if (settings && state[type]) state[type] = String(state[type]).padStart(settings.digits, '0')
                syncState(false)
                requestAnimationFrame(() => {
                    if (!root.contains(document.activeElement)) {
                        root.dataset.focus = 'false'
                        root.dataset.focusVisible = 'false'
                        AppUI.emit(root, 'time-input:blur', {value: hidden.value})
                        AppUI.emit(root, 'time-input:focus-change', {focused: false})
                    }
                })
            }, {signal: listeners.signal})
            segment.addEventListener('keydown', event => {
                AppUI.emit(root, 'time-input:key-down', {key: event.key, value: hidden.value})
                if (event.key === 'ArrowLeft') { event.preventDefault(); focusAdjacent(segment, -1); return }
                if (event.key === 'ArrowRight') { event.preventDefault(); focusAdjacent(segment, 1); return }
                if (!editable || type === 'timeZoneName') return
                if (type === 'dayPeriod') {
                    if (/^[ap]$/i.test(event.key) || ['ArrowUp', 'ArrowDown'].includes(event.key)) {
                        event.preventDefault()
                        state.dayPeriod = /^p$/i.test(event.key) ? 'PM' : /^a$/i.test(event.key) ? 'AM' : state.dayPeriod === 'PM' ? 'AM' : 'PM'
                        syncState(true)
                    }
                    if (event.key === 'Home' || event.key === 'End') {
                        event.preventDefault()
                        state.dayPeriod = event.key === 'End' ? 'PM' : 'AM'
                        syncState(true)
                    }
                } else if (/^\d$/.test(event.key)) {
                    event.preventDefault()
                    const settings = typeSettings[type]
                    const now = Date.now()
                    const stale = now - Number(segment.dataset.editTime || 0) > 1000
                    let buffer = stale ? '' : String(segment.dataset.editBuffer || '')
                    buffer = `${buffer}${event.key}`.slice(-settings.digits)
                    if (Number(buffer) > settings.max) buffer = event.key
                    segment.dataset.editBuffer = buffer
                    segment.dataset.editTime = String(now)
                    state[type] = buffer
                    syncState(true)
                    if (buffer.length >= settings.digits || (Number(buffer) > 0 && Number(buffer) * 10 > settings.max)) {
                        state[type] = String(Math.max(settings.min, Math.min(settings.max, Number(buffer)))).padStart(settings.digits, '0')
                        syncState(true)
                        focusAdjacent(segment, 1)
                    }
                }
                if (event.key === 'ArrowUp' || event.key === 'ArrowDown') {
                    if (type !== 'dayPeriod') {
                        event.preventDefault()
                        setNumericValue(segment, Number(state[type] || typeSettings[type].min) + (event.key === 'ArrowUp' ? 1 : -1))
                    }
                }
                if (event.key === 'Home' && typeSettings[type]) { event.preventDefault(); setNumericValue(segment, typeSettings[type].min) }
                if (event.key === 'End' && typeSettings[type]) { event.preventDefault(); setNumericValue(segment, typeSettings[type].max) }
                if (event.key === 'Backspace' || event.key === 'Delete') {
                    event.preventDefault()
                    state[type] = ''
                    segment.dataset.editBuffer = ''
                    syncState(true)
                }
            }, {signal: listeners.signal})
            segment.addEventListener('keyup', event => AppUI.emit(root, 'time-input:key-up', {key: event.key, value: hidden.value}), {signal: listeners.signal})
        }
        const renderSegments = () => {
            field.replaceChildren()
            segments = formatParts().map(createSegment)
            segments.forEach(segment => {
                field.appendChild(segment)
                bindSegment(segment)
            })
            syncState(false)
        }
        const setValue = (value, notify = false) => {
            state = parseValue(value)
            renderSegments()
            if (notify) syncState(true)
        }
        const clear = (notify = true) => {
            state = {}
            syncState(notify)
            focusableSegments()[0]?.focus()
        }

        root.addEventListener('pointerenter', () => { if (!config.disabled) root.dataset.hover = 'true' }, {signal: listeners.signal})
        root.addEventListener('pointerleave', () => { root.dataset.hover = 'false' }, {signal: listeners.signal})
        wrapper.addEventListener('click', event => {
            if (!focusable || event.target.closest('[data-slot="segment"],button,a')) return
            ;(focusableSegments().find(segment => segment.dataset.placeholder === 'true') || focusableSegments()[0])?.focus()
        }, {signal: listeners.signal})
        field.addEventListener('paste', event => {
            if (!editable) return
            const match = (event.clipboardData?.getData('text') || '').match(/(\d{1,2}):(\d{2})(?::(\d{2}))?\s*(AM|PM)?/i)
            if (!match) return
            event.preventDefault()
            let hour = Number(match[1])
            if (match[4]) {
                hour %= 12
                if (match[4].toUpperCase() === 'PM') hour += 12
            }
            setValue(`${pad(hour)}:${match[2]}${granularity === 'second' ? `:${match[3] || '00'}` : ''}`, true)
        }, {signal: listeners.signal})
        hidden.addEventListener('invalid', event => {
            event.preventDefault()
            root.dataset.invalid = 'true'
            field.setAttribute('aria-invalid', 'true')
            segments.filter(segment => segment.dataset.type !== 'literal').forEach(segment => segment.setAttribute('aria-invalid', 'true'))
            if (description) description.hidden = true
            if (errorMessage) errorMessage.hidden = false
            focusableSegments()[0]?.focus()
        }, {signal: listeners.signal})
        hidden.form?.addEventListener('reset', () => requestAnimationFrame(() => setValue(initialValue, false)), {signal: listeners.signal})

        setValue(initialValue, false)
        if (config.autoFocus && editable) requestAnimationFrame(() => focusableSegments()[0]?.focus())
        if (config.disableAnimation || matchMedia('(prefers-reduced-motion: reduce)').matches) root.dataset.disableAnimation = 'true'

        return {
            getValue: () => hidden.value,
            setValue,
            clear,
            focus: () => focusableSegments()[0]?.focus(),
            destroy: () => listeners.abort(),
        }
    })

    AppUI.register('calendar', '[data-ui-component="calendar"]', root => {
        const config = JSON.parse(root.dataset.calendarConfig || '{}')
        const locale = config.locale || document.documentElement.lang || 'ko-KR'
        const mode = config.selectionMode || 'single'
        const input = root.querySelector('[data-calendar-input]')
        const ui = root.querySelector('[data-calendar-ui]')
        const listeners = new AbortController()
        const months = Math.max(1, Math.min(3, Number(config.visibleMonths || 1)))
        const min = parse(config.minValue)
        const max = parse(config.maxValue)
        const disabledValues = new Set((config.disabledValues || []).map(String))
        const unavailableValues = new Set((config.unavailableValues || []).map(String))
        const reducedMotion = matchMedia('(prefers-reduced-motion: reduce)').matches
        const disabledRoot = () => config.disabled === true || root.dataset.disabled === 'true'
        const readOnly = () => config.readOnly === true || root.dataset.readonly === 'true'
        const getWeekStart = () => {
            if (typeof config.firstDayOfWeek === 'number') return Math.max(0, Math.min(6, config.firstDayOfWeek))
            if (dayNames[config.firstDayOfWeek] !== undefined) return dayNames[config.firstDayOfWeek]
            try {
                const firstDay = new Intl.Locale(locale).weekInfo?.firstDay
                if (firstDay) return firstDay % 7
            } catch {}
            return /^(en-US|ko|ja|zh-TW|zh-HK)/i.test(locale) ? 0 : 1
        }
        const weekStart = getWeekStart()
        const normalize = value => {
            if (mode === 'range') return {
                start: String(value?.start || value?.from || ''),
                end: String(value?.end || value?.to || ''),
            }
            if (mode === 'multiple') return Array.isArray(value) ? value.map(String) : []
            return String(value || '')
        }
        let selected = normalize(config.value)
        const selectedAnchor = mode === 'range' ? (selected.start || selected.end) : mode === 'multiple' ? selected.at(-1) : selected
        const anchor = parse(config.visibleMonth) || parse(config.focusedValue) || parse(config.defaultFocusedValue) || parse(selectedAnchor) || new Date()
        const alignmentOffset = config.selectionAlignment === 'end' ? months - 1 : config.selectionAlignment === 'start' ? 0 : Math.floor((months - 1) / 2)
        let current = addMonths(firstOfMonth(anchor), config.visibleMonth ? 0 : -alignmentOffset)
        let focusedValue = String(config.focusedValue || config.defaultFocusedValue || selectedAnchor || iso(new Date()))
        let hovered = ''
        let pickerExpanded = config.headerExpanded === true || config.headerDefaultExpanded === true
        let navigationDirection = 0

        const isOutsideBounds = value => {
            const date = parse(value)
            return !date || (min && date < min) || (max && date > max) || disabledValues.has(value)
        }
        const isUnavailable = value => unavailableValues.has(value)
        const serialise = () => mode === 'single' ? selected : JSON.stringify(selected)
        const emit = () => {
            if (input) {
                input.value = serialise()
                input.dispatchEvent(new Event('input', {bubbles: true}))
                input.dispatchEvent(new Event('change', {bubbles: true}))
            }
            AppUI.emit(root, mode === 'range' ? 'range-calendar:change' : 'calendar:change', {value: selected})
        }
        const announceFocus = value => AppUI.emit(root, 'calendar:focus-change', {value})
        const pageAmount = () => config.pageBehavior === 'single' ? 1 : months
        const resolvePreset = preset => {
            const raw = String(preset || '').trim()
            const range = raw.match(/^(\d{4}-\d{2}-\d{2})\s*\/\s*(\d{4}-\d{2}-\d{2})$/)
            if (mode === 'range' && range && parse(range[1]) && parse(range[2])) {
                return range[1] <= range[2]
                    ? {start: range[1], end: range[2]}
                    : {start: range[2], end: range[1]}
            }
            if (parse(raw)) return raw
            const today = new Date()
            if (raw === 'today') return iso(today)
            if (raw === 'tomorrow') return iso(new Date(today.getFullYear(), today.getMonth(), today.getDate() + 1))
            if (raw === 'nextWeek') return iso(new Date(today.getFullYear(), today.getMonth(), today.getDate() + 7))
            if (raw === 'nextMonth') return iso(addPresetMonths(today, 1))
            const relative = raw.match(/^([+-]?\d+)\s*(day|days|week|weeks|month|months)$/i)
            if (!relative) return ''
            const amount = Number(relative[1])
            const unit = relative[2].toLowerCase()
            if (unit.startsWith('month')) return iso(addPresetMonths(today, amount))
            return iso(new Date(today.getFullYear(), today.getMonth(), today.getDate() + amount * (unit.startsWith('week') ? 7 : 1)))
        }
        const syncPresets = () => {
            root.querySelectorAll('[data-calendar-preset]').forEach(preset => {
                const value = resolvePreset(preset.dataset.calendarPreset)
                if ('disabled' in preset) preset.disabled = disabledRoot() || readOnly() || !value
            })
        }

        const setInteractionState = button => {
            button.addEventListener('pointerenter', () => {
                if (readOnly() || button.dataset.disabled === 'true' || button.dataset.unavailable === 'true') return
                button.dataset.hovered = 'true'
                if (mode === 'range' && selected.start && !selected.end && !isOutsideBounds(button.dataset.value) && !isUnavailable(button.dataset.value)) {
                    if (hovered === button.dataset.value) return
                    const start = button.dataset.value < selected.start ? button.dataset.value : selected.start
                    const end = button.dataset.value < selected.start ? selected.start : button.dataset.value
                    if (rangeContainsUnavailable(start, end)) return
                    hovered = button.dataset.value
                    render()
                }
            })
            button.addEventListener('pointerleave', () => { button.dataset.hovered = 'false' })
            button.addEventListener('pointerdown', () => {
                if (readOnly() || button.dataset.disabled === 'true' || button.dataset.unavailable === 'true') return
                button.dataset.pressed = 'true'
            })
            button.addEventListener('pointerup', () => { button.dataset.pressed = 'false' })
            button.addEventListener('focus', event => {
                focusedValue = button.dataset.value
                button.dataset.focused = 'true'
                button.dataset.focusVisible = event.target.matches(':focus-visible') ? 'true' : 'false'
                announceFocus(focusedValue)
            })
            button.addEventListener('blur', () => {
                button.dataset.focused = 'false'
                button.dataset.focusVisible = 'false'
            })
        }

        const rangeContainsUnavailable = (start, end) => {
            if (config.allowsNonContiguousRanges === true) return false
            const cursor = parse(start)
            const limit = parse(end)
            if (!cursor || !limit) return false
            while (cursor <= limit) {
                const candidate = iso(cursor)
                if (isUnavailable(candidate)) return true
                cursor.setDate(cursor.getDate() + 1)
            }
            return false
        }

        const choose = value => {
            if (disabledRoot() || readOnly() || isOutsideBounds(value) || isUnavailable(value)) return false
            if (mode === 'range') {
                if (!selected.start || selected.end) selected = {start: value, end: ''}
                else {
                    const start = value < selected.start ? value : selected.start
                    const end = value < selected.start ? selected.start : value
                    if (rangeContainsUnavailable(start, end)) return false
                    selected = {start, end}
                }
            } else if (mode === 'multiple') {
                selected = selected.includes(value) ? selected.filter(item => item !== value) : [...selected, value]
            } else selected = value
            focusedValue = value
            hovered = ''
            render()
            emit()
            requestAnimationFrame(() => focusValue(value))
            return true
        }

        const renderPicker = monthDate => {
            const wrapper = document.createElement('div')
            wrapper.dataset.slot = 'picker-wrapper'
            wrapper.className = 'app-calendar-picker'
            const highlight = document.createElement('div')
            highlight.dataset.slot = 'picker-highlight'
            highlight.className = 'app-calendar-picker-highlight'
            const monthList = document.createElement('div')
            monthList.dataset.slot = 'picker-month-list'
            monthList.className = 'app-calendar-picker-list'
            monthList.setAttribute('role', 'listbox')
            monthList.setAttribute('aria-label', '월 선택')
            const monthFormatter = new Intl.DateTimeFormat(locale, {month: 'long'})
            for (let month = 0; month < 12; month++) {
                const item = document.createElement('button')
                item.type = 'button'
                item.dataset.slot = 'picker-item'
                item.dataset.value = String(month)
                item.dataset.selected = String(month === monthDate.getMonth())
                item.className = 'app-calendar-picker-item'
                item.setAttribute('role', 'option')
                item.setAttribute('aria-selected', String(month === monthDate.getMonth()))
                item.textContent = monthFormatter.format(new Date(2024, month, 1))
                item.addEventListener('click', () => {
                    current = new Date(current.getFullYear(), month, 1)
                    render()
                })
                monthList.appendChild(item)
            }
            const yearList = document.createElement('div')
            yearList.dataset.slot = 'picker-year-list'
            yearList.className = 'app-calendar-picker-list'
            yearList.setAttribute('role', 'listbox')
            yearList.setAttribute('aria-label', '연도 선택')
            const startYear = min?.getFullYear() || 1900
            const endYear = max?.getFullYear() || 2099
            for (let year = startYear; year <= endYear; year++) {
                const item = document.createElement('button')
                item.type = 'button'
                item.dataset.slot = 'picker-item'
                item.dataset.value = String(year)
                item.dataset.selected = String(year === monthDate.getFullYear())
                item.className = 'app-calendar-picker-item'
                item.setAttribute('role', 'option')
                item.setAttribute('aria-selected', String(year === monthDate.getFullYear()))
                item.textContent = String(year)
                item.addEventListener('click', () => {
                    current = new Date(year, current.getMonth(), 1)
                    render()
                })
                yearList.appendChild(item)
            }
            wrapper.append(highlight, yearList, monthList)
            requestAnimationFrame(() => {
                for (const list of [monthList, yearList]) {
                    const active = list.querySelector('[data-selected="true"]')
                    if (active) list.scrollTop = active.offsetTop - (list.clientHeight - active.offsetHeight) / 2
                }
            })
            return wrapper
        }

        const renderMonth = (monthDate, index) => {
            const year = monthDate.getFullYear()
            const month = monthDate.getMonth()
            const first = new Date(year, month, 1)
            const offset = (first.getDay() - weekStart + 7) % 7
            const previousDays = new Date(year, month, 0).getDate()
            const titleFormatter = new Intl.DateTimeFormat(locale, {month: 'long', year: 'numeric'})
            const dateFormatter = new Intl.DateTimeFormat(locale, {dateStyle: 'full'})
            const weekdayFormatter = new Intl.DateTimeFormat(locale, {weekday: config.weekdayStyle || 'narrow'})
            const monthEl = document.createElement('section')
            monthEl.className = 'app-calendar-month'
            monthEl.style.width = 'var(--calendar-width)'

            const headerWrapper = document.createElement('div')
            headerWrapper.dataset.slot = 'header-wrapper'
            headerWrapper.className = 'app-calendar-header-wrapper'
            const previous = document.createElement('button')
            previous.type = 'button'
            previous.dataset.slot = 'prev-button'
            previous.dataset.calendarPrevious = ''
            previous.className = 'app-calendar-nav'
            previous.setAttribute('aria-label', '이전')
            previous.innerHTML = chevron('previous')
            previous.hidden = index !== 0
            previous.disabled = disabledRoot()

            const header = document.createElement('header')
            header.dataset.slot = 'header'
            header.className = 'app-calendar-header'
            const title = document.createElement('span')
            title.dataset.slot = 'title'
            title.className = 'app-calendar-title'
            title.textContent = titleFormatter.format(monthDate)
            header.appendChild(title)

            const next = document.createElement('button')
            next.type = 'button'
            next.dataset.slot = 'next-button'
            next.dataset.calendarNext = ''
            next.className = 'app-calendar-nav'
            next.setAttribute('aria-label', '다음')
            next.innerHTML = chevron('next')
            next.hidden = index !== months - 1
            next.disabled = disabledRoot()
            headerWrapper.append(previous, header, next)
            monthEl.appendChild(headerWrapper)
            const gridWrapper = document.createElement('div')
            gridWrapper.dataset.slot = 'grid-wrapper'
            gridWrapper.className = 'app-calendar-grid-wrapper'
            const table = document.createElement('table')
            table.dataset.slot = 'grid'
            table.className = 'app-calendar-grid'
            table.setAttribute('role', 'grid')
            table.setAttribute('aria-label', `${config.label || '날짜'}, ${titleFormatter.format(monthDate)}`)
            const thead = document.createElement('thead')
            thead.dataset.slot = 'grid-header'
            thead.className = 'app-calendar-grid-header'
            const headerRow = document.createElement('tr')
            headerRow.dataset.slot = 'grid-header-row'
            headerRow.className = 'app-calendar-grid-header-row'
            for (let day = 0; day < 7; day++) {
                const th = document.createElement('th')
                th.dataset.slot = 'grid-header-cell'
                th.className = 'app-calendar-grid-header-cell'
                th.scope = 'col'
                th.textContent = weekdayFormatter.format(new Date(2024, 0, 7 + ((day + weekStart) % 7)))
                headerRow.appendChild(th)
            }
            thead.appendChild(headerRow)
            const tbody = document.createElement('tbody')
            tbody.dataset.slot = 'grid-body'
            tbody.className = 'app-calendar-grid-body'
            for (let rowIndex = 0; rowIndex < 6; rowIndex++) {
                const row = document.createElement('tr')
                row.dataset.slot = 'grid-body-row'
                row.className = 'app-calendar-grid-body-row'
                for (let column = 0; column < 7; column++) {
                    const cellIndex = rowIndex * 7 + column
                    const day = cellIndex - offset + 1
                    const date = day < 1 ? new Date(year, month - 1, previousDays + day) : new Date(year, month, day)
                    const value = iso(date)
                    const outsideMonth = date.getMonth() !== month
                    const disabled = disabledRoot() || outsideMonth || isOutsideBounds(value)
                    const unavailable = !disabled && isUnavailable(value)
                    const previewEnd = mode === 'range' && selected.start && !selected.end ? hovered : ''
                    const visualStart = mode === 'range' && previewEnd && previewEnd < selected.start ? previewEnd : selected.start
                    const visualEnd = mode === 'range' && previewEnd && previewEnd < selected.start ? selected.start : (selected.end || previewEnd)
                    const inRange = mode === 'range' && Boolean(visualStart && visualEnd) && value >= visualStart && value <= visualEnd
                    const selectedInRange = inRange && !unavailable
                    const isSelected = mode === 'range'
                        ? selectedInRange || (!visualEnd && value === visualStart)
                        : mode === 'multiple' ? selected.includes(value) : selected === value
                    const selectionStart = mode === 'range' && Boolean(visualStart) && value === visualStart
                    const selectionEnd = mode === 'range' && Boolean(visualEnd) && value === visualEnd
                    const previousValue = iso(new Date(date.getFullYear(), date.getMonth(), date.getDate() - 1))
                    const nextValue = iso(new Date(date.getFullYear(), date.getMonth(), date.getDate() + 1))
                    const rangeStart = selectedInRange && (selectionStart || column === 0 || isUnavailable(previousValue))
                    const rangeEnd = selectedInRange && (selectionEnd || column === 6 || isUnavailable(nextValue))
                    const td = document.createElement('td')
                    td.dataset.slot = 'cell'
                    td.setAttribute('role', 'gridcell')
                    td.setAttribute('aria-selected', String(isSelected))
                    const button = document.createElement('button')
                    button.type = 'button'
                    button.dataset.slot = 'cell-button'
                    button.className = 'app-calendar-cell'
                    button.dataset.value = value
                    button.dataset.outsideMonth = String(outsideMonth)
                    button.dataset.outsideVisibleRange = String(outsideMonth)
                    button.dataset.today = String(value === iso(new Date()))
                    button.dataset.disabled = String(disabled)
                    button.dataset.unavailable = String(unavailable)
                    button.dataset.readonly = String(readOnly())
                    button.dataset.selected = String(isSelected)
                    button.dataset.rangeSelection = String(mode === 'range' && selectedInRange)
                    button.dataset.selectionStart = String(selectionStart)
                    button.dataset.selectionEnd = String(selectionEnd)
                    button.dataset.rangeStart = String(rangeStart)
                    button.dataset.rangeEnd = String(rangeEnd)
                    button.dataset.selectedStart = String(selectionStart)
                    button.dataset.selectedEnd = String(selectionEnd)
                    button.dataset.rangeMiddle = String(selectedInRange && !selectionStart && !selectionEnd)
                    button.dataset.invalid = String(config.invalid === true && isSelected)
                    button.dataset.focused = 'false'
                    button.dataset.focusVisible = 'false'
                    button.dataset.hovered = 'false'
                    button.dataset.pressed = 'false'
                    button.textContent = String(date.getDate())
                    button.setAttribute('aria-label', dateFormatter.format(date))
                    button.setAttribute('aria-pressed', String(isSelected))
                    if (config.invalid === true && isSelected) {
                        button.setAttribute('aria-invalid', 'true')
                        if (config.errorId) button.setAttribute('aria-describedby', config.errorId)
                    }
                    if (disabled) {
                        button.disabled = true
                        td.setAttribute('aria-disabled', 'true')
                    }
                    if (unavailable) {
                        td.setAttribute('aria-disabled', 'true')
                        button.setAttribute('aria-disabled', 'true')
                    }
                    if (config.hideDisabledDates && disabled) button.hidden = true
                    button.addEventListener('click', () => choose(value))
                    setInteractionState(button)
                    td.appendChild(button)
                    row.appendChild(td)
                }
                tbody.appendChild(row)
            }
            table.append(thead, tbody)
            gridWrapper.appendChild(table)
            monthEl.appendChild(gridWrapper)
            return monthEl
        }

        const render = () => {
            ui.replaceChildren()
            const renderedMonths = Array.from({length: months}, (_, index) => renderMonth(addMonths(current, index), index))
            const headerWrapper = document.createElement('div')
            headerWrapper.dataset.slot = 'header-wrapper'
            headerWrapper.className = 'app-calendar-header-wrapper'
            const previousButton = renderedMonths[0].querySelector('[data-slot="prev-button"]')
            const nextButton = renderedMonths.at(-1).querySelector('[data-slot="next-button"]')
            previousButton.hidden = false
            nextButton.hidden = false
            if (pickerExpanded) {
                previousButton.tabIndex = -1
                nextButton.tabIndex = -1
                previousButton.dataset.pickerHidden = 'true'
                nextButton.dataset.pickerHidden = 'true'
            }
            headerWrapper.appendChild(previousButton)
            renderedMonths.forEach((monthElement, index) => {
                const sourceTitle = monthElement.querySelector('[data-slot="title"]')
                const header = document.createElement(config.showMonthAndYearPickers && months === 1 ? 'button' : 'header')
                header.dataset.slot = 'header'
                header.className = 'app-calendar-header'
                if (header.tagName === 'BUTTON') {
                    header.type = 'button'
                    header.setAttribute('aria-label', pickerExpanded ? '날짜 보기로 전환' : '월과 연도 선택으로 전환')
                    header.setAttribute('aria-expanded', String(pickerExpanded))
                    header.addEventListener('click', () => {
                        pickerExpanded = !pickerExpanded
                        render()
                    })
                }
                const title = document.createElement('span')
                title.dataset.slot = 'title'
                title.className = 'app-calendar-title'
                title.textContent = sourceTitle.textContent
                header.appendChild(title)
                if (header.tagName === 'BUTTON') {
                    const pickerChevron = document.createElement('span')
                    pickerChevron.className = 'app-calendar-picker-chevron'
                    pickerChevron.innerHTML = '<svg aria-hidden="true" viewBox="0 0 24 24"><path d="m19 9-7 6-7-6" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/></svg>'
                    header.appendChild(pickerChevron)
                }
                headerWrapper.appendChild(header)
            })
            headerWrapper.appendChild(nextButton)

            const gridWrapper = document.createElement('div')
            gridWrapper.dataset.slot = 'grid-wrapper'
            gridWrapper.className = 'app-calendar-grid-wrapper'
            for (const monthElement of renderedMonths) {
                const table = monthElement.querySelector('[data-slot="grid"]')
                table.style.width = 'var(--calendar-width)'
                table.style.flex = 'none'
                if (pickerExpanded) {
                    table.setAttribute('aria-hidden', 'true')
                    table.dataset.pickerHidden = 'true'
                }
                gridWrapper.appendChild(table)
            }
            if (pickerExpanded && config.showMonthAndYearPickers && months === 1) gridWrapper.appendChild(renderPicker(current))
            ui.append(headerWrapper, gridWrapper)

            if (navigationDirection && !config.disableAnimation && !reducedMotion) {
                const offset = navigationDirection > 0 ? 16 : -16
                const animated = [...headerWrapper.querySelectorAll('[data-slot="title"]'), ...gridWrapper.querySelectorAll('[data-slot="grid-body"]')]
                animated.forEach(element => element.animate(
                    [{transform: `translateX(${offset}px)`}, {transform: 'translateX(0)'}],
                    {duration: 180, easing: 'cubic-bezier(.25,.46,.45,.94)'}
                ))
            }
            navigationDirection = 0
            const cells = [...ui.querySelectorAll('.app-calendar-cell:not(:disabled)')]
            cells.forEach(cell => { cell.tabIndex = -1 })
            const active = cells.find(cell => cell.dataset.value === focusedValue)
                || cells.find(cell => cell.dataset.selected === 'true')
                || cells.find(cell => cell.dataset.today === 'true')
                || cells[0]
            if (active) active.tabIndex = 0
            ui.querySelector('[data-calendar-previous]:not([hidden])')?.addEventListener('click', () => previous())
            ui.querySelector('[data-calendar-next]:not([hidden])')?.addEventListener('click', () => next())
            syncPresets()
        }

        const focusValue = value => {
            if (mode === 'range' && selected.start && !selected.end && value !== selected.start && !isOutsideBounds(value) && !isUnavailable(value)) {
                const start = value < selected.start ? value : selected.start
                const end = value < selected.start ? selected.start : value
                if (!rangeContainsUnavailable(start, end)) {
                    hovered = value
                    render()
                }
            }
            let target = ui.querySelector(`[data-value="${value}"]:not(:disabled)`)
            if (target) {
                ui.querySelectorAll('.app-calendar-cell').forEach(cell => { cell.tabIndex = -1 })
                target.tabIndex = 0
                target.focus()
                return true
            }
            const date = parse(value)
            if (!date) return false
            current = firstOfMonth(date)
            render()
            requestAnimationFrame(() => ui.querySelector(`[data-value="${value}"]:not(:disabled)`)?.focus())
            return true
        }
        const next = () => {
            if (disabledRoot()) return false
            navigationDirection = 1
            current = addMonths(current, pageAmount())
            render()
            AppUI.emit(root, 'calendar:page-change', {direction: 'next', visibleMonth: iso(current)})
            return true
        }
        const previous = () => {
            if (disabledRoot()) return false
            navigationDirection = -1
            current = addMonths(current, -pageAmount())
            render()
            AppUI.emit(root, 'calendar:page-change', {direction: 'previous', visibleMonth: iso(current)})
            return true
        }

        root.addEventListener('keydown', event => {
            const cell = event.target.closest('.app-calendar-cell')
            if (!cell) return
            const date = parse(cell.dataset.value)
            if (!date) return
            let target = null
            if (event.key === 'ArrowLeft') target = new Date(date.getFullYear(), date.getMonth(), date.getDate() - 1)
            if (event.key === 'ArrowRight') target = new Date(date.getFullYear(), date.getMonth(), date.getDate() + 1)
            if (event.key === 'ArrowUp') target = new Date(date.getFullYear(), date.getMonth(), date.getDate() - 7)
            if (event.key === 'ArrowDown') target = new Date(date.getFullYear(), date.getMonth(), date.getDate() + 7)
            if (event.key === 'Home') target = new Date(date.getFullYear(), date.getMonth(), date.getDate() - ((date.getDay() - weekStart + 7) % 7))
            if (event.key === 'End') target = new Date(date.getFullYear(), date.getMonth(), date.getDate() + (6 - ((date.getDay() - weekStart + 7) % 7)))
            if (event.key === 'PageUp') target = new Date(date.getFullYear(), date.getMonth() - 1, date.getDate())
            if (event.key === 'PageDown') target = new Date(date.getFullYear(), date.getMonth() + 1, date.getDate())
            if (target) {
                event.preventDefault()
                focusValue(iso(target))
            }
        }, {signal: listeners.signal})
        root.addEventListener('pointerleave', () => {
            if (hovered) {
                hovered = ''
                render()
            }
        }, {signal: listeners.signal})
        root.addEventListener('click', event => {
            const preset = event.target.closest('[data-calendar-preset]')
            if (preset && root.contains(preset)) {
                const value = resolvePreset(preset.dataset.calendarPreset)
                if (mode === 'range' && value && typeof value === 'object') {
                    if (rangeContainsUnavailable(value.start, value.end)) return
                    selected = value
                    focusedValue = value.end
                    hovered = ''
                    render()
                    emit()
                    requestAnimationFrame(() => focusValue(value.end))
                } else if (value) choose(value)
            }
        }, {signal: listeners.signal})

        render()
        if (config.autoFocus && !disabledRoot()) requestAnimationFrame(() => focusValue(focusedValue))
        if (config.disableAnimation || reducedMotion) root.dataset.disableAnimation = 'true'

        return {
            getValue: () => selected,
            setValue: (value, notify = false) => {
                selected = normalize(value)
                const anchorValue = mode === 'range' ? (selected.end || selected.start) : mode === 'multiple' ? selected.at(-1) : selected
                if (parse(anchorValue)) focusedValue = anchorValue
                render()
                if (notify) emit()
            },
            focus: () => focusValue(focusedValue),
            next,
            previous,
            destroy: () => listeners.abort(),
        }
    })

    const registerDatePicker = root => {
        const trigger = root.querySelector('[data-slot="selector-button"]')
        const popover = root.querySelector('[data-slot="popover-content"]')
        const calendar = popover?.querySelector('[data-ui-component="calendar"]')
        const field = root.querySelector('[data-date-picker-field]')
        const input = field?.querySelector('[data-date-value]')
        const anchor = field?.querySelector('[data-slot="inner-wrapper"]')
        const wrapper = field?.querySelector('[data-slot="input-wrapper"]')
        const timeInput = calendar?.querySelector('[data-picker-time]')
        if (!trigger || !popover || !calendar || !field || !input || !anchor) return {}

        const listeners = new AbortController()
        const config = JSON.parse(root.dataset.datePickerConfig || '{}')
        const calendarValue = value => typeof value === 'string' && value.length >= 10 ? value.slice(0, 10) : ''
        const combineWithTime = date => {
            const current = input.value || ''
            if (config.granularity === 'day') return date
            const fallback = config.granularity === 'second' ? '00:00:00' : '00:00'
            const time = AppUI.get(timeInput)?.getValue() || (current.includes('T') ? current.slice(11).replace(/(?:Z|[+-]\d{2}:?\d{2}|\[[^]]+\])$/, '') : fallback)
            return `${date}T${time}`
        }
        const emit = value => AppUI.emit(root, 'date-picker:change', {value})
        const overlay = AppUI.overlay.create({
            root,
            trigger: anchor,
            panel: popover,
            placement: 'bottom-center',
            matchWidth: false,
            offset: 13,
            toggleOnTriggerClick: false,
            onOpen: () => {
                root.dataset.open = 'true'
                trigger.setAttribute('aria-expanded', 'true')
                requestAnimationFrame(() => AppUI.get(calendar)?.focus())
            },
            onClose: () => {
                root.dataset.open = 'false'
                trigger.setAttribute('aria-expanded', 'false')
            },
        })

        trigger.addEventListener('click', event => {
            event.preventDefault()
            overlay.isOpen() ? overlay.close(false) : overlay.open(trigger)
        }, {signal: listeners.signal})

        wrapper?.addEventListener('pointerdown', event => {
            if (event.target !== wrapper || root.dataset.disabled === 'true') return
            event.preventDefault()
            event.stopPropagation()
            overlay.open(trigger)
        }, {capture: true, signal: listeners.signal})

        field.addEventListener('app-ui:date-input:change', event => {
            const value = event.detail?.value || input.value || ''
            const date = calendarValue(value)
            if (date) AppUI.get(calendar)?.setValue(date)
            if (timeInput && value.includes('T')) {
                const nextTime = value.slice(11).replace(/(?:Z|[+-]\d{2}:?\d{2}|\[[^]]+\])$/, '')
                const timeController = AppUI.get(timeInput)
                if (timeController?.getValue() !== nextTime) timeController?.setValue(nextTime)
            }
            root.dataset.invalid = field.dataset.invalid || 'false'
            emit(value)
        }, {signal: listeners.signal})

        timeInput?.addEventListener('app-ui:time-input:change', event => {
            const date = calendarValue(input.value) || AppUI.get(calendar)?.getValue()
            if (typeof date !== 'string' || !date) return
            AppUI.get(field)?.setValue(`${date}T${event.detail.value}`, true)
        }, {signal: listeners.signal})

        calendar.addEventListener('app-ui:calendar:change', event => {
            const date = event.detail?.value
            if (typeof date !== 'string') return
            const value = combineWithTime(date)
            AppUI.get(field)?.setValue(value, true)
            if (config.granularity === 'day') overlay.close(true)
        }, {signal: listeners.signal})

        root.addEventListener('keydown', event => {
            if (root.dataset.disabled === 'true') return
            if (event.altKey && event.key === 'ArrowDown') {
                event.preventDefault()
                overlay.open(trigger)
            }
        }, {signal: listeners.signal})

        document.addEventListener('keydown', event => {
            if (event.key !== 'Escape' || !overlay.isOpen()) return
            event.preventDefault()
            overlay.close(true)
        }, {signal: listeners.signal})

        if (config.disableAnimation || matchMedia('(prefers-reduced-motion: reduce)').matches) {
            root.dataset.disableAnimation = 'true'
        }

        return {
            getValue: () => input.value,
            setValue: (value, notify = false) => {
                AppUI.get(field)?.setValue(value, notify)
                const date = calendarValue(value)
                if (date) AppUI.get(calendar)?.setValue(date)
            },
            open: () => overlay.open(trigger),
            close: overlay.close,
            focus: () => AppUI.get(field)?.focus(),
            destroy: () => {
                listeners.abort()
                overlay.destroy()
            },
        }
    }

    const registerRangePicker = (name, root) => {
        const trigger = root.querySelector('[data-slot="selector-button"]')
        const wrapper = root.querySelector('[data-date-range-wrapper]')
        const popover = root.querySelector('[data-slot="popover-content"]')
        const calendar = popover?.querySelector('[data-ui-component="calendar"]')
        const startField = root.querySelector('[data-range-part="start"]')
        const endField = root.querySelector('[data-range-part="end"]')
        const aggregate = root.querySelector('[data-date-range-value]')
        const clearButton = root.querySelector('[data-date-range-clear]')
        const startTime = popover?.querySelector('[data-range-time="start"]')
        const endTime = popover?.querySelector('[data-range-time="end"]')
        if (!trigger || !wrapper || !popover || !calendar || !startField || !endField || !aggregate) return {}

        const listeners = new AbortController()
        const config = JSON.parse(root.dataset.dateRangePickerConfig || '{}')
        let syncing = false
        let focusCalendarOnOpen = false
        const controller = field => AppUI.get(field)
        const datePart = value => typeof value === 'string' && value.length >= 10 ? value.slice(0, 10) : ''
        const timePart = value => typeof value === 'string' && value.includes('T')
            ? value.slice(11).replace(/(?:Z|[+-]\d{2}:?\d{2}|\[[^]]+\])$/, '')
            : ''
        const rangeValue = () => ({
            start: controller(startField)?.getValue() || '',
            end: controller(endField)?.getValue() || '',
        })
        const calendarValue = value => ({start: datePart(value.start), end: datePart(value.end)})
        const updateInvalid = value => {
            const dates = calendarValue(value)
            const invalid = config.invalid === true || Boolean(dates.start && dates.end && dates.start > dates.end)
            root.dataset.invalid = String(invalid)
            wrapper.setAttribute('aria-invalid', String(invalid))
            for (const field of [startField, endField]) {
                field.dataset.invalid = String(invalid)
                field.querySelector('[data-slot="input-field"]')?.setAttribute('aria-invalid', String(invalid))
            }
        }
        const emit = value => {
            aggregate.value = JSON.stringify(value)
            aggregate.dispatchEvent(new Event('input', {bubbles: true}))
            aggregate.dispatchEvent(new Event('change', {bubbles: true}))
            AppUI.emit(root, `${name}:change`, {value})
        }
        const syncCalendar = value => {
            const dates = calendarValue(value)
            AppUI.get(calendar)?.setValue(dates)
        }
        const setRange = (value, notify = false, updateCalendar = true) => {
            const next = {
                start: String(value?.start || value?.from || ''),
                end: String(value?.end || value?.to || ''),
            }
            syncing = true
            controller(startField)?.setValue(next.start, notify)
            controller(endField)?.setValue(next.end, notify)
            syncing = false
            aggregate.value = JSON.stringify(next)
            updateInvalid(next)
            if (updateCalendar) syncCalendar(next)
            if (notify) emit(next)
            return next
        }
        const combineDateAndTime = (date, current) => {
            if (config.granularity === 'day') return date
            const fallback = config.granularity === 'second' ? '00:00:00' : '00:00'
            return `${date}T${timePart(current) || fallback}`
        }
        const overlay = AppUI.overlay.create({
            root,
            trigger: wrapper,
            panel: popover,
            placement: 'bottom-center',
            matchWidth: false,
            offset: 13,
            toggleOnTriggerClick: false,
            onOpen: () => {
                root.dataset.open = 'true'
                trigger.setAttribute('aria-expanded', 'true')
                if (focusCalendarOnOpen) requestAnimationFrame(() => AppUI.get(calendar)?.focus())
                focusCalendarOnOpen = false
            },
            onClose: () => {
                root.dataset.open = 'false'
                trigger.setAttribute('aria-expanded', 'false')
            },
        })

        trigger.addEventListener('click', event => {
            event.preventDefault()
            focusCalendarOnOpen = false
            overlay.isOpen() ? overlay.close(false) : overlay.open(trigger)
        }, {signal: listeners.signal})

        root.addEventListener('pointerenter', () => {
            if (root.dataset.disabled !== 'true') root.dataset.hover = 'true'
        }, {signal: listeners.signal})
        root.addEventListener('pointerleave', () => { root.dataset.hover = 'false' }, {signal: listeners.signal})
        root.addEventListener('focusin', event => {
            if (!wrapper.contains(event.target)) return
            root.dataset.focus = 'true'
            root.dataset.focusVisible = String(event.target.matches(':focus-visible'))
        }, {signal: listeners.signal})
        root.addEventListener('focusout', event => {
            if (wrapper.contains(event.relatedTarget)) return
            root.dataset.focus = 'false'
            root.dataset.focusVisible = 'false'
        }, {signal: listeners.signal})

        for (const field of [startField, endField]) {
            field.addEventListener('app-ui:date-input:change', () => {
                if (syncing) return
                const value = rangeValue()
                aggregate.value = JSON.stringify(value)
                updateInvalid(value)
                syncCalendar(value)
                emit(value)
            }, {signal: listeners.signal})
        }

        calendar.addEventListener('app-ui:range-calendar:change', event => {
            const dates = event.detail?.value
            if (!dates || typeof dates !== 'object') return
            const current = rangeValue()
            const value = {
                start: dates.start ? combineDateAndTime(dates.start, current.start) : '',
                end: dates.end ? combineDateAndTime(dates.end, current.end) : '',
            }
            setRange(value, true, false)
            if (dates.end && config.granularity === 'day') overlay.close(true)
        }, {signal: listeners.signal})

        for (const timeField of [startTime, endTime]) {
            timeField?.addEventListener('app-ui:time-input:change', event => {
                if (syncing) return
                const part = timeField.dataset.rangeTime
                const value = rangeValue()
                const date = datePart(value[part]) || AppUI.get(calendar)?.getValue()?.[part]
                if (!date) return
                value[part] = `${date}T${event.detail.value}`
                setRange(value, true, false)
            }, {signal: listeners.signal})
        }

        clearButton?.addEventListener('click', () => setRange({start: '', end: ''}, true), {signal: listeners.signal})

        root.addEventListener('keydown', event => {
            if (root.dataset.disabled === 'true') return
            if (event.altKey && event.key === 'ArrowDown') {
                event.preventDefault()
                focusCalendarOnOpen = true
                overlay.open(trigger)
            }
        }, {signal: listeners.signal})
        document.addEventListener('keydown', event => {
            if (event.key !== 'Escape' || !overlay.isOpen()) return
            event.preventDefault()
            overlay.close(true)
        }, {signal: listeners.signal})

        const initial = rangeValue()
        aggregate.value = JSON.stringify(initial)
        updateInvalid(initial)
        if (config.disableAnimation || matchMedia('(prefers-reduced-motion: reduce)').matches) root.dataset.disableAnimation = 'true'

        return {
            getValue: rangeValue,
            setValue: (value, notify = false) => setRange(value, notify),
            open: () => overlay.open(trigger),
            close: overlay.close,
            focus: () => controller(startField)?.focus(),
            destroy: () => {
                listeners.abort()
                overlay.destroy()
            },
        }
    }

    AppUI.register('date-picker', '[data-ui-component="date-picker"]', registerDatePicker)
    AppUI.register('date-range-picker', '[data-ui-component="date-range-picker"]', root => registerRangePicker('date-range-picker', root))
})(window, document)
