<?php

namespace App\Filament\Employee\Pages;

use App\Events\MessageSent;
use App\Jobs\SendEscalationNotification;
use App\Models\ChatMessage;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Support\Exceptions\Halt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;

class Chat extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationGroup = 'Communication';
    protected static ?string $navigationLabel = 'Chat with Admin';
    protected static ?int $navigationSort = 1;
    protected static string $view = 'filament.employee.pages.chat';
    protected static ?string $title = 'Chat with Admin';
    protected static ?string $slug = 'chat';

    public static function canAccess(): bool
    {
        // return Auth::user()->hasRole(['employee']);
        return true;
    }


    public ?array $data = [];
    public ?int $selectedAdminId = null;
    public Collection $admins;
    public Collection $messages;
    public string $messageText = '';

    public function mount(): void
    {
        try {
            $this->admins = User::role('admin')->get();

            if ($this->admins->isNotEmpty()) {
                $this->selectedAdminId = $this->admins->first()->id;
            }

            $this->loadMessages();

            $this->form->fill([
                'selectedAdminId' => $this->selectedAdminId,
                'messageText' => ''
            ]);

        } catch (\Exception $e) {
            logger('Employee Chat mount error: ' . $e->getMessage());
            $this->admins = collect();
            $this->messages = collect();
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('selectedAdminId')
                    ->label('Admin')
                    ->options($this->admins->pluck('name', 'id'))
                    ->live() 
                    ->afterStateUpdated(function ($state) {
                        $this->selectedAdminId = $state;
                        $this->loadMessages();
                    }),

                Textarea::make('messageText')
                    ->label('Your Message')
                    ->placeholder('Type your message to admin...')
                    ->required()
                    ->rows(3)
                    ->live()
                    ->afterStateUpdated(function ($state) {
                        $this->messageText = $state;
                    }),
            ])
            ->statePath('data');
    }

    public function loadMessages(): void
    {
        try {
            if (!$this->selectedAdminId) {
                $this->messages = collect();
                return;
            }

            $this->messages = ChatMessage::betweenUsers(Auth::id(), $this->selectedAdminId)
                ->with(['sender', 'receiver'])
                ->orderBy('created_at', 'asc')
                ->get();

            ChatMessage::where('sender_id', $this->selectedAdminId)
                ->where('receiver_id', Auth::id())
                ->where('is_read', false)
                ->update([
                    'is_read' => true,
                    'read_at' => now()
                ]);

        } catch (\Exception $e) {
            logger('Employee load messages error: ' . $e->getMessage());
            $this->messages = collect();
        }
    }


    public function sendMessage(): void
    {
        try {

            if (!$this->selectedAdminId) {
                $this->addError('selectedAdminId', 'Please select an admin first.');
                return;
            }

            $messageContent = $this->data['messageText'] ?? $this->messageText ?? '';

            if (empty(trim($messageContent))) {
                $this->addError('messageText', 'Please enter a message.');
                return;
            }

            if (strlen($messageContent) > 1000) {
                $this->addError('messageText', 'Message is too long (max 1000 characters).');
                return;
            }

            $message = ChatMessage::create([
                'sender_id' => Auth::id(),
                'receiver_id' => $this->selectedAdminId,
                'message' => trim($messageContent),
                'is_read' => false,
            ]);

            if (class_exists(MessageSent::class)) {
                MessageSent::dispatch($message);
            }

            if (class_exists(SendEscalationNotification::class)) {
                SendEscalationNotification::dispatch($message)
                    ->delay(now()->addMinutes(config('app.chat_escalation_minutes', 10)));
            }

            $this->messageText = '';
            $this->data['messageText'] = '';

            $this->form->fill([
                'selectedAdminId' => $this->selectedAdminId,
                'messageText' => ''
            ]);

            $this->loadMessages();

            $this->dispatch('message-sent');

            session()->flash('success', 'Message sent to admin successfully!');

        } catch (\Exception $e) {
            logger('Employee send message error: ' . $e->getMessage());
            $this->addError('messageText', 'Failed to send message. Please try again.');
        }
    }

    #[On('message-received')]
    public function refreshMessages(): void
    {
        $this->loadMessages();
    }

    public function getUnreadCount(): int
    {
        try {
            if (!$this->selectedAdminId) {
                return 0;
            }

            return ChatMessage::where('sender_id', $this->selectedAdminId)
                ->where('receiver_id', Auth::id())
                ->where('is_read', false)
                ->count();
        } catch (\Exception $e) {
            logger('Get unread count error: ' . $e->getMessage());
            return 0;
        }
    }

    public function canSendMessage(): bool
    {
        return $this->selectedAdminId !== null && !empty(trim($this->data['messageText'] ?? ''));
    }

    public function getSelectedAdmin(): ?User
    {
        if (!$this->selectedAdminId) {
            return null;
        }

        return $this->admins->where('id', $this->selectedAdminId)->first();
    }
}
