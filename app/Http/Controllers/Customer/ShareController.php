<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentShare;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\DocumentSharedMail;

class ShareController extends Controller
{
    public function index(Document $document)
    {
        if ($document->user_id !== auth()->id()) {
            abort(403);
        }

        $shares = $document->signedDocuments()->exists()
            ? DocumentShare::where('document_id', $document->id)->orWhere('signed_document_id', $document->signedDocuments()->pluck('id'))->latest()->get()
            : DocumentShare::where('document_id', $document->id)->latest()->get();

        return view('customer.documents.shares.index', compact('document', 'shares'));
    }

    public function create(Document $document)
    {
        if ($document->user_id !== auth()->id()) {
            abort(403);
        }

        $users = User::where('role', 'customer')->where('id', '!=', auth()->id())->orderBy('name')->get();

        return view('customer.documents.shares.create', compact('document', 'users'));
    }

    public function store(Request $request, Document $document)
    {
        if ($document->user_id !== auth()->id()) {
            abort(403);
        }

        $data = $request->validate([
            'share_type' => 'required|in:public_link,email,registered_user',
            'recipient_emails' => 'nullable|array',
            'recipient_emails.*' => 'nullable|email',
            'shared_with_user_ids' => 'nullable|array',
            'shared_with_user_ids.*' => 'nullable|exists:users,id',
            'expires_at' => 'nullable|date',
            'message' => 'nullable|string|max:1000',
        ]);

        $sharesCreated = 0;
        $emailsSent = 0;
        $shares = [];
        $errors = [];

        // For public link, create only one share
        if ($data['share_type'] === 'public_link') {
            $share = DocumentShare::create([
                'document_id' => $document->id,
                'shared_by_user_id' => auth()->id(),
                'share_type' => 'public_link',
                'expires_at' => $data['expires_at'] ?? null,
                'metadata' => ['message' => $data['message'] ?? null],
            ]);
            $shares[] = $share;
            $sharesCreated++;
        }

        // For email shares, create one share per email
        if ($data['share_type'] === 'email' && !empty($data['recipient_emails'])) {
            $emails = array_filter($data['recipient_emails']); // Remove empty values

            foreach ($emails as $email) {
                $share = DocumentShare::create([
                    'document_id' => $document->id,
                    'shared_by_user_id' => auth()->id(),
                    'share_type' => 'email',
                    'recipient_email' => $email,
                    'expires_at' => $data['expires_at'] ?? null,
                    'metadata' => ['message' => $data['message'] ?? null],
                ]);
                $shares[] = $share;
                $sharesCreated++;

                // Send email
                try {
                    Mail::to($email)->send(new DocumentSharedMail($share));
                    $emailsSent++;
                } catch (\Exception $e) {
                    $errors[] = "Failed to send email to {$email}";
                }
            }
        }

        // For registered users, create one share per user
        if ($data['share_type'] === 'registered_user' && !empty($data['shared_with_user_ids'])) {
            $userIds = array_filter($data['shared_with_user_ids']); // Remove empty values

            foreach ($userIds as $userId) {
                $share = DocumentShare::create([
                    'document_id' => $document->id,
                    'shared_by_user_id' => auth()->id(),
                    'share_type' => 'registered_user',
                    'shared_with_user_id' => $userId,
                    'expires_at' => $data['expires_at'] ?? null,
                    'metadata' => ['message' => $data['message'] ?? null],
                ]);
                $shares[] = $share;
                $sharesCreated++;

                // Send notification email
                if ($share->sharedWith) {
                    try {
                        Mail::to($share->sharedWith->email)->send(new DocumentSharedMail($share));
                        $emailsSent++;
                    } catch (\Exception $e) {
                        $errors[] = "Failed to send email to {$share->sharedWith->name}";
                    }
                }
            }
        }

        // Build success message
        if ($sharesCreated === 0) {
            return redirect()->route('customer.documents.shares.create', $document)
                ->with('error', 'Please select at least one recipient.');
        }

        $successMessage = "Successfully created {$sharesCreated} share(s)!";

        if ($data['share_type'] === 'email') {
            $successMessage .= " {$emailsSent} email(s) sent.";
        } elseif ($data['share_type'] === 'registered_user') {
            $successMessage .= " {$emailsSent} notification(s) sent to registered users.";
        }

        if (!empty($errors)) {
            $successMessage .= " Note: " . implode(', ', $errors);
        }

        // Return with first share link
        return redirect()->route('customer.documents.show', $document)
            ->with('success', $successMessage)
            ->with('share_link', $shares[0]->getShareUrl());
    }

    public function destroy(DocumentShare $share)
    {
        // Ensure owner
        $docId = $share->document_id ?? $share->signed_document_id ? $share->signedDocument->document_id ?? $share->document_id : $share->document_id;

        // If share points to signed document, get original document's owner
        $ownerId = $share->document ? $share->document->user_id : ($share->signedDocument ? $share->signedDocument->document->user_id : null);

        if ($ownerId !== auth()->id()) {
            abort(403);
        }

        $share->delete();

        return redirect()->back()->with('success', 'Share revoked successfully.');
    }
}
