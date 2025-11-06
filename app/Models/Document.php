<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Document extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'original_document_id',
    ];

    protected $casts = [];

    /**
     * Get the user that owns the document.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the original (unsigned) document.
     */
    public function originalDocument()
    {
        return $this->belongsTo(Document::class, 'original_document_id');
    }

    /**
     * Get all signed versions of this document.
     */
    public function signedVersions()
    {
        return $this->hasMany(Document::class, 'original_document_id');
    }

    /**
     * Get all signed documents (in signed_documents table).
     */
    public function signedDocuments()
    {
        return $this->hasMany(SignedDocument::class);
    }

    /**
     * Check if document has been signed (has any signed documents).
     */
    public function isSigned(): bool
    {
        return $this->signedDocuments()->exists();
    }

    /**
     * Get the count of signed documents.
     */
    public function getSignedCountAttribute(): int
    {
        return $this->signedDocuments()->count();
    }

    /**
     * Check if document is original (not a signed version).
     */
    public function isOriginal(): bool
    {
        return is_null($this->original_document_id);
    }

    /**
     * Register media collections.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('documents')
            ->singleFile()
            ->acceptsMimeTypes(['application/pdf']);
    }

    /**
     * Get the document file.
     */
    public function getDocumentFile()
    {
        return $this->getFirstMedia('documents');
    }

    /**
     * Get file name attribute.
     */
    public function getFileNameAttribute()
    {
        $media = $this->getFirstMedia('documents');
        return $media ? $media->file_name : null;
    }

    /**
     * Get file path attribute.
     */
    public function getFilePathAttribute()
    {
        $media = $this->getFirstMedia('documents');
        return $media ? $media->getPath() : null;
    }

    /**
     * Get file size attribute.
     */
    public function getFileSizeAttribute()
    {
        $media = $this->getFirstMedia('documents');
        return $media ? $media->size : 0;
    }

    /**
     * Get human readable file size.
     */
    public function getFileSizeHumanAttribute()
    {
        $bytes = $this->file_size;
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }

    /**
     * Get mime type attribute.
     */
    public function getMimeTypeAttribute()
    {
        $media = $this->getFirstMedia('documents');
        return $media ? $media->mime_type : null;
    }
}
