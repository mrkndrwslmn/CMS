

<?php $__env->startSection('title', 'Project Cost Analysis'); ?>

<?php $__env->startSection('content'); ?>
<div class="w-full">
    <div class="w-full px-4 py-5">
        <!-- Breadcrumb -->
        <?php if (isset($component)) { $__componentOriginal045477955e5b1d8c9df01934ca3836c0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal045477955e5b1d8c9df01934ca3836c0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.breadcrumb','data' => ['items' => [
            ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
            ['label' => 'Earnings Analytics', 'route' => 'admin.earnings-analytics.index', 'icon' => 'bar-chart-2'],
            ['label' => 'Project Costs', 'icon' => 'folder-kanban'],
        ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
            ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
            ['label' => 'Earnings Analytics', 'route' => 'admin.earnings-analytics.index', 'icon' => 'bar-chart-2'],
            ['label' => 'Project Costs', 'icon' => 'folder-kanban'],
        ])]); ?>
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

        <!-- Header -->
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6 mb-6">
            <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4">
                <div>
                    <h1 class="text-2xl font-semibold text-neutral-800">
                        Project Cost Analysis
                    </h1>
                    <p class="text-sm text-neutral-500 mt-1">Compare project budgets with actual labor costs</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <form method="GET" class="flex items-center gap-2">
                        <select name="period" class="px-4 py-2.5 text-sm text-neutral-700 bg-white border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all" onchange="this.form.submit()">
                            <option value="all" <?php echo e(request('period') == 'all' ? 'selected' : ''); ?>>All Time</option>
                            <option value="30" <?php echo e(request('period') == '30' ? 'selected' : ''); ?>>Last 30 days</option>
                            <option value="90" <?php echo e(request('period') == '90' ? 'selected' : ''); ?>>Last 90 days</option>
                            <option value="365" <?php echo e(request('period') == '365' ? 'selected' : ''); ?>>Last year</option>
                        </select>
                    </form>
                    <a href="<?php echo e(route('admin.earnings-analytics.export', ['type' => 'project_costs', 'period' => request('period', 'all')])); ?>" 
                       class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-primary-700 hover:shadow-md transition-all">
                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-download'); ?>
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
<?php endif; ?> Export CSV
                    </a>
                </div>
            </div>
        </div>

        <!-- Summary Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-neutral-500">Total Projects</p>
                        <p class="text-2xl font-semibold text-neutral-800 mt-1"><?php echo e(number_format($summary['total_projects'])); ?></p>
                    </div>
                    <div class="p-3 bg-neutral-50 rounded-xl">
                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-folder-kanban'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-5 h-5 text-neutral-400']); ?>
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
                </div>
            </div>
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-neutral-500">Total Budget</p>
                        <p class="text-2xl font-semibold text-neutral-800 mt-1">₱<?php echo e(number_format($summary['total_budget'], 2)); ?></p>
                    </div>
                    <div class="p-3 bg-primary-50 rounded-xl">
                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-target'); ?>
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
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-neutral-500">Total Spent</p>
                        <p class="text-2xl font-semibold text-neutral-800 mt-1">₱<?php echo e(number_format($summary['total_spent'], 2)); ?></p>
                    </div>
                    <div class="p-3 bg-success-50 rounded-xl">
                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-banknote'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-5 h-5 text-success-500']); ?>
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
                </div>
            </div>
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-neutral-500">Avg Cost/Project</p>
                        <p class="text-2xl font-semibold text-neutral-800 mt-1">₱<?php echo e(number_format($summary['average_cost_per_project'], 2)); ?></p>
                    </div>
                    <div class="p-3 bg-neutral-50 rounded-xl">
                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-calculator'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-5 h-5 text-neutral-400']); ?>
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
                </div>
            </div>
        </div>

        <!-- Projects Table -->
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-neutral-50 border-b border-neutral-100">
                        <tr>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-5 text-left">Project</th>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-5 text-right">Budget</th>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-5 text-right">Hourly Cost</th>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-5 text-right">Fixed Cost</th>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-5 text-right">Total Cost</th>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-5 text-right">Hours</th>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-5 text-center">Adiutors</th>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-5 text-center">Status</th>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-5">Budget Usage</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100">
                        <?php $__empty_1 = true; $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $totalCost = $project->hourly_cost + $project->fixed_cost;
                                $budgetUtilization = $project->budget > 0 
                                    ? round(($totalCost / $project->budget) * 100, 1) 
                                    : 0;
                            ?>
                            <tr class="hover:bg-neutral-50 transition-colors">
                                <td class="py-4 px-5">
                                    <div class="font-medium text-neutral-700"><?php echo e($project->title); ?></div>
                                    <div class="text-xs text-neutral-500">
                                        <?php echo e($project->client?->fullName ?? 'No Client'); ?>

                                    </div>
                                </td>
                                <td class="py-4 px-5 text-right font-medium text-neutral-600">
                                    <?php if($project->budget): ?>
                                        ₱<?php echo e(number_format($project->budget, 2)); ?>

                                    <?php else: ?>
                                        <span class="text-neutral-400">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-4 px-5 text-right text-primary-600">
                                    ₱<?php echo e(number_format($project->hourly_cost, 2)); ?>

                                </td>
                                <td class="py-4 px-5 text-right text-neutral-600">
                                    ₱<?php echo e(number_format($project->fixed_cost, 2)); ?>

                                </td>
                                <td class="py-4 px-5 text-right font-bold text-success-600">
                                    ₱<?php echo e(number_format($totalCost, 2)); ?>

                                </td>
                                <td class="py-4 px-5 text-right text-neutral-600">
                                    <?php echo e(number_format($project->total_hours, 1)); ?> hrs
                                </td>
                                <td class="py-4 px-5 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-neutral-100 text-neutral-600">
                                        <?php echo e($project->assignments_count); ?>

                                    </span>
                                </td>
                                <td class="py-4 px-5 text-center">
                                    <?php
                                        $statusColors = [
                                            'pending' => 'bg-warning-100 text-warning-700',
                                            'in_progress' => 'bg-primary-100 text-primary-700',
                                            'completed' => 'bg-success-100 text-success-700',
                                            'cancelled' => 'bg-error-100 text-error-700',
                                        ];
                                    ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo e($statusColors[$project->status] ?? 'bg-neutral-100 text-neutral-600'); ?>">
                                        <?php echo e(ucfirst(str_replace('_', ' ', $project->status))); ?>

                                    </span>
                                </td>
                                <td class="py-4 px-5 min-w-[150px]">
                                    <?php if($project->budget > 0): ?>
                                        <div class="flex items-center gap-2">
                                            <div class="flex-1 bg-neutral-100 rounded-full h-2 overflow-hidden">
                                                <div class="h-full transition-all <?php echo e($budgetUtilization > 100 ? 'bg-error-500' : ($budgetUtilization > 80 ? 'bg-warning-500' : 'bg-success-500')); ?>" 
                                                     style="width: <?php echo e(min($budgetUtilization, 100)); ?>%"></div>
                                            </div>
                                            <span class="text-xs font-medium <?php echo e($budgetUtilization > 100 ? 'text-error-500' : ($budgetUtilization > 80 ? 'text-warning-500' : 'text-success-500')); ?>">
                                                <?php echo e($budgetUtilization); ?>%
                                            </span>
                                        </div>
                                        <?php if($budgetUtilization > 100): ?>
                                            <div class="inline-flex items-center gap-1 text-xs text-error-500 mt-1">
                                                <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-alert-triangle'); ?>
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
<?php endif; ?> Over budget by ₱<?php echo e(number_format($totalCost - $project->budget, 2)); ?>

                                            </div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-xs text-neutral-400">No budget set</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="9" class="py-12 text-center text-neutral-500">
                                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-folder-open'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-12 h-12 mx-auto mb-3 text-neutral-300']); ?>
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
                                    <p class="text-sm">No projects found for the selected period</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <?php if($projects->hasPages()): ?>
                <div class="p-5 border-t border-neutral-100">
                    <?php if (isset($component)) { $__componentOriginal4d04f29578652eb91560cfbf2ab48c57 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4d04f29578652eb91560cfbf2ab48c57 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.pagination','data' => ['paginator' => $projects->withQueryString()]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.pagination'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['paginator' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($projects->withQueryString())]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4d04f29578652eb91560cfbf2ab48c57)): ?>
<?php $attributes = $__attributesOriginal4d04f29578652eb91560cfbf2ab48c57; ?>
<?php unset($__attributesOriginal4d04f29578652eb91560cfbf2ab48c57); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4d04f29578652eb91560cfbf2ab48c57)): ?>
<?php $component = $__componentOriginal4d04f29578652eb91560cfbf2ab48c57; ?>
<?php unset($__componentOriginal4d04f29578652eb91560cfbf2ab48c57); ?>
<?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\marka\Projects\cms\resources\views/admin/earnings-analytics/project-costs.blade.php ENDPATH**/ ?>