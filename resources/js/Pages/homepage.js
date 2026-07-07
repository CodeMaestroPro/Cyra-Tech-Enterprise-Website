/**
 * Homepage interactions — animated statistics counters on scroll.
 */
export function initHomepage() {
    const statElements = document.querySelectorAll('[data-stat-value]');

    if (statElements.length === 0) {
        return;
    }

    const animateValue = (element) => {
        const target = Number(element.dataset.statValue || 0);
        const suffix = element.dataset.statSuffix || '';
        const duration = 1200;
        const start = performance.now();

        const tick = (now) => {
            const progress = Math.min((now - start) / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 3);
            const value = Math.round(target * eased);
            element.textContent = `${value}${suffix}`;

            if (progress < 1) {
                requestAnimationFrame(tick);
            }
        };

        requestAnimationFrame(tick);
    };

    const observer = new IntersectionObserver(
        (entries, currentObserver) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) {
                    return;
                }

                animateValue(entry.target);
                currentObserver.unobserve(entry.target);
            });
        },
        { threshold: 0.35 },
    );

    statElements.forEach((element) => observer.observe(element));
}
