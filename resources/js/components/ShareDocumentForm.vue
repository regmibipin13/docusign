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
                            <small class="text-muted d-block mt-2">Share this link with anyone to give them access.</small>
                        </div>

                        <form :action="formAction" method="POST">
                            <input type="hidden" name="_token" :value="csrfToken">

                            <div class="mb-3">
                                <label class="form-label fw-bold">Share Type</label>
                                <select name="share_type" class="form-select" v-model="shareType">
                                    <option value="public_link">Copy Link (Public - No Email Needed)</option>
                                    <option value="email">Send via Email (Multiple Recipients)</option>
                                    <option value="registered_user">Send to Registered Users (Multiple)</option>
                                </select>
                                <small class="text-muted">Choose how you want to share this document</small>
                            </div>

                            <!-- Email Recipients Section -->
                            <share-email-manager v-show="shareType === 'email'"></share-email-manager>

                            <!-- Registered Users Section -->
                            <share-user-selector 
                                v-show="shareType === 'registered_user'"
                                :users="users"
                            ></share-user-selector>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Message (optional)</label>
                                <textarea name="message" class="form-control" rows="3" placeholder="Add a personal message to recipients..."></textarea>
                                <small class="text-muted">This message will be included in the email notification</small>
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
import { ref } from 'vue';
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
</script>
