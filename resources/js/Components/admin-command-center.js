import { bindNavigationSearchInput, readSearchIndex } from './navigation-search';

export function initAdminCommandCenter() {
    const input = document.querySelector('[data-admin-command-center-search]');
    const panel = document.querySelector('[data-admin-command-center-results]');

    if (!input || !panel) {
        return;
    }

    const index = readSearchIndex('admin-command-center-index');

    bindNavigationSearchInput(input, panel, index, {
        emptyMessage: 'No matching modules found.',
    });
}
