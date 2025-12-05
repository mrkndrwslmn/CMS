

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
        
        icons: {
            info: 'info',
            success: 'check-circle',
            warning: 'alert-triangle',
            error: 'x-circle',
            confirm: 'help-circle',
        },
        
        iconBgColors: {
            info: 'bg-primary-50',
            success: 'bg-success-50',
            warning: 'bg-warning-50',
            error: 'bg-error-50',
            confirm: 'bg-primary-50',
        },
        
        iconTextColors: {
            info: 'text-primary-500',
            success: 'text-success-500',
            warning: 'text-warning-500',
            error: 'text-error-500',
            confirm: 'text-primary-500',
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
            this.open = true;
        },
        
        confirm() {
            if (this.onConfirm && typeof this.onConfirm === 'function') {
                this.onConfirm();
            }
            this.$dispatch('global-modal-confirmed');
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
    ></div>

    <!-- Modal -->
    <div
        x-show="open"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 pointer-events-none"
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
                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-info'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-6 h-6',':class' => 'getIconText()']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $attributes = $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $component = $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
                    </template>
                    <template x-if="type === 'success'">
                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-check-circle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-6 h-6',':class' => 'getIconText()']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $attributes = $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $component = $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
                    </template>
                    <template x-if="type === 'warning'">
                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-alert-triangle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-6 h-6',':class' => 'getIconText()']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $attributes = $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $component = $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
                    </template>
                    <template x-if="type === 'error'">
                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-x-circle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-6 h-6',':class' => 'getIconText()']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $attributes = $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $component = $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
                    </template>
                    <template x-if="type === 'confirm'">
                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-help-circle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-6 h-6',':class' => 'getIconText()']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $attributes = $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $component = $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
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
                class="text-sm text-neutral-500 text-center mb-6"
            ></p>

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
</script>
<?php /**PATH C:\Users\marka\Projects\cms\resources\views/components/ui/modal-container.blade.php ENDPATH**/ ?>