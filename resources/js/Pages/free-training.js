/**
 * Free training onboarding — multi-step wizard + confirmation print/save.
 */
export function initFreeTrainingPage() {
    const page = document.querySelector('[data-free-training-page]');
    if (!page) {
        return;
    }

    initWizard(page);
    initPrintActions(page);
    initSuccessAlert(page);
}

function initWizard(page) {
    const form = page.querySelector('[data-free-training-wizard]');
    if (!form || page.getAttribute('data-wizard-ready') === 'true') {
        return;
    }

    page.setAttribute('data-wizard-ready', 'true');

    const panels = [...form.querySelectorAll('[data-step-panel]')];
    const indicators = [...form.querySelectorAll('[data-step-indicator]')];
    const prevBtn = form.querySelector('[data-free-training-prev]');
    const nextBtn = form.querySelector('[data-free-training-next]');
    const submitBtn = form.querySelector('[data-free-training-submit]');
    const stepMeta = page.querySelector('[data-free-training-step-meta]');
    const progressFill = page.querySelector('[data-free-training-progress]');
    const courseList = form.querySelector('[data-free-training-course-list]');
    const totalSteps = panels.length;
    let currentStep = Number(form.dataset.initialStep || 1);
    let submitting = false;

    const stepFields = {
        1: ['name', 'email', 'phone', 'date_of_birth', 'gender', 'state', 'city'],
        2: ['program', 'skill_level', 'has_tech_experience'],
    };

    const updateProgress = (step) => {
        if (!progressFill) {
            return;
        }

        progressFill.style.width = `${(step / totalSteps) * 100}%`;
    };

    const updateCourseSelection = () => {
        const selected = form.querySelector('input[name="program"]:checked');
        const slug = selected?.value || '';

        courseList?.querySelectorAll('[data-program-option]').forEach((option) => {
            option.classList.toggle('is-selected', option.dataset.programOption === slug);
        });
    };

    const setHidden = (element, isHidden) => {
        if (!element) {
            return;
        }

        element.classList.toggle('cyra-tp-step-hidden', isHidden);
        element.classList.toggle('hidden', isHidden);
    };

    const setPanelEnabled = (panel, enabled) => {
        panel.querySelectorAll('input, select, textarea').forEach((field) => {
            field.disabled = !enabled;
        });
    };

    const showStep = (step) => {
        currentStep = step;

        panels.forEach((panel) => {
            const panelStep = Number(panel.dataset.stepPanel);
            const isActive = panelStep === step;

            setHidden(panel, !isActive);
            panel.classList.toggle('is-active', isActive);
            setPanelEnabled(panel, isActive);
        });

        indicators.forEach((indicator) => {
            const indicatorStep = Number(indicator.dataset.stepIndicator);
            indicator.classList.toggle('is-active', indicatorStep === step);
            indicator.classList.toggle('is-complete', indicatorStep < step);
        });

        setHidden(prevBtn, step === 1);
        setHidden(nextBtn, step === totalSteps);
        setHidden(submitBtn, step !== totalSteps);

        if (stepMeta) {
            stepMeta.textContent = `Step ${step} of ${totalSteps}`;
        }

        updateProgress(step);

        if (step === 2) {
            updateCourseSelection();
        }
    };

    const validateStep = (step) => {
        const fields = stepFields[step] || [];
        let valid = true;

        fields.forEach((name) => {
            if (['program', 'skill_level', 'has_tech_experience'].includes(name)) {
                const selected = form.querySelector(`input[name="${name}"]:checked`);
                if (!selected) {
                    valid = false;
                }
                return;
            }

            const field = form.querySelector(`[name="${name}"]`);
            if (!field || field.disabled) {
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

    courseList?.addEventListener('change', (event) => {
        if (event.target?.name === 'program') {
            updateCourseSelection();
        }
    });

    form.addEventListener('change', (event) => {
        const name = event.target?.name;
        if (name === 'skill_level' || name === 'has_tech_experience') {
            form.querySelectorAll(`input[name="${name}"]`).forEach((input) => {
                input.closest('.cyra-tp-choice')?.classList.toggle('is-selected', input.checked);
            });
        }
    });

    nextBtn?.addEventListener('click', (event) => {
        event.preventDefault();
        event.stopPropagation();

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
        if (submitting) {
            event.preventDefault();
            return;
        }

        panels.forEach((panel) => setPanelEnabled(panel, true));

        if (!validateStep(currentStep)) {
            event.preventDefault();
            showStep(currentStep);
            return;
        }

        submitting = true;

        window.setTimeout(() => {
            if (!submitBtn) {
                return;
            }

            submitBtn.disabled = true;
            submitBtn.textContent = 'Submitting...';
        }, 0);
    });

    showStep(currentStep);
}

function printConfirmation() {
    window.print();
}

function initPrintActions(page) {
    page.querySelectorAll('[data-free-training-print]').forEach((button) => {
        button.addEventListener('click', printConfirmation);
    });
}

function initSuccessAlert(page) {
    const success = page.querySelector('[data-free-training-success]');
    if (!success || typeof window.Swal === 'undefined') {
        return;
    }

    const title = success.dataset.successTitle || "You're enrolled";
    const text = success.dataset.successText || 'Your free training registration was successful.';
    const reference = success.dataset.successReference || '';
    const hint = success.dataset.receiptHint
        || 'Print this confirmation or save it as a PDF on your phone.';

    window.Swal.fire({
        icon: 'success',
        title,
        html: `
            <p style="margin:0 0 8px;line-height:1.5;">${escapeHtml(text)}</p>
            ${reference ? `<p style="margin:0 0 12px;font-family:ui-monospace,monospace;font-size:13px;color:#0052ff;"><strong>Reference:</strong> ${escapeHtml(reference)}</p>` : ''}
            <p style="margin:0;font-size:13px;line-height:1.5;color:#64748b;">${escapeHtml(hint)}</p>
        `,
        confirmButtonText: 'Print / save confirmation',
        confirmButtonColor: '#0052ff',
        showCancelButton: true,
        cancelButtonText: 'View confirmation on page',
        reverseButtons: true,
        allowOutsideClick: false,
        customClass: {
            popup: 'cyra-it-swal',
            confirmButton: 'cyra-it-swal-confirm',
        },
    }).then((result) => {
        success.scrollIntoView({ behavior: 'smooth', block: 'start' });

        if (result.isConfirmed) {
            window.setTimeout(printConfirmation, 250);
        }
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
