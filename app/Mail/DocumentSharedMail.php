<?php

namespace App\Mail;

use App\Models\DocumentShare;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DocumentSharedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $share;

    /**
     * Create a new message instance.
     */
    public function __construct(DocumentShare $share)
    {
        $this->share = $share;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('A document has been shared with you')
            ->view('emails.document_shared')
            ->with(['share' => $this->share]);
    }
}
