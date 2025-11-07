<template>
    <div>
        <div class="mb-3">
            <label for="title" class="form-label required">Signature Title</label>
            <input 
                type="text" 
                class="form-control" 
                id="title" 
                name="title" 
                :value="initialTitle"
                required
            >
        </div>

        <div class="mb-3">
            <label class="form-label required">Signature Type</label>
            <div class="form-selectgroup">
                <label class="form-selectgroup-item">
                    <input 
                        type="radio" 
                        name="signature_type" 
                        value="image"
                        class="form-selectgroup-input" 
                        v-model="signatureType" 
                        required
                    >
                    <span class="form-selectgroup-label">
                        <i class='bx bx-image'></i> Upload Image
                    </span>
                </label>
                <label class="form-selectgroup-item">
                    <input 
                        type="radio" 
                        name="signature_type" 
                        value="draw"
                        class="form-selectgroup-input" 
                        v-model="signatureType" 
                        required
                    >
                    <span class="form-selectgroup-label">
                        <i class='bx bx-pen'></i> Draw Signature
                    </span>
                </label>
            </div>
        </div>

        <!-- Current Signature (Edit Mode) -->
        <div v-if="mode === 'edit' && currentSignatureUrl" class="mb-3">
            <label class="form-label">Current Signature</label>
            <div class="border rounded p-3 bg-light">
                <img 
                    :src="currentSignatureUrl" 
                    :alt="initialTitle"
                    style="max-height: 150px; border: 1px solid #ddd; padding: 5px; background: white;"
                >
            </div>
        </div>

        <!-- Image Upload -->
        <div class="mb-3" v-show="signatureType === 'image'">
            <label for="signature_image" class="form-label">
                {{ mode === 'edit' ? 'Replace Signature Image (Optional)' : 'Upload Signature Image' }}
            </label>
            <input 
                type="file"
                class="form-control"
                id="signature_image" 
                name="signature_image" 
                accept="image/*" 
                @change="previewImage"
            >
            <div class="form-text">
                {{ mode === 'edit' ? 'Leave empty to keep the current signature. ' : '' }}
                Supported formats: PNG, JPG, JPEG, GIF. Max size: 2MB
            </div>

            <!-- Image Preview -->
            <div v-if="imagePreview" class="mt-3">
                <img 
                    :src="imagePreview" 
                    alt="Preview"
                    style="max-height: 150px; border: 1px solid #ddd; padding: 5px;"
                >
            </div>
        </div>

        <!-- Draw Signature -->
        <div class="mb-3" v-show="signatureType === 'draw'">
            <label class="form-label">
                {{ mode === 'edit' ? 'Draw Your Signature (Optional - leave blank to keep current)' : 'Draw Your Signature' }}
            </label>
            <div class="border rounded p-2 bg-white" style="max-width: 600px;">
                <canvas 
                    ref="signatureCanvas" 
                    width="580" 
                    height="200"
                    style="border: 1px dashed #ccc; cursor: crosshair; touch-action: none;"
                    @mousedown="startDrawing" 
                    @mousemove="draw" 
                    @mouseup="stopDrawing"
                    @mouseleave="stopDrawing" 
                    @touchstart="startDrawing" 
                    @touchmove="draw"
                    @touchend="stopDrawing"
                ></canvas>
                <div class="mt-2">
                    <button type="button" class="btn btn-sm btn-secondary" @click="clearCanvas">
                        <i class='bx bx-eraser'></i> Clear
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary ms-2" @click="undoLastStroke">
                        <i class='bx bx-undo'></i> Undo
                    </button>
                </div>
            </div>
            <input type="hidden" name="signature_data" v-if="signatureType === 'draw'" :value="signatureData">
        </div>

        <div class="mb-3">
            <div class="form-check form-switch">
                <input 
                    class="form-check-input" 
                    type="checkbox" 
                    id="is_active" 
                    name="is_active"
                    value="1" 
                    :checked="initialIsActive"
                >
                <label class="form-check-label" for="is_active">
                    Set as Active
                </label>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';

const props = defineProps({
    mode: {
        type: String,
        default: 'create', // 'create' or 'edit'
        validator: (value) => ['create', 'edit'].includes(value)
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
    }
});

const signatureType = ref(props.initialSignatureType);
const imagePreview = ref(null);
const signatureData = ref('');
const isDrawing = ref(false);
const ctx = ref(null);
const strokes = ref([]);
const currentStroke = ref([]);
const signatureCanvas = ref(null);

const initCanvas = () => {
    if (signatureCanvas.value) {
        ctx.value = signatureCanvas.value.getContext('2d');
        ctx.value.strokeStyle = '#000';
        ctx.value.lineWidth = 2;
        ctx.value.lineCap = 'round';
        ctx.value.lineJoin = 'round';
    }
};

const getCoordinates = (e) => {
    const canvas = signatureCanvas.value;
    const rect = canvas.getBoundingClientRect();

    if (e.touches && e.touches[0]) {
        return {
            x: e.touches[0].clientX - rect.left,
            y: e.touches[0].clientY - rect.top
        };
    }
    return {
        x: e.clientX - rect.left,
        y: e.clientY - rect.top
    };
};

const startDrawing = (e) => {
    e.preventDefault();
    isDrawing.value = true;
    currentStroke.value = [];
    const pos = getCoordinates(e);
    ctx.value.beginPath();
    ctx.value.moveTo(pos.x, pos.y);
    currentStroke.value.push({ x: pos.x, y: pos.y });
};

const draw = (e) => {
    if (!isDrawing.value) return;
    e.preventDefault();

    const pos = getCoordinates(e);
    ctx.value.lineTo(pos.x, pos.y);
    ctx.value.stroke();
    currentStroke.value.push({ x: pos.x, y: pos.y });
};

const stopDrawing = (e) => {
    if (isDrawing.value) {
        e.preventDefault();
        isDrawing.value = false;
        if (currentStroke.value.length > 0) {
            strokes.value.push([...currentStroke.value]);
        }
        updateSignatureData();
    }
};

const clearCanvas = () => {
    const canvas = signatureCanvas.value;
    ctx.value.clearRect(0, 0, canvas.width, canvas.height);
    strokes.value = [];
    currentStroke.value = [];
    signatureData.value = '';
};

const undoLastStroke = () => {
    if (strokes.value.length > 0) {
        strokes.value.pop();
        redrawCanvas();
        updateSignatureData();
    }
};

const redrawCanvas = () => {
    const canvas = signatureCanvas.value;
    ctx.value.clearRect(0, 0, canvas.width, canvas.height);

    strokes.value.forEach(stroke => {
        if (stroke.length > 0) {
            ctx.value.beginPath();
            ctx.value.moveTo(stroke[0].x, stroke[0].y);
            for (let i = 1; i < stroke.length; i++) {
                ctx.value.lineTo(stroke[i].x, stroke[i].y);
            }
            ctx.value.stroke();
        }
    });
};

const updateSignatureData = () => {
    const canvas = signatureCanvas.value;
    signatureData.value = canvas.toDataURL('image/png');
};

const previewImage = (e) => {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

// Expose validation method
const validateSignature = () => {
    if (props.mode === 'create' && signatureType.value === 'draw' && !signatureData.value) {
        alert('Please draw your signature before submitting.');
        return false;
    }
    return true;
};

// Watch for signature type changes to init canvas
watch(signatureType, (newType) => {
    if (newType === 'draw') {
        // Delay init to ensure canvas is rendered
        setTimeout(() => {
            initCanvas();
        }, 100);
    }
});

onMounted(() => {
    if (signatureType.value === 'draw') {
        initCanvas();
    }
});

// Expose methods to parent
defineExpose({
    validateSignature
});
</script>
