<template>
    <div class="container-xl">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h3 class="card-title mb-0">
                            {{ mode === 'create' ? 'Create New Receiver Group' : 'Edit Receiver Group' }}
                        </h3>
                        <a :href="backUrl" class="btn btn-secondary">
                            <i class='bx bx-arrow-back'></i> Back
                        </a>
                    </div>
                    <div class="card-body">
                        <form :action="formAction" method="POST">
                            <input type="hidden" name="_token" :value="csrfToken">
                            <input v-if="mode === 'edit'" type="hidden" name="_method" value="PUT">

                            <!-- Owner Selection (Admin only) -->
                            <div v-if="isAdmin" class="mb-3">
                                <label for="user_id" class="form-label">
                                    <i class='bx bx-user'></i> Group Owner *
                                </label>
                                <select id="user_id" name="user_id" class="form-select" v-model="selectedOwnerId"
                                    required>
                                    <option value="">Select owner...</option>
                                    <option v-for="user in ownerUsers" :key="user.id" :value="user.id">
                                        {{ user.name }} ({{ user.email }})
                                    </option>
                                </select>
                            </div>

                            <!-- Group Name -->
                            <div class="mb-3">
                                <label for="name" class="form-label">
                                    <i class='bx bx-label'></i> Group Name *
                                </label>
                                <input type="text" id="name" name="name" class="form-control" v-model="groupName"
                                    placeholder="e.g., Sales Team, Legal Department" required maxlength="255">
                            </div>

                            <!-- Description -->
                            <div class="mb-3">
                                <label for="description" class="form-label">
                                    <i class='bx bx-file-blank'></i> Description
                                </label>
                                <textarea id="description" name="description" class="form-control"
                                    v-model="groupDescription" rows="3"
                                    placeholder="Optional description for this group"></textarea>
                            </div>

                            <!-- Email Recipients -->
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class='bx bx-envelope'></i> Email Recipients
                                </label>
                                <share-email-manager ref="emailManager" :initial-emails="initialEmails"
                                    @emails-updated="updateEmails"></share-email-manager>
                                <div class="form-text">
                                    Add email addresses for external recipients.
                                </div>
                            </div>

                            <!-- Registered Users -->
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class='bx bx-user-check'></i> Registered Users
                                </label>
                                <share-user-selector ref="userSelector" :users="availableUsers"
                                    :initial-selected-ids="initialUserIds"
                                    @users-updated="updateUsers"></share-user-selector>
                                <div class="form-text">
                                    Add registered users from your organization.
                                </div>
                            </div>

                            <!-- Hidden inputs for form submission -->
                            <template v-for="(email, index) in emails" :key="`email-${index}`">
                                <input type="hidden" :name="`emails[${index}]`" :value="email">
                            </template>
                            <template v-for="(userId, index) in userIds" :key="`user-${index}`">
                                <input type="hidden" :name="`user_ids[${index}]`" :value="userId">
                            </template>

                            <!-- Errors -->
                            <div v-if="errors.length > 0" class="alert alert-danger">
                                <ul class="mb-0">
                                    <li v-for="(error, index) in errors" :key="index">{{ error }}</li>
                                </ul>
                            </div>

                            <!-- Member Summary -->
                            <div v-if="emails.length > 0 || userIds.length > 0" class="alert alert-info">
                                <strong>Total Members: {{ emails.length + userIds.length }}</strong>
                                <ul class="mb-0 mt-2">
                                    <li v-if="emails.length > 0">{{ emails.length }} email recipient(s)</li>
                                    <li v-if="userIds.length > 0">{{ userIds.length }} registered user(s)</li>
                                </ul>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary" @click="validateForm">
                                    <i class='bx bx-save'></i>
                                    {{ mode === 'create' ? 'Create Group' : 'Update Group' }}
                                </button>
                                <a :href="cancelUrl" class="btn btn-secondary">
                                    <i class='bx bx-x'></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import ShareEmailManager from './ShareEmailManager.vue';
import ShareUserSelector from './ShareUserSelector.vue';

const props = defineProps({
    mode: {
        type: String,
        required: true,
        validator: (value) => ['create', 'edit'].includes(value)
    },
    formAction: {
        type: String,
        required: true
    },
    backUrl: {
        type: String,
        required: true
    },
    cancelUrl: {
        type: String,
        required: true
    },
    isAdmin: {
        type: Boolean,
        default: false
    },
    ownerUsers: {
        type: Array,
        default: () => []
    },
    availableUsers: {
        type: Array,
        required: true
    },
    initialName: {
        type: String,
        default: ''
    },
    initialDescription: {
        type: String,
        default: ''
    },
    initialOwnerId: {
        type: [Number, String],
        default: null
    },
    initialEmails: {
        type: Array,
        default: () => []
    },
    initialUserIds: {
        type: Array,
        default: () => []
    },
    errors: {
        type: Array,
        default: () => []
    },
    csrfToken: {
        type: String,
        required: true
    }
});

const groupName = ref(props.initialName);
const groupDescription = ref(props.initialDescription);
const selectedOwnerId = ref(props.initialOwnerId);
const emails = ref([...props.initialEmails]);
const userIds = ref([...props.initialUserIds]);

const emailManager = ref(null);
const userSelector = ref(null);

const updateEmails = (newEmails) => {
    emails.value = newEmails;
};

const updateUsers = (newUserIds) => {
    userIds.value = newUserIds;
};

const validateForm = (event) => {
    if (emails.value.length === 0 && userIds.value.length === 0) {
        event.preventDefault();
        alert('Please add at least one email or registered user to the group.');
        return false;
    }
    return true;
};

onMounted(() => {
    // Initialize with existing data if in edit mode
    if (props.mode === 'edit') {
        emails.value = [...props.initialEmails];
        userIds.value = [...props.initialUserIds];
    }
});
</script>

<style scoped>
.alert-info ul {
    padding-left: 1.5rem;
}
</style>
