<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class SignedDocument extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'label',
        'document_id',
        'signature_id',
        'signature_positions',
    ];

    protected $casts = [
        'signature_positions' => 'array',
    ];

    /**
     * Get the original document
     */
    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    /**
     * Get the signature used
     */
    public function signature(): BelongsTo
    {
        return $this->belongsTo(Signature::class);
    }

    /**
     * Register media collections
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('signed_pdf')
            ->singleFile()
            ->acceptsMimeTypes(['application/pdf']);
    }
}
