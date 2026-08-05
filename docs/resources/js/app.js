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
        .map((link) => document.querySelector(link.getAttribute('href')))
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

const initializeDocs = () => {
    initializeDocsSidebar()
    initializePageIndex()
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
