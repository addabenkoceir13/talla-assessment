<x-filament-panels::page>
    @push('styles')
        <meta name="csrf-token" content="{{ csrf_token() }}">
    @endpush
    <div class="space-y-6">

        <!-- Success Message -->
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 rounded-md p-4">
                <div class="flex">
                    <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Header Info -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                <h3 class="text-lg font-semibold text-blue-900">Admin Chat Center</h3>
            </div>
            <p class="text-sm text-blue-700 mt-1">
                Selected Employee:
                @if ($selectedUserId)
                    <strong>{{ $employees->where('id', $selectedUserId)->first()?->name ?? 'Unknown' }}</strong>
                @else
                    <em>None selected</em>
                @endif
            </p>
        </div>

        @if ($employees->isNotEmpty())
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 min-h-[600px]">

                <div class="lg:col-span-1 bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="p-4 bg-gray-50 border-b">
                        <h3 class="font-semibold text-gray-900">Employees ({{ $employees->count() }})</h3>
                    </div>

                    <div class="divide-y divide-gray-200 max-h-[500px] overflow-y-auto">
                        @foreach ($employees as $employee)
                            <div wire:click="selectEmployee({{ $employee->id }})"
                                class="p-3 hover:bg-gray-50 cursor-pointer transition-colors duration-200
                                        {{ $selectedUserId == $employee->id ? 'bg-blue-50 border-r-2 border-blue-500' : '' }}">

                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                            <span class="text-sm font-medium text-gray-700">
                                                {{ substr($employee->name, 0, 1) }}
                                            </span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $employee->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $employee->email }}</p>
                                        </div>
                                    </div>

                                    <!-- Unread Count -->
                                    @php
                                        $unreadCount = \App\Models\ChatMessage::where('sender_id', $employee->id)
                                            ->where('receiver_id', auth()->id())
                                            ->where('is_read', false)
                                            ->count();
                                    @endphp

                                    @if ($unreadCount > 0)
                                        <span
                                            class="inline-flex items-center justify-center w-5 h-5 text-xs font-medium text-dark bg-red-500 rounded-full">
                                            {{ $unreadCount }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Chat Area -->
                <div class="lg:col-span-3 bg-white rounded-lg shadow-sm border border-gray-200 flex flex-col">

                    @if ($selectedUserId)
                        @php
                            $selectedEmployee = $employees->where('id', $selectedUserId)->first();
                        @endphp

                        <!-- Chat Header -->
                        <div class="p-4 bg-gray-50 border-b">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center">
                                    <span class="text-dark font-medium">
                                        {{ substr($selectedEmployee->name, 0, 1) }}
                                    </span>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">{{ $selectedEmployee->name }}</h3>
                                    <p class="text-sm text-gray-500">{{ $selectedEmployee->email }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Messages Area -->
                        <div class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50 min-h-[300px] max-h-[400px]">

                            @forelse($messages as $message)
                                <div
                                    class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                                    <div class="max-w-xs lg:max-w-md">

                                        <!-- Message Bubble -->
                                        <div
                                            class="px-4 py-3 rounded-lg {{ $message->sender_id === auth()->id()
                                                ? 'bg-blue-500 text-dark'
                                                : 'bg-white text-gray-800 shadow-sm border border-gray-200' }}">

                                            <p class="text-sm leading-relaxed">{{ $message->message }}</p>
                                        </div>

                                        <!-- Message Info -->
                                        <div class="flex items-center justify-between mt-1 px-2">
                                            <span class="text-xs text-gray-500">
                                                {{ $message->sender_id === auth()->id() ? 'You' : $message->sender->name }}
                                            </span>
                                            <div class="flex items-center space-x-1">
                                                <span class="text-xs text-gray-500">
                                                    {{ $message->created_at->format('H:i') }}
                                                </span>

                                                @if ($message->sender_id === auth()->id())
                                                    @if ($message->is_read)
                                                        <span class="text-xs text-green-500">✓ Read</span>
                                                    @else
                                                        <span class="text-xs text-gray-400">✓ Sent</span>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-gray-500 py-12">
                                    <svg class="w-5 h-12 mx-auto mb-4 text-gray-300" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                    </svg>
                                    <p class="text-lg font-medium mb-2">No messages yet</p>
                                    <p>Start the conversation with {{ $selectedEmployee->name }}!</p>
                                </div>
                            @endforelse
                        </div>

                        <div class="p-4 border-t bg-white">
                            <div class="space-y-4">
                                {{ $this->form }}
                                <div class="flex items-center justify-between">
                                    <div class="flex space-x-2">
                                        <button wire:click="sendMessage" wire:loading.attr="disabled" type="button"
                                            class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-dark text-sm font-medium rounded-lg transition-colors duration-200 ">
                                            <span wire:loading.remove wire:target="sendMessage">
                                                Send Message
                                            </span>

                                            <span wire:loading wire:target="sendMessage" class="flex items-center">
                                                Sending...
                                            </span>
                                        </button>

                                        <!-- Refresh Button -->
                                        <button wire:click="loadMessages" type="button"
                                            class="inline-flex items-center px-3 py-2 bg-gray-600 hover:bg-gray-700 text-dark text-sm font-medium rounded-lg transition-colors duration-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                            </svg>
                                        </button>
                                    </div>

                                    <!-- Message Counter -->
                                    <div class="text-xs text-gray-500">
                                        <span>Messages: {{ $messages->count() }}</span>
                                    </div>
                                </div>


                            </div>
                        </div>
                    @else
                        <!-- No Employee Selected -->
                        <div class="flex-1 flex items-center justify-center">
                            <div class="text-center text-gray-500">
                                <svg class="w-6 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2z" />
                                </svg>
                                <h3 class="text-lg font-medium mb-2">Select an Employee</h3>
                                <p>Choose an employee from the sidebar to start chatting</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @else
            <!-- No Employees Available -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12">
                <div class="text-center text-gray-500">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                    </svg>
                    <h3 class="text-lg font-medium mb-2">No Employees Found</h3>
                    <p>There are no employees available to chat with at the moment.</p>
                    <p class="text-sm mt-2">Make sure employee users are created and have proper roles.</p>
                </div>
            </div>
        @endif
    </div>

    @push('scripts')
        @vite(['resources/js/app.js'])
        <script>
            // Auto-scroll to bottom of messages
            function scrollToBottom() {
                const messagesContainer = document.querySelector('[data-messages]');
                if (messagesContainer) {
                    messagesContainer.scrollTop = messagesContainer.scrollHeight;
                }
            }

            // Listen for new messages
            document.addEventListener('livewire:updated', function() {
                setTimeout(scrollToBottom, 100);
            });

            // Listen for form submission
            document.addEventListener('livewire:init', function() {
                Livewire.on('message-sent', function() {
                    scrollToBottom();
                    // Clear the textarea manually if needed
                    const textareas = document.querySelectorAll('textarea[wire\\:model*="messageText"]');
                    textareas.forEach(textarea => {
                        textarea.value = '';
                    });
                });
            });
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof window.Echo !== 'undefined') {
                    console.log('🎯 Setting up chat listeners for user {{ auth()->id() }}');

                    // Listen for new messages
                    window.Echo.private('chat.{{ auth()->id() }}')
                        .listen('message.sent', (e) => {
                            console.log('📨 New message received:', e);

                            // Refresh messages in Livewire
                            if (typeof Livewire !== 'undefined') {
                                Livewire.dispatch('message-received', e);
                            }

                            // Show notification
                            showNotification('New message from ' + e.sender.name);
                        })
                        .error((error) => {
                            console.error('❌ Echo channel error:', error);
                        });

                    // Show notification function
                    function showNotification(message) {
                        // Create notification element
                        const notification = document.createElement('div');
                        notification.className =
                            'fixed top-4 right-4 bg-blue-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
                        notification.textContent = message;

                        document.body.appendChild(notification);

                        // Remove after 3 seconds
                        setTimeout(() => {
                            notification.remove();
                        }, 3000);
                    }
                } else {
                    console.error('❌ Echo not loaded');
                }
            });
        </script>
        <script>
            function adminChatComponent() {
                return {
                    notification: {
                        show: false,
                        message: ''
                    },

                    initChat() {
                        console.log('🎯 Admin Chat initializing...');

                        setTimeout(() => {
                            this.setupEchoListeners();
                        }, 1000);

                        this.$wire.on('message-sent', () => {
                            this.showNotification('Message sent successfully!');
                            this.scrollToBottom();
                        });
                    },

                    setupEchoListeners() {
                        if (typeof window.Echo !== 'undefined') {
                            console.log('✅ Setting up Echo listeners for Admin {{ auth()->id() }}');

                            window.Echo.private('chat.{{ auth()->id() }}')
                                .listen('message.sent', (e) => {
                                    console.log('📨 New message received:', e);

                                    // Only show notification if message is from employee
                                    if (!e.sender.is_admin) {
                                        this.showNotification(`New message from ${e.sender.name}`);

                                        // Refresh messages
                                        this.$wire.call('refreshMessages');

                                        // Play sound (optional)
                                        this.playNotificationSound();
                                    }
                                })
                                .error((error) => {
                                    console.error('❌ Echo error:', error);
                                });

                            console.log('🎉 Echo listeners ready');
                        } else {
                            console.error('❌ Echo not available, retrying in 2 seconds...');
                            setTimeout(() => this.setupEchoListeners(), 2000);
                        }
                    },

                    showNotification(message) {
                        this.notification.message = message;
                        this.notification.show = true;

                        setTimeout(() => {
                            this.notification.show = false;
                        }, 4000);
                    },

                    playNotificationSound() {
                        // Simple notification sound
                        const audio = new Audio(
                            'data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+D2u2YdCTN+y+/eizEIHWq+8+GqWBYLTKXh8bllHgU7k9n'
                            );
                        audio.play().catch(() => {
                            // Ignore audio errors
                        });
                    },

                    scrollToBottom() {
                        setTimeout(() => {
                            const container = document.querySelector('[data-messages]');
                            if (container) {
                                container.scrollTop = container.scrollHeight;
                            }
                        }, 100);
                    }
                }
            }
        </script>
    @endpush
</x-filament-panels::page>
