/**
 * Scroll-triggered motion for elements marked with [data-animate].
 */
export function initMotion() {
    const elements = document.querySelectorAll('[data-animate]');

    if (elements.length === 0) {
        return;
    }

    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (prefersReducedMotion) {
        elements.forEach((element) => element.classList.add('is-visible'));

        return;
    }

    const observer = new IntersectionObserver(
        (entries, currentObserver) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) {
                    return;
                }

                entry.target.classList.add('is-visible');
                currentObserver.unobserve(entry.target);
            });
        },
        {
            threshold: 0.12,
            rootMargin: '0px 0px -5% 0px',
        },
    );

    elements.forEach((element, index) => {
        const delay = element.dataset.animateDelay ?? element.dataset.animateStagger;

        if (delay === undefined && element.closest('[data-animate-stagger]')) {
            const staggerRoot = element.closest('[data-animate-stagger]');
            const staggerIndex = Array.from(staggerRoot.querySelectorAll('[data-animate]')).indexOf(element);
            element.style.setProperty('--cyra-animate-delay', `${staggerIndex * 90}ms`);
        } else if (delay !== undefined) {
            element.style.setProperty('--cyra-animate-delay', `${delay}ms`);
        } else {
            element.style.setProperty('--cyra-animate-delay', `${Math.min(index, 6) * 60}ms`);
        }

        observer.observe(element);
    });
}
