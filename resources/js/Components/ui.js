export function initModals() {
    document.querySelectorAll('[data-cyra-modal-open]').forEach((trigger) => {
        trigger.addEventListener('click', () => {
            const targetId = trigger.getAttribute('data-cyra-modal-open');
            const modal = document.getElementById(targetId);

            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        });
    });

    document.querySelectorAll('[data-cyra-modal]').forEach((modal) => {
        modal.querySelectorAll('[data-cyra-modal-close]').forEach((closeButton) => {
            closeButton.addEventListener('click', () => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            });
        });

        modal.addEventListener('click', (event) => {
            if (event.target === modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        });
    });
}

export function initTabs() {
    document.querySelectorAll('[data-cyra-tabs]').forEach((tabsRoot) => {
        const triggers = tabsRoot.querySelectorAll('[data-cyra-tab-trigger]');
        const panels = tabsRoot.querySelectorAll('[data-cyra-tab-panel]');

        triggers.forEach((trigger) => {
            trigger.addEventListener('click', () => {
                const index = trigger.getAttribute('data-cyra-tab-trigger');

                triggers.forEach((item) => {
                    const isActive = item.getAttribute('data-cyra-tab-trigger') === index;
                    item.setAttribute('aria-selected', isActive ? 'true' : 'false');
                    item.classList.toggle('cyra-tab-active', isActive);
                    item.classList.toggle('cyra-tab-inactive', !isActive);
                });

                panels.forEach((panel) => {
                    const isActive = panel.getAttribute('data-cyra-tab-panel') === index;
                    panel.classList.toggle('hidden', !isActive);
                });
            });
        });
    });
}
