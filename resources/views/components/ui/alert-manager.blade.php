{{--
    Unified Alert System - Global Alert Manager
    
    This component provides a comprehensive, cohesive alert system that replaces:
    - Native JavaScript alert(), confirm()
    - Console.log/error/warn for user feedback
    - Inconsistent session-based flash messages
    - SweetAlert and similar libraries
    
    Include this ONCE in your main layout files (admin, client, adiutor layouts).
    
    USAGE GUIDE:
    ============
    
    1. TOAST NOTIFICATIONS (Non-blocking, auto-dismiss)
    --------------------------------------------------
    // Simple messages
    window.toast.success('Your changes have been saved');
    window.toast.error('Something went wrong');
    window.toast.warning('Please check your input');
    window.toast.info('New update available');
    
    // With title
    window.toast.success('Your changes have been saved', 'Success');
    
    // Loading state
    const loadingId = window.toast.loading('Processing...');
    // Later: window.toast.dismiss(loadingId);
    
    // Promise-based (shows loading, then success/error)
    window.toast.promise(
        fetch('/api/save'),
        {
            loading: 'Saving changes...',
            success: 'Changes saved!',
            error: 'Failed to save changes'
        }
    );
    
    2. MODAL DIALOGS (Blocking, requires user action)
    ------------------------------------------------
    // Information modal
    window.Alerts.info('Update Available', 'A new version is available.');
    
    // Success modal
    window.Alerts.success('Saved!', 'Your changes have been saved successfully.');
    
    // Error modal
    window.Alerts.error('Error', 'Something went wrong. Please try again.');
    
    // Warning modal
    window.Alerts.warning('Warning', 'This action cannot be undone.');
    
    // Confirmation dialog
    window.Alerts.confirm({
        title: 'Delete Item?',
        message: 'This action cannot be undone.',
        onConfirm: () => { /* delete logic */ },
        onCancel: () => { /* optional cancel logic */ }
    });
    
    // Delete confirmation (red styling)
    window.Alerts.confirmDelete({
        title: 'Delete Project?',
        message: 'All associated data will be permanently removed.',
        onConfirm: () => form.submit()
    });
    
    3. FORM CONFIRMATIONS (For form submissions)
    -------------------------------------------
    In your Blade template:
    <form onsubmit="return window.Alerts.confirmForm(event, 'Delete this item?', 'This cannot be undone.')">
    
    Or for delete forms:
    <form onsubmit="return window.Alerts.confirmDeleteForm(event, 'Delete this document?')">
    
    4. ASYNC OPERATIONS
    ------------------
    // Show loading, then auto-replace with result
    window.Alerts.async({
        action: () => fetch('/api/save').then(r => r.json()),
        loading: 'Saving changes...',
        success: (result) => `Saved ${result.count} items!`,
        error: (err) => err.message || 'Failed to save'
    });
    
    5. SESSION FLASH HANDLING (Automatic)
    ------------------------------------
    The component automatically shows toasts for session('success'), 
    session('error'), session('warning'), and session('info') messages.
    No need to add @if(session('success')) blocks in your views!
--}}

{{-- Include Toast Container --}}
<x-ui.toast-container />

{{-- Include Modal Container --}}
<x-ui.modal-container />

{{-- Global Alert Manager Script --}}
<script>
(function() {
    'use strict';
    
    // Unified Alerts API
    window.Alerts = {
        // Modal-based alerts
        info(title, message, confirmText = 'OK') {
            return window.showModal({ type: 'info', title, message, confirmText });
        },
        
        success(title, message, confirmText = 'Done') {
            return window.showModal({ type: 'success', title, message, confirmText });
        },
        
        error(title, message, confirmText = 'OK') {
            return window.showModal({ type: 'error', title, message, confirmText });
        },
        
        warning(title, message, confirmText = 'OK') {
            return window.showModal({ type: 'warning', title, message, confirmText });
        },
        
        // Confirmation dialogs - supports multiple calling conventions
        // Usage 1: Alerts.confirm({ title, message, onConfirm }) - object style
        // Usage 2: Alerts.confirm('Title', 'Message') - positional, returns Promise
        // Usage 3: Alerts.confirm('Title', 'Message', { onConfirm, confirmText }) - mixed style
        // Returns a Promise that resolves to true (confirmed) or false (cancelled)
        confirm(titleOrOptions, message, optionsOrType) {
            return new Promise((resolve) => {
                let config;
                
                // Determine calling convention
                if (typeof titleOrOptions === 'object' && titleOrOptions !== null) {
                    // Object-style: confirm({ title, message, onConfirm, ... })
                    const opts = titleOrOptions;
                    config = {
                        type: opts.type || 'confirm',
                        title: opts.title || 'Confirm',
                        message: opts.message || '',
                        confirmText: opts.confirmText || 'Confirm',
                        cancelText: opts.cancelText || 'Cancel',
                        confirmVariant: opts.confirmVariant || 'primary',
                        onConfirm: () => {
                            if (opts.onConfirm) opts.onConfirm();
                            resolve(true);
                        },
                        onCancel: () => {
                            if (opts.onCancel) opts.onCancel();
                            resolve(false);
                        }
                    };
                } else if (typeof optionsOrType === 'object' && optionsOrType !== null) {
                    // Mixed-style: confirm('Title', 'Message', { onConfirm, confirmText, ... })
                    const opts = optionsOrType;
                    config = {
                        type: opts.type || 'confirm',
                        title: titleOrOptions || 'Confirm',
                        message: message || '',
                        confirmText: opts.confirmText || 'Confirm',
                        cancelText: opts.cancelText || 'Cancel',
                        confirmVariant: opts.confirmVariant || 'primary',
                        onConfirm: () => {
                            if (opts.onConfirm) opts.onConfirm();
                            resolve(true);
                        },
                        onCancel: () => {
                            if (opts.onCancel) opts.onCancel();
                            resolve(false);
                        }
                    };
                } else {
                    // Positional-style: confirm('Title', 'Message', 'type')
                    config = {
                        type: (typeof optionsOrType === 'string' ? optionsOrType : null) || 'confirm',
                        title: titleOrOptions || 'Confirm',
                        message: message || '',
                        confirmText: 'Confirm',
                        cancelText: 'Cancel',
                        confirmVariant: 'primary',
                        onConfirm: () => resolve(true),
                        onCancel: () => resolve(false)
                    };
                }
                
                window.showModal(config);
            });
        },
        
        // Delete confirmation - supports multiple calling conventions, returns Promise
        confirmDelete(titleOrOptions, message, options) {
            return new Promise((resolve) => {
                let config;
                
                if (typeof titleOrOptions === 'object' && titleOrOptions !== null) {
                    const opts = titleOrOptions;
                    config = {
                        type: 'error',
                        title: opts.title || 'Confirm Delete',
                        message: opts.message || 'This action cannot be undone.',
                        confirmText: opts.confirmText || 'Delete',
                        cancelText: opts.cancelText || 'Cancel',
                        confirmVariant: 'danger',
                        onConfirm: () => {
                            if (opts.onConfirm) opts.onConfirm();
                            resolve(true);
                        },
                        onCancel: () => {
                            if (opts.onCancel) opts.onCancel();
                            resolve(false);
                        }
                    };
                } else if (typeof options === 'object' && options !== null) {
                    config = {
                        type: 'error',
                        title: titleOrOptions || 'Confirm Delete',
                        message: message || 'This action cannot be undone.',
                        confirmText: options.confirmText || 'Delete',
                        cancelText: options.cancelText || 'Cancel',
                        confirmVariant: 'danger',
                        onConfirm: () => {
                            if (options.onConfirm) options.onConfirm();
                            resolve(true);
                        },
                        onCancel: () => {
                            if (options.onCancel) options.onCancel();
                            resolve(false);
                        }
                    };
                } else {
                    config = {
                        type: 'error',
                        title: titleOrOptions || 'Confirm Delete',
                        message: message || 'This action cannot be undone.',
                        confirmText: 'Delete',
                        cancelText: 'Cancel',
                        confirmVariant: 'danger',
                        onConfirm: () => resolve(true),
                        onCancel: () => resolve(false)
                    };
                }
                
                window.showModal(config);
            });
        },
        
        // Form confirmation helpers
        confirmForm(event, title, message, options = {}) {
            event.preventDefault();
            // Get the form - handle both form submission and button click events
            const form = event.target.tagName === 'FORM' 
                ? event.target 
                : event.target.closest('form');
            
            if (!form) {
                console.error('confirmForm: Could not find form element');
                return false;
            }
            
            this.confirm({
                title: title || 'Confirm Action',
                message: message || 'Are you sure you want to continue?',
                confirmText: options.confirmText || 'Confirm',
                cancelText: options.cancelText || 'Cancel',
                onConfirm: () => {
                    // Add a flag to bypass confirmation on resubmit
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = '_confirmed';
                    input.value = '1';
                    form.appendChild(input);
                    // Use HTMLFormElement.prototype.submit to avoid conflicts with form elements named 'submit'
                    HTMLFormElement.prototype.submit.call(form);
                }
            });
            
            return false;
        },
        
        confirmDeleteForm(event, title, message) {
            event.preventDefault();
            // Get the form - handle both form submission and button click events
            const form = event.target.tagName === 'FORM' 
                ? event.target 
                : event.target.closest('form');
            
            if (!form) {
                console.error('confirmDeleteForm: Could not find form element');
                return false;
            }
            
            this.confirmDelete({
                title: title || 'Confirm Delete',
                message: message || 'This action cannot be undone.',
                onConfirm: () => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = '_confirmed';
                    input.value = '1';
                    form.appendChild(input);
                    // Use HTMLFormElement.prototype.submit to avoid conflicts with form elements named 'submit'
                    HTMLFormElement.prototype.submit.call(form);
                }
            });
            
            return false;
        },
        
        // Async operation with loading state
        async({ action, loading, success, error }) {
            const loadingId = window.toast.loading(loading || 'Processing...');
            
            try {
                const result = await (typeof action === 'function' ? action() : action);
                window.toast.dismiss(loadingId);
                
                const successMsg = typeof success === 'function' ? success(result) : success;
                window.toast.success(successMsg || 'Operation completed successfully');
                
                return result;
            } catch (err) {
                window.toast.dismiss(loadingId);
                
                const errorMsg = typeof error === 'function' ? error(err) : (error || err.message || 'An error occurred');
                window.toast.error(errorMsg);
                
                throw err;
            }
        },
        
        // Prompt dialog for text input
        // Returns: Promise<{ confirmed: boolean, value: string|null }>
        prompt(options = {}) {
            return window.showPrompt({
                title: options.title || 'Enter Value',
                message: options.message || '',
                placeholder: options.placeholder || '',
                defaultValue: options.defaultValue || '',
                confirmText: options.confirmText || 'OK',
                cancelText: options.cancelText || 'Cancel',
                required: options.required || false
            });
        }
    };
    
    // Convenience shortcuts (backwards compatibility with existing code)
    window.showInfo = (title, message) => window.Alerts.info(title, message);
    
    // Override native alert/confirm for consistency (optional, can be disabled)
    // Uncomment these lines if you want to fully replace native dialogs
    // window.nativeAlert = window.alert;
    // window.nativeConfirm = window.confirm;
    // window.alert = (message) => window.Alerts.info('Alert', message);
    // Note: We cannot truly replace confirm() as it's synchronous
    
})();
</script>

{{-- Session Flash Message Handler --}}
@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        window.toast.success(@json(session('success')));
    });
</script>
@endif

@if(session('error'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        window.toast.error(@json(session('error')));
    });
</script>
@endif

@if(session('warning'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        window.toast.warning(@json(session('warning')));
    });
</script>
@endif

@if(session('info'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        window.toast.info(@json(session('info')));
    });
</script>
@endif

@if($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @foreach($errors->all() as $error)
            window.toast.error(@json($error));
        @endforeach
    });
</script>
@endif
