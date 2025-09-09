<?php

namespace App\Notifications;

use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ChatEscalationNotification extends Notification
{
    use Queueable;

    public ChatMessage $message;
    public User $employee;

    /**
     * Create a new notification instance.
     */
    public function __construct(ChatMessage $message)
    {
        $this->message = $message;
        $this->employee = $message->sender;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'chat_escalation',
            'title' => 'Unread Message Alert',
            'message' => "Employee {$this->employee->name} is waiting for a reply",
            'employee_name' => $this->employee->name,
            'employee_id' => $this->employee->id,
            'original_message' => $this->message->message,
            'sent_at' => $this->message->created_at->format('Y-m-d H:i:s'),
            'action_url' => '/admin/chat?user=' . $this->employee->id,
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'type' => 'chat_escalation',
            'title' => 'Unread Message Alert',
            'message' => "Employee {$this->employee->name} is waiting for a reply",
            'employee_name' => $this->employee->name,
            'employee_id' => $this->employee->id,
            'original_message' => $this->message->message,
            'sent_at' => $this->message->created_at->format('Y-m-d H:i:s'),
            'action_url' => '/admin/chat?user=' . $this->employee->id,
        ]);
    }

    public function broadcastOn(): array
    {
        return ['admin-notifications'];
    }

    public function broadcastAs(): string
    {
        return 'escalation.alert';
    }

    // public function toMail(object $notifiable): MailMessage
    // {
    //     return (new MailMessage)
    //         ->line('The introduction to the notification.')
    //         ->action('Notification Action', url('/'))
    //         ->line('Thank you for using our application!');
    // }


    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    // public function toArray(object $notifiable): array
    // {
    //     return [
    //         //
    //     ];
    // }
}
