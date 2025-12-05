<?php $__env->startSection('title', 'Project Details - ' . $project->title); ?>
<?php $__env->startSection('page-title', 'Project Details'); ?>

<?php $__env->startSection('content'); ?>
<div class="px-6 py-8">
    <!-- Breadcrumb -->
    <?php if (isset($component)) { $__componentOriginal045477955e5b1d8c9df01934ca3836c0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal045477955e5b1d8c9df01934ca3836c0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.breadcrumb','data' => ['items' => [
        ['label' => 'Dashboard', 'url' => route('admin.dashboard'), 'icon' => 'home'],
        ['label' => 'Projects', 'url' => route('admin.projects.index'), 'icon' => 'folder-kanban'],
        ['label' => $project->title, 'icon' => 'file-text']
    ],'class' => 'mb-6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
        ['label' => 'Dashboard', 'url' => route('admin.dashboard'), 'icon' => 'home'],
        ['label' => 'Projects', 'url' => route('admin.projects.index'), 'icon' => 'folder-kanban'],
        ['label' => $project->title, 'icon' => 'file-text']
    ]),'class' => 'mb-6']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal045477955e5b1d8c9df01934ca3836c0)): ?>
<?php $attributes = $__attributesOriginal045477955e5b1d8c9df01934ca3836c0; ?>
<?php unset($__attributesOriginal045477955e5b1d8c9df01934ca3836c0); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal045477955e5b1d8c9df01934ca3836c0)): ?>
<?php $component = $__componentOriginal045477955e5b1d8c9df01934ca3836c0; ?>
<?php unset($__componentOriginal045477955e5b1d8c9df01934ca3836c0); ?>
<?php endif; ?>
        
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
        <div>
            <div class="flex items-center mb-2">
                <?php
                    $statusConfig = [
                        'active' => ['class' => 'bg-info-100 text-info-800', 'label' => 'Active', 'icon' => 'play-circle'],
                        'in_progress' => ['class' => 'bg-primary-100 text-primary-800', 'label' => 'In Progress', 'icon' => 'loader'],
                        'review' => ['class' => 'bg-warning-100 text-warning-800', 'label' => 'Review', 'icon' => 'eye'],
                        'completed' => ['class' => 'bg-success-100 text-success-800', 'label' => 'Completed', 'icon' => 'check-circle'],
                        'cancelled' => ['class' => 'bg-error-100 text-error-800', 'label' => 'Cancelled', 'icon' => 'x-circle'],
                        'on_hold' => ['class' => 'bg-neutral-100 text-neutral-800', 'label' => 'On Hold', 'icon' => 'pause-circle'],
                    ];
                    $config = $statusConfig[$project->status] ?? ['class' => 'bg-neutral-100 text-neutral-800', 'label' => ucfirst($project->status), 'icon' => 'circle'];
                ?>
                
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium <?php echo e($config['class']); ?> mr-3">
                    <?php $statusIcon = 'lucide-' . $config['icon']; ?>
                    <?php if (isset($component)) { $__componentOriginal511d4862ff04963c3c16115c05a86a9d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal511d4862ff04963c3c16115c05a86a9d = $attributes; } ?>
<?php $component = Illuminate\View\DynamicComponent::resolve(['component' => $statusIcon] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
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
                    <?php echo e($config['label']); ?>

                </span>
                
                <?php if($project->priority): ?>
                    <?php
                        $priorityConfig = [
                            'low' => ['class' => 'bg-info-100 text-info-800', 'icon' => 'arrow-down'],
                            'medium' => ['class' => 'bg-warning-100 text-warning-800', 'icon' => 'minus'],
                            'high' => ['class' => 'bg-orange-100 text-orange-800', 'icon' => 'arrow-up'],
                            'urgent' => ['class' => 'bg-error-100 text-error-800', 'icon' => 'alert-triangle'],
                        ];
                        $pConfig = $priorityConfig[$project->priority] ?? ['class' => 'bg-neutral-100 text-neutral-800', 'icon' => 'circle'];
                    ?>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium <?php echo e($pConfig['class']); ?>">
                        <?php $priorityIcon = 'lucide-' . $pConfig['icon']; ?>
                        <?php if (isset($component)) { $__componentOriginal511d4862ff04963c3c16115c05a86a9d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal511d4862ff04963c3c16115c05a86a9d = $attributes; } ?>
<?php $component = Illuminate\View\DynamicComponent::resolve(['component' => $priorityIcon] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
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
                        <?php echo e(ucfirst($project->priority)); ?> Priority
                    </span>
                <?php endif; ?>
            </div>
            
            <h1 class="text-2xl font-semibold text-neutral-800 mb-1"><?php echo e($project->title); ?></h1>
            
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
                <span>Created <?php echo e($project->created_at->format('F d, Y')); ?></span>
            </div>
        </div>
        
        <div class="mt-4 sm:mt-0 flex flex-wrap gap-3">
            <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['variant' => 'secondary','href' => ''.e(route('admin.projects.index')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'secondary','href' => ''.e(route('admin.projects.index')).'']); ?>
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
                Back to Projects
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
            
            <?php if($project->status !== 'completed'): ?>
                <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['variant' => 'success','onclick' => 'showCompleteModal()']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'success','onclick' => 'showCompleteModal()']); ?>
                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-check-circle'); ?>
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
                    Mark as Completed
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
            
            <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['variant' => 'primary','href' => ''.e(route('admin.projects.edit', $project->id)).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'primary','href' => ''.e(route('admin.projects.edit', $project->id)).'']); ?>
                <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-pencil'); ?>
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
                Edit Project
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
    </div>

    <!-- Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Project Details -->
        <div class="lg:col-span-2">
            <!-- Project Details Card -->
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
                <div class="px-6 py-4 border-b border-neutral-100">
                    <h2 class="text-lg font-semibold text-neutral-800 flex items-center gap-2">
                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-file-text'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-5 h-5 text-primary-500']); ?>
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
                        Project Details
                    </h2>
                </div>
                <div class="p-6">
                    <div class="mb-6">
                        <h3 class="text-sm font-medium text-neutral-500 uppercase tracking-wider mb-2">Description</h3>
                        <div class="prose max-w-none text-neutral-800">
                            <?php echo e($project->description); ?>

                        </div>
                    </div>

                    
                    <?php if($project->serviceRequest && (!empty($project->serviceRequest->requested_features) || !empty($project->serviceRequest->requested_skills) || !empty($project->serviceRequest->template_features) || !empty($project->serviceRequest->template_skills))): ?>
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
                                <?php if($project->serviceRequest->has_customizations): ?>
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
                                    $sr = $project->serviceRequest;
                                    $displayFeatures = $sr->effective_features ?? [];
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
                                        <?php if($sr->has_customizations && $sr->hasCustomizedFeatures()): ?>
                                            <div class="mt-2 pt-2 border-t border-neutral-100">
                                                <p class="text-xs text-neutral-400 mb-1">Original template:</p>
                                                <div class="flex flex-wrap gap-1">
                                                    <?php $__currentLoopData = $sr->template_features ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
                                    $displaySkills = $sr->effective_skills ?? [];
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
                                        <?php if($sr->has_customizations && $sr->hasCustomizedSkills()): ?>
                                            <div class="mt-2 pt-2 border-t border-neutral-100">
                                                <p class="text-xs text-neutral-400 mb-1">Original template:</p>
                                                <div class="flex flex-wrap gap-1">
                                                    <?php $__currentLoopData = $sr->template_skills ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
                            
                            
                            <?php if(!empty($sr->estimated_duration_days) || !empty($sr->template_base_price)): ?>
                                <div class="mt-4 pt-3 border-t border-secondary-200 flex flex-wrap gap-4 text-sm">
                                    <?php if(!empty($sr->estimated_duration_days)): ?>
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
                                            <span>Template Est.: <strong><?php echo e($sr->estimated_duration_days); ?> days</strong></span>
                                        </div>
                                    <?php endif; ?>
                                    <?php if(!empty($sr->template_base_price)): ?>
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
                                            <span>Template Base: <strong>₱<?php echo e(number_format($sr->template_base_price, 2)); ?></strong></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php elseif($project->serviceRequest && !$project->serviceRequest->template_service_id): ?>
                        
                        <div class="mb-6 p-4 bg-neutral-50 rounded-xl border border-neutral-200">
                            <div class="flex items-center gap-2 text-neutral-500">
                                <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-info'); ?>
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
                                <span class="text-sm">This project was created from a custom request without a service template.</span>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-neutral-500 uppercase tracking-wider mb-2">Timeline</h3>
                            <div class="space-y-3">
                                <div class="flex">
                                    <div class="w-32 text-neutral-500">Start Date:</div>
                                    <div class="flex-1 text-neutral-800">
                                        <?php echo e($project->start_date ? $project->start_date->format('M d, Y') : 'Not set'); ?>

                                    </div>
                                </div>
                                <div class="flex">
                                    <div class="w-32 text-neutral-500">Deadline:</div>
                                    <div class="flex-1 text-neutral-800">
                                        <?php echo e($project->deadline ? $project->deadline->format('M d, Y') : 'Not set'); ?>

                                    </div>
                                </div>
                                <?php if($project->completion_date): ?>
                                <div class="flex">
                                    <div class="w-32 text-neutral-500">Completed:</div>
                                    <div class="flex-1 text-neutral-800">
                                        <?php echo e($project->completion_date->format('M d, Y')); ?>

                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div>
                            <h3 class="text-sm font-medium text-neutral-500 uppercase tracking-wider mb-2">Project Information</h3>
                            <div class="space-y-3">
                                <div class="flex">
                                    <div class="w-32 text-neutral-500">Project ID:</div>
                                    <div class="flex-1 text-neutral-800 font-medium">
                                        #<?php echo e($project->id); ?>

                                    </div>
                                </div>
                                <div class="flex">
                                    <div class="w-32 text-neutral-500">Status:</div>
                                    <div class="flex-1 text-neutral-800">
                                        <?php echo e(ucwords(str_replace('_', ' ', $project->status))); ?>

                                    </div>
                                </div>
                                <?php if($project->priority): ?>
                                <div class="flex">
                                    <div class="w-32 text-neutral-500">Priority:</div>
                                    <div class="flex-1 text-neutral-800">
                                        <?php echo e(ucfirst($project->priority)); ?>

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

                <!-- Budget Overview Card -->
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
                    <div class="px-6 py-4 border-b border-neutral-100">
                        <h2 class="text-lg font-semibold text-neutral-800 flex items-center gap-2">
                            <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-wallet'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-5 h-5 text-primary-500']); ?>
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
                            Budget Overview
                        </h2>
                        <?php if($project->serviceRequest && ($project->serviceRequest->coupon_discount_amount > 0 || $project->serviceRequest->loyalty_discount_amount > 0)): ?>
                            <div class="mt-2 text-sm text-neutral-600">
                                <p class="flex items-center gap-2">
                                    <span class="text-neutral-500">Original Budget:</span>
                                    <span class="line-through text-neutral-400">₱<?php echo e(number_format($project->serviceRequest->getOriginalBudget(), 2)); ?></span>
                                </p>
                                <?php if($project->serviceRequest->coupon_discount_amount > 0): ?>
                                    <p class="flex items-center gap-2 text-success-600">
                                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-ticket'); ?>
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
                                        <span>Coupon Discount:</span>
                                        <span class="font-semibold">-₱<?php echo e(number_format($project->serviceRequest->coupon_discount_amount, 2)); ?></span>
                                    </p>
                                <?php endif; ?>
                                <?php if($project->serviceRequest->loyalty_discount_amount > 0): ?>
                                    <p class="flex items-center gap-2 text-primary-600">
                                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-award'); ?>
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
                                        <span>Loyalty Discount:</span>
                                        <span class="font-semibold">-₱<?php echo e(number_format($project->serviceRequest->loyalty_discount_amount, 2)); ?></span>
                                    </p>
                                <?php endif; ?>
                                <p class="flex items-center gap-2 mt-1">
                                    <span class="text-neutral-700 font-medium">Final Budget (Client Pays):</span>
                                    <span class="text-primary-600 font-bold">₱<?php echo e(number_format($project->budget, 2)); ?></span>
                                </p>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="p-6">
                        <!-- Main Budget Stats -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                            <div class="text-center p-4 bg-primary-50 rounded-xl">
                                <p class="text-sm text-primary-600 font-medium mb-1">Project Budget</p>
                                <p class="text-2xl font-bold text-primary-900">₱<?php echo e(number_format($budgetOverview['project_budget'], 2)); ?></p>
                                <?php if($project->serviceRequest && ($project->serviceRequest->coupon_discount_amount > 0 || $project->serviceRequest->loyalty_discount_amount > 0)): ?>
                                    <p class="text-xs text-neutral-500 mt-1">(After discounts)</p>
                                <?php endif; ?>
                            </div>
                            <div class="text-center p-4 bg-warning-50 rounded-xl">
                                <p class="text-sm text-warning-600 font-medium mb-1">Task Allocated</p>
                                <p class="text-2xl font-bold text-warning-900">₱<?php echo e(number_format($budgetOverview['total_allocated'], 2)); ?></p>
                            </div>
                            <div class="text-center p-4 bg-purple-50 rounded-xl">
                                <p class="text-sm text-purple-600 font-medium mb-1">Adiutor Earnings</p>
                                <p class="text-2xl font-bold text-purple-900">₱<?php echo e(number_format($budgetOverview['adiutor_earnings']['total_approved'] ?? 0, 2)); ?></p>
                                <p class="text-xs text-neutral-500 mt-1">Approved payments</p>
                            </div>
                            <div class="text-center p-4 bg-success-50 rounded-xl">
                                <p class="text-sm text-success-600 font-medium mb-1">Remaining</p>
                                <p class="text-2xl font-bold <?php echo e(($budgetOverview['adiutor_earnings']['remaining_after_earnings'] ?? 0) < 0 ? 'text-error-900' : 'text-success-900'); ?>">
                                    ₱<?php echo e(number_format($budgetOverview['adiutor_earnings']['remaining_after_earnings'] ?? $budgetOverview['remaining_budget'], 2)); ?>

                                </p>
                                <p class="text-xs text-neutral-500 mt-1">After earnings</p>
                            </div>
                        </div>

                        <!-- Adiutor Earnings Breakdown -->
                        <?php if(isset($budgetOverview['adiutor_earnings'])): ?>
                        <div class="border border-neutral-200 rounded-xl p-4 mb-6">
                            <h3 class="text-sm font-semibold text-neutral-700 mb-4 flex items-center">
                                <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-wallet'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-4 h-4 text-purple-500 mr-2']); ?>
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
                                Adiutor Earnings Breakdown
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Hourly Rate Earnings -->
                                <div class="bg-blue-50 rounded-xl p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-medium text-blue-700 flex items-center">
                                            <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-clock'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-4 h-4 mr-1']); ?>
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
                                            Hourly Rate
                                        </span>
                                        <span class="text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full">
                                            <?php echo e($budgetOverview['adiutor_earnings']['hourly']['entry_count']); ?> entries
                                        </span>
                                    </div>
                                    <p class="text-lg font-bold text-blue-900">
                                        ₱<?php echo e(number_format($budgetOverview['adiutor_earnings']['hourly']['approved_amount'], 2)); ?>

                                    </p>
                                    <p class="text-xs text-blue-600 mt-1">
                                        <?php echo e($budgetOverview['adiutor_earnings']['hourly']['approved_hours']); ?> hours approved
                                    </p>
                                    <?php if($budgetOverview['adiutor_earnings']['hourly']['pending_amount'] > 0): ?>
                                        <p class="text-xs text-warning-600 mt-1 flex items-center">
                                            <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-hourglass'); ?>
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
                                            ₱<?php echo e(number_format($budgetOverview['adiutor_earnings']['hourly']['pending_amount'], 2)); ?> pending
                                        </p>
                                    <?php endif; ?>
                                </div>

                                <!-- Fixed Rate Earnings -->
                                <div class="bg-purple-50 rounded-xl p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-medium text-purple-700 flex items-center">
                                            <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-file-text'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-4 h-4 mr-1']); ?>
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
                                            Fixed Rate
                                        </span>
                                        <span class="text-xs bg-purple-100 text-purple-800 px-2 py-0.5 rounded-full">
                                            <?php echo e($budgetOverview['adiutor_earnings']['fixed_rate']['approved_count']); ?> approved
                                        </span>
                                    </div>
                                    <p class="text-lg font-bold text-purple-900">
                                        ₱<?php echo e(number_format($budgetOverview['adiutor_earnings']['fixed_rate']['approved_amount'], 2)); ?>

                                    </p>
                                    <?php if($budgetOverview['adiutor_earnings']['fixed_rate']['pending_count'] > 0): ?>
                                        <p class="text-xs text-warning-600 mt-1 flex items-center">
                                            <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-hourglass'); ?>
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
                                            <?php echo e($budgetOverview['adiutor_earnings']['fixed_rate']['pending_count']); ?> pending 
                                            (₱<?php echo e(number_format($budgetOverview['adiutor_earnings']['fixed_rate']['pending_amount'], 2)); ?>)
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Projected Budget Warning -->
                            <?php if($budgetOverview['adiutor_earnings']['total_pending'] > 0): ?>
                                <div class="mt-4 bg-warning-50 border border-warning-200 rounded-xl p-3">
                                    <div class="flex items-start">
                                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-alert-triangle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-4 h-4 text-warning-500 mt-0.5 mr-2 flex-shrink-0']); ?>
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
                                        <div class="text-sm">
                                            <p class="font-medium text-warning-800">Pending Approvals</p>
                                            <p class="text-warning-700">
                                                ₱<?php echo e(number_format($budgetOverview['adiutor_earnings']['total_pending'], 2)); ?> in pending payments.
                                                Projected remaining: 
                                                <span class="font-semibold <?php echo e($budgetOverview['adiutor_earnings']['projected_remaining'] < 0 ? 'text-error-600' : ''); ?>">
                                                    ₱<?php echo e(number_format($budgetOverview['adiutor_earnings']['projected_remaining'], 2)); ?>

                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>

                        <!-- Budget Utilization Bar -->
                        <div>
                            <div class="flex justify-between text-sm mb-2">
                                <span class="text-neutral-600">Earnings vs Budget</span>
                                <span class="font-semibold <?php echo e(($budgetOverview['adiutor_earnings']['earnings_percentage'] ?? 0) > 100 ? 'text-error-600' : 'text-neutral-900'); ?>">
                                    <?php echo e($budgetOverview['adiutor_earnings']['earnings_percentage'] ?? $budgetOverview['budget_utilization_percentage']); ?>%
                                </span>
                            </div>
                            <div class="w-full bg-neutral-200 rounded-full h-3">
                                <div class="h-3 rounded-full <?php echo e(($budgetOverview['adiutor_earnings']['earnings_percentage'] ?? 0) > 100 ? 'bg-error-600' : 'bg-purple-600'); ?>" 
                                     style="width: <?php echo e(min($budgetOverview['adiutor_earnings']['earnings_percentage'] ?? $budgetOverview['budget_utilization_percentage'], 100)); ?>%">
                                </div>
                            </div>
                            <p class="text-xs text-neutral-500 mt-1">
                                Shows approved adiutor earnings as percentage of total project budget
                            </p>
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

                <!-- Tasks Section -->
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
                    <div class="px-6 py-4 border-b border-neutral-100 flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-neutral-800 flex items-center gap-2">
                            <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-list-checks'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-5 h-5 text-primary-500']); ?>
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
                            Project Tasks
                            <?php if($project->tasks->count() > 1): ?>
                            <span class="text-xs font-normal text-neutral-400 ml-2">
                                <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-grip-vertical'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-3 h-3 inline']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $attributes = $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $component = $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?> Drag to reorder
                            </span>
                            <?php endif; ?>
                        </h2>
                        <div class="flex items-center gap-2">
                            <span id="reorderStatus" class="text-xs text-neutral-500 hidden"></span>
                            <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['variant' => 'primary','size' => 'sm','href' => ''.e(route('admin.tasks.create', ['project_id' => $project->id])).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'primary','size' => 'sm','href' => ''.e(route('admin.tasks.create', ['project_id' => $project->id])).'']); ?>
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
                                Add Task
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
                    </div>
                    <div class="p-6">
                        <?php if($project->tasks->count() > 0): ?>
                            <div id="tasksList" class="space-y-3" data-project-id="<?php echo e($project->id); ?>">
                                <?php $__currentLoopData = $project->orderedTasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="task-item border border-neutral-200 rounded-xl p-4 hover:bg-neutral-50 transition cursor-move group" 
                                     data-task-id="<?php echo e($task->taskID); ?>">
                                    <div class="flex items-start justify-between">
                                        <div class="flex items-start gap-3 flex-1">
                                            <!-- Drag Handle -->
                                            <div class="drag-handle flex-shrink-0 mt-1 text-neutral-300 group-hover:text-neutral-500 transition cursor-grab active:cursor-grabbing">
                                                <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-grip-vertical'); ?>
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
                                            </div>
                                            <div class="flex-1">
                                                <div class="flex items-center space-x-3 mb-2">
                                                    <h3 class="font-semibold text-neutral-900"><?php echo e($task->taskTitle); ?></h3>
                                                    <?php
                                                        $taskStatusConfig = [
                                                            'pending' => ['class' => 'bg-warning-100 text-warning-800', 'label' => 'Pending'],
                                                            'in_progress' => ['class' => 'bg-primary-100 text-primary-800', 'label' => 'In Progress'],
                                                            'completed' => ['class' => 'bg-success-100 text-success-800', 'label' => 'Completed'],
                                                            'on_hold' => ['class' => 'bg-neutral-100 text-neutral-800', 'label' => 'On Hold'],
                                                            'cancelled' => ['class' => 'bg-error-100 text-error-800', 'label' => 'Cancelled'],
                                                            'pending_approval' => ['class' => 'bg-info-100 text-info-800', 'label' => 'Pending Approval'],
                                                        ];
                                                        $taskConfig = $taskStatusConfig[$task->status] ?? ['class' => 'bg-neutral-100 text-neutral-800', 'label' => 'Unknown'];
                                                    ?>
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium <?php echo e($taskConfig['class']); ?>">
                                                        <?php echo e($taskConfig['label']); ?>

                                                    </span>
                                                </div>
                                                <p class="text-sm text-neutral-600 mb-2"><?php echo e(Str::limit($task->taskDescription, 100)); ?></p>
                                                <div class="flex items-center space-x-4 text-xs text-neutral-500">
                                                    <span class="flex items-center">
                                                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-user'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-3.5 h-3.5 mr-1']); ?>
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
                                                        <?php echo e($task->assignedUser->fullName ?? 'Unassigned'); ?>

                                                    </span>
                                                    <span class="flex items-center">
                                                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-banknote'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-3.5 h-3.5 mr-1']); ?>
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
                                                        ₱<?php echo e(number_format($task->allocated_budget ?? 0, 2)); ?>

                                                    </span>
                                                    <span class="flex items-center">
                                                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-calendar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-3.5 h-3.5 mr-1']); ?>
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
                                                        <?php echo e($task->deadline ? $task->deadline->format('M d, Y') : 'No deadline'); ?>

                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <a href="<?php echo e(route('admin.tasks.show', $task->taskID)); ?>" 
                                           class="ml-4 text-primary-600 hover:text-primary-700 text-sm font-medium inline-flex items-center">
                                            View
                                            <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-arrow-right'); ?>
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
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-8">
                                <div class="bg-neutral-100 rounded-full h-16 w-16 flex items-center justify-center mx-auto mb-4">
                                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-clipboard-list'); ?>
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
                                <p class="text-neutral-400 text-sm mt-1">There are no tasks associated with this project yet</p>
                                <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['variant' => 'primary','href' => ''.e(route('admin.tasks.create', ['project_id' => $project->id])).'','class' => 'mt-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'primary','href' => ''.e(route('admin.tasks.create', ['project_id' => $project->id])).'','class' => 'mt-4']); ?>
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
                                    Create First Task
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

            <!-- Right Column: Client Info, Related Service Request, Team -->
            <div class="lg:col-span-1">
                <!-- Client Information Card -->
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
                    <!-- Header -->
                    <div class="px-4 py-3 border-b border-neutral-100 flex items-center gap-2">
                        <div class="w-7 h-7 bg-primary-50 rounded-lg flex items-center justify-center">
                            <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-user'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-4 h-4 text-primary-500']); ?>
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
                        <h2 class="text-sm font-semibold text-neutral-800">Client Information</h2>
                    </div>
                    
                    <!-- Content -->
                    <div class="p-3">
                        <div class="p-3 bg-neutral-50 rounded-xl">
                            <!-- Client Profile -->
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 bg-primary-500 rounded-full flex items-center justify-center text-white text-sm font-semibold flex-shrink-0 shadow-sm">
                                    <?php echo e(substr($project->client->fullName, 0, 1)); ?>

                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-medium text-neutral-900 text-sm truncate"><?php echo e($project->client->fullName); ?></h3>
                                    <p class="text-xs text-neutral-500 truncate"><?php echo e($project->client->email); ?></p>
                                </div>
                            </div>
                            
                            <!-- Contact Details -->
                            <?php if($project->client->phone): ?>
                            <div class="py-2 px-2.5 bg-white rounded-lg border border-neutral-200/60 mb-3">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 bg-neutral-100 rounded flex items-center justify-center flex-shrink-0">
                                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-phone'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-3 h-3 text-neutral-500']); ?>
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
                                    <div class="flex-1 min-w-0">
                                        <p class="text-[10px] text-neutral-400 uppercase tracking-wide">Phone</p>
                                        <p class="text-sm text-neutral-900 truncate"><?php echo e($project->client->phone); ?></p>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <!-- View Profile Link -->
                            <a href="<?php echo e(route('admin.clients.show', $project->client->id)); ?>" 
                            class="flex items-center justify-center gap-1.5 w-full py-2 text-xs font-medium text-primary-600 hover:text-primary-700 hover:bg-primary-50 rounded-lg transition-colors">
                                <span>View Client Profile</span>
                                <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-arrow-right'); ?>
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
                            </a>
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


                <!-- Related Service Request -->
                <?php if($project->serviceRequest): ?>
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
                    <!-- Header -->
                    <div class="px-4 py-3 border-b border-neutral-100 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 bg-secondary-50 rounded-lg flex items-center justify-center">
                                <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-file-text'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-4 h-4 text-secondary-500']); ?>
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
                            <h2 class="text-sm font-semibold text-neutral-800">Service Request</h2>
                        </div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-primary-100 text-primary-700">
                            #<?php echo e($project->serviceRequest->id); ?>

                        </span>
                    </div>
                    
                    <!-- Content -->
                    <div class="p-3">
                        <div class="p-3 bg-neutral-50 rounded-xl">
                            <!-- Request Details -->
                            <div class="space-y-2 mb-3">
                                <!-- Service Type -->
                                <div class="py-2 px-2.5 bg-white rounded-lg border border-neutral-200/60">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 bg-accent-100 rounded flex items-center justify-center flex-shrink-0">
                                            <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-concierge-bell'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-3 h-3 text-accent-600']); ?>
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
                                        <div class="flex-1 min-w-0">
                                            <p class="text-[10px] text-neutral-400 uppercase tracking-wide">Service Type</p>
                                            <p class="text-sm text-neutral-900 truncate"><?php echo e($project->serviceRequest->service_type ?? 'N/A'); ?></p>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Submitted Date -->
                                <div class="py-2 px-2.5 bg-white rounded-lg border border-neutral-200/60">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 bg-neutral-100 rounded flex items-center justify-center flex-shrink-0">
                                            <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-calendar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-3 h-3 text-neutral-500']); ?>
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
                                        <div class="flex-1 min-w-0">
                                            <p class="text-[10px] text-neutral-400 uppercase tracking-wide">Submitted</p>
                                            <p class="text-sm text-neutral-900"><?php echo e($project->serviceRequest->created_at->format('M d, Y')); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- View Details Link -->
                            <a href="<?php echo e(route('admin.requests.show', $project->serviceRequest->id)); ?>" 
                            class="flex items-center justify-center gap-1.5 w-full py-2 text-xs font-medium text-primary-600 hover:text-primary-700 hover:bg-primary-50 rounded-lg transition-colors">
                                <span>View Request Details</span>
                                <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-arrow-right'); ?>
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
                            </a>
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

                <!-- Assigned Team -->
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
                    <!-- Header -->
                    <div class="px-4 py-3 border-b border-neutral-100 flex justify-between items-center">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 bg-primary-50 rounded-lg flex items-center justify-center">
                                <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-users'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-4 h-4 text-primary-500']); ?>
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
                            <h2 class="text-sm font-semibold text-neutral-800">Assigned Team</h2>
                            <?php if($project->adiutors->count() > 0): ?>
                                <span class="bg-neutral-100 text-neutral-600 text-xs font-medium px-1.5 py-0.5 rounded-full">
                                    <?php echo e($project->adiutors->count()); ?>

                                </span>
                            <?php endif; ?>
                        </div>
                        <button onclick="showAssignModal()"
                                type="button"
                                class="inline-flex items-center justify-center w-7 h-7 bg-primary-500 hover:bg-primary-600 text-white rounded-lg text-xs transition-colors"
                                title="Assign Adiutor">
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
                        </button>
                    </div>
                    
                    <!-- Team List -->
                    <div class="p-3">
                        <?php if($project->adiutors->count() > 0): ?>
                            <div class="space-y-2">
                                <?php $__currentLoopData = $project->adiutors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $adiutor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="group p-3 bg-neutral-50 hover:bg-neutral-100/80 rounded-xl transition-colors">
                                    <!-- Member Info Row -->
                                    <div class="flex items-center gap-3 mb-3">
                                        <div class="w-9 h-9 bg-primary-500 rounded-full flex items-center justify-center text-white text-sm font-semibold flex-shrink-0 shadow-sm">
                                            <?php echo e(substr($adiutor->fullName, 0, 1)); ?>

                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-medium text-neutral-900 text-sm truncate"><?php echo e($adiutor->fullName); ?></p>
                                            <p class="text-xs text-neutral-500 truncate"><?php echo e($adiutor->email); ?></p>
                                        </div>
                                    </div>
                                    
                                    <!-- Payment Details -->
                                    <div class="space-y-2">
                                        <?php if($adiutor->pivot->payment_type === 'fixed_rate'): ?>
                                            <!-- Fixed Rate Info -->
                                            <div class="flex items-center justify-between py-2 px-2.5 bg-white rounded-lg border border-neutral-200/60">
                                                <div class="flex items-center gap-2">
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-700">
                                                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-file-text'); ?>
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
                                                        Fixed
                                                    </span>
                                                    <span class="text-sm font-semibold text-neutral-900">₱<?php echo e(number_format($adiutor->pivot->agreed_rate, 2)); ?></span>
                                                </div>
                                            </div>
                                            
                                            <!-- Approval Status -->
                                            <div class="flex items-center justify-between py-2 px-2.5 bg-white rounded-lg border border-neutral-200/60">
                                                <?php if($adiutor->pivot->fixed_rate_paid): ?>
                                                    <span class="inline-flex items-center text-xs font-medium text-success-700">
                                                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-check-circle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-3.5 h-3.5 mr-1.5 text-success-500']); ?>
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
                                                        Payment Complete
                                                    </span>
                                                <?php elseif($adiutor->pivot->fixed_rate_approved): ?>
                                                    <span class="inline-flex items-center text-xs font-medium text-success-700">
                                                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-check'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-3.5 h-3.5 mr-1.5']); ?>
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
                                                        Approved
                                                    </span>
                                                    <form action="<?php echo e(route('admin.projects.assignments.revoke-fixed-rate', [$project->id, $adiutor->pivot->id])); ?>" 
                                                        method="POST" class="inline">
                                                        <?php echo csrf_field(); ?>
                                                        <button type="submit"
                                                                onclick="return confirm('Revoke approval? This will prevent payout.');"
                                                                class="text-xs text-warning-600 hover:text-warning-700 font-medium">
                                                            Revoke
                                                        </button>
                                                    </form>
                                                <?php else: ?>
                                                    <span class="text-xs text-neutral-500">Pending Approval</span>
                                                    <form action="<?php echo e(route('admin.projects.assignments.approve-fixed-rate', [$project->id, $adiutor->pivot->id])); ?>" 
                                                        method="POST" class="inline">
                                                        <?php echo csrf_field(); ?>
                                                        <button type="submit"
                                                                class="inline-flex items-center px-2.5 py-1 bg-success-500 hover:bg-success-600 text-white rounded text-xs font-medium transition-colors">
                                                            <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-check'); ?>
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
                                                            Approve
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                            </div>
                                        <?php else: ?>
                                            <!-- Hourly Rate Info -->
                                            <div class="py-2 px-2.5 bg-white rounded-lg border border-neutral-200/60">
                                                <div class="flex items-center justify-between mb-2">
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-700">
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
                                                        Hourly
                                                    </span>
                                                    <span class="text-sm font-semibold text-neutral-900">₱<?php echo e(number_format($adiutor->pivot->hourly_rate ?? $adiutor->pivot->agreed_rate ?? $adiutor->hourlyRate ?? 0, 2)); ?><span class="text-xs font-normal text-neutral-500">/hr</span></span>
                                                </div>
                                                
                                                <!-- Hours Progress -->
                                                <?php if($adiutor->pivot->max_hours): ?>
                                                    <?php
                                                        $maxHours = (float) $adiutor->pivot->max_hours;
                                                        $loggedHours = (float) ($adiutor->pivot->total_billable_hours ?? 0);
                                                        $remainingHours = max(0, $maxHours - $loggedHours);
                                                        $percentage = $maxHours > 0 ? min(100, round(($loggedHours / $maxHours) * 100)) : 0;
                                                        
                                                        $statusColor = 'success';
                                                        if ($percentage >= 100) {
                                                            $statusColor = 'error';
                                                        } elseif ($percentage >= 80) {
                                                            $statusColor = 'warning';
                                                        }
                                                    ?>
                                                    <div class="pt-2 border-t border-neutral-100">
                                                        <div class="flex items-center justify-between text-xs mb-1.5">
                                                            <span class="text-neutral-600">Hours Used</span>
                                                            <span class="font-medium text-<?php echo e($statusColor); ?>-600">
                                                                <?php echo e(number_format($loggedHours, 1)); ?> / <?php echo e(number_format($maxHours, 1)); ?>

                                                            </span>
                                                        </div>
                                                        <div class="w-full bg-neutral-200 rounded-full h-1.5 overflow-hidden">
                                                            <div class="h-full rounded-full bg-<?php echo e($statusColor); ?>-500 transition-all duration-300" 
                                                                style="width: <?php echo e($percentage); ?>%"></div>
                                                        </div>
                                                        <div class="flex items-center justify-between mt-1.5">
                                                            <?php if($remainingHours > 0): ?>
                                                                <span class="text-[10px] text-neutral-500"><?php echo e(number_format($remainingHours, 1)); ?> hrs left</span>
                                                            <?php else: ?>
                                                                <span class="text-[10px] text-error-600 font-medium flex items-center">
                                                                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-ban'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-3 h-3 mr-0.5']); ?>
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
                                                                    Max reached
                                                                </span>
                                                            <?php endif; ?>
                                                            <span class="text-[10px] font-medium text-<?php echo e($statusColor); ?>-600"><?php echo e($percentage); ?>%</span>
                                                        </div>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="pt-2 border-t border-neutral-100">
                                                        <span class="inline-flex items-center text-[10px] text-neutral-400">
                                                            <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-infinity'); ?>
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
                                                            Unlimited hours
                                                        </span>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <!-- Remove Button -->
                                    <div class="mt-3 pt-2 border-t border-neutral-200/60">
                                        <form action="<?php echo e(route('admin.projects.remove-adiutor', [$project->id, $adiutor->id])); ?>" 
                                            method="POST"
                                            onsubmit="return confirm('Are you sure you want to remove this adiutor from the project?');">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit"
                                                    class="w-full flex items-center justify-center gap-1.5 py-1.5 text-xs font-medium text-neutral-500 hover:text-error-600 hover:bg-error-50 rounded-lg transition-colors">
                                                <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-user-minus'); ?>
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
                                                Remove from Project
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php else: ?>
                            <!-- Empty State -->
                            <div class="text-center py-8 px-4">
                                <div class="w-12 h-12 bg-neutral-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-user-plus'); ?>
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
                                <p class="text-sm font-medium text-neutral-600 mb-1">No team members</p>
                                <p class="text-xs text-neutral-400 mb-4">Assign adiutors to this project</p>
                                <button onclick="showAssignModal()"
                                        type="button"
                                        class="inline-flex items-center px-3 py-1.5 bg-primary-500 hover:bg-primary-600 text-white rounded-lg text-xs font-medium transition-colors">
                                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-plus'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-3.5 h-3.5 mr-1.5']); ?>
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
                                    Assign Adiutor
                                </button>
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

    <!-- Complete Project Modal -->
    <div id="completeModal" class="modal-overlay fixed inset-0 z-50 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div onclick="hideCompleteModal()" class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity z-40"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <div class="modal-content inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative z-50">
                
                <form action="<?php echo e(route('admin.projects.complete', $project->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PATCH'); ?>
                    
                    <div class="bg-neutral-50 border-b border-neutral-100 px-6 py-4 flex justify-between items-center">
                        <h5 class="text-lg font-semibold text-neutral-800">Complete Project</h5>
                        <button type="button" onclick="hideCompleteModal()" class="text-neutral-500 hover:text-neutral-700 focus:outline-none">
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
                        <?php
                            $incompleteTasks = $project->tasks->whereNotIn('status', ['completed', 'cancelled']);
                            $completedTasks = $project->tasks->where('status', 'completed');
                            $hasIncompleteTasks = $incompleteTasks->count() > 0;
                        ?>

                        <?php if($hasIncompleteTasks): ?>
                        <!-- Warning: Incomplete Tasks -->
                        <div class="mb-4 p-4 bg-warning-50 border border-warning-200 rounded-xl">
                            <div class="flex items-start">
                                <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-alert-triangle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-5 h-5 text-warning-600 mt-0.5 mr-3 flex-shrink-0']); ?>
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
                                <div>
                                    <h4 class="text-sm font-semibold text-warning-800 mb-1">Incomplete Tasks Detected</h4>
                                    <p class="text-xs text-warning-700 mb-2">
                                        <?php echo e($incompleteTasks->count()); ?> task(s) are not yet completed:
                                    </p>
                                    <ul class="text-xs text-warning-700 list-disc list-inside space-y-0.5 max-h-24 overflow-y-auto">
                                        <?php $__currentLoopData = $incompleteTasks->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li><?php echo e($task->taskTitle); ?> <span class="text-warning-500">(<?php echo e(ucfirst($task->status)); ?>)</span></li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($incompleteTasks->count() > 5): ?>
                                            <li class="text-warning-500">...and <?php echo e($incompleteTasks->count() - 5); ?> more</li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <?php else: ?>
                        <!-- Success: All Tasks Complete -->
                        <div class="flex items-start mb-4">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-success-100 flex items-center justify-center mr-3">
                                <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-check-circle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-6 h-6 text-success-600']); ?>
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
                                <p class="text-sm text-neutral-600 mb-1">
                                    All <?php echo e($completedTasks->count()); ?> task(s) are completed. Ready to mark project as complete.
                                </p>
                                <p class="text-xs text-neutral-500">
                                    This will notify the client about the project completion.
                                </p>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Task Summary -->
                        <div class="mb-4 p-3 bg-neutral-50 rounded-lg">
                            <div class="flex justify-between text-xs text-neutral-600">
                                <span>Completed: <strong class="text-success-600"><?php echo e($completedTasks->count()); ?></strong></span>
                                <span>Pending: <strong class="text-warning-600"><?php echo e($incompleteTasks->count()); ?></strong></span>
                                <span>Total: <strong><?php echo e($project->tasks->count()); ?></strong></span>
                            </div>
                        </div>

                        <?php if($hasIncompleteTasks): ?>
                        <!-- Force Complete Option -->
                        <div class="mb-4 p-3 bg-error-50 border border-error-100 rounded-lg">
                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="checkbox" name="force_complete" value="1" 
                                       class="mt-0.5 w-4 h-4 text-error-600 border-error-300 rounded focus:ring-error-500"
                                       onchange="toggleCompleteButton(this)">
                                <div>
                                    <span class="text-sm font-medium text-error-800">Force Complete</span>
                                    <p class="text-xs text-error-600 mt-0.5">
                                        Complete this project even with incomplete tasks. Use with caution.
                                    </p>
                                </div>
                            </label>
                        </div>
                        <?php endif; ?>
                        
                        <div class="mb-5">
                            <label class="block text-sm font-medium text-neutral-700 mb-2">Completion Notes (Optional)</label>
                            <textarea name="completion_notes" 
                                      rows="3" 
                                      class="w-full rounded-xl border border-neutral-200 px-4 py-2.5 text-neutral-800 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-colors"
                                      placeholder="Add any final notes about the project completion..."></textarea>
                        </div>
                    </div>
                    
                    <div class="bg-neutral-50 border-t border-neutral-100 px-6 py-4 flex justify-end gap-3">
                        <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['type' => 'button','variant' => 'secondary','onclick' => 'hideCompleteModal()']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','variant' => 'secondary','onclick' => 'hideCompleteModal()']); ?>
                            Cancel
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['type' => 'submit','variant' => 'success','id' => 'completeProjectBtn','disabled' => $hasIncompleteTasks]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit','variant' => 'success','id' => 'completeProjectBtn','disabled' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($hasIncompleteTasks)]); ?>
                            <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-check-circle'); ?>
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
                            Complete Project
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

    <!-- Assign Adiutor Modal -->
    <div id="assignModal" class="modal-overlay fixed inset-0 z-50 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div onclick="hideAssignModal()" class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity z-40"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <div class="modal-content inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-5xl sm:w-full relative z-50">
                
                <form action="<?php echo e(route('admin.projects.assign-adiutor', $project->id)); ?>" method="POST" id="assignAdiutorForm">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="adiutor_id" id="adiutor_id" required>
                    
                    <div class="bg-neutral-50 border-b border-neutral-100 px-6 py-4 flex justify-between items-center">
                        <h5 class="text-lg font-semibold text-neutral-800">Assign Adiutor to Project</h5>
                        <button type="button" onclick="hideAssignModal()" class="text-neutral-500 hover:text-neutral-700 focus:outline-none">
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
                    
                    <div class="p-6 space-y-4">
                        <div>
                            <h3 class="text-sm font-medium text-neutral-700 mb-4">Select Adiutor <span class="text-error-500">*</span></h3>
                            
                            <!-- Adiutors Table -->
                            <div class="overflow-x-auto border border-neutral-200 rounded-lg">
                                <table class="min-w-full divide-y divide-neutral-200">
                                    <thead class="bg-neutral-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Select</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Adiutor Name</th>
                                            <th class="px-4 py-3 text-center text-xs font-medium text-neutral-500 uppercase tracking-wider">Calendar</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Skills</th>
                                            <th class="px-4 py-3 text-center text-xs font-medium text-neutral-500 uppercase tracking-wider">Workload</th>
                                            <th class="px-4 py-3 text-center text-xs font-medium text-neutral-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-neutral-200">
                                        <?php $__empty_1 = true; $__currentLoopData = $availableAdiutors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $adiutor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr class="hover:bg-neutral-50 cursor-pointer adiutor-row" 
                                            data-adiutor-id="<?php echo e($adiutor['id']); ?>"
                                            data-adiutor-name="<?php echo e($adiutor['fullName']); ?>"
                                            onclick="selectAdiutor(<?php echo e($adiutor['id']); ?>, '<?php echo e($adiutor['fullName']); ?>', <?php echo e($adiutor['rating'] ?? 0); ?>)">
                                            <td class="px-4 py-4 whitespace-nowrap text-center">
                                                <input type="radio" 
                                                       name="adiutor_radio" 
                                                       value="<?php echo e($adiutor['id']); ?>"
                                                       class="w-4 h-4 text-primary-600 focus:ring-primary-500 border-neutral-300">
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10 bg-primary-600 rounded-full flex items-center justify-center text-white font-bold mr-3">
                                                        <?php echo e(substr($adiutor['fullName'], 0, 1)); ?>

                                                    </div>
                                                    <div>
                                                        <div class="text-sm font-medium text-neutral-900"><?php echo e($adiutor['fullName']); ?></div>
                                                        <div class="text-xs text-neutral-500 flex items-center gap-0.5">
                                                            <?php for($i = 0; $i < 5; $i++): ?>
                                                                <?php if($i < floor($adiutor['rating'])): ?>
                                                                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-star'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-3 h-3 text-yellow-400 fill-yellow-400']); ?>
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
                                                                <?php elseif($i < $adiutor['rating']): ?>
                                                                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-star-half'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-3 h-3 text-yellow-400 fill-yellow-400']); ?>
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
                                                                <?php else: ?>
                                                                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-star'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-3 h-3 text-neutral-300']); ?>
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
                                                                <?php endif; ?>
                                                            <?php endfor; ?>
                                                            <span class="ml-1"><?php echo e(number_format($adiutor['rating'], 1)); ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-center">
                                                <?php if($adiutor['calendar_connected']): ?>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success-100 text-success-800">
                                                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-check-circle'); ?>
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
                                                        Connected
                                                    </span>
                                                <?php else: ?>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-neutral-100 text-neutral-600">
                                                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-x-circle'); ?>
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
                                                        Not Connected
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-4 py-4">
                                                <div class="flex flex-wrap gap-1">
                                                    <?php if($adiutor['skills'] && $adiutor['skills']->count() > 0): ?>
                                                        <?php $__currentLoopData = $adiutor['skills']->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-primary-100 text-primary-800">
                                                                <?php echo e($skill->name); ?>

                                                            </span>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        <?php if($adiutor['skills']->count() > 3): ?>
                                                            <?php
                                                                $remainingSkills = $adiutor['skills']->slice(3)->pluck('name')->implode(', ');
                                                            ?>
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-neutral-100 text-neutral-600 cursor-help relative group"
                                                                  title="<?php echo e($remainingSkills); ?>">
                                                                +<?php echo e($adiutor['skills']->count() - 3); ?> more
                                                                <!-- Tooltip -->
                                                                <span class="invisible group-hover:visible absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 bg-neutral-800 text-white text-xs rounded-lg shadow-lg whitespace-nowrap z-50 max-w-xs">
                                                                    <?php echo e($remainingSkills); ?>

                                                                    <span class="absolute top-full left-1/2 transform -translate-x-1/2 -mt-1 border-4 border-transparent border-t-neutral-800"></span>
                                                                </span>
                                                            </span>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <span class="text-xs text-neutral-400 italic">No skills listed</span>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-center">
                                                <?php
                                                    $projectCount = $adiutor['active_projects_count'];
                                                    if ($projectCount == 0) {
                                                        $workloadClass = 'bg-success-100 text-success-800';
                                                    } elseif ($projectCount <= 3) {
                                                        $workloadClass = 'bg-warning-100 text-warning-800';
                                                    } else {
                                                        $workloadClass = 'bg-error-100 text-error-800';
                                                    }
                                                ?>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo e($workloadClass); ?>">
                                                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-circle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-2 h-2 mr-1.5 fill-current']); ?>
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
                                                    <?php echo e($projectCount); ?> <?php echo e(Str::plural('project', $projectCount)); ?>

                                                </span>
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-center">
                                                <button type="button" 
                                                        onclick="event.stopPropagation(); viewAdiutorSchedule(<?php echo e($adiutor['id']); ?>, '<?php echo e(addslashes($adiutor['fullName'])); ?>')"
                                                        class="inline-flex items-center px-3 py-1.5 border border-primary-300 rounded-lg text-xs font-medium text-primary-700 bg-white hover:bg-primary-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                                                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-calendar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-3.5 h-3.5 mr-1.5']); ?>
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
                                                    View Schedule
                                                </button>
                                            </td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="6" class="px-4 py-8 text-center text-neutral-500">
                                                <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-users'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-8 h-8 mx-auto mb-2']); ?>
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
                                                <p>No adiutors available</p>
                                            </td>
                                        </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <div id="selected_adiutor_info" class="mt-4 hidden">
                                <div class="bg-primary-50 border border-primary-200 rounded-xl p-4">
                                    <p class="text-sm text-primary-800 flex items-center">
                                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-check-circle'); ?>
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
                                        <strong>Selected:</strong> <span id="selected_name" class="ml-1"></span>
                                        <span id="selected_rating" class="ml-2"></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Payment Type Selection (Phase 1) -->
                        <div class="bg-neutral-50 border border-neutral-200 rounded-xl p-4">
                            <h3 class="text-sm font-semibold text-neutral-800 mb-3 flex items-center">
                                <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-banknote'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-4 h-4 mr-2 text-primary-600']); ?>
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
                                Payment Method <span class="text-error-500 ml-1">*</span>
                            </h3>
                            <p class="text-xs text-neutral-500 mb-4">Choose how this adiutor will be paid for this project. Each adiutor can only use ONE payment method.</p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Fixed Rate Option -->
                                <label class="payment-type-option relative flex cursor-pointer rounded-lg border border-neutral-300 bg-white p-4 shadow-sm hover:border-primary-400 focus:outline-none transition-all"
                                       id="payment_type_fixed_label">
                                    <input type="radio" name="payment_type" value="fixed_rate" 
                                           class="sr-only" id="payment_type_fixed"
                                           onchange="togglePaymentType('fixed_rate')">
                                    <span class="flex flex-1">
                                        <span class="flex flex-col">
                                            <span class="block text-sm font-medium text-neutral-900">
                                                Fixed Rate
                                            </span>
                                            <span class="mt-1 flex items-center text-xs text-neutral-500">
                                                One-time payment when work completes
                                            </span>
                                            <span class="mt-2 text-xs text-neutral-400">
                                                No time tracking required. Tasks are for organization only.
                                            </span>
                                        </span>
                                    </span>
                                    <span class="pointer-events-none absolute -inset-px rounded-lg border-2 border-transparent payment-type-border" aria-hidden="true"></span>
                                </label>
                                
                                <!-- Hourly Rate Option -->
                                <label class="payment-type-option relative flex cursor-pointer rounded-lg border border-neutral-300 bg-white p-4 shadow-sm hover:border-primary-400 focus:outline-none transition-all"
                                       id="payment_type_hourly_label">
                                    <input type="radio" name="payment_type" value="hourly_rate" 
                                           class="sr-only" id="payment_type_hourly" checked
                                           onchange="togglePaymentType('hourly_rate')">
                                    <span class="flex flex-1">
                                        <span class="flex flex-col">
                                            <span class="block text-sm font-medium text-neutral-900">
                                                Hourly Rate
                                            </span>
                                            <span class="mt-1 flex items-center text-xs text-neutral-500">
                                                Multiple payments based on time logged
                                            </span>
                                            <span class="mt-2 text-xs text-neutral-400">
                                                Time tracking required. Billed per task.
                                            </span>
                                        </span>
                                    </span>
                                    <span class="pointer-events-none absolute -inset-px rounded-lg border-2 border-primary-500 payment-type-border" aria-hidden="true"></span>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Fixed Rate Fields (shown when Fixed Rate is selected) -->
                        <div id="fixed_rate_container" class="hidden">
                            <label class="block text-sm font-medium text-neutral-700 mb-2">
                                Agreed Fixed Rate (₱) <span class="text-error-500">*</span>
                            </label>
                            <input type="number" name="agreed_rate" id="agreed_rate_input" step="0.01" min="0"
                                   class="w-full rounded-xl border border-neutral-200 px-4 py-2.5 text-neutral-800 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-colors"
                                   placeholder="e.g., 50000.00">
                            <p class="text-xs text-neutral-500 mt-1 flex items-center">
                                <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-info'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-3 h-3 mr-1 flex-shrink-0']); ?>
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
                                Total payment for this adiutor's complete work on this project. Paid when admin approves completion.
                            </p>
                        </div>
                        
                        <!-- Hourly Rate Fields (shown when Hourly Rate is selected) -->
                        <div id="hourly_rate_container">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-neutral-700 mb-2">
                                    Hourly Rate (₱)
                                </label>
                                <input type="number" name="hourly_rate" id="hourly_rate_input" step="0.01" min="0"
                                       class="w-full rounded-xl border border-neutral-200 px-4 py-2.5 text-neutral-800 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-colors"
                                       placeholder="Leave empty to use adiutor's standard rate">
                                <p class="text-xs text-neutral-500 mt-1">
                                    <span id="standard_rate_display" class="font-medium text-primary-600"></span>
                                    Override the adiutor's standard rate for this project
                                </p>
                                <div id="hourly_rate_warning" class="hidden mt-2 p-2 bg-warning-50 border border-warning-200 rounded-xl">
                                    <p class="text-xs text-warning-700 flex items-start">
                                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-alert-triangle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-3.5 h-3.5 mr-2 mt-0.5 flex-shrink-0']); ?>
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
                                        <span>Warning: The overridden hourly rate is below the adiutor's standard rate.</span>
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Max Hours Limit (Phase 3) -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-neutral-700 mb-2">
                                    Maximum Billable Hours <span class="text-neutral-400 text-xs">(Optional)</span>
                                </label>
                                <input type="number" name="max_hours" id="max_hours_input" step="0.5" min="0.5"
                                       class="w-full rounded-xl border border-neutral-200 px-4 py-2.5 text-neutral-800 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-colors"
                                       placeholder="No limit">
                                <p class="text-xs text-neutral-500 mt-1">
                                    Set a cap on billable hours. Leave empty for unlimited.
                                </p>
                                <div id="max_hours_estimate" class="hidden mt-2 p-2 bg-primary-50 border border-primary-200 rounded-xl">
                                    <p class="text-xs text-primary-700 flex items-start">
                                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-calculator'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-3.5 h-3.5 mr-2 mt-0.5 flex-shrink-0']); ?>
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
                                        <span>Estimated max cost: <strong id="max_hours_cost">₱0.00</strong></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 mb-2">
                                Expected Completion Date
                            </label>
                            <input type="date" name="expected_completion"
                                   class="w-full rounded-xl border border-neutral-200 px-4 py-2.5 text-neutral-800 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-colors">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 mb-2">
                                Notes (Optional)
                            </label>
                            <textarea name="notes" rows="3"
                                      class="w-full rounded-xl border border-neutral-200 px-4 py-2.5 text-neutral-800 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-colors"
                                      placeholder="Additional notes about this assignment..."></textarea>
                        </div>
                    </div>
                    
                    <div class="bg-neutral-50 border-t border-neutral-100 px-6 py-4 flex justify-end gap-3">
                        <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['type' => 'button','variant' => 'secondary','onclick' => 'hideAssignModal()']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','variant' => 'secondary','onclick' => 'hideAssignModal()']); ?>
                            Cancel
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['type' => 'submit','variant' => 'primary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit','variant' => 'primary']); ?>
                            <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-user-plus'); ?>
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
                            Assign Adiutor
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

<!-- Schedule View Modal -->
<div id="scheduleModal" class="modal-overlay fixed inset-0 bg-neutral-900 bg-opacity-50 hidden flex items-center justify-center z-50" onclick="hideScheduleModal()">
    <div class="modal-content bg-white rounded-2xl shadow-xl max-w-6xl w-full mx-4 max-h-[90vh] overflow-hidden" onclick="event.stopPropagation()">
        <div class="sticky top-0 bg-white border-b border-neutral-100 px-6 py-4 flex items-center justify-between z-10">
            <div>
                <h2 class="text-xl font-bold text-neutral-900 flex items-center gap-2">
                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-calendar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-5 h-5 text-primary-600']); ?>
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
                    Schedule: <span id="schedule_adiutor_name" class="text-primary-600"></span>
                </h2>
                <p class="text-sm text-neutral-500 mt-1">
                    Week: <span id="schedule_week_range"></span>
                </p>
            </div>
            <button onclick="hideScheduleModal()" class="text-neutral-400 hover:text-neutral-600 transition-colors">
                <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-x'); ?>
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
            </button>
        </div>

        <div class="p-6">
            <!-- Week Navigation -->
            <div class="flex items-center justify-between mb-6">
                <button onclick="navigateWeek('prev')" class="inline-flex items-center px-4 py-2 bg-white border border-neutral-200 rounded-xl hover:bg-neutral-50 transition-colors">
                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-chevron-left'); ?>
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
                    Previous Week
                </button>
                <button onclick="navigateWeek('today')" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-colors">
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
                    Today
                </button>
                <button onclick="navigateWeek('next')" class="inline-flex items-center px-4 py-2 bg-white border border-neutral-200 rounded-xl hover:bg-neutral-50 transition-colors">
                    Next Week
                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-chevron-right'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-4 h-4 ml-2']); ?>
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

            <!-- Calendar Grid -->
            <div class="overflow-x-auto border border-neutral-200 rounded-xl">
                <table class="min-w-full divide-y divide-neutral-200">
                    <thead class="bg-neutral-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider w-20">Time</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-neutral-500 uppercase tracking-wider" id="day_0_header">Mon</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-neutral-500 uppercase tracking-wider" id="day_1_header">Tue</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-neutral-500 uppercase tracking-wider" id="day_2_header">Wed</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-neutral-500 uppercase tracking-wider" id="day_3_header">Thu</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-neutral-500 uppercase tracking-wider" id="day_4_header">Fri</th>
                        </tr>
                    </thead>
                    <tbody id="schedule_calendar_body" class="bg-white divide-y divide-neutral-200">
                        <!-- Time slots will be dynamically inserted here -->
                    </tbody>
                </table>
            </div>

            <!-- Summary -->
            <div class="mt-6 bg-neutral-50 border border-neutral-200 rounded-xl p-4">
                <div class="flex items-center justify-center gap-6 text-sm">
                    <div class="flex items-center">
                        <span class="w-4 h-4 bg-primary-100 border border-primary-300 rounded mr-2"></span>
                        <span class="text-neutral-600">CMS Task</span>
                    </div>
                    <div class="flex items-center">
                        <span class="w-4 h-4 bg-purple-100 border border-purple-300 rounded mr-2"></span>
                        <span class="text-neutral-600">Calendar Event</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="sticky bottom-0 bg-neutral-50 border-t border-neutral-100 px-6 py-4 flex justify-end gap-3">
            <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['variant' => 'secondary','onclick' => 'hideScheduleModal()']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'secondary','onclick' => 'hideScheduleModal()']); ?>
                Close
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
    </div>
</div>

<style>
/* Modal CSS */
.modal-overlay {
    transition: opacity 0.3s ease-in-out;
}

.modal-overlay.hidden {
    opacity: 0;
    visibility: hidden;
}

.modal-overlay:not(.hidden) {
    opacity: 1;
    visibility: visible;
}

.modal-content {
    transition: transform 0.3s ease-in-out, opacity 0.3s ease-in-out;
    transform: translateY(0) scale(1);
    opacity: 1;
}

.modal-overlay.hidden .modal-content {
    transform: translateY(4px) scale(0.95);
    opacity: 0;
}
</style>

<script>
// Global variable for standard rate validation
let adiutorStandardRate = 0;

// Toggle complete button when force_complete checkbox is changed
function toggleCompleteButton(checkbox) {
    const btn = document.getElementById('completeProjectBtn');
    if (checkbox.checked) {
        btn.disabled = false;
        btn.classList.remove('opacity-50', 'cursor-not-allowed');
    } else {
        btn.disabled = true;
        btn.classList.add('opacity-50', 'cursor-not-allowed');
    }
}

// Modal JavaScript Functions
function showCompleteModal() {
    const modal = document.getElementById('completeModal');
    modal.classList.remove('hidden');
    // Add smooth animation
    setTimeout(() => {
        modal.style.opacity = '1';
    }, 10);
}

function hideCompleteModal() {
    const modal = document.getElementById('completeModal');
    modal.classList.add('hidden');
}

function showAssignModal() {
    const modal = document.getElementById('assignModal');
    modal.classList.remove('hidden');
    
    // Auto-fill Expected Completion Date from project deadline
    <?php if($project->deadline): ?>
        const expectedCompletionInput = document.querySelector('input[name="expected_completion"]');
        if (expectedCompletionInput) {
            expectedCompletionInput.value = '<?php echo e($project->deadline->format('Y-m-d')); ?>';
        }
    <?php endif; ?>
    
    // Add smooth animation
    setTimeout(() => {
        modal.style.opacity = '1';
    }, 10);
}

function hideAssignModal() {
    const modal = document.getElementById('assignModal');
    modal.classList.add('hidden');
    // Reset form
    document.getElementById('assignAdiutorForm').reset();
    document.getElementById('adiutor_id').value = '';
    document.getElementById('selected_adiutor_info').classList.add('hidden');
    document.getElementById('hourly_rate_input').value = '';
    document.getElementById('standard_rate_display').textContent = '';
    
    // Uncheck all radio buttons
    document.querySelectorAll('input[name="adiutor_radio"]').forEach(radio => {
        radio.checked = false;
    });
    
    // Remove selected styling from rows
    document.querySelectorAll('.adiutor-row').forEach(row => {
        row.classList.remove('bg-primary-50', 'border-l-4', 'border-primary-500');
    });
    
    // Reset payment type to hourly (default)
    togglePaymentType('hourly_rate');
    document.getElementById('payment_type_hourly').checked = true;
}

// Toggle payment type fields
function togglePaymentType(type) {
    const fixedRateContainer = document.getElementById('fixed_rate_container');
    const hourlyRateContainer = document.getElementById('hourly_rate_container');
    const fixedLabel = document.getElementById('payment_type_fixed_label');
    const hourlyLabel = document.getElementById('payment_type_hourly_label');
    
    if (type === 'fixed_rate') {
        // Show fixed rate, hide hourly rate
        fixedRateContainer.classList.remove('hidden');
        hourlyRateContainer.classList.add('hidden');
        
        // Update border styling
        fixedLabel.querySelector('.payment-type-border').classList.add('border-primary-500');
        fixedLabel.querySelector('.payment-type-border').classList.remove('border-transparent');
        hourlyLabel.querySelector('.payment-type-border').classList.remove('border-primary-500');
        hourlyLabel.querySelector('.payment-type-border').classList.add('border-transparent');
        
        // Make agreed_rate required
        document.getElementById('agreed_rate_input').setAttribute('required', 'required');
        document.getElementById('hourly_rate_input').removeAttribute('required');
    } else {
        // Show hourly rate, hide fixed rate
        fixedRateContainer.classList.add('hidden');
        hourlyRateContainer.classList.remove('hidden');
        
        // Update border styling
        hourlyLabel.querySelector('.payment-type-border').classList.add('border-primary-500');
        hourlyLabel.querySelector('.payment-type-border').classList.remove('border-transparent');
        fixedLabel.querySelector('.payment-type-border').classList.remove('border-primary-500');
        fixedLabel.querySelector('.payment-type-border').classList.add('border-transparent');
        
        // Remove required from agreed_rate
        document.getElementById('agreed_rate_input').removeAttribute('required');
    }
    
    // Reset max hours
    document.getElementById('max_hours_input').value = '';
    updateMaxHoursEstimate();
}

// Update max hours cost estimate
function updateMaxHoursEstimate() {
    const maxHoursInput = document.getElementById('max_hours_input');
    const hourlyRateInput = document.getElementById('hourly_rate_input');
    const estimateDiv = document.getElementById('max_hours_estimate');
    const costSpan = document.getElementById('max_hours_cost');
    
    const maxHours = parseFloat(maxHoursInput.value) || 0;
    const hourlyRate = parseFloat(hourlyRateInput.value) || 0;
    
    if (maxHours > 0 && hourlyRate > 0) {
        const maxCost = maxHours * hourlyRate;
        costSpan.textContent = '₱' + maxCost.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        estimateDiv.classList.remove('hidden');
    } else {
        estimateDiv.classList.add('hidden');
    }
}

// Add event listeners for max hours calculation
document.getElementById('max_hours_input').addEventListener('input', updateMaxHoursEstimate);
document.getElementById('hourly_rate_input').addEventListener('input', updateMaxHoursEstimate);

// Select adiutor from table
function selectAdiutor(adiutorId, adiutorName, rating) {
    // Update hidden input
    document.getElementById('adiutor_id').value = adiutorId;
    
    // Update selected info display
    document.getElementById('selected_name').textContent = adiutorName;
    
    // Build rating stars
    let ratingHtml = '';
    for (let i = 0; i < 5; i++) {
        if (i < Math.floor(rating)) {
            ratingHtml += '<i class="fas fa-star text-yellow-400"></i>';
        } else if (i < rating) {
            ratingHtml += '<i class="fas fa-star-half-alt text-yellow-400"></i>';
        } else {
            ratingHtml += '<i class="far fa-star text-neutral-300"></i>';
        }
    }
    ratingHtml += ` <span class="ml-1">${rating.toFixed(1)}</span>`;
    document.getElementById('selected_rating').innerHTML = ratingHtml;
    
    // Show selected info
    document.getElementById('selected_adiutor_info').classList.remove('hidden');
    
    // Remove previous selection styling
    document.querySelectorAll('.adiutor-row').forEach(row => {
        row.classList.remove('bg-primary-50', 'border-l-4', 'border-primary-500');
    });
    
    // Add selection styling to current row
    const selectedRow = document.querySelector(`.adiutor-row[data-adiutor-id="${adiutorId}"]`);
    if (selectedRow) {
        selectedRow.classList.add('bg-primary-50', 'border-l-4', 'border-primary-500');
    }
    
    // Check the radio button
    const radioButton = document.querySelector(`input[name="adiutor_radio"][value="${adiutorId}"]`);
    if (radioButton) {
        radioButton.checked = true;
    }
    
    // Fetch adiutor's standard rate
    fetch(`/api/adiutor/${adiutorId}/rate`)
        .then(response => response.json())
        .then(data => {
            const standardRateDisplay = document.getElementById('standard_rate_display');
            const hourlyRateInput = document.getElementById('hourly_rate_input');
            
            if (data.rate && data.rate > 0) {
                adiutorStandardRate = parseFloat(data.rate);
                standardRateDisplay.textContent = `Standard rate: ₱${parseFloat(data.rate).toFixed(2)}/hour. `;
                
                // Auto-fill if field is empty
                if (!hourlyRateInput.value) {
                    hourlyRateInput.value = data.rate;
                }
            } else {
                adiutorStandardRate = 0;
                standardRateDisplay.textContent = 'No standard rate set. ';
            }
        })
        .catch(error => {
            console.error('Error fetching rate:', error);
            adiutorStandardRate = 0;
            document.getElementById('standard_rate_display').textContent = '';
        });
}

// Old dropdown logic removed - no longer needed

// Schedule View Modal
let currentAdiutorId = null;
let currentWeekStart = null;

// View adiutor's schedule
function viewAdiutorSchedule(adiutorId, adiutorName) {
    currentAdiutorId = adiutorId;
    currentWeekStart = getStartOfWeek(new Date());
    
    // Set adiutor name
    document.getElementById('schedule_adiutor_name').textContent = adiutorName;
    
    // Show modal
    const modal = document.getElementById('scheduleModal');
    modal.classList.remove('hidden');
    
    // Load schedule
    loadSchedule();
}

function hideScheduleModal() {
    const modal = document.getElementById('scheduleModal');
    modal.classList.add('hidden');
    currentAdiutorId = null;
    currentWeekStart = null;
}

function navigateWeek(direction) {
    if (direction === 'today') {
        currentWeekStart = getStartOfWeek(new Date());
    } else if (direction === 'prev') {
        currentWeekStart.setDate(currentWeekStart.getDate() - 7);
    } else if (direction === 'next') {
        currentWeekStart.setDate(currentWeekStart.getDate() + 7);
    }
    
    loadSchedule();
}

function getStartOfWeek(date) {
    const d = new Date(date);
    const day = d.getDay();
    const diff = d.getDate() - day + (day === 0 ? -6 : 1); // Adjust when day is Sunday
    return new Date(d.setDate(diff));
}

function formatDate(date) {
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    return `${months[date.getMonth()]} ${date.getDate()}`;
}

function loadSchedule() {
    // Update week range display
    const weekEnd = new Date(currentWeekStart);
    weekEnd.setDate(weekEnd.getDate() + 4); // Friday
    document.getElementById('schedule_week_range').textContent = 
        `${formatDate(currentWeekStart)} - ${formatDate(weekEnd)}, ${currentWeekStart.getFullYear()}`;
    
    // Update day headers
    for (let i = 0; i < 5; i++) {
        const dayDate = new Date(currentWeekStart);
        dayDate.setDate(dayDate.getDate() + i);
        const dayNames = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'];
        document.getElementById(`day_${i}_header`).innerHTML = 
            `${dayNames[i]}<br><span class="text-neutral-400 font-normal">${dayDate.getMonth() + 1}/${dayDate.getDate()}</span>`;
    }
    
    // Fetch schedule data from server
    const startDate = currentWeekStart.toISOString().split('T')[0];
    fetch(`/api/schedule/adiutor/${currentAdiutorId}/timeline?start_date=${startDate}`)
        .then(response => response.json())
        .then(data => {
            renderSchedule(data);
        })
        .catch(error => {
            console.error('Error loading schedule:', error);
            // Render empty schedule as fallback
            renderSchedule({ slots: [], summary: { scheduled: 0, available: 0 } });
        });
}

function renderSchedule(data) {
    const tbody = document.getElementById('schedule_calendar_body');
    tbody.innerHTML = '';
    
    // Working hours: 8 AM to 6 PM
    const startHour = 8;
    const endHour = 18;
    
    for (let hour = startHour; hour < endHour; hour++) {
        const row = document.createElement('tr');
        
        // Time column
        const timeCell = document.createElement('td');
        timeCell.className = 'px-4 py-3 text-sm text-neutral-500 font-medium border-r border-neutral-200';
        timeCell.textContent = `${hour.toString().padStart(2, '0')}:00`;
        row.appendChild(timeCell);
        
        // Day columns (Mon-Fri)
        for (let day = 0; day < 5; day++) {
            const cell = document.createElement('td');
            cell.className = 'px-2 py-3 text-center text-xs border-r border-neutral-200 min-w-[120px]';
            
            // Find events for this time slot
            const dayDate = new Date(currentWeekStart);
            dayDate.setDate(dayDate.getDate() + day);
            const dateStr = dayDate.toISOString().split('T')[0];
            const timeStr = `${hour.toString().padStart(2, '0')}:00`;
            
            const events = (data.slots || []).filter(slot => 
                slot.date === dateStr && slot.hour === hour
            );
            
            if (events.length > 0) {
                events.forEach(event => {
                    const eventDiv = document.createElement('div');
                    if (event.type === 'task') {
                        eventDiv.className = 'bg-primary-100 border border-primary-300 text-primary-800 rounded px-2 py-1 mb-1 text-left';
                        eventDiv.innerHTML = `
                            <div class="font-medium">📋 ${event.title}</div>
                            <div class="text-xs">${event.duration}</div>
                        `;
                    } else if (event.type === 'calendar') {
                        eventDiv.className = 'bg-purple-100 border border-purple-300 text-purple-800 rounded px-2 py-1 mb-1 text-left';
                        eventDiv.innerHTML = `
                            <div class="font-medium">⚫ ${event.title}</div>
                            <div class="text-xs">${event.duration}</div>
                        `;
                    }
                    cell.appendChild(eventDiv);
                });
            } else {
                // Empty slot - check if it's lunch time
                if (hour === 12) {
                    cell.innerHTML = '<span class="text-neutral-400 italic">Lunch</span>';
                    cell.className += ' bg-neutral-50';
                }
            }
            
            row.appendChild(cell);
        }
        
        tbody.appendChild(row);
    }
}

// Close modal when pressing Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        hideCompleteModal();
        hideAssignModal();
        hideScheduleModal();
    }
});

// Prevent modal from closing when clicking inside the modal content
document.addEventListener('DOMContentLoaded', function() {
    const modalContents = document.querySelectorAll('.modal-content');
    modalContents.forEach(function(content) {
        content.addEventListener('click', function(event) {
            event.stopPropagation();
        });
    });
    
    // Toggle between Hourly Rate and Agreed Rate based on time tracking checkbox
    const timeTrackingCheckbox = document.querySelector('input[name="requires_time_tracking"]');
    const hourlyRateDiv = document.getElementById('hourly_rate_container');
    const agreedRateDiv = document.getElementById('agreed_rate_container');
    
    if (timeTrackingCheckbox && hourlyRateDiv && agreedRateDiv) {
        function toggleRateFields() {
            if (timeTrackingCheckbox.checked) {
                // Show Hourly Rate, hide Agreed Rate
                hourlyRateDiv.style.display = 'block';
                agreedRateDiv.style.display = 'none';
            } else {
                // Hide Hourly Rate, show Agreed Rate
                hourlyRateDiv.style.display = 'none';
                agreedRateDiv.style.display = 'block';
            }
        }
        
        // Initial state
        toggleRateFields();
        
        // Listen for changes
        timeTrackingCheckbox.addEventListener('change', toggleRateFields);
    }
    
    // Validate hourly rate against standard rate
    const hourlyRateInput = document.getElementById('hourly_rate_input');
    const hourlyRateWarning = document.getElementById('hourly_rate_warning');
    
    if (hourlyRateInput && hourlyRateWarning) {
        hourlyRateInput.addEventListener('input', function() {
            const overriddenRate = parseFloat(this.value);
            
            // Only show warning if a value was entered and it's below standard rate
            if (this.value && overriddenRate && adiutorStandardRate && overriddenRate < adiutorStandardRate) {
                hourlyRateWarning.classList.remove('hidden');
            } else {
                hourlyRateWarning.classList.add('hidden');
            }
        });
    }
});

// ============================================
// Task Reordering with Drag & Drop
// ============================================

// Load SortableJS from CDN
const sortableScript = document.createElement('script');
sortableScript.src = 'https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js';
sortableScript.onload = initializeSortable;
document.head.appendChild(sortableScript);

let sortableInstance = null;
let isReorderMode = false;

function initializeSortable() {
    const taskList = document.getElementById('sortable-tasks');
    const toggleBtn = document.getElementById('toggle-reorder-mode');
    const saveBtn = document.getElementById('save-task-order');
    const cancelBtn = document.getElementById('cancel-reorder');
    const reorderControls = document.getElementById('reorder-controls');
    
    if (!taskList || !toggleBtn) return;
    
    // Initialize Sortable but keep it disabled initially
    sortableInstance = new Sortable(taskList, {
        animation: 150,
        handle: '.drag-handle',
        ghostClass: 'bg-blue-50',
        chosenClass: 'bg-blue-100',
        dragClass: 'shadow-lg',
        disabled: true,
        onEnd: function(evt) {
            // Visual feedback that order changed
            if (evt.oldIndex !== evt.newIndex) {
                saveBtn.classList.remove('bg-gray-400');
                saveBtn.classList.add('bg-green-600', 'hover:bg-green-700');
            }
        }
    });
    
    // Toggle reorder mode
    toggleBtn.addEventListener('click', function() {
        isReorderMode = !isReorderMode;
        
        if (isReorderMode) {
            // Enable reorder mode
            sortableInstance.option('disabled', false);
            taskList.classList.add('reorder-mode');
            reorderControls.classList.remove('hidden');
            toggleBtn.innerHTML = '<i class="fas fa-times mr-1"></i> Exit Reorder Mode';
            toggleBtn.classList.remove('bg-gray-600');
            toggleBtn.classList.add('bg-red-600', 'hover:bg-red-700');
            
            // Show drag handles
            document.querySelectorAll('.drag-handle').forEach(handle => {
                handle.classList.remove('opacity-0');
                handle.classList.add('opacity-100');
            });
        } else {
            exitReorderMode();
        }
    });
    
    // Cancel reordering
    if (cancelBtn) {
        cancelBtn.addEventListener('click', function() {
            // Reload to reset order
            window.location.reload();
        });
    }
    
    // Save new order
    if (saveBtn) {
        saveBtn.addEventListener('click', saveTaskOrder);
    }
}

function exitReorderMode() {
    const taskList = document.getElementById('sortable-tasks');
    const toggleBtn = document.getElementById('toggle-reorder-mode');
    const reorderControls = document.getElementById('reorder-controls');
    
    isReorderMode = false;
    sortableInstance.option('disabled', true);
    taskList.classList.remove('reorder-mode');
    reorderControls.classList.add('hidden');
    toggleBtn.innerHTML = '<i class="fas fa-sort mr-1"></i> Reorder Tasks';
    toggleBtn.classList.remove('bg-red-600', 'hover:bg-red-700');
    toggleBtn.classList.add('bg-gray-600');
    
    // Hide drag handles
    document.querySelectorAll('.drag-handle').forEach(handle => {
        handle.classList.remove('opacity-100');
        handle.classList.add('opacity-0');
    });
}

function saveTaskOrder() {
    const taskList = document.getElementById('sortable-tasks');
    const saveBtn = document.getElementById('save-task-order');
    const taskItems = taskList.querySelectorAll('[data-task-id]');
    
    // Collect task IDs in new order
    const taskIds = Array.from(taskItems).map(item => parseInt(item.dataset.taskId));
    
    // Disable save button and show loading
    saveBtn.disabled = true;
    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Saving...';
    
    // Send AJAX request
    fetch('<?php echo e(route("admin.tasks.reorder")); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            project_id: <?php echo e($project->id); ?>,
            task_ids: taskIds
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success notification
            showToast('Task order saved successfully!', 'success');
            exitReorderMode();
        } else {
            showToast(data.message || 'Failed to save task order', 'error');
        }
    })
    .catch(error => {
        console.error('Error saving task order:', error);
        showToast('An error occurred while saving task order', 'error');
    })
    .finally(() => {
        saveBtn.disabled = false;
        saveBtn.innerHTML = '<i class="fas fa-save mr-1"></i> Save Order';
    });
}

function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';
    toast.className = `fixed bottom-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg z-50 transition-opacity duration-300`;
    toast.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'} mr-2"></i>${message}`;
    document.body.appendChild(toast);
    
    // Fade out and remove after 3 seconds
    setTimeout(() => {
        toast.classList.add('opacity-0');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}
</script>

<style>
/* Drag and Drop Styles */
#sortable-tasks.reorder-mode [data-task-id] {
    cursor: move;
    transition: transform 0.15s ease, box-shadow 0.15s ease;
}

#sortable-tasks.reorder-mode [data-task-id]:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.drag-handle {
    cursor: grab;
    transition: opacity 0.2s ease;
}

.drag-handle:active {
    cursor: grabbing;
}

.sortable-ghost {
    opacity: 0.4;
}

.sortable-chosen {
    background-color: #EBF4FF !important;
}
</style>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\marka\Projects\cms\resources\views/admin/projects/show.blade.php ENDPATH**/ ?>