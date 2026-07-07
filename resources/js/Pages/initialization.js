/**
 * Module 01 initialization page enhancements using vanilla JavaScript.
 */
export function initInitializationPage() {
    const statusValue = document.getElementById('database-status-value');
    const liveRegion = document.getElementById('platform-live-status');

    if (!statusValue || !liveRegion) {
        return;
    }

    refreshDatabaseStatus(statusValue, liveRegion);
}

async function refreshDatabaseStatus(statusElement, liveRegion) {
    try {
        const response = await window.axios.get('/api/v1/health');
        const connected = Boolean(response.data?.data?.checks?.database);
        const label = connected ? 'Connected' : 'Disconnected';

        statusElement.textContent = label;
        statusElement.classList.toggle('text-cyra-success', connected);
        statusElement.classList.toggle('text-cyra-danger', !connected);
        liveRegion.textContent = `Database status updated: ${label}.`;
    } catch {
        statusElement.textContent = 'Unavailable';
        statusElement.classList.add('text-cyra-danger');
        liveRegion.textContent = 'Unable to refresh database status from health API.';
    }
}
