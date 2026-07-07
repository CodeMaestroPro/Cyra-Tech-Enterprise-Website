/**
 * Contact page — preselect inquiry type from query string.
 */
export function initContactPage() {
    const select = document.getElementById('inquiry-type');

    if (!select) {
        return;
    }

    const params = new URLSearchParams(window.location.search);
    const inquiry = params.get('inquiry') || params.get('type');

    if (!inquiry) {
        return;
    }

    const option = select.querySelector(`option[value="${inquiry}"]`);

    if (option) {
        select.value = inquiry;
    }
}
