export function normalizeQuery(value) {
    return value.trim().toLowerCase();
}

export function scoreEntry(entry, query) {
    const label = entry.label.toLowerCase();
    const group = (entry.group || '').toLowerCase();
    const haystack = `${label} ${group}`;

    if (label === query) {
        return 100;
    }

    if (label.startsWith(query)) {
        return 80;
    }

    if (label.includes(query)) {
        return 60;
    }

    if (group.includes(query)) {
        return 40;
    }

    if (haystack.includes(query)) {
        return 20;
    }

    return 0;
}

export function filterEntries(index, query, limit = 8) {
    if (!query) {
        return [];
    }

    return index
        .map((entry) => ({ entry, score: scoreEntry(entry, query) }))
        .filter(({ score }) => score > 0)
        .sort((left, right) => {
            if (right.score !== left.score) {
                return right.score - left.score;
            }

            return left.entry.label.localeCompare(right.entry.label);
        })
        .slice(0, limit)
        .map(({ entry }) => entry);
}

export function renderSearchResults(container, results, activeIndex, options = {}) {
    const {
        resultAttribute = 'data-nav-search-result',
        emptyMessage = 'No matching pages found.',
    } = options;

    container.innerHTML = '';

    if (results.length === 0) {
        const empty = document.createElement('p');
        empty.className = 'px-4 py-3 text-sm text-cyra-muted';
        empty.textContent = emptyMessage;
        container.appendChild(empty);

        return;
    }

    results.forEach((entry, index) => {
        const link = document.createElement('a');
        link.href = entry.url;
        link.className = [
            'flex flex-col gap-0.5 px-4 py-2.5 text-sm transition-colors',
            index === activeIndex
                ? 'bg-cyra-primary/15 text-cyra-text'
                : 'text-cyra-text hover:bg-cyra-soft',
        ].join(' ');
        link.dataset.navSearchResult = 'true';
        link.setAttribute(resultAttribute, 'true');
        link.dataset.index = String(index);

        if (entry.opens_in_new_tab) {
            link.target = '_blank';
            link.rel = 'noreferrer';
        }

        const label = document.createElement('span');
        label.className = 'font-medium';
        label.textContent = entry.label;

        const group = document.createElement('span');
        group.className = 'text-xs text-cyra-muted';
        group.textContent = entry.group;

        link.append(label, group);
        container.appendChild(link);
    });
}

export function bindNavigationSearchInput(input, panel, index, options = {}) {
    const {
        resultAttribute = 'data-nav-search-result',
        emptyMessage = 'No matching pages found.',
        onNavigate,
    } = options;

    let results = [];
    let activeIndex = -1;
    let isOpen = false;

    const closeResults = () => {
        isOpen = false;
        activeIndex = -1;
        panel.hidden = true;
        input.setAttribute('aria-expanded', 'false');
    };

    const openResults = () => {
        isOpen = true;
        panel.hidden = false;
        input.setAttribute('aria-expanded', 'true');
    };

    const refreshResults = () => {
        const query = normalizeQuery(input.value);
        results = filterEntries(index, query, options.limit ?? 8);
        activeIndex = results.length > 0 ? 0 : -1;
        renderSearchResults(panel, results, activeIndex, { resultAttribute, emptyMessage });

        if (query) {
            openResults();
        } else {
            closeResults();
        }
    };

    const navigateToActive = () => {
        if (activeIndex < 0 || !results[activeIndex]) {
            return;
        }

        if (typeof onNavigate === 'function') {
            onNavigate(results[activeIndex]);
        }

        window.location.href = results[activeIndex].url;
    };

    input.addEventListener('input', refreshResults);

    input.addEventListener('focus', () => {
        if (normalizeQuery(input.value)) {
            refreshResults();
        }
    });

    input.addEventListener('keydown', (event) => {
        if (!isOpen && event.key === 'ArrowDown' && normalizeQuery(input.value)) {
            refreshResults();
        }

        if (!isOpen) {
            return;
        }

        if (event.key === 'ArrowDown') {
            event.preventDefault();
            activeIndex = Math.min(activeIndex + 1, results.length - 1);
            renderSearchResults(panel, results, activeIndex, { resultAttribute, emptyMessage });
        }

        if (event.key === 'ArrowUp') {
            event.preventDefault();
            activeIndex = Math.max(activeIndex - 1, 0);
            renderSearchResults(panel, results, activeIndex, { resultAttribute, emptyMessage });
        }

        if (event.key === 'Enter') {
            event.preventDefault();
            navigateToActive();
        }

        if (event.key === 'Escape') {
            event.preventDefault();
            closeResults();
            options.onEscape?.();
        }
    });

    panel.addEventListener('mousemove', (event) => {
        const target = event.target.closest(`[${resultAttribute}]`);

        if (!target) {
            return;
        }

        activeIndex = Number.parseInt(target.dataset.index || '-1', 10);
        renderSearchResults(panel, results, activeIndex, { resultAttribute, emptyMessage });
    });

    const handleOutsideClick = (event) => {
        if (event.target === input || panel.contains(event.target)) {
            return;
        }

        closeResults();
    };

    document.addEventListener('click', handleOutsideClick);

    return {
        refreshResults,
        closeResults,
        destroy() {
            document.removeEventListener('click', handleOutsideClick);
        },
    };
}

export function readSearchIndex(elementId) {
    const source = document.getElementById(elementId);

    if (!source) {
        return [];
    }

    try {
        const parsed = JSON.parse(source.textContent || '[]');

        return Array.isArray(parsed) ? parsed : [];
    } catch {
        return [];
    }
}
