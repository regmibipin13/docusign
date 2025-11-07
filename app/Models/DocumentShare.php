<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class DocumentShare extends Model
{
    protected $fillable = [
        'document_id',
        'signed_document_id',
        'shared_by_user_id',
        'shared_with_user_id',
        'share_type',
        'share_token',
        'recipient_email',
        'access_count',
        'expires_at',
        'last_accessed_at',
        'is_active',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'expires_at' => 'datetime',
        'last_accessed_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($share) {
            if (empty($share->share_token)) {
                $share->share_token = Str::uuid()->toString();
            }
        });
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    public function signedDocument(): BelongsTo
    {
        return $this->belongsTo(SignedDocument::class, 'signed_document_id');
    }

    public function sharedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'shared_by_user_id');
    }

    public function sharedWith(): BelongsTo
    {
        return $this->belongsTo(User::class, 'shared_with_user_id');
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function getShareUrl(): string
    {
        return route('public.document.show', $this->share_token);
    }

    public function incrementAccessCount(): void
    {
        $this->increment('access_count');
        $this->last_accessed_at = now();
        $this->save();
    }
}
