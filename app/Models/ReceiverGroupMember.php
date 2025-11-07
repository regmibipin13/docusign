<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReceiverGroupMember extends Model
{
    protected $fillable = [
        'receiver_group_id',
        'recipient_type',
        'recipient_value',
    ];

    /**
     * Get the group this member belongs to.
     */
    public function receiverGroup()
    {
        return $this->belongsTo(ReceiverGroup::class);
    }

    /**
     * Get the user if this is a registered user member.
     */
    public function getUser()
    {
        if ($this->recipient_type === 'registered_user') {
            return User::find($this->recipient_value);
        }
        return null;
    }

    /**
     * Get display name for the member.
     */
    public function getDisplayNameAttribute()
    {
        if ($this->recipient_type === 'email') {
            return $this->recipient_value;
        }

        $user = $this->getUser();
        return $user ? $user->name : 'Unknown User';
    }
}
