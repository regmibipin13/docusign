// Import Bootstrap (Tabler includes Bootstrap)
import * as bootstrap from 'bootstrap';
import { createApp } from 'vue';

// Import Vue Components
import DocumentSigningApp from './components/DocumentSigningApp.vue';

// Make Bootstrap available globally
window.bootstrap = bootstrap;

// Initialize Vue apps when DOM is ready
document.addEventListener('DOMContentLoaded', function () {
    // Initialize Document Signing App if element exists
    const signAppElement = document.getElementById('signApp');
    if (signAppElement) {
        const app = createApp(DocumentSigningApp, {
            documentName: signAppElement.dataset.documentName,
            documentId: parseInt(signAppElement.dataset.documentId),
            documentUrl: signAppElement.dataset.documentUrl,
            signaturesUrl: signAppElement.dataset.signaturesUrl,
            createSignatureUrl: signAppElement.dataset.createSignatureUrl,
            cancelUrl: signAppElement.dataset.cancelUrl
        });
        app.mount('#signApp');
    }

    // Initialize all tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Initialize all popovers
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });

    // Auto-dismiss alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
    alerts.forEach(function (alert) {
        setTimeout(function () {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });

    // Handle theme toggle
    const themeToggleDark = document.querySelector('.hide-theme-dark');
    const themeToggleLight = document.querySelector('.hide-theme-light');

    if (themeToggleDark) {
        themeToggleDark.addEventListener('click', function (e) {
            e.preventDefault();
            document.body.classList.add('theme-dark');
            localStorage.setItem('theme', 'dark');
        });
    }

    if (themeToggleLight) {
        themeToggleLight.addEventListener('click', function (e) {
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

    console.log('Customer dashboard initialized');
});

// Export for use in other modules
export { bootstrap };
