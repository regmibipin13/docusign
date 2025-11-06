@extends('customer.layouts.app')

@section('title', 'Create Signature')

@section('page-title', 'Create Signature')

@section('content')
    <div class="container-xl">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h3 class="card-title mb-0">Create New Signature</h3>
                        <a href="{{ route('customer.signatures.index') }}" class="btn btn-secondary">
                            <i class='bx bx-arrow-back'></i> Back to List
                        </a>
                    </div>
                    <div class="card-body">
                        <div id="signatureApp">
                            <form method="POST" action="{{ route('customer.signatures.store') }}"
                                enctype="multipart/form-data" @submit="handleSubmit">
                                @csrf

                                <div class="mb-3">
                                    <label for="title" class="form-label required">Signature Title</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                        id="title" name="title" value="{{ old('title') }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label required">Signature Type</label>
                                    <div class="form-selectgroup">
                                        <label class="form-selectgroup-item">
                                            <input type="radio" name="signature_type" value="image"
                                                class="form-selectgroup-input" v-model="signatureType" required>
                                            <span class="form-selectgroup-label">
                                                <i class='bx bx-image'></i> Upload Image
                                            </span>
                                        </label>
                                        <label class="form-selectgroup-item">
                                            <input type="radio" name="signature_type" value="draw"
                                                class="form-selectgroup-input" v-model="signatureType" required>
                                            <span class="form-selectgroup-label">
                                                <i class='bx bx-pen'></i> Draw Signature
                                            </span>
                                        </label>
                                    </div>
                                    @error('signature_type')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Image Upload -->
                                <div class="mb-3" v-show="signatureType === 'image'">
                                    <label for="signature_image" class="form-label">Upload Signature Image</label>
                                    <input type="file"
                                        class="form-control @error('signature_image') is-invalid @enderror"
                                        id="signature_image" name="signature_image" accept="image/*" @change="previewImage">
                                    <div class="form-text">Supported formats: PNG, JPG, JPEG, GIF. Max size: 2MB</div>
                                    @error('signature_image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                    <!-- Image Preview -->
                                    <div v-if="imagePreview" class="mt-3">
                                        <img :src="imagePreview" alt="Preview"
                                            style="max-height: 150px; border: 1px solid #ddd; padding: 5px;">
                                    </div>
                                </div>

                                <!-- Draw Signature -->
                                <div class="mb-3" v-show="signatureType === 'draw'">
                                    <label class="form-label">Draw Your Signature</label>
                                    <div class="border rounded p-2 bg-white" style="max-width: 600px;">
                                        <canvas ref="signatureCanvas" width="580" height="200"
                                            style="border: 1px dashed #ccc; cursor: crosshair; touch-action: none;"
                                            @mousedown="startDrawing" @mousemove="draw" @mouseup="stopDrawing"
                                            @mouseleave="stopDrawing" @touchstart="startDrawing" @touchmove="draw"
                                            @touchend="stopDrawing">
                                        </canvas>
                                        <div class="mt-2">
                                            <button type="button" class="btn btn-sm btn-secondary" @click="clearCanvas">
                                                <i class='bx bx-eraser'></i> Clear
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary ms-2"
                                                @click="undoLastStroke">
                                                <i class='bx bx-undo'></i> Undo
                                            </button>
                                        </div>
                                    </div>
                                    <input type="hidden" name="signature_data" :value="signatureData">
                                    @error('signature_data')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                            value="1" checked>
                                        <label class="form-check-label" for="is_active">
                                            Set as Active
                                        </label>
                                    </div>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class='bx bx-save'></i> Create Signature
                                    </button>
                                    <a href="{{ route('customer.signatures.index') }}"
                                        class="btn btn-secondary">Cancel</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/vue@3/dist/vue.global.prod.js"></script>
    <script>
        const {
            createApp
        } = Vue;

        createApp({
            data() {
                return {
                    signatureType: '{{ old('signature_type', 'image') }}',
                    imagePreview: null,
                    signatureData: '',
                    isDrawing: false,
                    ctx: null,
                    strokes: [], // Store all strokes for undo functionality
                    currentStroke: [] // Current stroke being drawn
                }
            },
            mounted() {
                this.initCanvas();
            },
            methods: {
                initCanvas() {
                    if (this.$refs.signatureCanvas) {
                        this.ctx = this.$refs.signatureCanvas.getContext('2d');
                        this.ctx.strokeStyle = '#000';
                        this.ctx.lineWidth = 2;
                        this.ctx.lineCap = 'round';
                        this.ctx.lineJoin = 'round';
                    }
                },
                getCoordinates(e) {
                    const canvas = this.$refs.signatureCanvas;
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
                },
                startDrawing(e) {
                    e.preventDefault();
                    this.isDrawing = true;
                    this.currentStroke = [];
                    const pos = this.getCoordinates(e);
                    this.ctx.beginPath();
                    this.ctx.moveTo(pos.x, pos.y);
                    this.currentStroke.push({
                        x: pos.x,
                        y: pos.y
                    });
                },
                draw(e) {
                    if (!this.isDrawing) return;
                    e.preventDefault();

                    const pos = this.getCoordinates(e);
                    this.ctx.lineTo(pos.x, pos.y);
                    this.ctx.stroke();
                    this.currentStroke.push({
                        x: pos.x,
                        y: pos.y
                    });
                },
                stopDrawing(e) {
                    if (this.isDrawing) {
                        e.preventDefault();
                        this.isDrawing = false;
                        if (this.currentStroke.length > 0) {
                            this.strokes.push([...this.currentStroke]);
                        }
                        this.updateSignatureData();
                    }
                },
                clearCanvas() {
                    const canvas = this.$refs.signatureCanvas;
                    this.ctx.clearRect(0, 0, canvas.width, canvas.height);
                    this.strokes = [];
                    this.currentStroke = [];
                    this.signatureData = '';
                },
                undoLastStroke() {
                    if (this.strokes.length > 0) {
                        this.strokes.pop();
                        this.redrawCanvas();
                        this.updateSignatureData();
                    }
                },
                redrawCanvas() {
                    const canvas = this.$refs.signatureCanvas;
                    this.ctx.clearRect(0, 0, canvas.width, canvas.height);

                    this.strokes.forEach(stroke => {
                        if (stroke.length > 0) {
                            this.ctx.beginPath();
                            this.ctx.moveTo(stroke[0].x, stroke[0].y);
                            for (let i = 1; i < stroke.length; i++) {
                                this.ctx.lineTo(stroke[i].x, stroke[i].y);
                            }
                            this.ctx.stroke();
                        }
                    });
                },
                updateSignatureData() {
                    const canvas = this.$refs.signatureCanvas;
                    this.signatureData = canvas.toDataURL('image/png');
                },
                previewImage(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.imagePreview = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                },
                handleSubmit(e) {
                    if (this.signatureType === 'draw' && !this.signatureData) {
                        e.preventDefault();
                        alert('Please draw your signature before submitting.');
                        return false;
                    }
                }
            }
        }).mount('#signatureApp');
    </script>
@endpush
