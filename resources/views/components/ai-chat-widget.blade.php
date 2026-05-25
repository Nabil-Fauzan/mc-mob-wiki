<div x-data="aiChatWidget()" class="fixed bottom-40 md:bottom-6 right-4 z-[90]">
    <!-- Floating Button -->
    <button @click="toggleChat()" 
            class="relative w-14 h-14 bg-brand-600 rounded-full shadow-[0_0_20px_rgba(14,165,233,0.4)] flex items-center justify-center hover:scale-110 transition-transform duration-300 group z-50 border border-brand-400/30">
        
        <!-- Pulse effect -->
        <div class="absolute inset-0 rounded-full bg-brand-500 opacity-20 animate-ping" x-show="!isOpen"></div>

        <svg x-show="!isOpen" class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
        
        <svg x-cloak x-show="isOpen" class="w-6 h-6 text-white transform rotate-90 group-hover:rotate-180 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        
        <!-- Notification badge -->
        <div x-show="hasUnread" class="absolute -top-1 -right-1 w-3.5 h-3.5 bg-red-500 rounded-full border-2 border-[#020617]"></div>
    </button>

    <!-- Chat Modal -->
    <div x-cloak 
         x-show="isOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 scale-95"
         @click.away="isOpen = false"
         class="absolute bottom-20 right-0 w-[350px] sm:w-[400px] h-[500px] max-h-[80vh] glass-card rounded-[2rem] border-brand-500/20 shadow-2xl flex flex-col overflow-hidden">
        
        <!-- Header -->
        <div class="px-5 py-4 border-b border-white/5 flex justify-between items-center bg-gradient-to-r from-brand-900/40 to-transparent">
            <div class="flex items-center space-x-3">
                <div class="w-2 h-2 rounded-full bg-brand-400 animate-pulse"></div>
                <div>
                    <h3 class="text-sm font-black text-white uppercase tracking-widest">Oracle AI</h3>
                    <p class="text-[9px] text-brand-300/80 font-bold uppercase tracking-widest">Aether Protocol v2</p>
                </div>
            </div>
            <button @click="clearHistory()" class="text-[10px] text-gray-500 hover:text-red-400 font-bold uppercase tracking-widest transition-colors" title="Clear Chat History">Clear</button>
        </div>

        <!-- Chat Area -->
        <div id="ai-chat-scroll" class="flex-1 overflow-y-auto p-5 space-y-4 custom-scrollbar">
            <template x-for="(msg, index) in messages" :key="index">
                <div class="flex flex-col" :class="msg.role === 'user' ? 'items-end' : 'items-start'">
                    <!-- Message Bubble -->
                    <div class="max-w-[85%] rounded-2xl px-4 py-3"
                         :class="msg.role === 'user' ? 'bg-brand-600 text-white rounded-br-sm' : 'bg-white/5 border border-white/5 text-gray-200 rounded-bl-sm shadow-inner'">
                         <p class="text-sm leading-relaxed" x-text="msg.content"></p>
                    </div>
                    <!-- Role Indicator -->
                    <span class="text-[8px] font-black uppercase tracking-widest text-gray-500 mt-1" x-text="msg.role === 'user' ? 'You' : 'Oracle'"></span>
                </div>
            </template>

            <!-- Typing Indicator -->
            <div x-show="isTyping" class="flex items-start">
                <div class="bg-white/5 border border-white/5 rounded-2xl rounded-bl-sm px-4 py-3 flex space-x-1 items-center h-10">
                    <div class="w-1.5 h-1.5 bg-brand-400 rounded-full animate-bounce"></div>
                    <div class="w-1.5 h-1.5 bg-brand-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                    <div class="w-1.5 h-1.5 bg-brand-400 rounded-full animate-bounce" style="animation-delay: 0.4s"></div>
                </div>
            </div>
            
            <!-- Empty state -->
            <div x-show="messages.length === 0" class="h-full flex flex-col items-center justify-center text-center opacity-50">
                <svg class="w-12 h-12 text-brand-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Awaiting Input</p>
                <p class="text-[10px] text-gray-500 mt-2">Ask about entities, biomes, or dimensions.</p>
            </div>
        </div>

        <!-- Input Area -->
        <div class="p-3 border-t border-white/5 bg-black/20">
            <form @submit.prevent="sendMessage()" class="flex items-center space-x-2">
                <input type="text" 
                       x-model="inputMsg" 
                       x-ref="aiInput"
                       :disabled="isTyping"
                       placeholder="Ask Oracle..." 
                       class="flex-1 bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-white placeholder-gray-500 focus:ring-brand-500 focus:border-brand-500 disabled:opacity-50">
                <button type="submit" 
                        :disabled="!inputMsg.trim() || isTyping"
                        class="p-3 bg-brand-500 hover:bg-brand-400 text-white rounded-xl transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                </button>
            </form>
        </div>
    </div>
</div>

<style>
    /* Custom scrollbar for chat */
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.2); }
</style>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('aiChatWidget', () => ({
            isOpen: false,
            hasUnread: false,
            inputMsg: '',
            isTyping: false,
            messages: [],
            init() {
                // Load history
                const saved = localStorage.getItem('ai_chat_history');
                if (saved) {
                    this.messages = JSON.parse(saved);
                } else {
                    // Initial greeting
                    this.messages = [
                        { role: 'oracle', content: 'Greetings, Researcher. I am the Aether Oracle Assistant. How may I aid your exploration today?' }
                    ];
                    this.saveHistory();
                }
            },
            toggleChat() {
                this.isOpen = !this.isOpen;
                if (this.isOpen) {
                    this.hasUnread = false;
                    this.$nextTick(() => {
                        this.$refs.aiInput.focus();
                        this.scrollToBottom();
                    });
                }
            },
            clearHistory() {
                this.messages = [
                    { role: 'oracle', content: 'Memory purged. Awaiting new directives.' }
                ];
                this.saveHistory();
            },
            saveHistory() {
                // Keep only last 50 messages to prevent localstorage bloat
                if(this.messages.length > 50) {
                    this.messages = this.messages.slice(this.messages.length - 50);
                }
                localStorage.setItem('ai_chat_history', JSON.stringify(this.messages));
            },
            scrollToBottom() {
                const el = document.getElementById('ai-chat-scroll');
                if (el) {
                    el.scrollTop = el.scrollHeight;
                }
            },
            async sendMessage() {
                if (!this.inputMsg.trim() || this.isTyping) return;
                
                const userText = this.inputMsg.trim();
                this.inputMsg = '';
                
                this.messages.push({ role: 'user', content: userText });
                this.saveHistory();
                this.scrollToBottom();
                
                this.isTyping = true;
                this.scrollToBottom();

                // Placeholder for Oracle response
                const oracleMessageIndex = this.messages.length;
                this.messages.push({ role: 'oracle', content: '' });

                try {
                    const response = await fetch('/api/oracle', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ query: userText, stream: true })
                    });
                    
                    if (!response.ok) throw new Error('Network error');
                    
                    const reader = response.body.getReader();
                    const decoder = new TextDecoder("utf-8");
                    let done = false;

                    while (!done) {
                        const { value, done: readerDone } = await reader.read();
                        done = readerDone;
                        if (value) {
                            const chunkString = decoder.decode(value, { stream: true });
                            const lines = chunkString.split("\n");
                            
                            for (let line of lines) {
                                if (line.startsWith('data: ')) {
                                    const dataStr = line.substring(6).trim();
                                    if (dataStr === '[DONE]') {
                                        done = true;
                                        break;
                                    }
                                    try {
                                        const parsed = JSON.parse(dataStr);
                                        if (parsed.chunk) {
                                            this.messages[oracleMessageIndex].content += parsed.chunk;
                                            this.scrollToBottom();
                                        }
                                    } catch (e) {
                                        // Ignore incomplete JSON chunks, handled natively by streaming
                                    }
                                }
                            }
                        }
                    }
                } catch (error) {
                    console.error('Oracle Error:', error);
                    this.messages[oracleMessageIndex].content = '[SIGNAL INTERRUPTED] Unable to connect to the Aether Network.';
                } finally {
                    this.isTyping = false;
                    this.saveHistory();
                    this.$nextTick(() => {
                        this.scrollToBottom();
                        this.$refs.aiInput.focus();
                    });
                }
            }
        }));
    });
</script>
