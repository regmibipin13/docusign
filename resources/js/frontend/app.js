// Import Bootstrap
import * as bootstrap from 'bootstrap';
import { createApp } from 'vue';

// Make Bootstrap available globally
window.bootstrap = bootstrap;

// Create and mount main Vue app
const app = createApp({});

// Register Vue components here if needed for frontend
// app.component('example-component', ExampleComponent);

// Mount Vue app
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
});

// Export for use in other modules
export { bootstrap };
