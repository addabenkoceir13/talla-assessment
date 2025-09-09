<?php

namespace App\Console\Commands;

use App\Jobs\SendEscalationNotification;
use App\Models\ChatMessage;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckUnreadMessages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chat:check-unread';
    protected $description = 'Check for unread messages that need escalation';


    /**
     * Execute the console command.
     */
    public function handle()
    {
          $this->info('Checking for unread messages that need escalation...');

          $escalationMinutes = config('app.chat_escalation_minutes', 10);
        $cutoffTime = Carbon::now()->subMinutes($escalationMinutes);

          $messages = ChatMessage::where('is_read', false)
            ->where('escalated', false)
            ->where('created_at', '<=', $cutoffTime)
            ->whereHas('sender', function ($query) {
                $query->whereHas('roles', function ($roleQuery) {
                    $roleQuery->whereIn('name', ['employee']);
                });
            })
            ->whereHas('receiver', function ($query) {
                $query->whereHas('roles', function ($roleQuery) {
                    $roleQuery->where('name', 'admin');
                });
            })
            ->with(['sender', 'receiver'])
            ->get();

        if ($messages->isEmpty()) {
            $this->info('No messages found that need escalation.');
            return self::SUCCESS;
        }

        $this->info("Found {$messages->count()} message(s) that need escalation:");

        foreach ($messages as $message) {
            try {
                $this->line("- Message ID: {$message->id} from {$message->sender->name} to {$message->receiver->name}");
                $this->line("  Sent: {$message->created_at->format('Y-m-d H:i:s')} (over {$escalationMinutes} minutes ago)");
                
                
                SendEscalationNotification::dispatch($message);
                
                $this->info("  ✓ Escalation job dispatched");
                
            } catch (\Exception $e) {
                $this->error("  ✗ Failed to dispatch escalation for message {$message->id}: " . $e->getMessage());
                Log::error("Failed to dispatch escalation for message {$message->id}: " . $e->getMessage());
            }
        }

        $this->info('Finished checking unread messages.');
        return self::SUCCESS;
    }
}
