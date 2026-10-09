/**
 * Paid student training registration — multi-step wizard + Paystack checkout + success SweetAlert.
 */
export function initStudentsRegistrationPage() {
    const page = document.querySelector('[data-students-registration-page]');
    if (!page) {
        return;
    }

    initWizard(page);
    initPrintActions(page);
    initSuccessAlert(page);
}

function initWizard(page) {
    const form = page.querySelector('[data-students-wizard]');
    if (!form) {
        return;
    }

    const panels = [...form.querySelectorAll('[data-step-panel]')];
    const indicators = [...form.querySelectorAll('[data-step-indicator]')];
    const prevBtn = form.querySelector('[data-students-prev]');
    const nextBtn = form.querySelector('[data-students-next]');
    const submitBtn = form.querySelector('[data-students-submit]');
    const stepMeta = page.querySelector('[data-students-step-meta]');
    const progressFill = page.querySelector('[data-students-progress]');
    const summary = form.querySelector('[data-students-summary]');
    const summaryProgram = form.querySelector('[data-students-summary-program]');
    const summaryFee = form.querySelector('[data-students-summary-fee]');
    const planList = form.querySelector('[data-students-plan-list]');
    const dueWrap = form.querySelector('[data-students-due-wrap]');
    const summaryAmount = form.querySelector('[data-students-summary-amount]');
    const summaryBalance = form.querySelector('[data-students-summary-balance]');
    const programList = form.querySelector('[data-students-program-list]');
    const totalSteps = panels.length;
    let currentStep = Number(form.dataset.initialStep || 1);

    const installmentInitial = Number(form.dataset.installmentInitial || 60);
    const installmentBalance = Number(form.dataset.installmentBalance || 40);

    let programs = [];
    try {
        programs = JSON.parse(form.dataset.programs || '[]');
    } catch {
        programs = [];
    }

    const stepFields = {
        1: ['name', 'email', 'phone', 'date_of_birth', 'gender', 'state', 'city'],
        2: ['education_level', 'field_of_study'],
        3: ['program', 'payment_plan', 'skill_level', 'has_tech_experience'],
    };

    const formatAmount = (amount) => {
        const program = programs[0];
        const formatted = program?.amount_formatted || '';

        if (formatted.startsWith('₦')) {
            return `₦${Number(amount).toLocaleString('en-NG')}`;
        }

        return Number(amount).toLocaleString('en-NG');
    };

    const calculateBreakdown = (totalAmount, paymentPlan) => {
        if (paymentPlan === 'installment') {
            const dueNow = Math.round(totalAmount * (installmentInitial / 100));

            return {
                dueNow,
                balance: Math.max(0, totalAmount - dueNow),
            };
        }

        return {
            dueNow: totalAmount,
            balance: 0,
        };
    };

    const updateProgress = (step) => {
        if (!progressFill) {
            return;
        }

        progressFill.style.width = `${(step / totalSteps) * 100}%`;
    };

    const updateCheckout = () => {
        const selectedProgram = form.querySelector('input[name="program"]:checked');
        const selectedPlan = form.querySelector('input[name="payment_plan"]:checked');
        const slug = selectedProgram?.value || '';
        const paymentPlan = selectedPlan?.value || '';

        programList?.querySelectorAll('[data-program-option]').forEach((option) => {
            option.classList.toggle('is-selected', option.dataset.programOption === slug);
        });

        planList?.querySelectorAll('[data-payment-plan-option]').forEach((option) => {
            option.classList.toggle('is-selected', option.dataset.paymentPlanOption === paymentPlan);
        });

        const program = programs.find((item) => item.slug === slug);

        if (!program || !summary) {
            summary.hidden = true;
            return;
        }

        summary.hidden = false;
        if (planList) {
            planList.hidden = false;
        }

        if (summaryProgram) {
            summaryProgram.textContent = program.label;
        }

        if (summaryFee) {
            summaryFee.textContent = program.amount_formatted;
        }

        if (!paymentPlan) {
            if (dueWrap) {
                dueWrap.hidden = true;
            }
            return;
        }

        const breakdown = calculateBreakdown(program.amount, paymentPlan);

        if (dueWrap) {
            dueWrap.hidden = false;
        }

        if (summaryAmount) {
            summaryAmount.textContent = formatAmount(breakdown.dueNow);
        }

        if (summaryBalance) {
            if (breakdown.balance > 0) {
                summaryBalance.hidden = false;
                summaryBalance.textContent = `Remaining ${installmentBalance}% (${formatAmount(breakdown.balance)}) payable later`;
            } else {
                summaryBalance.hidden = true;
                summaryBalance.textContent = '';
            }
        }
    };

    const showStep = (step) => {
        currentStep = step;

        panels.forEach((panel) => {
            const panelStep = Number(panel.dataset.stepPanel);
            const isActive = panelStep === step;

            panel.classList.toggle('hidden', !isActive);
            panel.classList.toggle('is-active', isActive);
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

        updateProgress(step);

        if (step === 3) {
            updateCheckout();
        }
    };

    const validateStep = (step) => {
        const fields = stepFields[step] || [];
        let valid = true;

        fields.forEach((name) => {
            if (['program', 'payment_plan', 'skill_level', 'has_tech_experience'].includes(name)) {
                const selected = form.querySelector(`input[name="${name}"]:checked`);
                if (!selected) {
                    valid = false;
                }
                return;
            }

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

    programList?.addEventListener('change', (event) => {
        if (event.target?.name === 'program') {
            updateCheckout();
        }
    });

    planList?.addEventListener('change', (event) => {
        if (event.target?.name === 'payment_plan') {
            updateCheckout();
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
    updateCheckout();
}

function printReceipt() {
    window.print();
}

function initPrintActions(page) {
    page.querySelectorAll('[data-students-print-receipt]').forEach((button) => {
        button.addEventListener('click', printReceipt);
    });
}

function initSuccessAlert(page) {
    const success = page.querySelector('[data-students-success]');
    if (!success || typeof window.Swal === 'undefined') {
        return;
    }

    const title = success.dataset.successTitle || 'Payment Successful';
    const text = success.dataset.successText || 'Your registration was successful.';
    const reference = success.dataset.successReference || '';
    const hint = success.dataset.receiptHint
        || 'Print this receipt or save it as a PDF on your phone. Scan the QR code anytime to verify authenticity.';

    window.Swal.fire({
        icon: 'success',
        title,
        html: `
            <p style="margin:0 0 8px;line-height:1.5;">${escapeHtml(text)}</p>
            ${reference ? `<p style="margin:0 0 12px;font-family:ui-monospace,monospace;font-size:13px;color:#0052ff;"><strong>Reference:</strong> ${escapeHtml(reference)}</p>` : ''}
            <p style="margin:0;font-size:13px;line-height:1.5;color:#64748b;">${escapeHtml(hint)}</p>
        `,
        confirmButtonText: 'Print / save receipt',
        confirmButtonColor: '#0052ff',
        showCancelButton: true,
        cancelButtonText: 'View receipt on page',
        reverseButtons: true,
        allowOutsideClick: false,
        customClass: {
            popup: 'cyra-it-swal',
            confirmButton: 'cyra-it-swal-confirm',
        },
    }).then((result) => {
        success.scrollIntoView({ behavior: 'smooth', block: 'start' });

        if (result.isConfirmed) {
            window.setTimeout(printReceipt, 250);
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
