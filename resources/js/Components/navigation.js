/**
 * Global navigation behaviors — mobile menu toggle, scroll lock, keyboard support.
 */
export function initNavigation() {
    const toggle = document.querySelector('[data-mobile-nav-toggle]');
    const panel = document.querySelector('[data-mobile-nav-panel]');
    const backdrop = document.querySelector('[data-mobile-nav-backdrop]');
    const closeButtons = document.querySelectorAll('[data-mobile-nav-close]');
    const navLinks = document.querySelectorAll('[data-mobile-nav-link]');

    if (!toggle || !panel) {
        return;
    }

    const openMenu = () => {
        panel.classList.remove('hidden');
        panel.setAttribute('aria-hidden', 'false');
        toggle.setAttribute('aria-expanded', 'true');
        document.body.classList.add('overflow-hidden');
    };

    const closeMenu = () => {
        panel.classList.add('hidden');
        panel.setAttribute('aria-hidden', 'true');
        toggle.setAttribute('aria-expanded', 'false');
        document.body.classList.remove('overflow-hidden');
    };

    toggle.addEventListener('click', () => {
        const isOpen = toggle.getAttribute('aria-expanded') === 'true';
        isOpen ? closeMenu() : openMenu();
    });

    backdrop?.addEventListener('click', closeMenu);
    closeButtons.forEach((button) => button.addEventListener('click', closeMenu));
    navLinks.forEach((link) => link.addEventListener('click', closeMenu));

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && toggle.getAttribute('aria-expanded') === 'true') {
            closeMenu();
            toggle.focus();
        }
    });
}
