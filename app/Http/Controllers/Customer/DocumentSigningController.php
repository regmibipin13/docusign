<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Signature;
use App\Models\SignedDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use setasign\Fpdi\Fpdi;

class DocumentSigningController extends Controller
{
    /**
     * Show the document signing interface.
     */
    public function sign(Document $document)
    {
        // Ensure user can only sign their own documents
        if ($document->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('customer.documents.sign', compact('document'));
    }

    /**
     * Get customer's active signatures for the signing interface.
     */
    public function getSignatures()
    {
        $signatures = auth()->user()->signatures()
            ->where('is_active', true)
            ->get()
            ->map(function ($signature) {
                return [
                    'id' => $signature->id,
                    'title' => $signature->title,
                    'type' => $signature->signature_type,
                    'url' => $signature->signature_url,
                ];
            });

        return response()->json($signatures);
    }

    /**
     * Save the signed document.
     */
    public function store(Request $request, Document $document)
    {
        // Ensure user can only sign their own documents
        if ($document->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        try {
            // Validate the request
            $request->validate([
                'label' => ['required', 'string', 'max:255'],
                'signatures' => ['required', 'array', 'min:1'],
                'signatures.*.signature_id' => ['required', 'exists:signatures,id'],
                'signatures.*.page' => ['required', 'integer', 'min:1'],
                'signatures.*.x' => ['required', 'numeric'],
                'signatures.*.y' => ['required', 'numeric'],
                'signatures.*.width' => ['required', 'numeric', 'min:1'],
                'signatures.*.height' => ['required', 'numeric', 'min:1'],
                'signatures.*.rotation' => ['required', 'numeric'],
                'signatures.*.canvas_width' => ['required', 'numeric'],
                'signatures.*.canvas_height' => ['required', 'numeric'],
            ]);

            // Verify all signatures belong to the authenticated user
            $allSignatureIds = collect($request->signatures)->pluck('signature_id')->unique();
            $userSignatures = auth()->user()->signatures()->whereIn('id', $allSignatureIds)->pluck('id');

            if ($allSignatureIds->count() !== $userSignatures->count()) {
                return response()->json(['error' => 'Invalid signature(s) selected.'], 403);
            }

            $originalMedia = $document->getFirstMedia('documents');
            if (!$originalMedia) {
                throw new \Exception("Original document has no attached file.");
            }

            $originalFilePath = $originalMedia->getPath();
            if (!file_exists($originalFilePath)) {
                throw new \Exception("Original document file not found.");
            }

            // Use the provided label
            $label = $request->label;

            // Generate signed PDF with ALL signatures
            $signedPdfPath = $this->generateSignedPdf(
                $originalFilePath,
                $request->signatures,
                auth()->user()
            );

            // Create signed document record
            $signedDocument = SignedDocument::create([
                'label' => $label,
                'document_id' => $document->id,
                'signature_id' => $request->signatures[0]['signature_id'], // First signature
                'signature_positions' => $request->signatures, // Store positions with signed document
            ]);

            // Attach the signed PDF
            $signedDocument->addMedia($signedPdfPath)
                ->toMediaCollection('signed_pdf');

            // Clean up temporary file
            if (file_exists($signedPdfPath)) {
                unlink($signedPdfPath);
            }

            return response()->json([
                'success' => true,
                'message' => 'Signed document created successfully.',
                'redirect_url' => route('customer.documents.show', $document),
            ]);
        } catch (\Exception $e) {
            // Clean up created signed document on error
            if (isset($signedDocument)) {
                $signedDocument->delete();
            }

            return response()->json([
                'error' => 'Failed to create signed document: ' . $e->getMessage()
            ], 500);
        }
    }
    /**
     * Generate a signed PDF with signatures overlaid on the original document.
     */
    protected function generateSignedPdf(string $originalPdfPath, array $signaturePositions, $user): string
    {
        // Create PDF instance with explicit units (points)
        $pdf = new Fpdi('P', 'pt');
        $pdf->SetAutoPageBreak(false);
        $pageCount = $pdf->setSourceFile($originalPdfPath);

        // Group signatures by page
        $signaturesByPage = collect($signaturePositions)->groupBy('page');

        // Process each page
        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            // Import the page from original PDF
            $templateId = $pdf->importPage($pageNo);
            $size = $pdf->getTemplateSize($templateId);

            // Add page with same dimensions as original
            $pdf->AddPage($size['orientation'] ?? 'P', [$size['width'], $size['height']]);
            $pdf->useTemplate($templateId, 0, 0, $size['width'], $size['height']);

            // Add signatures for this page
            if (isset($signaturesByPage[$pageNo])) {
                foreach ($signaturesByPage[$pageNo] as $sigPosition) {
                    $signature = Signature::find($sigPosition['signature_id']);

                    if ($signature && $signature->user_id === $user->id) {
                        $signatureMedia = $signature->getFirstMedia('signatures');

                        if ($signatureMedia) {
                            $signatureImagePath = $signatureMedia->getPath();

                            // Get canvas dimensions from frontend (in pixels)
                            $canvasWidth = (float) ($sigPosition['canvas_width'] ?? $size['width']);
                            $canvasHeight = (float) ($sigPosition['canvas_height'] ?? $size['height']);

                            // Calculate scale factors (canvas pixels to PDF points)
                            // PDF size is already in points, canvas is in pixels
                            $scaleX = $size['width'] / $canvasWidth;
                            $scaleY = $size['height'] / $canvasHeight;

                            // Convert browser coordinates to PDF coordinates
                            // Both browser AND FPDF Image() use top-left origin
                            // So we DON'T need to flip Y coordinate
                            $pdfX = (float) $sigPosition['x'] * $scaleX;
                            $pdfY = (float) $sigPosition['y'] * $scaleY;  // Simple conversion, no flipping needed
                            $pdfWidth = (float) $sigPosition['width'] * $scaleX;
                            $pdfHeight = (float) $sigPosition['height'] * $scaleY;

                            // Debug logging
                            Log::info("Signature placement debug", [
                                'page' => $pageNo,
                                'canvas_size' => ['width' => $canvasWidth, 'height' => $canvasHeight],
                                'pdf_size' => ['width' => $size['width'], 'height' => $size['height']],
                                'scale' => ['x' => $scaleX, 'y' => $scaleY],
                                'browser_coords' => ['x' => $sigPosition['x'], 'y' => $sigPosition['y'], 'w' => $sigPosition['width'], 'h' => $sigPosition['height']],
                                'pdf_coords' => ['x' => $pdfX, 'y' => $pdfY, 'w' => $pdfWidth, 'h' => $pdfHeight]
                            ]);

                            // Add signature image to PDF
                            try {
                                // Determine image type
                                $imageType = strtolower(pathinfo($signatureImagePath, PATHINFO_EXTENSION));

                                if (in_array($imageType, ['jpg', 'jpeg'])) {
                                    $pdf->Image($signatureImagePath, $pdfX, $pdfY, $pdfWidth, $pdfHeight, 'JPEG');
                                } elseif ($imageType === 'png') {
                                    $pdf->Image($signatureImagePath, $pdfX, $pdfY, $pdfWidth, $pdfHeight, 'PNG');
                                }
                            } catch (\Exception $e) {
                                // Skip this signature if image can't be added
                                Log::warning("Failed to add signature to PDF: " . $e->getMessage());
                            }
                        }
                    }
                }
            }
        }

        // Save to temporary file
        $tempPath = storage_path('app/temp/signed_' . uniqid() . '.pdf');

        // Ensure temp directory exists
        $tempDir = dirname($tempPath);
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $pdf->Output('F', $tempPath);

        return $tempPath;
    }
}
