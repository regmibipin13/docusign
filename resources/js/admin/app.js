// Import CoreUI
import * as coreui from '@coreui/coreui';
import { createApp } from 'vue';

// Import Vue Components
import ReceiverGroupForm from '../components/ReceiverGroupForm.vue';
import ShareEmailManager from '../components/ShareEmailManager.vue';
import ShareUserSelector from '../components/ShareUserSelector.vue';

// Make CoreUI available globally
window.coreui = coreui;

// Create and mount Vue app
const app = createApp({});

// Register components
app.component('receiver-group-form', ReceiverGroupForm);
app.component('share-email-manager', ShareEmailManager);
app.component('share-user-selector', ShareUserSelector);

// Mount Vue app
app.mount('#app');

// Initialize CoreUI components on page load
document.addEventListener('DOMContentLoaded', function () {
    // Initialize all tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new coreui.Tooltip(tooltipTriggerEl);
    });

    // Initialize all popovers
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function (popoverTriggerEl) {
        return new coreui.Popover(popoverTriggerEl);
    });

    // Auto-dismiss alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
    alerts.forEach(function (alert) {
        setTimeout(function () {
            const bsAlert = new coreui.Alert(alert);
            bsAlert.close();
        }, 5000);
    });

    console.log('Admin dashboard initialized');
});

// Export for use in other modules
export { coreui };
