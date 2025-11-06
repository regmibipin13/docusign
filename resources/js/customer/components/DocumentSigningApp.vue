<template>
    <div class="container-fluid">
        <!-- Flash Message -->
        <div v-if="flashMessage" :class="`alert alert-${flashType} alert-dismissible fade show`" role="alert">
            {{ flashMessage }}
            <button type="button" class="btn-close" @click="flashMessage = null" aria-label="Close"></button>
        </div>

        <div class="row">
            <!-- Sidebar - Signatures List -->
            <div class="col-md-3">
                <div class="card sticky-top" style="top: 20px;">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class='bx bx-pen'></i> Your Signatures</h5>
                    </div>
                    <div class="card-body signature-list">
                        <div v-if="signatures.length === 0" class="text-center text-muted py-4">
                            <i class='bx bx-info-circle' style="font-size: 2rem;"></i>
                            <p class="mb-2">No signatures available</p>
                            <a :href="createSignatureUrl" class="btn btn-sm btn-primary">
                                Create Signature
                            </a>
                        </div>
                        <div v-else class="alert alert-info mb-3 small">
                            <i class='bx bx-info-circle'></i>
                            <strong>Tip:</strong> You can drag the same signature multiple times onto the document!
                        </div>
                        <div v-for="signature in signatures" :key="signature.id" class="signature-preview"
                            draggable="true" @dragstart="dragStart($event, signature)" @dragend="dragEnd">
                            <div class="text-center">
                                <img :src="signature.url" :alt="signature.title">
                                <p class="mb-0 mt-2 small text-muted">{{ signature.title }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Area - Document Canvas -->
            <div class="col-md-9">
                <div class="card mb-3">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class='bx bx-file-pdf'></i> {{ documentName }}
                                <span v-if="placedSignatures.length > 0" class="badge bg-primary ms-2">
                                    {{ placedSignatures.length }} signature{{ placedSignatures.length !== 1 ? 's' : ''
                                    }} placed
                                </span>
                            </h5>
                            <div class="btn-group">
                                <button @click="saveSignedDocument" :disabled="placedSignatures.length === 0 || saving"
                                    class="btn btn-success">
                                    <i class='bx bx-save'></i>
                                    <span v-if="!saving">Save Signed Document</span>
                                    <span v-else>Saving...</span>
                                </button>
                                <a :href="cancelUrl" class="btn btn-secondary">
                                    <i class='bx bx-x'></i> Cancel
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div v-if="placedSignatures.length === 0" class="alert alert-info">
                            <i class='bx bx-info-circle'></i>
                            <span v-if="!allPagesReady">Please wait for the document to finish loading...</span>
                            <span v-else>Drag and drop signatures from the left panel onto the document. You can resize,
                                rotate, and position them as needed.</span>
                        </div>

                        <!-- Label Input Field -->
                        <div v-if="placedSignatures.length > 0" class="mb-3">
                            <label for="documentLabel" class="form-label">
                                <i class='bx bx-label'></i> Label for Signed Document *
                            </label>
                            <input type="text" id="documentLabel" v-model="documentLabel" class="form-control"
                                placeholder="e.g., CEO & Agent A Signed Agreement" maxlength="255" required>
                            <div class="form-text">
                                Give this signed document a descriptive name to identify it later.
                            </div>
                        </div>

                        <div v-if="!loading && !allPagesReady" class="alert alert-warning">
                            <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                            Rendering pages... ({{ pagesRendered }}/{{ totalPages }})
                        </div>

                        <div class="signature-canvas" ref="canvas" @dragover.prevent @drop="drop">
                            <div v-if="loading" class="text-center py-5">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-2">Loading document...</p>
                            </div>

                            <template v-if="!loading">
                                <div v-for="page in totalPages" :key="`page-${page}`" class="pdf-page">
                                    <div class="page-number">Page {{ page }}</div>
                                    <VuePdfEmbed :source="pdfSource" :page="page" @rendered="onPageRendered(page)" />
                                </div>
                            </template>

                            <template v-if="allPagesReady">
                                <div v-for="(sig, index) in placedSignatures" :key="`sig-${index}`" :data-index="index"
                                    class="signature-item" :class="{ selected: selectedSignature === index }" :style="{
                                        left: sig.x + 'px',
                                        top: sig.y + 'px',
                                        width: sig.width + 'px',
                                        height: sig.height + 'px',
                                        transform: `rotate(${sig.rotation}deg)`,
                                        zIndex: 100 + index
                                    }" @click="selectSignature(index)">
                                    <img :src="sig.url" :alt="sig.title">
                                    <button class="remove-btn" @click.stop="removeSignature(index)">
                                        ×
                                    </button>
                                </div>
                            </template>
                        </div>

                        <div class="mt-3 text-muted small">
                            <strong>Instructions:</strong>
                            <ul class="mb-0">
                                <li>Drag signatures from the left panel onto the document (you can use the same
                                    signature multiple times!)</li>
                                <li>Drag signatures to reposition them</li>
                                <li>Drag the corners to resize</li>
                                <li>Use Shift+Drag to rotate</li>
                                <li>Click the × button to remove a signature</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import VuePdfEmbed from 'vue-pdf-embed';
import interact from 'interactjs';
import * as pdfjsLib from 'pdfjs-dist';

export default {
    name: 'DocumentSigningApp',
    components: {
        VuePdfEmbed
    },
    props: {
        documentName: {
            type: String,
            required: true
        },
        documentId: {
            type: Number,
            required: true
        },
        documentUrl: {
            type: String,
            required: true
        },
        signaturesUrl: {
            type: String,
            required: true
        },
        createSignatureUrl: {
            type: String,
            required: true
        },
        cancelUrl: {
            type: String,
            required: true
        }
    },
    data() {
        return {
            signatures: [],
            placedSignatures: [],
            selectedSignature: null,
            loading: true,
            saving: false,
            totalPages: 0,
            pageOffsets: [],
            pdfSource: null,
            pagesRendered: 0,
            allPagesReady: false,
            flashMessage: null,
            flashType: 'success', // 'success', 'error', 'warning', 'info'
            documentLabel: '' // Label for the signed document
        };
    },
    mounted() {
        this.loadSignatures();
        this.initializePDF();
    },
    methods: {
        showFlash(message, type = 'success') {
            this.flashMessage = message;
            this.flashType = type;

            // Auto-hide after 5 seconds
            setTimeout(() => {
                this.flashMessage = null;
            }, 5000);

            // Scroll to top to show the message
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },

        async loadSignatures() {
            try {
                const response = await fetch(this.signaturesUrl);
                this.signatures = await response.json();
            } catch (error) {
                console.error('Error loading signatures:', error);
                this.showFlash('Failed to load signatures. Please refresh the page.', 'error');
            }
        },

        async initializePDF() {
            try {
                // Fetch the PDF as a blob to get page count
                const response = await fetch(this.documentUrl, {
                    credentials: 'include'
                });
                const blob = await response.blob();
                this.pdfSource = URL.createObjectURL(blob);

                // Use pdf.js just to get page count
                pdfjsLib.GlobalWorkerOptions.workerSrc = `https://cdnjs.cloudflare.com/ajax/libs/pdf.js/${pdfjsLib.version}/pdf.worker.min.js`;

                const loadingTask = pdfjsLib.getDocument(this.pdfSource);
                const pdfDoc = await loadingTask.promise;
                this.totalPages = pdfDoc.numPages;

                this.loading = false;

                // Wait for Vue to render the PDF components
                await this.$nextTick();
                setTimeout(() => {
                    this.calculatePageOffsets();
                    this.initializeInteract();
                }, 500);
            } catch (error) {
                console.error('Error loading PDF:', error);
                alert('Failed to load document. Please try again.');
                this.loading = false;
            }
        },

        onPageRendered(pageNum) {
            // Called when each VuePdfEmbed component finishes rendering
            this.pagesRendered++;

            if (this.pagesRendered === this.totalPages) {
                // All pages rendered
                this.$nextTick(() => {
                    setTimeout(() => {
                        this.calculatePageOffsets();
                        this.initializeInteract();
                        this.allPagesReady = true;
                    }, 300);
                });
            }
        },

        calculatePageOffsets() {
            this.pageOffsets = [];
            let cumulativeOffset = 0;

            const pdfPages = this.$refs.canvas?.querySelectorAll('.pdf-page');
            if (!pdfPages) return;

            pdfPages.forEach((pageEl, index) => {
                const pageNum = index + 1;
                const canvas = pageEl.querySelector('canvas');

                if (canvas) {
                    this.pageOffsets.push({
                        page: pageNum,
                        top: cumulativeOffset,
                        bottom: cumulativeOffset + canvas.height,
                        height: canvas.height,
                        width: canvas.width
                    });
                    cumulativeOffset += canvas.height + 20;
                }
            });
        }, getPageFromY(y) {
            for (let i = 0; i < this.pageOffsets.length; i++) {
                if (y >= this.pageOffsets[i].top && y < this.pageOffsets[i].bottom) {
                    return i + 1;
                }
            }
            return 1;
        },

        dragStart(event, signature) {
            event.dataTransfer.effectAllowed = 'copy';
            event.dataTransfer.setData('signature', JSON.stringify(signature));
        },

        dragEnd(event) {
            // Cleanup if needed
        },

        drop(event) {
            event.preventDefault();

            // Ensure PDF is fully loaded and all pages are rendered
            if (this.loading || !this.allPagesReady || !this.$refs.canvas || this.pageOffsets.length === 0) {
                console.warn('PDF not ready for signature placement yet');
                return;
            }

            const signatureData = JSON.parse(event.dataTransfer.getData('signature'));

            const rect = this.$refs.canvas.getBoundingClientRect();
            const x = event.clientX - rect.left + this.$refs.canvas.scrollLeft;
            const y = event.clientY - rect.top + this.$refs.canvas.scrollTop;

            const page = this.getPageFromY(y);

            this.placedSignatures.push({
                signature_id: signatureData.id,
                title: signatureData.title,
                url: signatureData.url,
                page: page,
                x: x - 75,
                y: y - 30,
                width: 150,
                height: 60,
                rotation: 0
            });

            this.$nextTick(() => {
                this.initializeInteract();
            });
        },

        initializeInteract() {
            const self = this;

            interact('.signature-item').unset();

            interact('.signature-item')
                .draggable({
                    inertia: true,
                    modifiers: [
                        interact.modifiers.restrictRect({
                            restriction: 'parent',
                            endOnly: true
                        })
                    ],
                    listeners: {
                        move(event) {
                            const index = parseInt(event.target.dataset.index);
                            if (!isNaN(index) && self.placedSignatures[index]) {
                                self.placedSignatures[index].x += event.dx;
                                self.placedSignatures[index].y += event.dy;
                                self.placedSignatures[index].page = self.getPageFromY(self.placedSignatures[index].y);
                            }
                        }
                    }
                })
                .resizable({
                    edges: { left: true, right: true, bottom: true, top: true },
                    listeners: {
                        move(event) {
                            const index = parseInt(event.target.dataset.index);
                            if (!isNaN(index) && self.placedSignatures[index]) {
                                self.placedSignatures[index].width = event.rect.width;
                                self.placedSignatures[index].height = event.rect.height;
                                self.placedSignatures[index].x += event.deltaRect.left;
                                self.placedSignatures[index].y += event.deltaRect.top;
                            }
                        }
                    },
                    modifiers: [
                        interact.modifiers.restrictSize({
                            min: { width: 50, height: 20 }
                        })
                    ]
                })
                .gesturable({
                    listeners: {
                        move(event) {
                            const index = parseInt(event.target.dataset.index);
                            if (!isNaN(index) && self.placedSignatures[index]) {
                                self.placedSignatures[index].rotation += event.da;
                            }
                        }
                    }
                });
        },

        selectSignature(index) {
            this.selectedSignature = index;
        },

        removeSignature(index) {
            this.placedSignatures.splice(index, 1);
            this.selectedSignature = null;
            this.$nextTick(() => {
                this.initializeInteract();
            });
        },

        async saveSignedDocument() {
            if (this.placedSignatures.length === 0) {
                this.showFlash('Please place at least one signature on the document.', 'warning');
                return;
            }

            // Validate label
            if (!this.documentLabel || this.documentLabel.trim() === '') {
                this.showFlash('Please enter a label for the signed document.', 'warning');
                // Scroll to the label input
                document.getElementById('documentLabel')?.focus();
                return;
            }

            if (!confirm('Save signed document: "' + this.documentLabel + '" with ' + this.placedSignatures.length + ' signature(s)?')) {
                return;
            }

            this.saving = true;

            try {
                // Prepare all signatures
                const signatures = this.placedSignatures.map(sig => {
                    const pageOffset = this.pageOffsets.find(p => p.page === sig.page);
                    return {
                        signature_id: sig.signature_id,
                        page: sig.page,
                        x: sig.x,
                        y: sig.y - (pageOffset ? pageOffset.top : 0),
                        width: sig.width,
                        height: sig.height,
                        rotation: sig.rotation,
                        canvas_width: pageOffset ? pageOffset.width : 0,
                        canvas_height: pageOffset ? pageOffset.height : 0
                    };
                });

                const response = await fetch(`/customer/documents/${this.documentId}/sign`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        signatures: signatures,
                        label: this.documentLabel.trim()
                    })
                });

                // Check if response is ok
                if (!response.ok) {
                    let errorMessage = 'Failed to save signed document.';
                    try {
                        const errorData = await response.json();
                        errorMessage = errorData.error || errorData.message || errorMessage;
                    } catch (e) {
                        errorMessage = `Server error (${response.status}): ${response.statusText}`;
                    }
                    throw new Error(errorMessage);
                }

                const result = await response.json();

                if (result.success) {
                    this.showFlash(result.message, 'success');
                    setTimeout(() => {
                        window.location.href = result.redirect_url;
                    }, 1000);
                } else {
                    this.showFlash('Error: ' + (result.error || 'Failed to save signed document.'), 'error');
                    this.saving = false;
                }
            } catch (error) {
                console.error('Error saving document:', error);
                this.showFlash('Failed to save signed document: ' + error.message, 'error');
                this.saving = false;
            }
        }
    }
};
</script>

<style scoped>
.signature-canvas {
    position: relative;
    border: 2px solid #ddd;
    background: white;
    min-height: 600px;
    overflow: auto;
}

.signature-item {
    position: absolute;
    cursor: move;
    border: 2px dashed transparent;
    transition: border-color 0.2s;
    user-select: none;
    touch-action: none;
}

.signature-item:hover,
.signature-item.selected {
    border-color: #0d6efd;
}

.signature-item img {
    width: 100%;
    height: 100%;
    pointer-events: none;
}

.signature-item .remove-btn {
    position: absolute;
    top: -10px;
    right: -10px;
    width: 24px;
    height: 24px;
    background: #dc3545;
    color: white;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    display: none;
    z-index: 10;
    font-size: 12px;
    line-height: 1;
}

.signature-item:hover .remove-btn,
.signature-item.selected .remove-btn {
    display: block;
}

.signature-list {
    max-height: 500px;
    overflow-y: auto;
}

.signature-preview {
    cursor: grab;
    padding: 10px;
    border: 2px solid #ddd;
    border-radius: 5px;
    margin-bottom: 10px;
    transition: all 0.2s;
    background: white;
}

.signature-preview:hover {
    border-color: #0d6efd;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.signature-preview:active {
    cursor: grabbing;
}

.signature-preview img {
    max-width: 100%;
    height: auto;
    max-height: 80px;
}

.pdf-page {
    position: relative;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.page-number {
    position: absolute;
    top: 10px;
    left: 10px;
    background: rgba(0, 0, 0, 0.7);
    color: white;
    padding: 5px 10px;
    border-radius: 5px;
    font-size: 12px;
    z-index: 5;
}
</style>
