<template>
    <div class="container-xl">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Share Document: {{ documentName }}</h5>
                        <a :href="backUrl" class="btn btn-sm btn-secondary">Back</a>
                    </div>
                    <div class="card-body">
                        <div v-if="shareLink" class="alert alert-success">
                            <h6><i class='bx bx-check-circle'></i> {{ successMessage }}</h6>
                            <share-link-copy :share-link="shareLink"></share-link-copy>
                            <small class="text-muted d-block mt-2">Share this link with anyone to give them
                                access.</small>
                        </div>

                        <form :action="formAction" method="POST">
                            <input type="hidden" name="_token" :value="csrfToken">

                            <div class="mb-3">
                                <label class="form-label fw-bold">Share Type</label>
                                <select name="share_type" class="form-select" v-model="shareType">
                                    <option value="public_link">Copy Link (Public - No Email Needed)</option>
                                    <option value="email">Send via Email (Multiple Recipients)</option>
                                    <option value="registered_user">Send to Registered Users (Multiple)</option>
                                    <option value="receiver_group">Use Receiver Group</option>
                                </select>
                                <small class="text-muted">Choose how you want to share this document</small>
                            </div>

                            <!-- Receiver Group Selection -->
                            <div v-show="shareType === 'receiver_group'" class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class='bx bx-group'></i> Select Receiver Group
                                </label>
                                <select name="receiver_group_id" class="form-select" v-model="selectedGroupId">
                                    <option value="">-- Select a group --</option>
                                    <option v-for="group in receiverGroups" :key="group.id" :value="group.id">
                                        {{ group.name }} ({{ group.member_count }} member{{ group.member_count !== 1 ?
                                        's' : '' }})
                                    </option>
                                </select>
                                <small class="text-muted d-block mt-1">
                                    Select a pre-configured group to share with all its members.
                                    <a :href="manageGroupsUrl" class="ms-2">Manage Groups</a>
                                </small>

                                <!-- Show group details when selected -->
                                <div v-if="selectedGroup" class="alert alert-info mt-3">
                                    <h6 class="mb-2"><i class='bx bx-info-circle'></i> Group Details: {{
                                        selectedGroup.name }}</h6>
                                    <p class="mb-2" v-if="selectedGroup.description">{{ selectedGroup.description }}</p>
                                    <div v-if="selectedGroup.email_count > 0 || selectedGroup.user_count > 0">
                                        <strong>Will share with:</strong>
                                        <ul class="mb-0 mt-1">
                                            <li v-if="selectedGroup.email_count > 0">
                                                {{ selectedGroup.email_count }} email recipient(s)
                                            </li>
                                            <li v-if="selectedGroup.user_count > 0">
                                                {{ selectedGroup.user_count }} registered user(s)
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Manual Entry Mode Toggle (for email and registered_user types) -->
                            <div v-show="shareType === 'email' || shareType === 'registered_user'" class="mb-3">
                                <div class="alert alert-light">
                                    <i class='bx bx-info-circle'></i>
                                    <strong>Tip:</strong> You can use a
                                    <a href="javascript:void(0)" @click="shareType = 'receiver_group'">Receiver
                                        Group</a>
                                    to save time when sharing with the same people frequently.
                                </div>
                            </div>

                            <!-- Email Recipients Section -->
                            <share-email-manager v-show="shareType === 'email'"></share-email-manager>

                            <!-- Registered Users Section -->
                            <share-user-selector v-show="shareType === 'registered_user'"
                                :users="users"></share-user-selector>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Message (optional)</label>
                                <textarea name="message" class="form-control" rows="3"
                                    placeholder="Add a personal message to recipients..."></textarea>
                                <small class="text-muted">This message will be included in the email
                                    notification</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Expires At (optional)</label>
                                <input type="datetime-local" name="expires_at" class="form-control">
                                <small class="text-muted">Leave empty for no expiration</small>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class='bx bx-share'></i> Create Share(s)
                                </button>
                                <a :href="backUrl" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import ShareEmailManager from './ShareEmailManager.vue';
import ShareUserSelector from './ShareUserSelector.vue';
import ShareLinkCopy from './ShareLinkCopy.vue';

const props = defineProps({
    documentName: {
        type: String,
        required: true
    },
    formAction: {
        type: String,
        required: true
    },
    backUrl: {
        type: String,
        required: true
    },
    users: {
        type: Array,
        default: () => []
    },
    receiverGroups: {
        type: Array,
        default: () => []
    },
    manageGroupsUrl: {
        type: String,
        default: '/customer/receiver-groups'
    },
    shareLink: {
        type: String,
        default: ''
    },
    successMessage: {
        type: String,
        default: 'Share created successfully!'
    },
    csrfToken: {
        type: String,
        required: true
    }
});

const shareType = ref('public_link');
const selectedGroupId = ref('');

const selectedGroup = computed(() => {
    if (!selectedGroupId.value) return null;
    return props.receiverGroups.find(g => g.id == selectedGroupId.value);
});
</script>
