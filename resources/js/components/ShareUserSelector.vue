<template>
    <div class="mb-3">
        <label class="form-label fw-bold">
            <i class='bx bx-user'></i> Search and Add Registered Users
        </label>

        <div class="input-group mb-3">
            <span class="input-group-text"><i class='bx bx-search'></i></span>
            <input type="text" v-model="searchTerm" class="form-control"
                placeholder="Type to search by name or email (minimum 2 characters)..." @input="handleSearch"
                autocomplete="off">
            <button type="button" class="btn btn-outline-secondary" @click="clearSearch">
                <i class='bx bx-x'></i> Clear
            </button>
        </div>

        <!-- Search Results -->
        <div v-if="showResults && searchTerm.length >= 2" class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <small class="text-muted">{{ filteredUsers.length }} result{{ filteredUsers.length !== 1 ? 's' : ''
                    }}</small>
                <button v-if="filteredUsers.length > 0" type="button" class="btn btn-sm btn-outline-primary"
                    @click="addAllResults">
                    <i class='bx bx-plus-circle'></i> Add All Results
                </button>
            </div>
            <div class="border rounded p-3" style="max-height: 250px; overflow-y: auto;">
                <div v-if="filteredUsers.length === 0" class="text-center text-muted py-3">
                    No users found
                </div>
                <div v-for="user in filteredUsers" :key="user.id" class="user-search-item p-2 border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ user.name }}</strong>
                            <small class="text-muted d-block">{{ user.email }}</small>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary" @click="addUser(user)">
                            <i class='bx bx-plus'></i> Add
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Selected Users -->
        <div>
            <div class="d-flex justify-content-between align-items-center mb-2">
                <label class="form-label fw-bold mb-0">Selected Users</label>
                <span class="text-muted small">{{ selectedUsers.length }} selected</span>
            </div>
            <div class="border rounded p-3 bg-light"
                style="min-height: 100px; max-height: 300px; overflow-y: auto; display: flex; flex-wrap: wrap; gap: 0.5rem; align-items: flex-start;">
                <div v-if="selectedUsers.length === 0" class="text-center text-muted py-4 w-100">
                    <i class='bx bx-search bx-lg'></i>
                    <p class="mb-0">Search and add users to share with</p>
                </div>
                <span v-for="user in selectedUsers" :key="user.id"
                    class="badge bg-primary d-inline-flex align-items-center text-white"
                    style="font-size: 0.9rem; padding: 0.5rem 0.75rem; max-width: 100%; white-space: normal; text-align: left;">
                    <span>{{ user.name }} ({{ user.email }})</span>
                    <button type="button" class="btn-close btn-close-white ms-2" style="font-size: 0.7rem;"
                        @click="removeUser(user.id)"></button>
                    <input type="hidden" name="shared_with_user_ids[]" :value="user.id">
                </span>
            </div>
        </div>

        <small class="text-muted mt-2 d-block">
            <i class='bx bx-info-circle'></i> Start typing to search for users, then click to add them to your selection
        </small>
    </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';

const props = defineProps({
    users: {
        type: Array,
        required: true
    },
    initialSelectedIds: {
        type: Array,
        default: () => []
    }
});

const emit = defineEmits(['users-updated']);

const searchTerm = ref('');
const selectedUsers = ref([]);
const showResults = ref(false);

// Initialize with pre-selected users
if (props.initialSelectedIds.length > 0) {
    selectedUsers.value = props.users.filter(user =>
        props.initialSelectedIds.includes(user.id)
    );
}

// Watch for changes and emit user IDs to parent
watch(selectedUsers, (newUsers) => {
    const userIds = newUsers.map(u => u.id);
    emit('users-updated', userIds);
}, { deep: true });

const filteredUsers = computed(() => {
    if (searchTerm.value.length < 2) {
        return [];
    }

    const term = searchTerm.value.toLowerCase();
    return props.users.filter(user => {
        const name = user.name.toLowerCase();
        const email = user.email.toLowerCase();
        const alreadySelected = selectedUsers.value.some(su => su.id === user.id);
        return !alreadySelected && (name.includes(term) || email.includes(term));
    });
});

const handleSearch = () => {
    showResults.value = searchTerm.value.length >= 2;
};

const addUser = (user) => {
    if (!selectedUsers.value.some(u => u.id === user.id)) {
        selectedUsers.value.push(user);
    }
};

const addAllResults = () => {
    filteredUsers.value.forEach(user => {
        if (!selectedUsers.value.some(u => u.id === user.id)) {
            selectedUsers.value.push(user);
        }
    });
};

const removeUser = (userId) => {
    selectedUsers.value = selectedUsers.value.filter(u => u.id !== userId);
};

const clearSearch = () => {
    searchTerm.value = '';
    showResults.value = false;
};
</script>

<style scoped>
.user-search-item {
    transition: background-color 0.2s;
    cursor: pointer;
}

.user-search-item:hover {
    background-color: #f8f9fa;
}

.user-search-item:last-child {
    border-bottom: none !important;
}
</style>
