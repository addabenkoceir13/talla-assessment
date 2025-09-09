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
                        d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-4l-4 4v-4z" />
                </svg>
                <h3 class="text-lg font-semibold text-blue-900">Employee Chat</h3>
            </div>
            <p class="text-sm text-blue-700 mt-1">
                Chatting with:
                @if ($selectedAdminId && ($getSelectedAdmin = $admins->where('id', $selectedAdminId)->first()))
                    <strong>{{ $getSelectedAdmin->name }}</strong>
                @else
                    <em>No admin selected</em>
                @endif
            </p>
        </div>

        <!-- Chat Container -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 min-h-[600px] flex flex-col">

            @if ($selectedAdminId)
                @php
                    $selectedAdmin = $admins->where('id', $selectedAdminId)->first();
                @endphp

                <div class="p-4 bg-blue-50 border-b">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center">
                                <span class="text-dark font-medium text-lg">
                                    {{ substr($selectedAdmin->name, 0, 1) }}
                                </span>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ $selectedAdmin->name }}</h3>
                                <p class="text-sm text-gray-600">Administrator</p>
                            </div>
                        </div>

                        <!-- Admin Selection (if multiple) -->
                        @if ($admins->count() > 1)
                            <div class="w-48">
                                {{ $this->form->getComponent('selectedAdminId') }}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Messages Area -->
                <div class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50 min-h-[300px] max-h-[400px]" data-messages>

                    @forelse($messages as $message)
                        <div class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                            <div class="max-w-xs lg:max-w-md">

                                <!-- Message Bubble -->
                                <div
                                    class="px-4 py-3 rounded-2xl {{ $message->sender_id === auth()->id()
                                        ? 'bg-blue-500 text-dark ml-auto'
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
                                            <!-- Read Status for Employee Messages -->
                                            @if ($message->is_read)
                                                <span class="text-xs text-green-500">✓ Read</span>
                                            @else
                                                <span class="text-xs text-gray-400">✓ Sent</span>
                                            @endif

                                            <!-- Escalation Indicator -->
                                            @if ($message->escalated)
                                                <span
                                                    class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    ⚠️ Escalated
                                                </span>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-gray-500 py-12">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-4l-4 4v-4z" />
                            </svg>
                            <h3 class="text-lg font-medium mb-2 text-gray-700">Start a Conversation</h3>
                            <p class="text-gray-500">Send your first message to {{ $selectedAdmin->name }}</p>
                        </div>
                    @endforelse
                </div>


                <div class="p-4 border-t bg-white">
                    <div class="space-y-4">
                        <!-- Form Fields -->
                        {{ $this->form }}
                        <div class="flex items-center justify-between">
                            <div class="flex space-x-2">
                                <button wire:click="sendMessage" wire:loading.attr="disabled" type="button"
                                    class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-dark text-sm font-medium rounded-lg transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed">

                                    <span wire:loading.remove wire:target="sendMessage">
                                        Send to Admin
                                    </span>

                                    <span wire:loading wire:target="sendMessage" class="flex items-center">
                                        Sending...
                                    </span>
                                </button>

                                <!-- Refresh Button -->
                                <button wire:click="loadMessages" type="button"
                                    class="inline-flex items-center px-3 py-2 bg-gray-600 hover:bg-gray-700 text-dark text-sm font-medium rounded-lg transition-colors duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                </button>
                            </div>

                            <div class="text-xs text-gray-500">
                                <span>Messages: {{ $messages->count() }}</span>
                            </div>
                        </div>

                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 text-sm">
                            <div class="flex items-start">
                                <svg class="w-4 h-4 text-yellow-500 mt-0.5 mr-2 flex-shrink-0" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                <p class="text-yellow-700">
                                    <strong>Note:</strong> If the admin doesn't respond within 10 minutes, a
                                    notification will be sent automatically.
                                </p>
                            </div>
                        </div>

                    </div>
                </div>
            @else
                <!-- No Admin Available -->
                <div class="flex-1 flex items-center justify-center">
                    <div class="text-center text-gray-500">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                        </svg>
                        <h3 class="text-lg font-medium mb-2">No Administrators Available</h3>
                        <p>Please contact your system administrator for assistance.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        @vite(['resources/js/app.js'])
        <script>
            function scrollToBottom() {
                const messagesContainer = document.querySelector('[data-messages]');
                if (messagesContainer) {
                    messagesContainer.scrollTop = messagesContainer.scrollHeight;
                }
            }

            document.addEventListener('livewire:updated', function() {
                setTimeout(scrollToBottom, 100);
            });

            document.addEventListener('livewire:init', function() {
                Livewire.on('message-sent', function() {
                    scrollToBottom();
                    // Clear textarea manually if needed
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
        function employeeChatComponent() {
            return {
                notification: {
                    show: false,
                    message: '',
                    type: 'info'
                },
                
                initChat() {
                    console.log('🎯 Employee Chat initializing...');
                    
                    setTimeout(() => {
                        this.setupEchoListeners();
                    }, 1000);
                    
                    this.$wire.on('message-sent', () => {
                        this.showNotification('Message sent to admin!', 'success');
                        this.scrollToBottom();
                    });
                },
                
                setupEchoListeners() {
                    if (typeof window.Echo !== 'undefined') {
                        console.log('✅ Setting up Echo listeners for Employee {{ auth()->id() }}');
                        
                        window.Echo.private('chat.{{ auth()->id() }}')
                            .listen('message.sent', (e) => {
                                console.log('📨 New message received:', e);
                                
                                // Only show notification if message is from admin
                                if (e.sender.is_admin) {
                                    this.showNotification(`New message from Admin: ${e.sender.name}`, 'info');
                                    
                                    // Refresh messages
                                    this.$wire.call('refreshMessages');
                                    
                                    this.playNotificationSound();
                                }
                            });
                            
                        console.log('🎉 Echo listeners ready');
                    } else {
                        console.error('❌ Echo not available, retrying...');
                        setTimeout(() => this.setupEchoListeners(), 2000);
                    }
                },
                
                showNotification(message, type = 'info') {
                    this.notification.message = message;
                    this.notification.type = type;
                    this.notification.show = true;
                    
                    setTimeout(() => {
                        this.notification.show = false;
                    }, 5000);
                },
                
                playNotificationSound() {
                    const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+D2u2YdCTN+y+/eizEIHWq+8+GqWBYLTKXh8bllHgU7k9n');
                    audio.play().catch(() => {});
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
