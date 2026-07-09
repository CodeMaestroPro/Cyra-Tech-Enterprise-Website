function escapeHtml(value) {
    return value
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#39;');
}

function renderMessage(role, content) {
    const isAssistant = role === 'assistant';

    return `
        <div class="flex items-start gap-3" data-message-role="${role}">
            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border ${
                isAssistant
                    ? 'border-cyra-primary/30 bg-cyra-primary/10 text-cyra-accent'
                    : 'border-cyra-border/70 bg-cyra-surface/60 text-cyra-text'
            }">
                ${
                    isAssistant
                        ? '<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" /></svg>'
                        : '<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>'
                }
            </span>
            <div class="min-w-0 flex-1 rounded-xl border border-cyra-border/60 ${
                isAssistant ? 'bg-cyra-surface/60' : 'bg-cyra-primary/5'
            } px-4 py-3">
                <p class="whitespace-pre-line text-sm leading-relaxed text-cyra-text">${escapeHtml(content)}</p>
            </div>
        </div>
    `;
}

async function submitAiQuery(root, message, prompt = null) {
    const messages = root.querySelector('#ai-assistant-messages');
    const status = root.querySelector('#ai-assistant-status');
    const submit = root.querySelector('#ai-assistant-submit');
    const input = root.querySelector('#ai-assistant-input');

    if (!messages || !status || !submit || !input) {
        return;
    }

    messages.insertAdjacentHTML('beforeend', renderMessage('user', message));
    messages.scrollTop = messages.scrollHeight;

    submit.setAttribute('disabled', 'disabled');
    input.setAttribute('disabled', 'disabled');
    status.textContent = 'Cyra AI is analyzing platform data...';
    status.classList.remove('hidden');

    try {
        const response = await fetch(root.dataset.queryUrl, {
            method: 'POST',
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': root.dataset.csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify({
                message,
                prompt,
            }),
        });

        if (!response.ok) {
            throw new Error('Assistant request failed.');
        }

        const payload = await response.json();
        const content = payload?.data?.content ?? 'No response was generated.';

        messages.insertAdjacentHTML('beforeend', renderMessage('assistant', content));
        messages.scrollTop = messages.scrollHeight;
        status.textContent = `Generated at ${payload?.data?.generated_at ?? 'just now'}.`;
    } catch (error) {
        messages.insertAdjacentHTML(
            'beforeend',
            renderMessage('assistant', 'I was unable to process that request. Please try again in a moment.'),
        );
        messages.scrollTop = messages.scrollHeight;
        status.textContent = 'Request failed. Please retry.';
    } finally {
        submit.removeAttribute('disabled');
        input.removeAttribute('disabled');
        input.focus();
    }
}

export function initAiAssistantPage() {
    const root = document.getElementById('ai-assistant-page');

    if (!root) {
        return;
    }

    const form = root.querySelector('#ai-assistant-form');
    const input = root.querySelector('#ai-assistant-input');

    form?.addEventListener('submit', async (event) => {
        event.preventDefault();

        const message = input?.value.trim();

        if (!message) {
            return;
        }

        input.value = '';
        await submitAiQuery(root, message);
    });

    root.querySelectorAll('[data-ai-prompt]').forEach((button) => {
        button.addEventListener('click', async () => {
            const prompt = button.getAttribute('data-ai-prompt');
            const label = button.getAttribute('data-ai-prompt-label') ?? prompt;

            if (!prompt) {
                return;
            }

            await submitAiQuery(root, label, prompt);
        });
    });
}
