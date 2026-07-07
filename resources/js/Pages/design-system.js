import { initModals, initTabs } from '../components/ui';

export function initDesignSystemPage() {
    if (!document.querySelector('[data-design-system-page]')) {
        return;
    }

    initModals();
    initTabs();
}
