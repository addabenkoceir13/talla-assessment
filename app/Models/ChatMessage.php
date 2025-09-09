<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
        'is_read',
        'escalated',
        'escalated_at',
        'read_at',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function markAsRead(): void
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    public function markAsEscalated(): void
    {
        $this->update([
            'escalated' => true,
            'escalated_at' => now(),
        ]);
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeBetweenUsers($query, $user1, $user2)
    {
        return $query->where(function ($q) use ($user1, $user2) {
            $q->where(function ($subQ) use ($user1, $user2) {
                $subQ->where('sender_id', $user1)
                    ->where('receiver_id', $user2);
            })->orWhere(function ($subQ) use ($user1, $user2) {
                $subQ->where('sender_id', $user2)
                    ->where('receiver_id', $user1);
            });
        });
    }

    public function needsEscalation(): bool
    {
        return !$this->escalated &&
            !$this->is_read &&
            $this->created_at->addMinutes(config('app.chat_escalation_minutes', 10)) <= now();
    }
}
