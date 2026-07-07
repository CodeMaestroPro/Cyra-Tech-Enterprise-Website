import './bootstrap';
import { initNavigation } from './components/navigation';
import { initModals, initTabs } from './components/ui';
import { initDesignSystemPage } from './pages/design-system';
import { initInitializationPage } from './pages/initialization';
import { initLoginPage } from './pages/login';

document.addEventListener('DOMContentLoaded', () => {
    initInitializationPage();
    initLoginPage();
    initDesignSystemPage();
    initNavigation();
    initModals();
    initTabs();
});
