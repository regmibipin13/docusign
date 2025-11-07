<template>
    <div class="container-xl">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h3 class="card-title mb-0">{{ mode === 'create' ? 'Create New Signature' : 'Edit Signature' }}</h3>
                        <a :href="backUrl" class="btn btn-secondary">
                            <i class='bx bx-arrow-back'></i> Back to List
                        </a>
                    </div>
                    <div class="card-body">
                        <form 
                            :action="formAction" 
                            method="POST" 
                            enctype="multipart/form-data"
                            @submit="handleSubmit"
                        >
                            <input type="hidden" name="_token" :value="csrfToken">
                            <input v-if="mode === 'edit'" type="hidden" name="_method" value="PUT">

                            <signature-form
                                :mode="mode"
                                :initial-signature-type="initialSignatureType"
                                :initial-title="initialTitle"
                                :initial-is-active="initialIsActive"
                                :current-signature-url="currentSignatureUrl"
                                ref="signatureForm"
                            ></signature-form>

                            <div v-if="errors.length > 0" class="alert alert-danger">
                                <ul class="mb-0">
                                    <li v-for="(error, index) in errors" :key="index">{{ error }}</li>
                                </ul>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class='bx bx-save'></i> {{ mode === 'create' ? 'Create Signature' : 'Update Signature' }}
                                </button>
                                <a :href="cancelUrl" class="btn btn-secondary">Cancel</a>
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
import SignatureForm from './SignatureForm.vue';

const props = defineProps({
    mode: {
        type: String,
        default: 'create',
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
    initialSignatureType: {
        type: String,
        default: 'image'
    },
    initialTitle: {
        type: String,
        default: ''
    },
    initialIsActive: {
        type: Boolean,
        default: true
    },
    currentSignatureUrl: {
        type: String,
        default: ''
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

const signatureForm = ref(null);

const handleSubmit = (e) => {
    if (signatureForm.value && signatureForm.value.validateSignature) {
        if (!signatureForm.value.validateSignature()) {
            e.preventDefault();
            return false;
        }
    }
    return true;
};
</script>
