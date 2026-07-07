/**
 * Leadership page — profile detail modal interactions.
 */
export function initLeadershipPage() {
    const modal = document.getElementById('leadership-profile-modal');
    const title = document.getElementById('leadership-profile-modal-title');
    const role = document.getElementById('leadership-profile-modal-role');
    const bio = document.getElementById('leadership-profile-modal-bio');
    const avatar = document.getElementById('leadership-profile-modal-avatar');
    const links = document.getElementById('leadership-profile-modal-links');
    const openButtons = document.querySelectorAll('[data-leadership-open]');

    if (!modal || openButtons.length === 0) {
        return;
    }

    const openModal = (button) => {
        let profile = {};

        try {
            profile = JSON.parse(button.dataset.profile || '{}');
        } catch {
            return;
        }

        title.textContent = profile.name || '';
        role.textContent = profile.title || '';
        bio.textContent = profile.bio || '';
        avatar.textContent = profile.initials || '';

        links.innerHTML = '';

        if (profile.linkedin_url) {
            const linkedin = document.createElement('a');
            linkedin.href = profile.linkedin_url;
            linkedin.target = '_blank';
            linkedin.rel = 'noreferrer';
            linkedin.className = 'text-sm font-medium text-cyra-accent hover:text-cyra-primary';
            linkedin.textContent = 'LinkedIn profile';
            links.appendChild(linkedin);
        }

        if (profile.email) {
            const email = document.createElement('a');
            email.href = `mailto:${profile.email}`;
            email.className = 'text-sm font-medium text-cyra-accent hover:text-cyra-primary';
            email.textContent = 'Email leader';
            links.appendChild(email);
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
    };

    const closeModal = () => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
    };

    openButtons.forEach((button) => {
        button.addEventListener('click', () => openModal(button));
    });

    modal.querySelectorAll('[data-cyra-modal-close]').forEach((button) => {
        button.addEventListener('click', closeModal);
    });

    modal.addEventListener('click', (event) => {
        if (event.target === modal) {
            closeModal();
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeModal();
        }
    });
}
