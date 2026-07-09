/**
 * Client portal mobile sidebar drawer.
 */
export function initPortalNavigation() {
    const toggle = document.querySelector('[data-portal-nav-toggle]');
    const panel = document.querySelector('[data-portal-nav-panel]');
    const drawer = document.querySelector('[data-portal-nav-drawer]');
    const backdrop = document.querySelector('[data-portal-nav-backdrop]');
    const closeButtons = document.querySelectorAll('[data-portal-nav-close]');
    const navLinks = document.querySelectorAll('[data-portal-nav-link]');

    if (!toggle || !panel || !drawer) {
        return;
    }

    const openMenu = () => {
        panel.classList.remove('hidden');
        panel.setAttribute('aria-hidden', 'false');
        toggle.setAttribute('aria-expanded', 'true');
        drawer.dataset.open = 'true';
        document.body.classList.add('overflow-hidden');
    };

    const closeMenu = () => {
        drawer.dataset.open = 'false';

        window.setTimeout(() => {
            panel.classList.add('hidden');
            panel.setAttribute('aria-hidden', 'true');
            toggle.setAttribute('aria-expanded', 'false');
            document.body.classList.remove('overflow-hidden');
        }, 220);
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
