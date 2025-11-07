<template>
    <div class="input-group mt-2">
        <input 
            type="text" 
            class="form-control" 
            :value="shareLink"
            readonly
            ref="linkInput"
        >
        <button class="btn btn-primary" @click="copyLink">
            <i :class="copied ? 'bx bx-check' : 'bx bx-copy'"></i> 
            {{ copied ? 'Copied!' : 'Copy' }}
        </button>
    </div>
</template>

<script setup>
import { ref } from 'vue';

const props = defineProps({
    shareLink: {
        type: String,
        required: true
    }
});

const linkInput = ref(null);
const copied = ref(false);

const copyLink = async () => {
    const text = props.shareLink;

    // Try modern clipboard API first
    if (navigator.clipboard && window.isSecureContext) {
        try {
            await navigator.clipboard.writeText(text);
            showCopySuccess();
        } catch (err) {
            fallbackCopy();
        }
    } else {
        fallbackCopy();
    }
};

const fallbackCopy = () => {
    const input = linkInput.value;
    input.select();
    input.setSelectionRange(0, 99999);
    try {
        document.execCommand('copy');
        showCopySuccess();
    } catch (err) {
        alert('Failed to copy. Please copy manually.');
    }
};

const showCopySuccess = () => {
    copied.value = true;
    setTimeout(() => {
        copied.value = false;
    }, 2000);
};
</script>
