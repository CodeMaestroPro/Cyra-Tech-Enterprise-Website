export function initNewsletterForm() {
    if (window.location.hash !== '#newsletter-signup') {
        return;
    }

    const target = document.getElementById('newsletter-signup');

    if (!target) {
        return;
    }

    window.requestAnimationFrame(() => {
        target.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    });
}
