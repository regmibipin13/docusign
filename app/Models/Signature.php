<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Signature extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'user_id',
        'title',
        'is_active',
        'signature_type',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the user that owns the signature.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Register media collections.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('signatures')
            ->singleFile()
            ->acceptsMimeTypes(['image/png', 'image/jpeg', 'image/jpg', 'image/gif']);
    }

    /**
     * Get the signature file.
     */
    public function getSignatureFile()
    {
        return $this->getFirstMedia('signatures');
    }

    /**
     * Get signature URL attribute.
     */
    public function getSignatureUrlAttribute()
    {
        $media = $this->getFirstMedia('signatures');
        return $media ? $media->getUrl() : null;
    }

    /**
     * Get file name attribute.
     */
    public function getFileNameAttribute()
    {
        $media = $this->getFirstMedia('signatures');
        return $media ? $media->file_name : null;
    }
}
