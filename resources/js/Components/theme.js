const STORAGE_KEY = 'cyra-theme';

export function getStoredTheme() {
    try {
        const stored = localStorage.getItem(STORAGE_KEY);

        return stored === 'light' ? 'light' : 'dark';
    } catch {
        return 'dark';
    }
}

export function applyTheme(theme) {
    const resolved = theme === 'light' ? 'light' : 'dark';

    document.documentElement.setAttribute('data-cyra-theme', resolved);
    document.documentElement.style.colorScheme = resolved;

    const metaTheme = document.querySelector('meta[name="theme-color"]');
    if (metaTheme) {
        metaTheme.setAttribute('content', resolved === 'light' ? '#f1f5f9' : '#050810');
    }

    document.querySelectorAll('[data-cyra-theme-toggle]').forEach((button) => {
        const label = resolved === 'light' ? 'Switch to dark mode' : 'Switch to light mode';
        button.setAttribute('aria-label', label);
        button.setAttribute('title', label);
        button.setAttribute('aria-pressed', resolved === 'light' ? 'true' : 'false');
    });
}

export function setTheme(theme) {
    const resolved = theme === 'light' ? 'light' : 'dark';

    try {
        localStorage.setItem(STORAGE_KEY, resolved);
    } catch {
        // Ignore storage failures (private browsing, etc.).
    }

    applyTheme(resolved);
}

export function toggleTheme() {
    setTheme(getStoredTheme() === 'light' ? 'dark' : 'light');
}

export function initTheme() {
    applyTheme(getStoredTheme());

    document.querySelectorAll('[data-cyra-theme-toggle]').forEach((button) => {
        if (button.dataset.cyraThemeBound === 'true') {
            return;
        }

        button.dataset.cyraThemeBound = 'true';
        button.removeAttribute('disabled');
        button.addEventListener('click', toggleTheme);
    });
}
