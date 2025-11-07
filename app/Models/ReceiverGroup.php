<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReceiverGroup extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'description',
    ];

    /**
     * Get the user that owns the group.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the members of the group.
     */
    public function members()
    {
        return $this->hasMany(ReceiverGroupMember::class);
    }

    /**
     * Get email members only.
     */
    public function emailMembers()
    {
        return $this->members()->where('recipient_type', 'email');
    }

    /**
     * Get registered user members only.
     */
    public function registeredUserMembers()
    {
        return $this->members()->where('recipient_type', 'registered_user');
    }

    /**
     * Get all recipients as array for sharing.
     */
    public function getRecipientsAttribute()
    {
        return [
            'emails' => $this->emailMembers->pluck('recipient_value')->toArray(),
            'user_ids' => $this->registeredUserMembers->pluck('recipient_value')->toArray(),
        ];
    }

    /**
     * Get member count.
     */
    public function getMemberCountAttribute()
    {
        return $this->members()->count();
    }

    /**
     * Scope to get only groups belonging to a specific user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
