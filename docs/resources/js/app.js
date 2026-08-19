// JDS 원본을 직접 가져와 문서 화면에서 Vite HMR로 확인
import '../../../package/resources/js/jds.js'

const initializeDocsSidebar = () => {
    const links = document.querySelector('.jds-docs-sidebar-links')
    const search = document.querySelector('[data-docs-component-search]')

    if (!links) {
        return
    }

    if (search) {
        const componentLinks = Array.from(links.querySelectorAll('a'))
        const empty = links.querySelector('[data-docs-search-empty]')

        search.addEventListener('input', () => {
            const query = search.value.trim().toLocaleLowerCase('ko')
            let visibleCount = 0

            componentLinks.forEach((link) => {
                const visible = !query || link.textContent.trim().toLocaleLowerCase('ko').includes(query)
                link.hidden = !visible
                visibleCount += visible ? 1 : 0
            })

            if (empty) {
                empty.hidden = visibleCount !== 0
            }
        })
    }

    let scrollTimer = null

    links.addEventListener('scroll', () => {
        links.classList.add('is-scrolling')
        window.clearTimeout(scrollTimer)
        scrollTimer = window.setTimeout(() => {
            links.classList.remove('is-scrolling')
        }, 650)
    }, {passive: true})

    const activeLink = links.querySelector('[aria-current="page"]')

    if (!activeLink) {
        return
    }

    window.requestAnimationFrame(() => {
        const linksRect = links.getBoundingClientRect()
        const activeRect = activeLink.getBoundingClientRect()
        const isOutsideView = activeRect.top < linksRect.top || activeRect.bottom > linksRect.bottom

        if (isOutsideView) {
            links.scrollTop = activeLink.offsetTop
                - links.clientHeight / 2
                + activeLink.offsetHeight / 2
        }
    })
}

const initializePageIndex = () => {
    const index = document.querySelector('.jds-docs-page-index')

    if (!index || !('IntersectionObserver' in window)) {
        return
    }

    const links = Array.from(index.querySelectorAll('a[href^="#"]'))
    links[0]?.setAttribute('aria-current', 'location')
    const targets = links
        .map((link) => {
            const hash = link.getAttribute('href')?.slice(1)
            return hash ? document.getElementById(decodeURIComponent(hash)) : null
        })
        .filter(Boolean)

    const setActive = (id) => {
        links.forEach((link) => {
            if (link.getAttribute('href') === `#${id}`) {
                link.setAttribute('aria-current', 'location')
            } else {
                link.removeAttribute('aria-current')
            }
        })
    }

    const observer = new IntersectionObserver((entries) => {
        const visible = entries
            .filter((entry) => entry.isIntersecting)
            .sort((a, b) => a.boundingClientRect.top - b.boundingClientRect.top)

        if (visible[0]) {
            setActive(visible[0].target.id)
        }
    }, {rootMargin: '-12% 0px -72% 0px', threshold: 0})

    targets.forEach((target) => observer.observe(target))
}

const initializeThemeToggle = () => {
    const toggle = document.querySelector('[data-docs-theme-toggle]')
    if (!toggle) return

    const sync = () => {
        const dark = document.documentElement.dataset.theme === 'dark'
        toggle.setAttribute('aria-label', dark ? '라이트 모드로 전환' : '다크 모드로 전환')
        toggle.setAttribute('aria-pressed', String(dark))
    }

    toggle.addEventListener('click', () => {
        const next = document.documentElement.dataset.theme === 'dark' ? 'light' : 'dark'
        document.documentElement.dataset.theme = next
        try { localStorage.setItem('jds-docs-theme', next) } catch {}
        sync()
    })

    sync()
}

const initializeIconGallery = () => {
    const gallery = document.querySelector('[data-icon-gallery]')
    if (!gallery) return

    const search = gallery.querySelector('[data-icon-search]')
    const list = gallery.querySelector('[data-icon-list]')
    const names = JSON.parse(gallery.querySelector('[data-icon-names]')?.textContent || '[]')
    const count = gallery.querySelector('[data-icon-count]')
    const empty = gallery.querySelector('[data-icon-empty]')
    const fragment = document.createDocumentFragment()

    names.forEach(name => {
        const card = document.createElement('button')
        card.type = 'button'
        card.className = 'jds-icon-gallery-item'
        card.dataset.iconCard = ''
        card.dataset.iconName = name
        card.setAttribute('aria-label', `${name} 사용 코드 복사`)

        const icon = document.createElement('span')
        icon.dataset.slot = 'icon'
        icon.dataset.icon = name
        icon.className = 'app-icon'
        icon.setAttribute('role', 'img')
        icon.setAttribute('aria-hidden', 'true')

        const code = document.createElement('code')
        code.textContent = name

        const copy = document.createElement('span')
        copy.className = 'jds-icon-copy-label'
        copy.dataset.iconCopyLabel = ''
        copy.textContent = '복사'

        card.append(icon, code, copy)
        fragment.append(card)
    })

    list?.append(fragment)
    const cards = Array.from(gallery.querySelectorAll('[data-icon-card]'))

    const filter = () => {
        const query = search?.value.trim().toLocaleLowerCase() || ''
        let visible = 0

        cards.forEach(card => {
            const matches = !query || card.dataset.iconName.toLocaleLowerCase().includes(query)
            card.hidden = !matches
            visible += matches ? 1 : 0
        })

        if (count) count.textContent = `${visible.toLocaleString('ko-KR')}개`
        if (empty) empty.hidden = visible !== 0
    }

    search?.addEventListener('input', filter)
    gallery.addEventListener('click', async event => {
        const card = event.target.closest('[data-icon-card]')
        if (!card) return

        const code = `<x-icon name="${card.dataset.iconName}" />`
        await navigator.clipboard.writeText(code)
        const label = card.querySelector('[data-icon-copy-label]')
        if (!label) return
        label.textContent = '복사됨'
        label.style.opacity = '1'
        window.setTimeout(() => {
            label.textContent = '복사'
            label.style.removeProperty('opacity')
        }, 1200)
    })
}

const initializeListboxDemos = () => {
    document.querySelectorAll('[data-listbox-demo]').forEach(demo => {
        if (demo.dataset.ready === 'true') return
        demo.dataset.ready = 'true'
        const listbox = demo.querySelector('[data-ui-component="listbox"]')
        if (!listbox) return

        listbox.addEventListener('app-ui:listbox:change', event => {
            const output = demo.querySelector('[data-listbox-selected-value]')
            if (!output) return
            const values = event.detail?.values || []
            output.textContent = `선택한 값: ${values.join(', ') || '없음'}`
        })

        demo.addEventListener('change', event => {
            const control = event.target.closest('[data-listbox-demo-control]')
            if (!control) return
            const type = control.dataset.listboxDemoControl
            const value = control.matches('input') ? control.value : control.querySelector('input')?.value
            if (!value) return

            if (type === 'variant') listbox.dataset.variant = value
            if (type === 'color') {
                listbox.classList.remove('app-color-default', 'app-color-primary', 'app-color-secondary', 'app-color-success', 'app-color-warning', 'app-color-danger')
                listbox.classList.add(`app-color-${value}`)
                listbox.dataset.color = value
            }
        })
    })
}

const initializeDocs = () => {
    // Keep the global theme control independent from page-specific navigation.
    // A malformed or unusual heading id must never disable the theme toggle.
    initializeThemeToggle()
    initializeDocsSidebar()
    initializePageIndex()
    initializeIconGallery()
    initializeListboxDemos()
    document.addEventListener('click', async (event) => {
        const button = event.target.closest('[data-docs-copy]')
        if (!button) return
        await navigator.clipboard.writeText(atob(button.dataset.docsCopy))
        const label = button.textContent
        button.textContent = '복사됨'
        window.setTimeout(() => { button.textContent = label }, 1200)
    })
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeDocs, {once: true})
} else {
    initializeDocs()
}
