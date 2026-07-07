export function initLoginPage() {
    const form = document.getElementById('login-form');
    const submit = document.getElementById('login-submit');

    if (!form || !submit) {
        return;
    }

    form.addEventListener('submit', () => {
        submit.disabled = true;
        submit.textContent = 'Signing in...';
    });
}
