import { bindNavigationSearchInput, readSearchIndex } from './navigation-search';

function closeMobileMenu() {
    const toggle = document.querySelector('[data-mobile-nav-toggle]');
    const panel = document.querySelector('[data-mobile-nav-panel]');
    const drawer = document.querySelector('[data-mobile-nav-drawer]');

    if (!toggle || !panel || toggle.getAttribute('aria-expanded') !== 'true') {
        return;
    }

    drawer?.setAttribute('data-open', 'false');

    window.setTimeout(() => {
        panel.classList.add('hidden');
        panel.setAttribute('aria-hidden', 'true');
        toggle.setAttribute('aria-expanded', 'false');
        document.body.classList.remove('overflow-hidden');
    }, 220);
}

export function initPublicNavigationSearch() {
    const panel = document.querySelector('[data-public-nav-search-panel]');
    const input = document.querySelector('[data-public-nav-search-input]');
    const results = document.querySelector('[data-public-nav-search-results]');
    const openButtons = document.querySelectorAll('[data-public-nav-search-open]');
    const closeButtons = document.querySelectorAll('[data-public-nav-search-close]');
    const backdrop = document.querySelector('[data-public-nav-search-backdrop]');

    if (!panel || !input || !results || openButtons.length === 0) {
        return;
    }

    const index = readSearchIndex('public-navigation-index');
    let searchController = null;

    const closeSearch = () => {
        panel.classList.add('hidden');
        panel.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('overflow-hidden');
        input.value = '';
        searchController?.closeResults();
        openButtons.forEach((button) => button.setAttribute('aria-expanded', 'false'));
    };

    const openSearch = () => {
        closeMobileMenu();
        panel.classList.remove('hidden');
        panel.setAttribute('aria-hidden', 'false');
        document.body.classList.add('overflow-hidden');
        openButtons.forEach((button) => button.setAttribute('aria-expanded', 'true'));
        window.requestAnimationFrame(() => input.focus());
    };

    if (!searchController) {
        searchController = bindNavigationSearchInput(input, results, index, {
            emptyMessage: 'No matching pages found.',
            onEscape: closeSearch,
        });
    }

    openButtons.forEach((button) => {
        button.addEventListener('click', openSearch);
    });

    closeButtons.forEach((button) => button.addEventListener('click', closeSearch));
    backdrop?.addEventListener('click', closeSearch);

    document.addEventListener('keydown', (event) => {
        const isShortcut = (event.metaKey || event.ctrlKey) && event.key.toLowerCase() === 'k';
        const isEscape = event.key === 'Escape' && panel.getAttribute('aria-hidden') === 'false';

        if (isShortcut) {
            event.preventDefault();
            openSearch();
        }

        if (isEscape) {
            event.preventDefault();
            closeSearch();
            openButtons[0]?.focus();
        }
    });
}
