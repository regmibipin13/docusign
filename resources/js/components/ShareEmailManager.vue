<template>
    <div class="mb-3">
        <label class="form-label fw-bold">
            <i class='bx bx-envelope'></i> Email Recipients
        </label>
        <textarea v-model="bulkInput" class="form-control mb-2" rows="3"
            placeholder="Paste multiple emails here (comma, semicolon, space, or line-separated)&#10;Example: user1@example.com, user2@example.com; user3@example.com"></textarea>
        <button type="button" class="btn btn-sm btn-primary mb-3" @click="parseEmails">
            <i class='bx bx-plus-circle'></i> Add Emails
        </button>

        <div v-if="emails.length > 0" class="border rounded p-3 mb-2" style="max-height: 200px; overflow-y: auto;">
            <span v-for="(email, index) in emails" :key="index"
                class="badge bg-primary me-2 mb-2 d-inline-flex align-items-center text-white"
                style="font-size: 0.9rem; padding: 0.5rem 0.75rem;">
                {{ email }}
                <button type="button" class="btn-close btn-close-white ms-2" style="font-size: 0.7rem;"
                    @click="removeEmail(index)"></button>
                <input type="hidden" name="recipient_emails[]" :value="email">
            </span>
        </div>
        <small v-if="emails.length > 0" class="text-muted">
            <i class='bx bx-check-circle'></i> {{ emails.length }} email(s) added
        </small>
        <small class="text-muted d-block mt-2">
            <i class='bx bx-info-circle'></i> Paste multiple emails separated by comma, semicolon, space, or new lines
        </small>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
    initialEmails: {
        type: Array,
        default: () => []
    }
});

const emit = defineEmits(['emails-updated']);

const bulkInput = ref('');
const emails = ref([...props.initialEmails]);

// Watch for changes and emit to parent
watch(emails, (newEmails) => {
    emit('emails-updated', newEmails);
}, { deep: true });

const validateEmail = (email) => {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
};

const parseEmails = () => {
    const text = bulkInput.value.trim();

    if (!text) {
        alert('Please enter at least one email address');
        return;
    }

    // Split by comma, semicolon, space, or newline
    const emailsArray = text.split(/[\s,;]+/).filter(email => email.trim() !== '');

    let addedCount = 0;
    let invalidEmails = [];

    emailsArray.forEach(email => {
        email = email.trim();
        if (validateEmail(email)) {
            if (!emails.value.includes(email)) {
                emails.value.push(email);
                addedCount++;
            }
        } else {
            invalidEmails.push(email);
        }
    });

    // Clear bulk input
    bulkInput.value = '';

    if (invalidEmails.length > 0) {
        alert(`Invalid email(s) skipped:\n${invalidEmails.join(', ')}`);
    }

    if (addedCount === 0 && invalidEmails.length === 0) {
        alert('No new emails to add');
    }
};

const removeEmail = (index) => {
    emails.value.splice(index, 1);
};
</script>
