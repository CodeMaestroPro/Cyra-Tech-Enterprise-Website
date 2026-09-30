/**
 * IT student registration — multi-step wizard + success SweetAlert.
 */
export function initItRegistrationPage() {
    const page = document.querySelector('[data-it-registration-page]');
    if (!page) {
        return;
    }

    initWizard(page);
    initSuccessAlert(page);
}

function initWizard(page) {
    const form = page.querySelector('[data-it-wizard]');
    if (!form) {
        return;
    }

    const panels = [...form.querySelectorAll('[data-step-panel]')];
    const indicators = [...form.querySelectorAll('[data-step-indicator]')];
    const prevBtn = form.querySelector('[data-it-prev]');
    const nextBtn = form.querySelector('[data-it-next]');
    const submitBtn = form.querySelector('[data-it-submit]');
    const stepMeta = form.querySelector('[data-it-step-meta]');
    const totalSteps = panels.length;
    let currentStep = Number(form.dataset.initialStep || 1);

    const stepFields = {
        1: ['name', 'email', 'phone'],
        2: ['institution', 'course_of_study', 'academic_level'],
        3: ['interest_area', 'availability', 'motivation'],
    };

    const showStep = (step) => {
        currentStep = step;

        panels.forEach((panel) => {
            const panelStep = Number(panel.dataset.stepPanel);
            panel.classList.toggle('hidden', panelStep !== step);
        });

        indicators.forEach((indicator) => {
            const indicatorStep = Number(indicator.dataset.stepIndicator);
            indicator.classList.toggle('is-active', indicatorStep === step);
            indicator.classList.toggle('is-complete', indicatorStep < step);
        });

        prevBtn?.classList.toggle('hidden', step === 1);
        nextBtn?.classList.toggle('hidden', step === totalSteps);
        submitBtn?.classList.toggle('hidden', step !== totalSteps);

        if (stepMeta) {
            stepMeta.textContent = `Step ${step} of ${totalSteps}`;
        }
    };

    const validateStep = (step) => {
        const fields = stepFields[step] || [];
        let valid = true;

        fields.forEach((name) => {
            const field = form.querySelector(`[name="${name}"]`);
            if (!field) {
                return;
            }

            const value = (field.value || '').trim();
            const fieldValid = field.checkValidity() && value !== '';

            field.classList.toggle('cyra-input-error', !fieldValid);
            if (fieldValid) {
                field.setCustomValidity('');
            } else {
                field.setCustomValidity('Required');
                valid = false;
            }
        });

        if (!valid) {
            form.reportValidity();
        }

        return valid;
    };

    nextBtn?.addEventListener('click', () => {
        if (!validateStep(currentStep)) {
            return;
        }

        if (currentStep < totalSteps) {
            showStep(currentStep + 1);
        }
    });

    prevBtn?.addEventListener('click', () => {
        if (currentStep > 1) {
            showStep(currentStep - 1);
        }
    });

    form.addEventListener('submit', (event) => {
        if (!validateStep(currentStep)) {
            event.preventDefault();
        }
    });

    showStep(currentStep);
}

function initSuccessAlert(page) {
    const success = page.querySelector('[data-it-success]');
    if (!success || typeof window.Swal === 'undefined') {
        return;
    }

    const title = success.dataset.successTitle || 'Registration Successful';
    const text = success.dataset.successText || 'Your registration was successful.';
    const reference = success.dataset.successReference || '';
    const hint = success.dataset.whatsappHint || 'WhatsApp will open with your acknowledgement already filled in. Just tap Send.';

    let links = [];
    try {
        links = JSON.parse(success.dataset.whatsappLinks || '[]');
    } catch {
        links = [];
    }

    const primary = links[0] || null;
    const confirmText = primary ? 'Send acknowledgement on WhatsApp' : 'View acknowledgement slip';

    window.Swal.fire({
        icon: 'success',
        title,
        html: `
            <p style="margin:0 0 8px;line-height:1.5;">${escapeHtml(text)}</p>
            ${reference ? `<p style="margin:0 0 12px;font-family:ui-monospace,monospace;font-size:13px;color:#059669;"><strong>Reference:</strong> ${escapeHtml(reference)}</p>` : ''}
            <p style="margin:0;font-size:13px;line-height:1.5;color:#64748b;">${escapeHtml(hint)}</p>
        `,
        confirmButtonText: confirmText,
        confirmButtonColor: primary ? '#25D366' : '#0052ff',
        showCancelButton: true,
        cancelButtonText: 'View slip on page',
        reverseButtons: true,
        allowOutsideClick: false,
        customClass: {
            popup: 'cyra-it-swal',
            confirmButton: 'cyra-it-swal-confirm',
        },
    }).then((result) => {
        if (result.isConfirmed && primary?.url) {
            window.open(primary.url, '_blank', 'noopener,noreferrer');
        }

        success.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
}

function escapeHtml(value) {
    return String(value)
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
}
