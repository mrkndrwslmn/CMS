<?php $__env->startSection('title', 'Request Details'); ?>
<?php $__env->startSection('page-title', 'Request Details'); ?>

<?php $__env->startSection('content'); ?>
<div class="px-6 py-8" x-data="{
    showApproveModal: false,
    showRejectModal: false,
    showCreateTaskModal: false,
    
    openApproveModal() {
        this.showApproveModal = true;
    },
    
    openRejectModal() {
        this.showRejectModal = true;
    },
    
    openCreateTaskModal() {
        this.showCreateTaskModal = true;
    }
}">
    <!-- Alert Messages -->
    <?php if(session('success')): ?>
        <div class="bg-success-50 border-l-4 border-success-500 text-success-700 p-6 rounded-2xl shadow-sm mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-check-circle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-6 h-6 text-success-500']); ?>
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
                </div>
                <div class="ml-3">
                    <p class="text-success-700"><?php echo e(session('success')); ?></p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="bg-error-50 border-l-4 border-error-500 text-error-700 p-6 rounded-2xl shadow-sm mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-alert-circle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-6 h-6 text-error-500']); ?>
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
                </div>
                <div class="ml-3">
                    <p class="text-error-700"><?php echo e(session('error')); ?></p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
        <div>
            <div class="flex items-center mb-2">
                <?php
                    $requestStatus = $request['status'] ?? 'pending';
                    $statusBadgeClasses = [
                        'pending' => 'bg-warning-100 text-warning-800',
                        'approved' => 'bg-success-100 text-success-800',
                        'rejected' => 'bg-error-100 text-error-800',
                        'pending_payment' => 'bg-warning-100 text-warning-800',
                        'paid' => 'bg-success-100 text-success-800',
                    ];
                    $statusBadgeClass = $statusBadgeClasses[$requestStatus] ?? 'bg-neutral-100 text-neutral-800';
                ?>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium <?php echo e($statusBadgeClass); ?> mr-3">
                    <?php if($requestStatus === 'pending'): ?>
                        <span class="h-2 w-2 rounded-full bg-warning-500 mr-1.5"></span>Pending
                    <?php elseif($requestStatus === 'approved'): ?>
                        <span class="h-2 w-2 rounded-full bg-success-500 mr-1.5"></span>Approved
                    <?php elseif($requestStatus === 'rejected'): ?>
                        <span class="h-2 w-2 rounded-full bg-error-500 mr-1.5"></span>Rejected
                    <?php elseif($requestStatus === 'pending_payment'): ?>
                        <span class="h-2 w-2 rounded-full bg-warning-500 mr-1.5"></span>Pending Payment
                    <?php elseif($requestStatus === 'paid'): ?>
                        <span class="h-2 w-2 rounded-full bg-success-500 mr-1.5"></span>Paid
                    <?php else: ?>
                        <span class="h-2 w-2 rounded-full bg-neutral-500 mr-1.5"></span><?php echo e(ucfirst($requestStatus)); ?>

                    <?php endif; ?>
                </span>
                
                <?php
                    $requestPriority = $request['priority'] ?? 'low';
                    $priorityBadgeClasses = [
                        'low' => 'bg-info-100 text-info-800',
                        'medium' => 'bg-warning-100 text-warning-800',
                        'high' => 'bg-orange-100 text-orange-800',
                        'urgent' => 'bg-error-100 text-error-800'
                    ];
                    $priorityBadgeClass = $priorityBadgeClasses[$requestPriority] ?? 'bg-neutral-100 text-neutral-800';
                    
                    $priorityIcons = [
                        'low' => 'arrow-down',
                        'medium' => 'minus',
                        'high' => 'arrow-up',
                        'urgent' => 'alert-triangle'
                    ];
                    $priorityIcon = $priorityIcons[$requestPriority] ?? 'circle';
                ?>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium <?php echo e($priorityBadgeClass); ?>">
                    <?php if (isset($component)) { $__componentOriginal511d4862ff04963c3c16115c05a86a9d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal511d4862ff04963c3c16115c05a86a9d = $attributes; } ?>
<?php $component = Illuminate\View\DynamicComponent::resolve(['component' => 'lucide-' . $priorityIcon] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dynamic-component'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\DynamicComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-3.5 h-3.5 mr-1.5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal511d4862ff04963c3c16115c05a86a9d)): ?>
<?php $attributes = $__attributesOriginal511d4862ff04963c3c16115c05a86a9d; ?>
<?php unset($__attributesOriginal511d4862ff04963c3c16115c05a86a9d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal511d4862ff04963c3c16115c05a86a9d)): ?>
<?php $component = $__componentOriginal511d4862ff04963c3c16115c05a86a9d; ?>
<?php unset($__componentOriginal511d4862ff04963c3c16115c05a86a9d); ?>
<?php endif; ?>
                    <?php echo e(ucfirst($requestPriority)); ?> Priority
                </span>
            </div>
            
            <h1 class="text-2xl font-semibold text-primary-600 mb-1">
                <?php echo e($request['title'] ?? 'Request #' . $request['id']); ?>

            </h1>
            
            <div class="text-neutral-500 flex items-center">
                <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-calendar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-4 h-4 mr-2']); ?>
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
                <span>Submitted <?php echo e(isset($request['submissionDate']) ? date('F d, Y', strtotime($request['submissionDate'])) : 
                (isset($request['submission_date']) ? date('F d, Y', strtotime($request['submission_date'])) : 
                date('F d, Y', strtotime($request['created_at'])))); ?></span>
            </div>
        </div>
        
        <div class="mt-4 sm:mt-0 flex flex-wrap gap-3">
            <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['href' => ''.e(route('admin.requests.index')).'','variant' => 'secondary','class' => 'inline-flex items-center gap-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('admin.requests.index')).'','variant' => 'secondary','class' => 'inline-flex items-center gap-2']); ?>
                <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-arrow-left'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-4 h-4']); ?>
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
                Back to Requests
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
            
            <?php if($requestStatus === 'pending'): ?>
                <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['type' => 'button','@click' => 'openApproveModal()','variant' => 'success','class' => 'inline-flex items-center gap-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','@click' => 'openApproveModal()','variant' => 'success','class' => 'inline-flex items-center gap-2']); ?>
                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-check'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-4 h-4']); ?>
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
                    Approve
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['type' => 'button','@click' => 'openRejectModal()','variant' => 'danger','class' => 'inline-flex items-center gap-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','@click' => 'openRejectModal()','variant' => 'danger','class' => 'inline-flex items-center gap-2']); ?>
                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-x'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-4 h-4']); ?>
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
                    Reject
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
            <?php elseif($requestStatus === 'approved' || $requestStatus === 'pending_payment'): ?>
                <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['href' => ''.e(route('client.maya.checkout', $request['id'])).'','target' => '_blank','variant' => 'primary','class' => 'inline-flex items-center gap-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('client.maya.checkout', $request['id'])).'','target' => '_blank','variant' => 'primary','class' => 'inline-flex items-center gap-2']); ?>
                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-credit-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-4 h-4']); ?>
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
                    View Payment Link
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
            <?php elseif($requestStatus === 'rejected'): ?>
                <form action="<?php echo e(route('admin.requests.reopen', $request['id'])); ?>" method="POST" class="inline">
                    <?php echo csrf_field(); ?>
                    <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['type' => 'submit','variant' => 'warning','class' => 'inline-flex items-center gap-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit','variant' => 'warning','class' => 'inline-flex items-center gap-2']); ?>
                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-rotate-ccw'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-4 h-4']); ?>
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
                        Reopen
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <!-- Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Request Details -->
        <div class="lg:col-span-2">
            <!-- Request Details Card -->
            <?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => ['class' => 'mb-6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mb-6']); ?>
                <div class="px-6 py-4 border-b border-neutral-200">
                    <h2 class="text-lg font-semibold text-primary-500">Request Details</h2>
                </div>
                <div class="p-6">
                    <?php if(!empty($request['projectDescription']) || !empty($request['project_description'])): ?>
                        <div class="mb-6">
                            <h3 class="text-sm font-medium text-neutral-500 uppercase tracking-wider mb-2">Project Description</h3>
                            <div class="prose max-w-none text-neutral-800">
                                <?php echo nl2br(e($request['projectDescription'] ?? $request['project_description'])); ?>

                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if(!empty($request['specialRequests']) || !empty($request['special_requests'])): ?>
                        <div class="mb-6">
                            <h3 class="text-sm font-medium text-neutral-500 uppercase tracking-wider mb-2">Special Requests</h3>
                            <div class="prose max-w-none text-neutral-800">
                                <?php echo nl2br(e($request['specialRequests'] ?? $request['special_requests'])); ?>

                            </div>
                        </div>
                    <?php endif; ?>
                    
                    
                    <?php if(!empty($request['template_service_id']) || !empty($request['requested_features']) || !empty($request['requested_skills'])): ?>
                        <div class="mb-6 p-4 bg-secondary-50 rounded-xl border border-secondary-200">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-sm font-medium text-secondary-700 uppercase tracking-wider flex items-center">
                                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-layers'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-4 h-4 mr-2']); ?>
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
                                    Service Requirements
                                </h3>
                                <?php if(!empty($request['has_customizations']) && $request['has_customizations']): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-edit-3'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-3 h-3 mr-1']); ?>
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
                                        Client Customized
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-neutral-100 text-neutral-600">
                                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-copy'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-3 h-3 mr-1']); ?>
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
                                        Template Default
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                
                                <?php
                                    $requestedFeatures = $request['requested_features'] ?? [];
                                    $templateFeatures = $request['template_features'] ?? [];
                                    $displayFeatures = !empty($requestedFeatures) ? $requestedFeatures : $templateFeatures;
                                    if (is_string($displayFeatures)) {
                                        $displayFeatures = json_decode($displayFeatures, true) ?? [];
                                    }
                                ?>
                                <?php if(!empty($displayFeatures)): ?>
                                    <div class="bg-white p-3 rounded-lg border border-secondary-100">
                                        <h4 class="text-xs font-semibold text-neutral-600 uppercase mb-2 flex items-center">
                                            <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-check-circle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-3 h-3 mr-1 text-success-500']); ?>
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
                                            What's Included
                                        </h4>
                                        <div class="flex flex-wrap gap-1.5">
                                            <?php $__currentLoopData = $displayFeatures; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-success-50 text-success-700 border border-success-200">
                                                    <?php echo e($feature); ?>

                                                </span>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                        <?php if(!empty($request['has_customizations']) && !empty($templateFeatures) && $displayFeatures !== $templateFeatures): ?>
                                            <div class="mt-2 pt-2 border-t border-neutral-100">
                                                <p class="text-xs text-neutral-400 mb-1">Original template:</p>
                                                <div class="flex flex-wrap gap-1">
                                                    <?php
                                                        $templateFeaturesArr = is_string($templateFeatures) ? json_decode($templateFeatures, true) : $templateFeatures;
                                                    ?>
                                                    <?php $__currentLoopData = $templateFeaturesArr ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-neutral-100 text-neutral-500 line-through">
                                                            <?php echo e($feature); ?>

                                                        </span>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                
                                
                                <?php
                                    $requestedSkills = $request['requested_skills'] ?? [];
                                    $templateSkills = $request['template_skills'] ?? [];
                                    $displaySkills = !empty($requestedSkills) ? $requestedSkills : $templateSkills;
                                    if (is_string($displaySkills)) {
                                        $displaySkills = json_decode($displaySkills, true) ?? [];
                                    }
                                ?>
                                <?php if(!empty($displaySkills)): ?>
                                    <div class="bg-white p-3 rounded-lg border border-secondary-100">
                                        <h4 class="text-xs font-semibold text-neutral-600 uppercase mb-2 flex items-center">
                                            <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-wrench'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-3 h-3 mr-1 text-primary-500']); ?>
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
                                            Skills Required
                                        </h4>
                                        <div class="flex flex-wrap gap-1.5">
                                            <?php $__currentLoopData = $displaySkills; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-primary-50 text-primary-700 border border-primary-200">
                                                    <?php echo e($skill); ?>

                                                </span>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                        <?php if(!empty($request['has_customizations']) && !empty($templateSkills) && $displaySkills !== $templateSkills): ?>
                                            <div class="mt-2 pt-2 border-t border-neutral-100">
                                                <p class="text-xs text-neutral-400 mb-1">Original template:</p>
                                                <div class="flex flex-wrap gap-1">
                                                    <?php
                                                        $templateSkillsArr = is_string($templateSkills) ? json_decode($templateSkills, true) : $templateSkills;
                                                    ?>
                                                    <?php $__currentLoopData = $templateSkillsArr ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-neutral-100 text-neutral-500 line-through">
                                                            <?php echo e($skill); ?>

                                                        </span>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            
                            <?php if(!empty($request['estimated_duration_days']) || !empty($request['template_base_price'])): ?>
                                <div class="mt-4 pt-3 border-t border-secondary-200 flex flex-wrap gap-4 text-sm">
                                    <?php if(!empty($request['estimated_duration_days'])): ?>
                                        <div class="flex items-center text-neutral-600">
                                            <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-calendar-days'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-4 h-4 mr-1.5 text-secondary-500']); ?>
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
                                            <span>Est. Duration: <strong><?php echo e($request['estimated_duration_days']); ?> days</strong></span>
                                        </div>
                                    <?php endif; ?>
                                    <?php if(!empty($request['template_base_price'])): ?>
                                        <div class="flex items-center text-neutral-600">
                                            <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-banknote'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-4 h-4 mr-1.5 text-success-500']); ?>
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
                                            <span>Base Price: <strong>₱<?php echo e(number_format($request['template_base_price'], 2)); ?></strong></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-neutral-500 uppercase tracking-wider mb-2">Request Information</h3>
                            <div class="space-y-3">
                                <div class="flex">
                                    <div class="w-32 text-neutral-500">Request ID:</div>
                                    <div class="flex-1 text-neutral-800 font-medium">
                                        <?php echo e($request['id'] ?? 'N/A'); ?>

                                    </div>
                                </div>
                                
                                <div class="flex">
                                    <div class="w-32 text-neutral-500">Type:</div>
                                    <div class="flex-1 text-neutral-800">
                                        <?php echo e(ucfirst($request['businessType'] ?? $request['business_type'] ?? 'N/A')); ?>

                                    </div>
                                </div>
                                
                                <div class="flex">
                                    <div class="w-32 text-neutral-500">Status:</div>
                                    <div class="flex-1 text-neutral-800">
                                        <?php echo e(ucfirst($requestStatus)); ?>

                                    </div>
                                </div>
                                
                                <div class="flex">
                                    <div class="w-32 text-neutral-500">Priority:</div>
                                    <div class="flex-1">
                                        <div class="flex items-center">
                                            <form action="<?php echo e(route('admin.requests.update-priority', $request['id'])); ?>" method="POST" class="flex items-center" id="priorityForm">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PATCH'); ?>
                                                <select name="priority" id="priority" onchange="document.getElementById('priorityForm').submit()"
                                                        class="rounded-lg border border-neutral-300 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                                                    <option value="low" <?php echo e($requestPriority === 'low' ? 'selected' : ''); ?>>Low</option>
                                                    <option value="medium" <?php echo e($requestPriority === 'medium' ? 'selected' : ''); ?>>Medium</option>
                                                    <option value="high" <?php echo e($requestPriority === 'high' ? 'selected' : ''); ?>>High</option>
                                                    <option value="urgent" <?php echo e($requestPriority === 'urgent' ? 'selected' : ''); ?>>Urgent</option>
                                                </select>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="flex">
                                    <div class="w-32 text-neutral-500">Company:</div>
                                    <div class="flex-1 text-neutral-800">
                                        <?php echo e($request['companyName'] ?? $request['company_name'] ?? 'N/A'); ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <h3 class="text-sm font-medium text-neutral-500 uppercase tracking-wider mb-2">Timeline</h3>
                            <div class="space-y-3">
                                <div class="flex">
                                    <div class="w-32 text-neutral-500">Submitted:</div>
                                    <div class="flex-1 text-neutral-800">
                                        <?php echo e(isset($request['submission_date']) ? date('M d, Y', strtotime($request['submission_date'])) : date('M d, Y', strtotime($request['created_at']))); ?>

                                    </div>
                                </div>
                                
                                <div class="flex">
                                    <div class="w-32 text-neutral-500">Deadline:</div>
                                    <div class="flex-1 text-neutral-800">
                                        <?php if(isset($request['deadline'])): ?>
                                            <?php 
                                                $deadline = \Carbon\Carbon::parse($request['deadline']);
                                                $isPast = $deadline->isPast();
                                                $isClose = $deadline->diffInDays(now()) <= 3 && !$isPast;
                                            ?>
                                            
                                            <span class="<?php echo e($isPast ? 'text-error-600' : ($isClose ? 'text-warning-600' : 'text-neutral-600')); ?>">
                                                <?php echo e($deadline->format('M d, Y')); ?>

                                                <?php if($isPast): ?>
                                                    <span class="flex items-center text-xs mt-1">
                                                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-alert-circle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-3 h-3 mr-1']); ?>
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
                                                        <?php echo e($deadline->diffForHumans()); ?>

                                                    </span>
                                                <?php elseif($isClose): ?>
                                                    <span class="flex items-center text-xs mt-1">
                                                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-clock'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-3 h-3 mr-1']); ?>
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
                                                        <?php echo e($deadline->diffForHumans()); ?>

                                                    </span>
                                                <?php endif; ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-neutral-400">Not set</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <?php if(!empty($request['reviewed_at'])): ?>
                                <div class="flex">
                                    <div class="w-32 text-neutral-500">Reviewed:</div>
                                    <div class="flex-1 text-neutral-800">
                                        <?php echo e(date('M d, Y', strtotime($request['reviewed_at']))); ?>

                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <?php if(!empty($request['reviewed_by'])): ?>
                                <div class="flex">
                                    <div class="w-32 text-neutral-500">Reviewed by:</div>
                                    <div class="flex-1 text-neutral-800">
                                        <?php echo e(\App\Models\User::find($request['reviewed_by'])?->fullName ?? 'Admin #' . $request['reviewed_by']); ?>

                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $attributes = $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $component = $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>
            
            <!-- Payment Information Card (Maya automatic payment info) -->
            <?php if($requestStatus === 'approved' || $requestStatus === 'pending_payment'): ?>
            <?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => ['class' => 'mb-6 border-2 border-primary-200']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mb-6 border-2 border-primary-200']); ?>
                <div class="px-6 py-4 border-b border-primary-200 bg-primary-50">
                    <h2 class="text-lg font-semibold text-primary-700 flex items-center">
                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-credit-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-5 h-5 mr-2']); ?>
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
                        Payment Information
                    </h2>
                </div>
                <div class="p-6">
                    <div class="bg-primary-50 border border-primary-200 rounded-xl p-4 mb-4">
                        <div class="flex items-center mb-3">
                            <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-info'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-5 h-5 text-primary-600 mr-2']); ?>
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
                            <p class="text-primary-800 font-medium">This request uses Maya automatic payment gateway</p>
                        </div>
                        <p class="text-primary-700 text-sm">Client will pay via Maya, and the payment will be automatically verified. Once confirmed, the project will be created automatically.</p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-neutral-500 uppercase tracking-wider mb-3">Payment Details</h3>
                            <div class="space-y-3">
                                <div class="flex">
                                    <div class="w-36 text-neutral-500">Amount:</div>
                                    <div class="flex-1 text-neutral-800 font-semibold text-lg">
                                        ₱<?php echo e(number_format($request['approved_budget'] ?? 0, 2)); ?>

                                    </div>
                                </div>
                                
                                <div class="flex">
                                    <div class="w-36 text-neutral-500">Payment Method:</div>
                                    <div class="flex-1 text-neutral-800">
                                        Maya Payment Gateway
                                    </div>
                                </div>
                                
                                <?php if(!empty($request['payment_due_date'])): ?>
                                <div class="flex">
                                    <div class="w-36 text-neutral-500">Due Date:</div>
                                    <div class="flex-1 text-neutral-800">
                                        <?php echo e(\Carbon\Carbon::parse($request['payment_due_date'])->format('M d, Y')); ?>

                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div>
                            <h3 class="text-sm font-medium text-neutral-500 uppercase tracking-wider mb-3">Payment Status</h3>
                            <div class="space-y-3">
                                <div class="bg-warning-50 border border-warning-200 rounded-xl p-4">
                                    <div class="flex items-center text-warning-800">
                                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-clock'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-5 h-5 mr-2']); ?>
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
                                        <span class="font-medium">Waiting for Client Payment</span>
                                    </div>
                                    <p class="text-warning-700 text-sm mt-2">Client needs to complete payment via Maya</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $attributes = $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $component = $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>
            <?php endif; ?>
            
            <!-- Attached Files -->
            <?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => ['class' => 'mb-6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mb-6']); ?>
                <div class="px-6 py-4 border-b border-neutral-200">
                    <h2 class="text-lg font-semibold text-primary-500">Attached Files</h2>
                </div>
                <div class="p-6">
                    <?php if(isset($request['files']) && count($request['files']) > 0): ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <?php $__currentLoopData = $request['files']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="bg-neutral-50 rounded-xl p-4 flex items-center">
                                    <?php
                                        $extension = strtolower(pathinfo($file['filename'], PATHINFO_EXTENSION));
                                        
                                        $iconInfo = match($extension) {
                                            'pdf' => ['icon' => 'file-text', 'color' => 'text-error-600'],
                                            'doc', 'docx' => ['icon' => 'file-text', 'color' => 'text-info-600'],
                                            'xls', 'xlsx' => ['icon' => 'file-spreadsheet', 'color' => 'text-success-600'],
                                            'ppt', 'pptx' => ['icon' => 'file-text', 'color' => 'text-orange-600'],
                                            'jpg', 'jpeg', 'png', 'gif' => ['icon' => 'image', 'color' => 'text-purple-600'],
                                            'zip', 'rar' => ['icon' => 'archive', 'color' => 'text-warning-600'],
                                            default => ['icon' => 'file', 'color' => 'text-neutral-600']
                                        };
                                    ?>
                                    
                                    <div class="h-10 w-10 flex-shrink-0 flex items-center justify-center">
                                        <?php if (isset($component)) { $__componentOriginal511d4862ff04963c3c16115c05a86a9d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal511d4862ff04963c3c16115c05a86a9d = $attributes; } ?>
<?php $component = Illuminate\View\DynamicComponent::resolve(['component' => 'lucide-' . $iconInfo['icon']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dynamic-component'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\DynamicComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-6 h-6 '.e($iconInfo['color']).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal511d4862ff04963c3c16115c05a86a9d)): ?>
<?php $attributes = $__attributesOriginal511d4862ff04963c3c16115c05a86a9d; ?>
<?php unset($__attributesOriginal511d4862ff04963c3c16115c05a86a9d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal511d4862ff04963c3c16115c05a86a9d)): ?>
<?php $component = $__componentOriginal511d4862ff04963c3c16115c05a86a9d; ?>
<?php unset($__componentOriginal511d4862ff04963c3c16115c05a86a9d); ?>
<?php endif; ?>
                                    </div>
                                    
                                    <div class="ml-4 flex-1 min-w-0">
                                        <div class="text-sm font-medium text-neutral-900 truncate">
                                            <?php echo e($file['filename']); ?>

                                        </div>
                                        <div class="text-xs text-neutral-500">
                                            <?php echo e(isset($file['uploaded_at']) ? date('M d, Y', strtotime($file['uploaded_at'])) : 'Unknown date'); ?>

                                            <?php if(isset($file['file_size'])): ?>
                                                · <?php echo e($file['file_size']); ?>

                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    
                                    <a href="<?php echo e(route('admin.requests.download-file', ['request' => $request['id'], 'file' => $file['id']])); ?>" 
                                       class="ml-4 p-2 text-neutral-500 hover:text-primary-600 rounded-full hover:bg-primary-50">
                                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-download'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-5 h-5']); ?>
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
                                    </a>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-neutral-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-file-text'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-8 h-8 text-neutral-400']); ?>
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
                            </div>
                            <h3 class="text-neutral-500 text-base">No files attached</h3>
                            <p class="text-neutral-400 text-sm mt-1">This request doesn't have any attached files</p>
                        </div>
                    <?php endif; ?>
                </div>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $attributes = $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $component = $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>

            <!-- Associated Tasks -->
            <?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => ['class' => 'mb-6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mb-6']); ?>
                <div class="px-6 py-4 border-b border-neutral-200">
                    <h2 class="text-lg font-semibold text-primary-500">Associated Tasks</h2>
                </div>
                <div class="p-6">
                    <?php if(isset($request['tasks']) && count($request['tasks']) > 0): ?>
                        <div class="overflow-x-auto">
                            <table class="w-full whitespace-nowrap">
                                <thead>
                                    <tr class="bg-neutral-50 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                        <th class="px-4 py-3 rounded-l-lg">Task</th>
                                        <th class="px-4 py-3">Status</th>
                                        <th class="px-4 py-3">Priority</th>
                                        <th class="px-4 py-3">Assigned To</th>
                                        <th class="px-4 py-3 rounded-r-lg">Due Date</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-neutral-100">
                                    <?php $__currentLoopData = $request['tasks']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="hover:bg-neutral-50">
                                            <td class="px-4 py-3">
                                                <div class="font-medium text-neutral-900">
                                                    <?php echo e($task['title'] ?? 'Task #' . $task['id']); ?>

                                                </div>
                                                <?php if(!empty($task['description'])): ?>
                                                    <div class="text-neutral-500 text-sm truncate max-w-xs">
                                                        <?php echo e(Str::limit($task['description'], 50)); ?>

                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-4 py-3">
                                                <?php if($task['status'] === 'completed'): ?>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success-100 text-success-800">
                                                        <span class="h-1.5 w-1.5 rounded-full bg-success-500 mr-1.5"></span>
                                                        Completed
                                                    </span>
                                                <?php elseif($task['status'] === 'in_progress'): ?>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-warning-100 text-warning-800">
                                                        <span class="h-1.5 w-1.5 rounded-full bg-warning-500 mr-1.5"></span>
                                                        In Progress
                                                    </span>
                                                <?php else: ?>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-neutral-100 text-neutral-800">
                                                        <span class="h-1.5 w-1.5 rounded-full bg-neutral-500 mr-1.5"></span>
                                                        Pending
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-4 py-3">
                                                <?php
                                                    $taskPriority = $task['priority'] ?? 'low';
                                                    $priorityClasses = [
                                                        'low' => 'bg-info-100 text-info-800',
                                                        'medium' => 'bg-warning-100 text-warning-800',
                                                        'high' => 'bg-orange-100 text-orange-800',
                                                        'urgent' => 'bg-error-100 text-error-800',
                                                    ];
                                                    $priorityClass = $priorityClasses[$taskPriority] ?? 'bg-neutral-100 text-neutral-800';
                                                    
                                                    $priorityIcons = [
                                                        'low' => 'arrow-down',
                                                        'medium' => 'minus',
                                                        'high' => 'arrow-up',
                                                        'urgent' => 'alert-triangle',
                                                    ];
                                                    $priorityIcon = $priorityIcons[$taskPriority] ?? 'circle';
                                                ?>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo e($priorityClass); ?>">
                                                    <?php if (isset($component)) { $__componentOriginal511d4862ff04963c3c16115c05a86a9d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal511d4862ff04963c3c16115c05a86a9d = $attributes; } ?>
<?php $component = Illuminate\View\DynamicComponent::resolve(['component' => 'lucide-' . $priorityIcon] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dynamic-component'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\DynamicComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-3 h-3 mr-1']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal511d4862ff04963c3c16115c05a86a9d)): ?>
<?php $attributes = $__attributesOriginal511d4862ff04963c3c16115c05a86a9d; ?>
<?php unset($__attributesOriginal511d4862ff04963c3c16115c05a86a9d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal511d4862ff04963c3c16115c05a86a9d)): ?>
<?php $component = $__componentOriginal511d4862ff04963c3c16115c05a86a9d; ?>
<?php unset($__componentOriginal511d4862ff04963c3c16115c05a86a9d); ?>
<?php endif; ?>
                                                    <?php echo e(ucfirst($taskPriority)); ?>

                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <?php if(!empty($task['adiutor_id'])): ?>
                                                    <div class="flex items-center">
                                                        <div class="h-6 w-6 rounded-full bg-neutral-200 text-neutral-600 flex items-center justify-center mr-2">
                                                            <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-user'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-3 h-3']); ?>
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
                                                        </div>
                                                        <span><?php echo e(\App\Models\User::find($task['adiutor_id'])?->fullName ?? 'Adiutor #' . $task['adiutor_id']); ?></span>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="text-neutral-400">Unassigned</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-4 py-3 text-neutral-600">
                                                <?php echo e(isset($task['due_date']) ? date('M d, Y', strtotime($task['due_date'])) : 'Not set'); ?>

                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-neutral-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-list-todo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-8 h-8 text-neutral-400']); ?>
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
                            </div>
                            <h3 class="text-neutral-500 text-base">No tasks created</h3>
                            <p class="text-neutral-400 text-sm mt-1">There are no tasks associated with this request yet</p>
                            
                            <?php if($requestStatus === 'approved'): ?>
                                <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['type' => 'button','@click' => 'openCreateTaskModal()','variant' => 'primary','class' => 'mt-4 inline-flex items-center gap-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','@click' => 'openCreateTaskModal()','variant' => 'primary','class' => 'mt-4 inline-flex items-center gap-2']); ?>
                                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-plus'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-4 h-4']); ?>
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
                                    Create Task
                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $attributes = $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $component = $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>
        </div>
        
        <!-- Right Column: Client Info, Admin Notes -->
        <div class="lg:col-span-1">
            <!-- Client Info Card -->
            <?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => ['class' => 'mb-6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mb-6']); ?>
                <div class="px-6 py-4 border-b border-neutral-200">
                    <h2 class="text-lg font-semibold text-primary-500">Client Information</h2>
                </div>
                <div class="p-6">
                    <?php if(isset($request['client'])): ?>
                        <div class="flex items-center mb-6">
                            <div class="bg-primary-100 h-12 w-12 rounded-full flex items-center justify-center text-primary-600 mr-4">
                                <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-user'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-6 h-6']); ?>
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
                            </div>
                            <div>
                                <h3 class="text-lg font-medium text-neutral-900"><?php echo e($request['client']['full_name'] ?? $request['client']['name'] ?? 'Client #' . $request['client']['id']); ?></h3>
                                <div class="text-neutral-500 flex items-center">
                                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-mail'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-4 h-4 mr-2']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $attributes = $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $component = $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?><?php echo e($request['client']['email'] ?? 'No email'); ?>

                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <?php if(!empty($request['client']['phone_number'])): ?>
                                <div class="flex">
                                    <div class="w-8 flex-shrink-0 text-neutral-400">
                                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-phone'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-4 h-4']); ?>
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
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-neutral-500">Phone</div>
                                        <div class="text-neutral-900"><?php echo e($request['client']['phone_number']); ?></div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <?php if(!empty($request['company_name'])): ?>
                                <div class="flex">
                                    <div class="w-8 flex-shrink-0 text-neutral-400">
                                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-building-2'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-4 h-4']); ?>
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
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-neutral-500">Company</div>
                                        <div class="text-neutral-900"><?php echo e($request['company_name']); ?></div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <div class="flex">
                                <div class="w-8 flex-shrink-0 text-neutral-400">
                                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-calendar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-4 h-4']); ?>
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
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-neutral-500">Client Since</div>
                                    <div class="text-neutral-900"><?php echo e(isset($request['client']['created_at']) ? date('M d, Y', strtotime($request['client']['created_at'])) : 'Unknown'); ?></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-6 pt-4 border-t border-neutral-100">
                            <a href="<?php echo e(route('admin.clients.show', $request['client']['id'])); ?>" 
                               class="inline-flex items-center text-primary-600 hover:text-primary-700">
                                <span>View Client Profile</span>
                                <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-chevron-right'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-4 h-4 ml-1']); ?>
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
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-6">
                            <div class="w-12 h-12 bg-neutral-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-user'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-6 h-6 text-neutral-400']); ?>
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
                            </div>
                            <h3 class="text-neutral-500 text-base">No client information</h3>
                            <p class="text-neutral-400 text-sm mt-1">This request is not associated with a client</p>
                        </div>
                    <?php endif; ?>
                </div>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $attributes = $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $component = $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>
            
            <!-- Admin Notes -->
            <?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => ['class' => 'mb-6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mb-6']); ?>
                <div class="px-6 py-4 border-b border-neutral-200">
                    <h2 class="text-lg font-semibold text-primary-500">Admin Notes</h2>
                </div>
                <div class="p-6">
                    <form action="<?php echo e(route('admin.requests.add-note', $request['id'])); ?>" method="POST" class="mb-6">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3">
                            <textarea name="note" rows="3" required
                                      class="w-full rounded-xl border border-neutral-300 px-3 py-2 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
                                      placeholder="Add a note about this request..."></textarea>
                        </div>
                        <div class="flex justify-end">
                            <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['type' => 'submit','variant' => 'primary','class' => 'inline-flex items-center gap-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit','variant' => 'primary','class' => 'inline-flex items-center gap-2']); ?>
                                <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-plus'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-4 h-4']); ?>
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
                                Add Note
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
                        </div>
                    </form>
                    
                    <?php if(!empty($request['admin_notes'])): ?>
                        <div class="bg-neutral-50 rounded-xl p-4 text-neutral-800 whitespace-pre-line text-sm">
                            <?php echo nl2br(e($request['admin_notes'])); ?>

                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <div class="text-neutral-500">No admin notes yet</div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if($requestStatus === 'rejected' && !empty($request['rejection_reason'])): ?>
                        <div class="mt-6">
                            <h3 class="text-sm font-medium text-neutral-500 uppercase tracking-wider mb-2">Rejection Reason</h3>
                            <div class="bg-error-50 text-error-700 rounded-xl p-4 text-sm">
                                <?php echo e($request['rejection_reason']); ?>

                            </div>
                        </div>
                    <?php endif; ?>
                </div>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $attributes = $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $component = $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>
        </div>
    </div>

    <!-- Approve Request Modal -->
    <div x-show="showApproveModal" 
     x-cloak 
     @keydown.escape.window="showApproveModal = false"
     class="fixed inset-0 z-50 overflow-y-auto" 
     style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div x-show="showApproveModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="showApproveModal = false" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40" aria-hidden="true"></div>
        
        <!-- Center modal -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div x-show="showApproveModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative z-50">
            <form action="<?php echo e(route('admin.requests.approve', $request['id'])); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="bg-neutral-50 border-b border-neutral-200 px-6 py-4 flex justify-between items-center">
                    <h5 class="text-lg font-semibold text-neutral-800">Approve Request</h5>
                    <button type="button" @click="showApproveModal = false" class="text-neutral-500 hover:text-neutral-700 focus:outline-none">
                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-x'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-5 h-5']); ?>
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
                    </button>
                </div>
                <div class="modal-body p-6" x-data="{
                    paymentType: 'full_payment',
                    totalMilestones: 3,
                    milestonePhases: [
                        { name: 'Phase 1', percentage: 30, description: '' },
                        { name: 'Phase 2', percentage: 40, description: '' },
                        { name: 'Phase 3', percentage: 30, description: '' }
                    ],
                    downpaymentPercentage: 30,
                    budgetDisplay: '',
                    budgetValue: '',
                    
                    init() {
                        // Set default payment due date to +7 days from today
                        const today = new Date();
                        today.setDate(today.getDate() + 7);
                        const year = today.getFullYear();
                        const month = String(today.getMonth() + 1).padStart(2, '0');
                        const day = String(today.getDate()).padStart(2, '0');
                        document.getElementById('payment_due_date').value = `${year}-${month}-${day}`;
                    },
                    
                    formatBudget(event) {
                        // Remove all non-digit characters
                        let value = event.target.value.replace(/[^\d.]/g, '');
                        
                        // Store raw value
                        this.budgetValue = value;
                        
                        // Format with commas
                        if (value) {
                            const parts = value.split('.');
                            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                            this.budgetDisplay = parts.join('.');
                        } else {
                            this.budgetDisplay = '';
                        }
                        
                        // Update display
                        event.target.value = this.budgetDisplay;
                    },
                    
                    addPhase() {
                        this.milestonePhases.push({ 
                            name: 'Phase ' + (this.milestonePhases.length + 1), 
                            percentage: 0, 
                            description: '' 
                        });
                        this.totalMilestones = this.milestonePhases.length;
                    },
                    
                    removePhase(index) {
                        if (this.milestonePhases.length > 1) {
                            this.milestonePhases.splice(index, 1);
                            this.totalMilestones = this.milestonePhases.length;
                            // Renumber phases
                            this.milestonePhases.forEach((phase, idx) => {
                                if (!phase.name || phase.name.startsWith('Phase ')) {
                                    phase.name = 'Phase ' + (idx + 1);
                                }
                            });
                        }
                    },
                    
                    getTotalPercentage() {
                        return this.milestonePhases.reduce((sum, phase) => sum + parseFloat(phase.percentage || 0), 0);
                    },
                    
                    isPercentageValid() {
                        return this.getTotalPercentage() === 100;
                    }
                }" x-init="init()">
                    <!-- Budget and Payment Section -->
                    <div class="bg-primary-50 rounded-xl p-4 mb-5">
                        <h3 class="text-neutral-800 font-medium mb-3 flex items-center">
                            <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-circle-dollar-sign'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-5 h-5 mr-2 text-primary-600']); ?>
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
                            Budget & Payment
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="approved_budget" class="block text-sm font-medium text-neutral-700 mb-1">Approved Budget *</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-neutral-500">₱</span>
                                    <input type="text" id="approved_budget_display" 
                                           @input="formatBudget($event)"
                                           class="w-full pl-8 pr-4 py-2 rounded-lg border border-neutral-300 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
                                           placeholder="0.00"
                                           required>
                                    <input type="hidden" id="approved_budget" name="approved_budget" :value="budgetValue" required>
                                </div>
                                <p class="text-xs text-neutral-500 mt-1">Amount will be formatted with commas (e.g., 100,000.00)</p>
                            </div>
                            
                            <div>
                                <label for="payment_due_date" class="block text-sm font-medium text-neutral-700 mb-1">Payment Due Date *</label>
                                <input type="date" id="payment_due_date" name="payment_due_date" required
                                       min="<?php echo e(date('Y-m-d', strtotime('+1 day'))); ?>"
                                       class="w-full rounded-lg border border-neutral-300 px-4 py-2 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                                <p class="text-xs text-neutral-500 mt-1">Default: 7 days from today</p>
                            </div>
                        </div>
                        
                        <!-- Payment Type Selection -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-neutral-700 mb-2">Payment Type *</label>
                            <div class="space-y-3">
                                <label class="flex items-start p-3 border rounded-lg cursor-pointer hover:bg-primary-50 transition-colors" 
                                       :class="paymentType === 'full_payment' ? 'border-primary-500 bg-primary-50' : 'border-neutral-300'">
                                    <input type="radio" name="payment_type" value="full_payment" 
                                           x-model="paymentType" required
                                           class="mt-1 text-primary-600 focus:ring-primary-500">
                                    <div class="ml-3">
                                        <div class="font-medium text-neutral-800">Full Payment</div>
                                        <div class="text-sm text-neutral-600">Client pays entire amount upfront. All tasks and documents are immediately accessible.</div>
                                    </div>
                                </label>
                                
                                <label class="flex items-start p-3 border rounded-lg cursor-pointer hover:bg-primary-50 transition-colors"
                                       :class="paymentType === 'milestone_payment' ? 'border-primary-500 bg-primary-50' : 'border-neutral-300'">
                                    <input type="radio" name="payment_type" value="milestone_payment" 
                                           x-model="paymentType"
                                           class="mt-1 text-primary-600 focus:ring-primary-500">
                                    <div class="ml-3">
                                        <div class="font-medium text-neutral-800">Milestone Payment</div>
                                        <div class="text-sm text-neutral-600">Client pays per phase. Tasks and documents are locked per phase until paid.</div>
                                    </div>
                                </label>
                                
                                <label class="flex items-start p-3 border rounded-lg cursor-pointer hover:bg-primary-50 transition-colors"
                                       :class="paymentType === 'downpayment' ? 'border-primary-500 bg-primary-50' : 'border-neutral-300'">
                                    <input type="radio" name="payment_type" value="downpayment" 
                                           x-model="paymentType"
                                           class="mt-1 text-primary-600 focus:ring-primary-500">
                                    <div class="ml-3">
                                        <div class="font-medium text-neutral-800">Downpayment</div>
                                        <div class="text-sm text-neutral-600">Client pays initial downpayment to start. All content locked until remaining balance is paid.</div>
                                    </div>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Milestone Payment Configuration -->
                        <div x-show="paymentType === 'milestone_payment'" 
                             x-transition
                             class="mt-4 p-4 bg-white rounded-lg border border-primary-200">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="font-medium text-neutral-800">Configure Phases</h4>
                                <button type="button" @click="addPhase()" 
                                        class="text-sm px-3 py-1 bg-primary-500 hover:bg-primary-600 text-white rounded-lg transition-colors inline-flex items-center gap-1">
                                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-plus'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-3.5 h-3.5']); ?>
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
                                    Add Phase
                                </button>
                            </div>
                            
                            <input type="hidden" name="total_milestones" :value="milestonePhases.length">
                            
                            <div class="space-y-3 mb-3">
                                <template x-for="(phase, index) in milestonePhases" :key="index">
                                    <div class="p-3 bg-neutral-50 rounded-lg border border-neutral-200">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-sm font-medium text-neutral-700" x-text="'Phase ' + (index + 1)"></span>
                                            <button type="button" @click="removePhase(index)" 
                                                    x-show="milestonePhases.length > 1"
                                                    class="text-error-600 hover:text-error-700 text-sm">
                                                <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-x'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-4 h-4']); ?>
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
                                            </button>
                                        </div>
                                        <div class="grid grid-cols-2 gap-2">
                                            <div>
                                                <input type="text" 
                                                       :name="'milestone_phases[' + index + '][name]'"
                                                       x-model="phase.name"
                                                       placeholder="Phase name"
                                                       class="w-full text-sm rounded border border-neutral-300 px-2 py-1.5 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                                            </div>
                                            <div class="relative">
                                                <input type="number" 
                                                       :name="'milestone_phases[' + index + '][percentage]'"
                                                       x-model="phase.percentage"
                                                       min="0" max="100" step="0.01"
                                                       placeholder="Percentage"
                                                       class="w-full text-sm rounded border border-neutral-300 pl-2 pr-7 py-1.5 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                                                <span class="absolute right-2 top-1/2 -translate-y-1/2 text-neutral-500 text-sm">%</span>
                                            </div>
                                        </div>
                                        <div class="mt-2">
                                            <input type="text" 
                                                   :name="'milestone_phases[' + index + '][description]'"
                                                   x-model="phase.description"
                                                   placeholder="Description (optional)"
                                                   class="w-full text-sm rounded border border-neutral-300 px-2 py-1.5 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                                        </div>
                                    </div>
                                </template>
                            </div>
                            
                            <div class="flex items-center justify-between p-3 rounded-lg"
                                 :class="isPercentageValid() ? 'bg-success-50 border border-success-200' : 'bg-warning-50 border border-warning-200'">
                                <span class="text-sm font-medium" 
                                      :class="isPercentageValid() ? 'text-success-700' : 'text-warning-700'">
                                    Total Percentage:
                                </span>
                                <span class="text-lg font-bold"
                                      :class="isPercentageValid() ? 'text-success-700' : 'text-warning-700'"
                                      x-text="getTotalPercentage() + '%'"></span>
                            </div>
                            <p class="text-xs text-neutral-600 mt-2 flex items-center" x-show="!isPercentageValid()">
                                <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-alert-triangle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-3.5 h-3.5 text-warning-600 mr-1']); ?>
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
                                Percentages must total exactly 100%
                            </p>
                        </div>
                        
                        <!-- Downpayment Configuration -->
                        <div x-show="paymentType === 'downpayment'" 
                             x-transition
                             class="mt-4 p-4 bg-white rounded-lg border border-primary-200">
                            <h4 class="font-medium text-neutral-800 mb-3">Downpayment Configuration</h4>
                            <div>
                                <label for="downpayment_percentage" class="block text-sm font-medium text-neutral-700 mb-1">
                                    Downpayment Percentage *
                                </label>
                                <div class="relative">
                                    <input type="number" id="downpayment_percentage" name="downpayment_percentage" 
                                           x-model="downpaymentPercentage"
                                           min="1" max="99" step="0.01"
                                           class="w-full rounded border border-neutral-300 pl-3 pr-10 py-2 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
                                           placeholder="30">
                                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-neutral-500">%</span>
                                </div>
                                <p class="text-xs text-neutral-600 mt-1">
                                    Client will pay <span class="font-medium" x-text="downpaymentPercentage + '%'"></span> upfront, 
                                    then <span class="font-medium" x-text="(100 - downpaymentPercentage) + '%'"></span> as remaining balance.
                                </p>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <label for="payment_instructions" class="block text-sm font-medium text-neutral-700 mb-1">Payment Instructions (Optional)</label>
                            <textarea id="payment_instructions" name="payment_instructions" rows="2" 
                                      class="w-full rounded-lg border border-neutral-300 px-4 py-2 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
                                      placeholder="e.g., Bank transfer details, payment link, etc."></textarea>
                        </div>
                    </div>
                    
                    <!-- Coupon Assignment Section -->
                    <div class="bg-success-50 rounded-xl p-4 mb-5" x-data="{
                        assignCoupon: false,
                        couponType: 'existing',
                        discountType: 'percentage',
                        discountValue: '',
                        couponCode: ''
                    }">
                        <h3 class="text-neutral-800 font-medium mb-3 flex items-center">
                            <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-ticket'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-5 h-5 mr-2 text-success-600']); ?>
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
                            Coupon Assignment (Optional)
                        </h3>
                        
                        <div class="flex items-center mb-3">
                            <input type="checkbox" id="assign_coupon" name="assign_coupon" value="1" 
                                   x-model="assignCoupon"
                                   class="rounded border-neutral-300 text-success-600 focus:ring-success-500">
                            <label for="assign_coupon" class="ml-2 text-neutral-800 font-medium">
                                Assign a discount coupon to this request
                            </label>
                        </div>
                        
                        <div x-show="assignCoupon" x-transition class="space-y-4">
                            <!-- Coupon Type Selection -->
                            <div class="flex gap-3">
                                <label class="flex-1 flex items-center p-3 border rounded-lg cursor-pointer hover:bg-white transition-colors" 
                                       :class="couponType === 'existing' ? 'border-success-500 bg-white' : 'border-neutral-300'">
                                    <input type="radio" name="coupon_type" value="existing" 
                                           x-model="couponType"
                                           class="text-success-600 focus:ring-success-500">
                                    <span class="ml-2 text-sm font-medium text-neutral-800">Use Existing Coupon</span>
                                </label>
                                
                                <label class="flex-1 flex items-center p-3 border rounded-lg cursor-pointer hover:bg-white transition-colors"
                                       :class="couponType === 'new' ? 'border-success-500 bg-white' : 'border-neutral-300'">
                                    <input type="radio" name="coupon_type" value="new" 
                                           x-model="couponType"
                                           class="text-success-600 focus:ring-success-500">
                                    <span class="ml-2 text-sm font-medium text-neutral-800">Generate New Coupon</span>
                                </label>
                            </div>
                            
                            <!-- Existing Coupon Selection -->
                            <div x-show="couponType === 'existing'" class="p-3 bg-white rounded-lg">
                                <label for="coupon_code" class="block text-sm font-medium text-neutral-700 mb-1">Select Coupon</label>
                                <select id="coupon_code" name="coupon_code"
                                        class="w-full rounded border border-neutral-300 px-3 py-2 text-neutral-800 focus:border-success-500 focus:ring focus:ring-success-200 focus:ring-opacity-50">
                                    <option value="">-- Select an existing coupon --</option>
                                    <?php if(isset($availableCoupons) && $availableCoupons->count() > 0): ?>
                                        <?php $__currentLoopData = $availableCoupons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $coupon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($coupon->code); ?>">
                                            <?php echo e($coupon->code); ?> - 
                                            <?php if($coupon->discount_type === 'percentage'): ?>
                                                <?php echo e($coupon->discount_value); ?>% OFF
                                            <?php else: ?>
                                                ₱<?php echo e(number_format($coupon->discount_value, 2)); ?> OFF
                                            <?php endif; ?>
                                        </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </select>
                                <p class="text-xs text-neutral-600 mt-1">Choose from active public coupons</p>
                            </div>
                            
                            <!-- New Coupon Generation -->
                            <div x-show="couponType === 'new'" class="p-3 bg-white rounded-lg space-y-3">
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-sm font-medium text-neutral-700 mb-1">Discount Type</label>
                                        <select name="new_coupon_discount_type" x-model="discountType"
                                                class="w-full rounded border border-neutral-300 px-3 py-2 text-neutral-800 focus:border-success-500 focus:ring focus:ring-success-200 focus:ring-opacity-50">
                                            <option value="percentage">Percentage (%)</option>
                                            <option value="fixed">Fixed Amount (₱)</option>
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-neutral-700 mb-1">Discount Value</label>
                                        <div class="relative">
                                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-neutral-500" x-text="discountType === 'percentage' ? '%' : '₱'"></span>
                                            <input type="number" name="new_coupon_discount_value" x-model="discountValue"
                                                   class="w-full pl-8 pr-3 py-2 rounded border border-neutral-300 text-neutral-800 focus:border-success-500 focus:ring focus:ring-success-200 focus:ring-opacity-50"
                                                   :max="discountType === 'percentage' ? '100' : ''"
                                                   step="0.01"
                                                   placeholder="Enter value">
                                        </div>
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-neutral-700 mb-1">Coupon Description (Optional)</label>
                                    <input type="text" name="new_coupon_description"
                                           class="w-full rounded border border-neutral-300 px-3 py-2 text-neutral-800 focus:border-success-500 focus:ring focus:ring-success-200 focus:ring-opacity-50"
                                           placeholder="e.g., Approval bonus discount">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-neutral-700 mb-1">Valid Until</label>
                                    <input type="date" name="new_coupon_valid_until"
                                           :min="new Date().toISOString().split('T')[0]"
                                           class="w-full rounded border border-neutral-300 px-3 py-2 text-neutral-800 focus:border-success-500 focus:ring focus:ring-success-200 focus:ring-opacity-50">
                                    <p class="text-xs text-neutral-600 mt-1">Leave empty for 30 days from today</p>
                                </div>
                                
                                <div class="flex items-center">
                                    <input type="checkbox" name="new_coupon_auto_apply" value="1" 
                                           class="rounded border-neutral-300 text-success-600 focus:ring-success-500"
                                           checked>
                                    <label class="ml-2 text-sm text-neutral-700">
                                        Automatically apply to this request
                                    </label>
                                </div>
                                
                                <p class="text-xs text-info-600 flex items-start">
                                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-info'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-3.5 h-3.5 mr-1 mt-0.5 flex-shrink-0']); ?>
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
                                    <span>A unique coupon code will be generated and assigned specifically to this client for this request.</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-5">
                        <label for="admin_notes" class="block text-sm font-medium text-neutral-700 mb-1">Admin Notes (Optional)</label>
                        <textarea id="admin_notes" name="admin_notes" rows="3" 
                                  class="w-full rounded-lg border border-neutral-300 px-4 py-2.5 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
                                  placeholder="Add any notes about this approval..."></textarea>
                    </div>
                    
                    <div class="mb-5">
                        <div class="flex items-center mb-2">
                            <input type="checkbox" id="create_task" name="create_task" value="1" 
                                   class="rounded border-neutral-300 text-primary-600 focus:ring-primary-500">
                            <label for="create_task" class="ml-2 text-neutral-800 font-medium">
                                Create Task from this Request
                            </label>
                        </div>
                        <p class="text-neutral-500 text-sm">If checked, a task will be created automatically.</p>
                    </div>
                    
                    <div id="task_fields" class="bg-neutral-50 rounded-lg p-4 mb-2 hidden">
                        <h3 class="text-neutral-800 font-medium mb-3">Task Details</h3>
                        
                        <div class="mb-4">
                            <label for="task_title" class="block text-sm font-medium text-neutral-700 mb-1">Task Title *</label>
                            <input type="text" id="task_title" name="task_title" 
                                   class="w-full rounded-lg border border-neutral-300 px-4 py-2 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
                                   placeholder="Enter task title">
                        </div>
                        
                        <div class="mb-4">
                            <label for="task_description" class="block text-sm font-medium text-neutral-700 mb-1">Description *</label>
                            <textarea id="task_description" name="task_description" rows="3" 
                                      class="w-full rounded-lg border border-neutral-300 px-4 py-2 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
                                      placeholder="Enter task description"></textarea>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="task_priority" class="block text-sm font-medium text-neutral-700 mb-1">Priority *</label>
                                <select id="task_priority" name="task_priority" 
                                        class="w-full rounded-lg border border-neutral-300 px-4 py-2 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                                    <option value="low">Low</option>
                                    <option value="medium" selected>Medium</option>
                                    <option value="high">High</option>
                                    <option value="urgent">Urgent</option>
                                </select>
                            </div>
                            
                            <div>
                                <label for="task_due_date" class="block text-sm font-medium text-neutral-700 mb-1">Due Date</label>
                                <input type="date" id="task_due_date" name="task_due_date" 
                                       class="w-full rounded-lg border border-neutral-300 px-4 py-2 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <label for="adiutor_id" class="block text-sm font-medium text-neutral-700 mb-1">Assign To (Optional)</label>
                            <select id="adiutor_id" name="adiutor_id" 
                                    class="w-full rounded-lg border border-neutral-300 px-4 py-2 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                                <option value="">Select an Adiutor</option>
                                <?php $__currentLoopData = \App\Models\User::where('role', 'adiutor')->orderBy('fullName')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $adiutor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($adiutor->id); ?>"><?php echo e($adiutor->fullName); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="bg-neutral-50 border-t border-neutral-200 px-6 py-4 flex justify-end">
                    <button type="button" @click="showApproveModal = false" class="px-4 py-2 border border-neutral-300 bg-white text-neutral-700 rounded-lg hover:bg-neutral-100 mr-3">
                        Cancel
                    </button>
                    <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['type' => 'submit','variant' => 'success','class' => 'inline-flex items-center gap-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit','variant' => 'success','class' => 'inline-flex items-center gap-2']); ?>
                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-check'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-4 h-4']); ?>
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
                        Approve Request
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Request Modal -->
<div x-show="showRejectModal" 
     x-cloak 
     @keydown.escape.window="showRejectModal = false"
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div x-show="showRejectModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="showRejectModal = false" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40" aria-hidden="true"></div>
        
        <!-- Center modal -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div x-show="showRejectModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative z-50">
            <form action="<?php echo e(route('admin.requests.reject', $request['id'])); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="bg-neutral-50 border-b border-neutral-200 px-6 py-4 flex justify-between items-center">
                    <h5 class="text-lg font-semibold text-neutral-800">Reject Request</h5>
                    <button type="button" @click="showRejectModal = false" class="text-neutral-500 hover:text-neutral-700 focus:outline-none">
                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-x'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-5 h-5']); ?>
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
                    </button>
                </div>
                <div class="p-6">
                    <div class="mb-5">
                        <label for="rejection_reason" class="block text-sm font-medium text-neutral-700 mb-1">Rejection Reason *</label>
                        <textarea id="rejection_reason" name="rejection_reason" rows="3" required
                                  class="w-full rounded-xl border border-neutral-300 px-4 py-2.5 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
                                  placeholder="Please provide a reason for rejecting this request..."></textarea>
                    </div>
                    
                    <div class="mb-5">
                        <label for="reject_admin_notes" class="block text-sm font-medium text-neutral-700 mb-1">Additional Notes</label>
                        <textarea id="reject_admin_notes" name="admin_notes" rows="3" 
                                  class="w-full rounded-xl border border-neutral-300 px-4 py-2.5 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
                                  placeholder="Add any additional notes..."></textarea>
                    </div>
                </div>
                <div class="bg-neutral-50 border-t border-neutral-200 px-6 py-4 flex justify-end">
                    <button type="button" @click="showRejectModal = false" class="px-4 py-2 border border-neutral-300 bg-white text-neutral-700 rounded-lg hover:bg-neutral-100 mr-3">
                        Cancel
                    </button>
                    <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['type' => 'submit','variant' => 'danger','class' => 'inline-flex items-center gap-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit','variant' => 'danger','class' => 'inline-flex items-center gap-2']); ?>
                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-x'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-4 h-4']); ?>
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
                        Reject Request
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Create Task Modal -->
<div x-show="showCreateTaskModal"
     x-cloak 
     @keydown.escape.window="showCreateTaskModal = false"
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div x-show="showCreateTaskModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="showCreateTaskModal = false" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40" aria-hidden="true"></div>
        
        <!-- Center modal -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div x-show="showCreateTaskModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative z-50">
            <form action="<?php echo e(route('admin.requests.approve', $request['id'])); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="create_task" value="1">
                
                <div class="bg-neutral-50 border-b border-neutral-200 px-6 py-4 flex justify-between items-center">
                    <h5 class="text-lg font-semibold text-neutral-800">Create Task</h5>
                    <button type="button" @click="showCreateTaskModal = false" class="text-neutral-500 hover:text-neutral-700 focus:outline-none">
                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-x'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-5 h-5']); ?>
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
                    </button>
                </div>
                <div class="p-6">
                    <div class="mb-5">
                        <label for="new_task_title" class="block text-sm font-medium text-neutral-700 mb-1">Task Title *</label>
                        <input type="text" id="new_task_title" name="task_title" required
                               class="w-full rounded-xl border border-neutral-300 px-4 py-2 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
                               placeholder="Enter task title" 
                               value="<?php echo e(isset($request['title']) ? 'Task for: ' . $request['title'] : 'New task for request #' . $request['id']); ?>">
                    </div>
                    
                    <div class="mb-5">
                        <label for="new_task_description" class="block text-sm font-medium text-neutral-700 mb-1">Description *</label>
                        <textarea id="new_task_description" name="task_description" rows="3" required
                                  class="w-full rounded-xl border border-neutral-300 px-4 py-2 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
                                  placeholder="Enter task description"><?php echo e('Based on client request #' . $request['id']); ?></textarea>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="new_task_priority" class="block text-sm font-medium text-neutral-700 mb-1">Priority *</label>
                            <select id="new_task_priority" name="task_priority" required
                                    class="w-full rounded-xl border border-neutral-300 px-4 py-2 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                                <option value="low">Low</option>
                                <option value="medium" selected>Medium</option>
                                <option value="high">High</option>
                                <option value="urgent">Urgent</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="new_task_due_date" class="block text-sm font-medium text-neutral-700 mb-1">Due Date</label>
                            <input type="date" id="new_task_due_date" name="task_due_date" 
                                   class="w-full rounded-xl border border-neutral-300 px-4 py-2 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <label for="new_adiutor_id" class="block text-sm font-medium text-neutral-700 mb-1">Assign To (Optional)</label>
                        <select id="new_adiutor_id" name="adiutor_id" 
                                class="w-full rounded-xl border border-neutral-300 px-4 py-2 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                            <option value="">Select an Adiutor</option>
                            <?php $__currentLoopData = \App\Models\User::where('role', 'adiutor')->orderBy('fullName')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $adiutor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($adiutor->id); ?>"><?php echo e($adiutor->fullName); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="bg-neutral-50 border-t border-neutral-200 px-6 py-4 flex justify-end">
                    <button type="button" @click="showCreateTaskModal = false" class="px-4 py-2 border border-neutral-300 bg-white text-neutral-700 rounded-lg hover:bg-neutral-100 mr-3">
                        Cancel
                    </button>
                    <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['type' => 'submit','variant' => 'primary','class' => 'inline-flex items-center gap-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit','variant' => 'primary','class' => 'inline-flex items-center gap-2']); ?>
                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-plus'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-4 h-4']); ?>
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
                        Create Task
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
                </div>
            </form>
        </div>
    </div>
</div>

</div>
<!-- End of Alpine.js scope -->

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
$(document).ready(function() {
    // Toggle task fields based on checkbox
    $('#create_task').on('change', function() {
        if($(this).is(':checked')) {
            $('#task_fields').removeClass('hidden').slideDown();
            
            // Pre-populate fields with default values
            var requestId = '<?php echo e($request["id"] ?? ""); ?>';
            var requestTitle = '<?php echo e($request["project_name"] ?? $request["title"] ?? ""); ?>';
            
            if (requestTitle) {
                $('#task_title').val('Task for: ' + requestTitle);
            } else {
                $('#task_title').val('New task for request #' + requestId);
            }
            
            $('#task_description').val('Based on client request #' + requestId);
            
            // Don't set HTML5 required - we handle this on server side
        } else {
            $('#task_fields').slideUp().addClass('hidden');
            
            // Clear fields when unchecked
            $('#task_title, #task_description').val('');
        }
    });
    
    // No need for form validation since server handles it
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\marka\Projects\cms\resources\views/admin/requests/show.blade.php ENDPATH**/ ?>