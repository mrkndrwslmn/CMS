{{--
    Toast Container Component - Unified Notification System
    
    A global toast notification container for non-blocking feedback.
    Supports success, error, warning, info, and loading states.
    Include this once in your layout file.
    
    Usage in layout:
    <x-ui.toast-container />
    
    Trigger via JavaScript:
    window.toast.success('Your changes have been saved');
    window.toast.error('Something went wrong');
    window.toast.warning('Please check your input');
    window.toast.info('New update available');
    window.toast.loading('Processing...');
    
    With titles:
    window.toast.success('Your changes have been saved', 'Success');
    
    Advanced options:
    window.toast.show({
        type: 'success',
        title: 'Success!',
        message: 'Your action was completed.',
        duration: 5000, // ms, 0 for persistent
        action: { label: 'Undo', onClick: () => {} },
    });
    
    Dismiss:
    const id = window.toast.success('Saved!');
    window.toast.dismiss(id);
    window.toast.dismissAll();
--}}

<div
    x-data="toastManager()"
    x-on:show-toast.window="add($event.detail)"
    class="fixed bottom-4 left-4 z-[100] flex flex-col gap-3 max-w-sm w-full pointer-events-none"
    x-cloak
>
    <template x-for="toast in toasts" :key="toast.id">
        <div
            x-show="toast.visible"
            x-transition:enter="transform transition ease-out duration-300"
            x-transition:enter-start="translate-x-full opacity-0"
            x-transition:enter-end="translate-x-0 opacity-100"
            x-transition:leave="transform transition ease-in duration-200"
            x-transition:leave-start="translate-x-0 opacity-100"
            x-transition:leave-end="translate-x-full opacity-0"
            class="pointer-events-auto bg-white rounded-xl shadow-lg border border-neutral-100 overflow-hidden"
            role="alert"
            aria-live="assertive"
        >
            <!-- Progress bar for auto-dismiss -->
            <div 
                x-show="toast.duration > 0"
                class="h-1 bg-neutral-100"
            >
                <div 
                    class="h-full transition-all ease-linear"
                    :class="getProgressColor(toast.type)"
                    :style="`width: ${toast.progress}%; transition-duration: 100ms;`"
                ></div>
            </div>
            
            <div class="p-4 flex items-start gap-3">
                <!-- Icon -->
                <div class="flex-shrink-0">
                    <div 
                        class="w-8 h-8 rounded-full flex items-center justify-center"
                        :class="getIconBg(toast.type)"
                    >
                        <!-- Loading spinner -->
                        <template x-if="toast.type === 'loading'">
                            <svg class="animate-spin w-4 h-4 text-primary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </template>
                        <template x-if="toast.type === 'success'">
                            <x-lucide-check class="w-4 h-4 text-success-500" />
                        </template>
                        <template x-if="toast.type === 'error'">
                            <x-lucide-x class="w-4 h-4 text-error-500" />
                        </template>
                        <template x-if="toast.type === 'warning'">
                            <x-lucide-alert-triangle class="w-4 h-4 text-warning-500" />
                        </template>
                        <template x-if="toast.type === 'info'">
                            <x-lucide-info class="w-4 h-4 text-primary-500" />
                        </template>
                    </div>
                </div>
                
                <!-- Content -->
                <div class="flex-1 min-w-0">
                    <p 
                        x-show="toast.title"
                        x-text="toast.title"
                        class="text-sm font-semibold text-neutral-800"
                    ></p>
                    <p 
                        x-text="toast.message"
                        class="text-sm text-neutral-600"
                        :class="{ 'mt-0.5': toast.title }"
                    ></p>
                    
                    <!-- Action button -->
                    <button
                        x-show="toast.action"
                        @click="handleAction(toast)"
                        x-text="toast.action?.label"
                        class="mt-2 text-sm font-medium text-primary-600 hover:text-primary-700 transition-colors"
                    ></button>
                </div>
                
                <!-- Dismiss button -->
                <button
                    @click="dismiss(toast.id)"
                    class="flex-shrink-0 p-1 rounded-lg text-neutral-400 hover:text-neutral-600 hover:bg-neutral-100 transition-colors"
                >
                    <x-lucide-x class="w-4 h-4" />
                </button>
            </div>
        </div>
    </template>
</div>

<script>
    function toastManager() {
        return {
            toasts: [],
            counter: 0,
            
            add(config) {
                const id = ++this.counter;
                const toast = {
                    id,
                    type: config.type || 'info',
                    title: config.title || null,
                    message: config.message || '',
                    duration: config.duration ?? 5000,
                    action: config.action || null,
                    visible: false,
                    progress: 100,
                };
                
                this.toasts.push(toast);
                
                // Trigger enter animation
                setTimeout(() => {
                    const t = this.toasts.find(t => t.id === id);
                    if (t) t.visible = true;
                }, 10);
                
                // Auto-dismiss
                if (toast.duration > 0) {
                    this.startProgress(id, toast.duration);
                }
                
                return id;
            },
            
            startProgress(id, duration) {
                const interval = 100;
                const decrement = (interval / duration) * 100;
                
                const timer = setInterval(() => {
                    const toast = this.toasts.find(t => t.id === id);
                    if (!toast) {
                        clearInterval(timer);
                        return;
                    }
                    
                    toast.progress -= decrement;
                    
                    if (toast.progress <= 0) {
                        clearInterval(timer);
                        this.dismiss(id);
                    }
                }, interval);
            },
            
            dismiss(id) {
                const toast = this.toasts.find(t => t.id === id);
                if (toast) {
                    toast.visible = false;
                    setTimeout(() => {
                        this.toasts = this.toasts.filter(t => t.id !== id);
                    }, 200);
                }
            },
            
            dismissAll() {
                this.toasts.forEach(t => t.visible = false);
                setTimeout(() => {
                    this.toasts = [];
                }, 200);
            },
            
            handleAction(toast) {
                if (toast.action?.onClick) {
                    toast.action.onClick();
                }
                this.dismiss(toast.id);
            },
            
            getIconBg(type) {
                const colors = {
                    success: 'bg-success-50',
                    error: 'bg-error-50',
                    warning: 'bg-warning-50',
                    info: 'bg-primary-50',
                    loading: 'bg-primary-50',
                };
                return colors[type] || colors.info;
            },
            
            getProgressColor(type) {
                const colors = {
                    success: 'bg-success-500',
                    error: 'bg-error-500',
                    warning: 'bg-warning-500',
                    info: 'bg-primary-500',
                    loading: 'bg-primary-500',
                };
                return colors[type] || colors.info;
            }
        };
    }
    
    // Global toast API
    window.toast = {
        show(config) {
            window.dispatchEvent(new CustomEvent('show-toast', { detail: config }));
            return config.id;
        },
        
        success(message, title = null, options = {}) {
            return this.show({ type: 'success', message, title, ...options });
        },
        
        error(message, title = null, options = {}) {
            return this.show({ type: 'error', message, title, duration: 8000, ...options });
        },
        
        warning(message, title = null, options = {}) {
            return this.show({ type: 'warning', message, title, ...options });
        },
        
        info(message, title = null, options = {}) {
            return this.show({ type: 'info', message, title, ...options });
        },
        
        loading(message, title = null) {
            return this.show({ type: 'loading', message, title, duration: 0 });
        },
        
        dismiss(id) {
            // This will be handled by the Alpine component
            window.dispatchEvent(new CustomEvent('dismiss-toast', { detail: { id } }));
        },
        
        dismissAll() {
            window.dispatchEvent(new CustomEvent('dismiss-all-toasts'));
        },
        
        // Promise-based toast for async operations
        promise(promise, { loading: loadingMsg, success: successMsg, error: errorMsg }) {
            const id = this.loading(loadingMsg);
            
            promise
                .then((result) => {
                    this.dismiss(id);
                    const msg = typeof successMsg === 'function' ? successMsg(result) : successMsg;
                    this.success(msg);
                    return result;
                })
                .catch((err) => {
                    this.dismiss(id);
                    const msg = typeof errorMsg === 'function' ? errorMsg(err) : errorMsg;
                    this.error(msg);
                    throw err;
                });
                
            return promise;
        }
    };
</script>
