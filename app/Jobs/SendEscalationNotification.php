<?php

namespace App\Jobs;

use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Notifications\ChatEscalationNotification;

class SendEscalationNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public ChatMessage $message;

    /**
     * Create a new job instance.
     */
    public function __construct(ChatMessage $message)
    {
        $this->message = $message;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->message->refresh();

        if ($this->message->is_read || $this->message->escalated) {
            Log::info("Message {$this->message->id} already read or escalated, skipping notification");
            return;
        }

        if (
            !$this->message->sender->hasRole(['employee']) ||
            !$this->message->receiver->hasRole('admin')
        ) {
            Log::info("Message {$this->message->id} not eligible for escalation");
            return;
        }

        $admins = User::role('admin')->get();

        foreach ($admins as $admin) {
            try {
                $admin->notify(new ChatEscalationNotification($this->message));
                Log::info("Escalation notification sent to admin {$admin->name} for message {$this->message->id}");
            } catch (\Exception $e) {
                Log::error("Failed to send escalation notification to admin {$admin->name}: " . $e->getMessage());
            }
        }
        $this->message->markAsEscalated();

        Log::info("Message {$this->message->id} from employee {$this->message->sender->name} has been escalated");
    }

    public function failed(\Throwable $exception): void
    {
        Log::error("Escalation notification job failed for message {$this->message->id}: " . $exception->getMessage());
    }
}
