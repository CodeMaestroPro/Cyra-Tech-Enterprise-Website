import './bootstrap';
import { initInitializationPage } from './pages/initialization';
import { initLoginPage } from './pages/login';

document.addEventListener('DOMContentLoaded', () => {
    initInitializationPage();
    initLoginPage();
});
