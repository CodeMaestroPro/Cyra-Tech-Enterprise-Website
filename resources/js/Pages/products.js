/**
 * Products page — category filter for product cards.
 */
export function initProductsPage() {
    const filters = document.querySelectorAll('[data-product-filter]');
    const cards = document.querySelectorAll('[data-product-card]');

    if (filters.length === 0 || cards.length === 0) {
        return;
    }

    const setActiveFilter = (button) => {
        filters.forEach((filter) => {
            const isActive = filter === button;
            filter.setAttribute('aria-selected', isActive ? 'true' : 'false');
            filter.classList.toggle('border-cyra-primary', isActive);
            filter.classList.toggle('bg-cyra-primary/15', isActive);
            filter.classList.toggle('text-cyra-text', isActive);
            filter.classList.toggle('border-cyra-border', !isActive);
            filter.classList.toggle('text-cyra-muted', !isActive);
        });
    };

    const applyFilter = (category) => {
        cards.forEach((card) => {
            const matches = category === 'all' || card.dataset.productCategory === category;
            card.classList.toggle('hidden', !matches);
        });
    };

    filters.forEach((button) => {
        button.addEventListener('click', () => {
            setActiveFilter(button);
            applyFilter(button.dataset.productFilter || 'all');
        });
    });
}
