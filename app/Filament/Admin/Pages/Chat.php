<?php

namespace App\Filament\Admin\Pages;

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

    protected static ?string $navigationIcon  = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationGroup = 'Communication';
    protected static ?string $navigationLabel = 'Chat with Employees';
    protected static ?int $navigationSort = 2;
    protected static string $view   = 'filament.admin.pages.chat';
    protected static ?string $title = 'Chat with Employees';
    protected static ?string $slug  = 'chat';

    public static function canAccess(): bool
    {
        return Auth::user()->hasRole(['admin']);
    }

    public ?array $data = [];
    public ?int $selectedUserId = null;
    public Collection $employees;
    public Collection $messages;
    public string $newMessage = '';

    public function mount(): void
    {
        try {

            $this->employees = User::whereHas('roles', function ($query) {
                $query->whereIn('name', ['employee']);
            })
                ->get();

            $this->selectedUserId = request()->get('user');

            $this->loadMessages();
            $this->form->fill([
                'selectedUserId' => $this->selectedUserId,
                'messageText' => ''
            ]);
        } catch (\Exception $e) {
            logger('Admin Chat mount error: ' . $e->getMessage());
            $this->employees = collect();
            $this->messages = collect();
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('selectedUserId')
                    ->label('Select Employee')
                    ->options($this->employees->pluck('name', 'id'))
                    ->placeholder('Choose an employee to chat with...')
                    ->live()
                    ->afterStateUpdated(function ($state) {
                        $this->selectedUserId = $state;
                        $this->loadMessages();
                    }),

                Textarea::make('messageText')
                    ->label('Your Message')
                    ->placeholder('Type your message here...')
                    ->required()
                    ->rows(3)
                    ->live()
                    ->afterStateUpdated(function ($state) {
                        $this->newMessage = $state;
                    }),
            ])
            ->statePath('data');
    }

    public function loadMessages(): void
    {
        if (!$this->selectedUserId) {
            $this->messages = collect();
            return;
        }

        $this->messages = ChatMessage::betweenUsers(Auth::id(), $this->selectedUserId)
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'asc')
            ->get();


        ChatMessage::where('sender_id', $this->selectedUserId)
            ->where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);
    }

    public function sendMessage(): void
    {
         try {

            if (!$this->selectedUserId) {
                $this->addError('selectedUserId', 'Please select an employee first.');
                return;
            }

            $messageContent = $this->data['messageText'] ?? $this->messageText ?? '';

            if (empty(trim($messageContent))) {
                $this->addError('messageText', 'Please enter a message.');
                return;
            }

            $message = ChatMessage::create([
                'sender_id' => Auth::id(),
                'receiver_id' => $this->selectedUserId,
                'message' => trim($messageContent),
                'is_read' => false,
            ]);


                MessageSent::dispatch($message);


            $this->newMessage = '';
            $this->data['messageText'] = '';

            $this->form->fill([
                'selectedUserId' => $this->selectedUserId,
                'messageText' => ''
            ]);

            $this->loadMessages();

            $this->dispatch('message-sent');

            session()->flash('success', 'Message sent successfully!');

        } catch (\Exception $e) {
            logger('Send message error: ' . $e->getMessage());
            $this->addError('messageText', 'Failed to send message: ' . $e->getMessage());
        }
    }

    #[On('message-received')]
    public function refreshMessages(): void
    {
        $this->loadMessages();
    }

    public function getUnreadCounts(): array
    {
        $counts = [];
        foreach ($this->employees as $employee) {
            $counts[$employee->id] = ChatMessage::where('sender_id', $employee->id)
                ->where('receiver_id', Auth::id())
                ->where('is_read', false)
                ->count();
        }
        return $counts;
    }

    public function selectEmployee(int $userId): void
    {
        $this->selectedUserId = $userId;
        $this->data['selectedUserId'] = $userId;
        $this->loadMessages();
    }
}
