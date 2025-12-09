{{--
    Modal Container Component
    
    A global modal container that can be triggered programmatically.
    Include this once in your layout file.
    
    Usage in layout:
    <x-ui.modal-container />
    
    Trigger via JavaScript:
    window.showModal({
        type: 'success',
        title: 'Success!',
        message: 'Your action was completed.',
        confirmText: 'OK',
    });
    
    Prompt for text input:
    const result = await window.Alerts.prompt({
        title: 'Enter Template Name',
        message: 'Please provide a name for the template',
        placeholder: 'Template name...',
        confirmText: 'Save',
        cancelText: 'Cancel'
    });
    // result = { confirmed: true, value: 'User input' } or { confirmed: false }
    
    Trigger via Alpine.js:
    $dispatch('show-modal', {
        type: 'confirm',
        title: 'Delete Item?',
        message: 'This cannot be undone.',
        confirmText: 'Delete',
        cancelText: 'Cancel',
        confirmVariant: 'danger',
    });
    
    Listen for responses:
    @modal-confirmed.window="handleConfirm"
    @modal-cancelled.window="handleCancel"
--}}

<div
    x-data="{
        open: false,
        type: 'info',
        title: '',
        message: '',
        confirmText: 'OK',
        cancelText: null,
        confirmVariant: 'primary',
        onConfirm: null,
        onCancel: null,
        isPrompt: false,
        promptValue: '',
        promptPlaceholder: '',
        promptRequired: false,
        
        icons: {
            info: 'info',
            success: 'check-circle',
            warning: 'alert-triangle',
            error: 'x-circle',
            confirm: 'help-circle',
            prompt: 'edit-3',
        },
        
        iconBgColors: {
            info: 'bg-primary-50',
            success: 'bg-success-50',
            warning: 'bg-warning-50',
            error: 'bg-error-50',
            confirm: 'bg-primary-50',
            prompt: 'bg-primary-50',
        },
        
        iconTextColors: {
            info: 'text-primary-500',
            success: 'text-success-500',
            warning: 'text-warning-500',
            error: 'text-error-500',
            confirm: 'text-primary-500',
            prompt: 'text-primary-500',
        },
        
        show(config) {
            this.type = config.type || 'info';
            this.title = config.title || '';
            this.message = config.message || '';
            this.confirmText = config.confirmText || 'OK';
            this.cancelText = config.cancelText || null;
            this.confirmVariant = config.confirmVariant || 'primary';
            this.onConfirm = config.onConfirm || null;
            this.onCancel = config.onCancel || null;
            this.isPrompt = config.isPrompt || false;
            this.promptValue = config.defaultValue || '';
            this.promptPlaceholder = config.placeholder || '';
            this.promptRequired = config.required || false;
            this.open = true;
            
            // Focus input for prompts
            if (this.isPrompt) {
                this.$nextTick(() => {
                    if (this.$refs.promptInput) this.$refs.promptInput.focus();
                });
            }
        },
        
        confirm() {
            if (this.isPrompt && this.promptRequired && !this.promptValue.trim()) {
                return; // Don't allow empty required prompts
            }
            
            if (this.onConfirm && typeof this.onConfirm === 'function') {
                if (this.isPrompt) {
                    this.onConfirm(this.promptValue);
                } else {
                    this.onConfirm();
                }
            }
            this.$dispatch('global-modal-confirmed', { value: this.promptValue });
            this.close();
        },
        
        cancel() {
            if (this.onCancel && typeof this.onCancel === 'function') {
                this.onCancel();
            }
            this.$dispatch('global-modal-cancelled');
            this.close();
        },
        
        close() {
            this.open = false;
            this.onConfirm = null;
            this.onCancel = null;
            this.promptValue = '';
        },
        
        getIcon() {
            return this.icons[this.type] || this.icons.info;
        },
        
        getIconBg() {
            return this.iconBgColors[this.type] || this.iconBgColors.info;
        },
        
        getIconText() {
            return this.iconTextColors[this.type] || this.iconTextColors.info;
        }
    }"
    x-on:show-modal.window="show($event.detail)"
    x-on:keydown.escape.window="cancel()"
    x-cloak
>
    <!-- Backdrop -->
    <div
        x-show="open"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 bg-neutral-900/50 backdrop-blur-sm"
        @click="cancel()"
        style="display: none;"
    ></div>

    <!-- Modal -->
    <div
        x-show="open"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 pointer-events-none"
        style="display: none;"
    >
        <div
            x-show="open"
            x-transition:enter="ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            @click.stop
            class="relative bg-white rounded-2xl shadow-lg w-full max-w-md p-6 pointer-events-auto"
        >
            <!-- Icon -->
            <div class="flex justify-center mb-4">
                <div :class="'w-12 h-12 rounded-full flex items-center justify-center ' + getIconBg()">
                    <template x-if="type === 'info'">
                        <x-lucide-info class="w-6 h-6" ::class="getIconText()" />
                    </template>
                    <template x-if="type === 'success'">
                        <x-lucide-check-circle class="w-6 h-6" ::class="getIconText()" />
                    </template>
                    <template x-if="type === 'warning'">
                        <x-lucide-alert-triangle class="w-6 h-6" ::class="getIconText()" />
                    </template>
                    <template x-if="type === 'error'">
                        <x-lucide-x-circle class="w-6 h-6" ::class="getIconText()" />
                    </template>
                    <template x-if="type === 'confirm'">
                        <x-lucide-help-circle class="w-6 h-6" ::class="getIconText()" />
                    </template>
                    <template x-if="type === 'prompt'">
                        <x-lucide-edit-3 class="w-6 h-6" ::class="getIconText()" />
                    </template>
                </div>
            </div>

            <!-- Title -->
            <h3 
                x-show="title"
                x-text="title"
                class="text-lg font-semibold text-neutral-800 text-center mb-2"
            ></h3>

            <!-- Message -->
            <p 
                x-show="message"
                x-text="message"
                class="text-sm text-neutral-500 text-center mb-4"
            ></p>
            
            <!-- Prompt Input -->
            <div x-show="isPrompt" class="mb-6">
                <input
                    type="text"
                    x-ref="promptInput"
                    x-model="promptValue"
                    :placeholder="promptPlaceholder"
                    @keydown.enter="confirm()"
                    class="w-full px-4 py-2.5 text-sm border border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-colors"
                />
            </div>
            
            <!-- Spacer for non-prompt modals -->
            <div x-show="!isPrompt && message" class="mb-2"></div>

            <!-- Actions -->
            <div class="flex items-center justify-center gap-3">
                <button
                    x-show="cancelText"
                    x-text="cancelText"
                    @click="cancel()"
                    type="button"
                    class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-neutral-700 bg-white border border-neutral-200 rounded-lg hover:bg-neutral-50 transition-colors"
                ></button>

                <button
                    x-text="confirmText"
                    @click="confirm()"
                    type="button"
                    :class="confirmVariant === 'danger' 
                        ? 'bg-error-600 text-white hover:bg-error-700' 
                        : 'bg-primary-600 text-white hover:bg-primary-700'"
                    class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium rounded-lg transition-colors"
                ></button>
            </div>
        </div>
    </div>
</div>

<script>
    // Global function to show modal from vanilla JavaScript
    window.showModal = function(config) {
        window.dispatchEvent(new CustomEvent('show-modal', { detail: config }));
    };
    
    // Convenience methods
    window.showAlert = function(title, message, type = 'info') {
        window.showModal({ type, title, message, confirmText: 'OK' });
    };
    
    window.showSuccess = function(title, message) {
        window.showModal({ type: 'success', title, message, confirmText: 'Done' });
    };
    
    window.showError = function(title, message) {
        window.showModal({ type: 'error', title, message, confirmText: 'OK' });
    };
    
    window.showWarning = function(title, message) {
        window.showModal({ type: 'warning', title, message, confirmText: 'OK' });
    };
    
    window.showConfirm = function(title, message, onConfirm, onCancel = null) {
        window.showModal({
            type: 'confirm',
            title,
            message,
            confirmText: 'Confirm',
            cancelText: 'Cancel',
            onConfirm,
            onCancel
        });
    };
    
    window.showDeleteConfirm = function(title, message, onConfirm) {
        window.showModal({
            type: 'error',
            title,
            message,
            confirmText: 'Delete',
            cancelText: 'Cancel',
            confirmVariant: 'danger',
            onConfirm
        });
    };
    
    // Promise-based prompt function
    window.showPrompt = function(config = {}) {
        return new Promise((resolve) => {
            window.showModal({
                type: 'prompt',
                title: config.title || 'Enter Value',
                message: config.message || '',
                confirmText: config.confirmText || 'OK',
                cancelText: config.cancelText || 'Cancel',
                isPrompt: true,
                placeholder: config.placeholder || '',
                defaultValue: config.defaultValue || '',
                required: config.required || false,
                onConfirm: (value) => resolve({ confirmed: true, value }),
                onCancel: () => resolve({ confirmed: false, value: null })
            });
        });
    };
    
    // Add to window.Alerts namespace if it exists
    if (window.Alerts) {
        window.Alerts.prompt = window.showPrompt;
    }
</script>
