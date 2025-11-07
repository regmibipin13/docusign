// Import Bootstrap (Tabler includes Bootstrap)
import * as bootstrap from 'bootstrap';
import { createApp } from 'vue';

// Import Vue Components
import DocumentSigningApp from './components/DocumentSigningApp.vue';
import ShareEmailManager from '../components/ShareEmailManager.vue';
import ShareUserSelector from '../components/ShareUserSelector.vue';
import ShareLinkCopy from '../components/ShareLinkCopy.vue';
import SignatureForm from '../components/SignatureForm.vue';
import SignatureFormWrapper from '../components/SignatureFormWrapper.vue';
import ShareDocumentForm from '../components/ShareDocumentForm.vue';
import ReceiverGroupForm from '../components/ReceiverGroupForm.vue';

// Make Bootstrap available globally
window.bootstrap = bootstrap;

// Create and mount main Vue app - SINGLE MOUNT
const app = createApp({});

// Register all global components
app.component('document-signing-app', DocumentSigningApp);
app.component('share-email-manager', ShareEmailManager);
app.component('share-user-selector', ShareUserSelector);
app.component('share-link-copy', ShareLinkCopy);
app.component('signature-form', SignatureForm);
app.component('signature-form-wrapper', SignatureFormWrapper);
app.component('share-document-form', ShareDocumentForm);
app.component('receiver-group-form', ReceiverGroupForm);

// Mount Vue app once
app.mount('#app');

// Initialize Bootstrap components
document.addEventListener('DOMContentLoaded', function () {
    // Initialize all tooltips
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltipTriggerList.forEach(el => new bootstrap.Tooltip(el));

    // Initialize all popovers
    const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]');
    popoverTriggerList.forEach(el => new bootstrap.Popover(el));

    // Auto-dismiss alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });

    // Handle theme toggle
    const themeToggleDark = document.querySelector('.hide-theme-dark');
    const themeToggleLight = document.querySelector('.hide-theme-light');

    if (themeToggleDark) {
        themeToggleDark.addEventListener('click', (e) => {
            e.preventDefault();
            document.body.classList.add('theme-dark');
            localStorage.setItem('theme', 'dark');
        });
    }

    if (themeToggleLight) {
        themeToggleLight.addEventListener('click', (e) => {
            e.preventDefault();
            document.body.classList.remove('theme-dark');
            localStorage.setItem('theme', 'light');
        });
    }

    // Apply saved theme
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark') {
        document.body.classList.add('theme-dark');
    }
});

// Export for use in other modules
export { bootstrap };
