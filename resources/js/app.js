import './bootstrap';
import { initNavigation } from './components/navigation';
import { initModals, initTabs } from './components/ui';
import { initSolutionsPage } from './pages/solutions';
import { initLeadershipPage } from './pages/leadership';
import { initHomepage } from './pages/homepage';
import { initDesignSystemPage } from './pages/design-system';
import { initInitializationPage } from './pages/initialization';
import { initLoginPage } from './pages/login';

document.addEventListener('DOMContentLoaded', () => {
    initInitializationPage();
    initLoginPage();
    initHomepage();
    initLeadershipPage();
    initSolutionsPage();
    initDesignSystemPage();
    initNavigation();
    initModals();
    initTabs();
});
