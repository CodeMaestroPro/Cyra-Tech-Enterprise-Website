import './bootstrap';
import { initNavigation } from './components/navigation';
import { initModals, initTabs } from './components/ui';
import { initHomepage } from './pages/homepage';
import { initDesignSystemPage } from './pages/design-system';
import { initInitializationPage } from './pages/initialization';
import { initLoginPage } from './pages/login';

document.addEventListener('DOMContentLoaded', () => {
    initInitializationPage();
    initLoginPage();
    initHomepage();
    initDesignSystemPage();
    initNavigation();
    initModals();
    initTabs();
});
