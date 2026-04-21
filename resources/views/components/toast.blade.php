<div x-data="{
        notifications: [],
        add(e) {
            const id = Date.now();
            this.notifications.push({
                id: id,
                type: e.detail.type || 'info',
                content: e.detail.content,
                show: false
            });
            this.$nextTick(() => {
                this.notifications.find(n => n.id === id).show = true;
            });
            setTimeout(() => {
                this.remove(id);
            }, 5000);
        },
        remove(id) {
            const n = this.notifications.find(n => n.id === id);
            if (n) {
                n.show = false;
                setTimeout(() => {
                    this.notifications = this.notifications.filter(v => v.id !== id);
                }, 500);
            }
        }
    }"
    @notify.window="add($event)"
    class="fixed top-6 right-6 z-[200] flex flex-col space-y-4 w-full max-w-sm pointer-events-none"
    x-cloak>
    
    <template x-for="notification in notifications" :key="notification.id">
        <div x-show="notification.show"
            x-transition:enter="transition ease-out duration-500"
            x-transition:enter-start="translate-x-full opacity-0"
            x-transition:enter-end="translate-x-0 opacity-100"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="translate-x-0 opacity-100"
            x-transition:leave-end="translate-x-full opacity-0"
            class="pointer-events-auto relative overflow-hidden group">
            
            <div class="glass-card p-5 rounded-3xl border-white/10 flex items-start space-x-4 shadow-[0_20px_50px_rgba(0,0,0,0.3)] bg-gray-950/40 backdrop-blur-2xl">
                <!-- Icon based on type -->
                <div class="flex-shrink-0 w-10 h-10 rounded-2xl flex items-center justify-center"
                    :class="{
                        'bg-green-500/20 text-green-500': notification.type === 'success',
                        'bg-red-500/20 text-red-500': notification.type === 'error',
                        'bg-brand-500/20 text-brand-500': notification.type === 'info',
                        'bg-yellow-500/20 text-yellow-500': notification.type === 'warning'
                    }">
                    <template x-if="notification.type === 'success'">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </template>
                    <template x-if="notification.type === 'error'">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </template>
                    <template x-if="notification.type === 'info'">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </template>
                    <template x-if="notification.type === 'warning'">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </template>
                </div>
                
                <div class="flex-1 pt-1">
                    <p class="text-xs font-black uppercase tracking-[0.2em] mb-1"
                        :class="{
                            'text-green-500': notification.type === 'success',
                            'text-red-500': notification.type === 'error',
                            'text-brand-500': notification.type === 'info',
                            'text-yellow-500': notification.type === 'warning'
                        }" x-text="notification.type === 'info' ? 'Update' : notification.type"></p>
                    <p class="text-sm text-gray-300 font-bold leading-relaxed" x-text="notification.content"></p>
                </div>

                <button @click="remove(notification.id)" class="text-gray-600 hover:text-white transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <!-- Progress Bar -->
            <div class="absolute bottom-0 left-0 h-1 bg-white/10" style="width: 100%"
                x-init="setTimeout(() => $el.style.width = '0%', 50)"
                :style="'transition: width 5000ms linear; background-color: ' + (
                    notification.type === 'success' ? '#10b981' : 
                    notification.type === 'error' ? '#ef4444' : 
                    notification.type === 'info' ? '#0ea5e9' : '#f59e0b'
                )"></div>
        </div>
    </template>
</div>
