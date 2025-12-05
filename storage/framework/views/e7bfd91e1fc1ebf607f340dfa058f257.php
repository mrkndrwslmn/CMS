<?php $__env->startSection('title', 'Task Scheduling - ' . $project->title); ?>
<?php $__env->startSection('page-title', 'Task Scheduling'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6 lg:py-8">
    <!-- Breadcrumb -->
    <?php if (isset($component)) { $__componentOriginal045477955e5b1d8c9df01934ca3836c0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal045477955e5b1d8c9df01934ca3836c0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.breadcrumb','data' => ['items' => [
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
        ['label' => 'Calendar', 'route' => 'admin.calendar.index', 'icon' => 'calendar'],
        ['label' => 'Task Scheduling', 'icon' => 'calendar-clock'],
    ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
        ['label' => 'Calendar', 'route' => 'admin.calendar.index', 'icon' => 'calendar'],
        ['label' => 'Task Scheduling', 'icon' => 'calendar-clock'],
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
    <div class="mb-6 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <a href="<?php echo e(route('admin.calendar.index')); ?>" class="inline-flex items-center justify-center w-10 h-10 bg-white border border-neutral-200 rounded-xl hover:bg-neutral-50 transition-colors">
                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-arrow-left'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-5 h-5 text-neutral-600']); ?>
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
                <h1 class="text-2xl font-semibold text-neutral-800">Task Scheduling</h1>
            </div>
            <p class="text-neutral-600"><?php echo e($project->title); ?></p>
            <p class="text-sm text-neutral-500">Client: <?php echo e($project->client ? $project->client->fullName : 'N/A'); ?></p>
        </div>
        
        <!-- Adiutor Filter -->
        <div class="flex items-center gap-3">
            <label class="text-sm font-medium text-neutral-700">Filter by Adiutor:</label>
            <select id="adiutorFilter" class="px-4 py-2.5 border border-neutral-200 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-colors">
                <option value="">All Adiutors</option>
                <?php $__currentLoopData = $assignedAdiutors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $adiutor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($adiutor['id']); ?>"><?php echo e($adiutor['name']); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Unscheduled Tasks (Left Side - 4 columns) -->
        <div class="lg:col-span-4">
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6 h-full">
                <h2 class="text-lg font-semibold text-neutral-800 mb-4 flex items-center gap-2">
                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-list-todo'); ?>
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
                    Unscheduled Tasks 
                    <span id="taskCount" class="text-sm font-normal text-neutral-500">(<?php echo e($unscheduledTasks->count()); ?>)</span>
                </h2>
                
                <div id="unscheduledTasksList" class="space-y-3">
                    <?php $__empty_1 = true; $__currentLoopData = $unscheduledTasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="task-card border rounded-xl p-4 hover:shadow-md transition-all <?php echo e($task['has_conflict'] ? 'border-warning-300 bg-warning-50' : 'border-neutral-200'); ?>" 
                             data-task-id="<?php echo e($task['id']); ?>"
                             data-task-title="<?php echo e($task['title']); ?>"
                             data-task-description="<?php echo e($task['description'] ?? 'No description'); ?>"
                             data-task-priority="<?php echo e($task['priority'] ?? 'medium'); ?>"
                             data-assigned-to="<?php echo e($task['assigned_to_id'] ?? ''); ?>"
                             data-assigned-to-name="<?php echo e($task['assigned_to'] ?? 'Unassigned'); ?>"
                             data-deadline="<?php echo e($task['deadline'] ?? ''); ?>"
                             data-deadline-formatted="<?php echo e($task['deadline'] ? \Carbon\Carbon::parse($task['deadline'])->format('M d, Y') : 'N/A'); ?>"
                             data-estimated-hours="<?php echo e($task['estimated_hours'] ?? 8); ?>"
                             data-conflicting-with="<?php echo e($task['conflicting_with'] ?? ''); ?>">
                            
                            <?php if($task['has_conflict']): ?>
                                <div class="mb-3 flex items-center gap-2 text-warning-700 bg-warning-100 px-3 py-2 rounded-lg">
                                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-alert-triangle'); ?>
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
                                    <span class="text-xs font-semibold">Deadline Conflict Detected</span>
                                </div>
                            <?php endif; ?>
                            
                            <div class="flex items-start justify-between mb-2">
                                <h4 class="font-semibold text-neutral-800"><?php echo e($task['title']); ?></h4>
                                <span class="text-xs px-2.5 py-0.5 rounded-full font-medium
                                    <?php echo e($task['priority'] === 'high' ? 'bg-error-100 text-error-700' : ''); ?>

                                    <?php echo e($task['priority'] === 'medium' ? 'bg-warning-100 text-warning-700' : ''); ?>

                                    <?php echo e($task['priority'] === 'low' ? 'bg-success-100 text-success-700' : ''); ?>">
                                    <?php echo e(ucfirst($task['priority'])); ?>

                                </span>
                            </div>
                            
                            <?php if($task['description']): ?>
                                <p class="text-sm text-neutral-600 mb-2"><?php echo e(Str::limit($task['description'], 80)); ?></p>
                            <?php endif; ?>
                            
                            <div class="flex items-center justify-between text-sm">
                                <div class="text-neutral-600 flex items-center gap-1">
                                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-clock'); ?>
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
                                    <?php echo e($task['estimated_hours']); ?> hours
                                </div>
                                <?php if($task['deadline']): ?>
                                    <div class="text-neutral-600 flex items-center gap-1">
                                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-calendar'); ?>
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
                                        <?php echo e(\Carbon\Carbon::parse($task['deadline'])->format('M d')); ?>

                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="mt-2 pt-2 border-t border-neutral-200">
                                <p class="text-xs text-neutral-500 flex items-center gap-1">
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
                                    Assigned to: <span class="font-medium"><?php echo e($task['assigned_to']); ?></span>
                                </p>
                            </div>
                            
                            <button onclick="scheduleTask(<?php echo e($task['id']); ?>, <?php echo e($task['has_conflict'] ? 'true' : 'false'); ?>)" class="mt-3 w-full inline-flex items-center justify-center gap-2 px-3 py-2 bg-primary-600 text-white text-sm font-medium rounded-xl hover:bg-primary-700 transition-colors">
                                <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-calendar-plus'); ?>
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
                                Schedule Task
                            </button>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-success-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-check-circle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-8 h-8 text-success-500']); ?>
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
                            <p class="text-neutral-500">All tasks are scheduled!</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Calendar Timeline (Right Side - 8 columns) -->
        <div class="lg:col-span-8">
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-semibold text-neutral-800 flex items-center gap-2">
                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-calendar-days'); ?>
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
                        Calendar Timeline
                    </h2>
                    
                    <!-- Week Navigation -->
                    <div class="flex items-center gap-2">
                        <button onclick="navigateWeek(-1)" class="inline-flex items-center justify-center w-10 h-10 bg-white border border-neutral-200 rounded-xl hover:bg-neutral-50 transition-colors">
                            <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-chevron-left'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-5 h-5 text-neutral-600']); ?>
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
                        <button onclick="navigateWeek(0)" class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-xl transition-colors">
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
                            Today
                        </button>
                        <button onclick="navigateWeek(1)" class="inline-flex items-center justify-center w-10 h-10 bg-white border border-neutral-200 rounded-xl hover:bg-neutral-50 transition-colors">
                            <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-chevron-right'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-5 h-5 text-neutral-600']); ?>
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
                </div>

                <p class="text-sm text-neutral-600 mb-4">
                    Week of <span id="currentWeek"></span>
                </p>

                <!-- Legend -->
                <div class="flex flex-wrap items-center gap-4 mb-4 text-sm">
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-primary-500 rounded"></div>
                        <span class="text-neutral-600">CMS Tasks</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-purple-500 rounded"></div>
                        <span class="text-neutral-600">Calendar Events</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-success-100 border-2 border-success-300 rounded"></div>
                        <span class="text-neutral-600">Available</span>
                    </div>
                </div>

                <!-- Calendar Grid -->
                <div id="calendarGrid" class="bg-white border border-neutral-200 rounded-xl overflow-hidden">
                    <!-- Calendar will be rendered here by JavaScript -->
                    <div class="flex items-center justify-center py-12 text-neutral-500">
                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-loader-2'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-5 h-5 mr-2 animate-spin']); ?>
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
                        Loading calendar...
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Schedule Conflict Modal -->
<div id="scheduleConflictModal" class="hidden fixed inset-0 backdrop-blur-md bg-white/30 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full border border-neutral-100">
        <!-- Modal Header -->
        <div class="bg-warning-50 border-b border-warning-200 px-6 py-4 flex items-center justify-between rounded-t-2xl">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-warning-100 rounded-xl flex items-center justify-center">
                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-alert-triangle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-5 h-5 text-warning-600']); ?>
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
                <h2 class="text-xl font-semibold text-warning-800">Schedule Conflict</h2>
            </div>
            <button onclick="closeConflictModal()" class="w-8 h-8 flex items-center justify-center rounded-lg text-neutral-400 hover:text-neutral-600 hover:bg-white/50 transition-colors">
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

        <!-- Modal Body -->
        <div class="p-6">
            <div class="mb-4">
                <h3 class="font-semibold text-neutral-800 mb-2" id="conflictTaskTitle">Task Title</h3>
                <p class="text-sm text-neutral-600 mb-3">
                    This task has the same deadline as another task assigned to the same adiutor.
                </p>
            </div>

            <div class="bg-warning-50 border border-warning-200 rounded-xl p-4 mb-4">
                <p class="text-sm text-warning-800 font-medium mb-2 flex items-center gap-2">
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
                    Deadline Conflict Details:
                </p>
                <div class="text-sm text-neutral-700 space-y-1">
                    <p><strong>Assigned to:</strong> <span id="conflictAssignedTo">-</span></p>
                    <p><strong>Deadline:</strong> <span id="conflictDeadline">-</span></p>
                    <p><strong>Estimated Hours:</strong> <span id="conflictHours">-</span> hours</p>
                </div>
            </div>

            <div class="bg-error-50 border-l-4 border-error-500 rounded-r-xl p-4 mb-6">
                <p class="text-sm text-error-800 font-medium mb-1 flex items-center gap-2">
                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-calendar-x'); ?>
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
                    Conflicts with:
                </p>
                <p class="text-sm text-error-900 font-semibold" id="conflictingTaskName">-</p>
            </div>

            <p class="text-sm text-neutral-600 mb-6">
                To avoid overloading the adiutor, please choose one of the following options:
            </p>

            <!-- Action Buttons -->
            <div class="space-y-3">
                <button onclick="manuallyScheduleConflict()" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-colors font-medium">
                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-calendar'); ?>
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
                    Manually Schedule This Task
                </button>
                <button onclick="reassignTask()" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-purple-600 text-white rounded-xl hover:bg-purple-700 transition-colors font-medium">
                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-user-pen'); ?>
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
                    Reassign to Different Adiutor
                </button>
                <button onclick="closeConflictModal()" class="w-full px-4 py-3 border border-neutral-200 text-neutral-700 rounded-xl hover:bg-neutral-50 transition-colors font-medium">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Task Scheduling Modal -->
<div id="scheduleTaskModal" class="hidden fixed inset-0 backdrop-blur-md bg-white/30 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl border border-neutral-100 max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="sticky top-0 bg-white border-b border-neutral-200 px-6 py-4 flex items-center justify-between rounded-t-2xl">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-primary-50 rounded-xl flex items-center justify-center">
                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-calendar-plus'); ?>
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
                </div>
                <h2 class="text-xl font-semibold text-neutral-800">Schedule Task</h2>
            </div>
            <button onclick="closeScheduleModal()" class="w-8 h-8 flex items-center justify-center rounded-lg text-neutral-400 hover:text-neutral-600 hover:bg-neutral-100 transition-colors">
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

        <!-- Modal Body -->
        <div class="p-6">
            <!-- Task Information -->
            <div class="bg-neutral-50 rounded-xl p-4 mb-6">
                <h3 class="font-semibold text-neutral-800 mb-2" id="modalTaskTitle">Task Title</h3>
                <p class="text-sm text-neutral-600 mb-3" id="modalTaskDescription">Task description</p>
                <div class="flex flex-wrap items-center gap-4 text-sm text-neutral-600">
                    <div class="flex items-center gap-1">
                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-clock'); ?>
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
                        <span id="modalTaskHours">0</span> hours
                    </div>
                    <div class="flex items-center gap-1">
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
                        Deadline: <span id="modalTaskDeadline">N/A</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-flag'); ?>
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
                        Priority: <span id="modalTaskPriority" class="font-medium">Medium</span>
                    </div>
                </div>
                <div class="mt-2 pt-2 border-t border-neutral-200">
                    <p class="text-sm text-neutral-600 flex items-center gap-1">
                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-user'); ?>
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
                        Currently assigned to: <span id="modalTaskAssignedTo" class="font-medium">Unassigned</span>
                    </p>
                </div>
            </div>

            <!-- Assigned Adiutors List -->
            <div class="mb-6">
                <h4 class="font-semibold text-neutral-800 mb-3">Assign to Adiutor</h4>
                <div id="adiutorList" class="space-y-2">
                    <?php $__currentLoopData = $assignedAdiutors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $adiutor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <label class="flex items-center p-3 border border-neutral-200 rounded-xl hover:bg-neutral-50 cursor-pointer transition-colors">
                            <input type="radio" name="selected_adiutor" value="<?php echo e($adiutor['id']); ?>" class="mr-3 h-4 w-4 text-primary-600">
                            <div class="flex items-center flex-1">
                                <img src="<?php echo e($adiutor['avatar']); ?>" alt="<?php echo e($adiutor['name']); ?>" class="w-10 h-10 rounded-full mr-3 object-cover">
                                <div>
                                    <p class="font-medium text-neutral-800"><?php echo e($adiutor['name']); ?></p>
                                    <p class="text-xs flex items-center gap-1">
                                        <?php if($adiutor['calendar_connected']): ?>
                                            <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-check-circle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-3.5 h-3.5 text-success-500']); ?>
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
                                            <span class="text-success-600">Calendar Connected</span>
                                        <?php else: ?>
                                            <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-x-circle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-3.5 h-3.5 text-error-500']); ?>
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
                                            <span class="text-error-600">Calendar Not Connected</span>
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </div>
                        </label>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <!-- Schedule Date & Time -->
            <div class="mb-6">
                <h4 class="font-semibold text-neutral-800 mb-3">Schedule Details</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Start Date & Time</label>
                        <input type="datetime-local" id="scheduledStart" class="w-full px-3 py-2.5 border border-neutral-200 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">End Date & Time</label>
                        <input type="datetime-local" id="scheduledEnd" class="w-full px-3 py-2.5 border border-neutral-200 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-colors">
                    </div>
                </div>
                <p class="text-xs text-neutral-500 mt-2 flex items-center gap-1">
                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-info'); ?>
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
                    This will create a time block in the adiutor's calendar
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-3">
                <button onclick="closeScheduleModal()" class="px-4 py-2.5 text-neutral-700 bg-neutral-100 rounded-xl hover:bg-neutral-200 transition-colors font-medium">
                    Cancel
                </button>
                <button onclick="confirmSchedule()" class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-colors font-medium">
                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-calendar-check'); ?>
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
                    Assign to Adiutor
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let currentWeekStart = null;
let selectedAdiutorId = null;

document.addEventListener('DOMContentLoaded', function() {
    // Initialize
    currentWeekStart = new Date();
    currentWeekStart.setDate(currentWeekStart.getDate() - currentWeekStart.getDay() + 1); // Monday
    
    loadCalendar();
    
    // Setup adiutor filter
    document.getElementById('adiutorFilter').addEventListener('change', function(e) {
        selectedAdiutorId = e.target.value || null;
        filterTasks();
        loadCalendar();
    });
});

function filterTasks() {
    const taskCards = document.querySelectorAll('.task-card');
    let visibleCount = 0;
    
    taskCards.forEach(card => {
        const assignedTo = card.getAttribute('data-assigned-to');
        
        if (!selectedAdiutorId || assignedTo === selectedAdiutorId) {
            card.style.display = 'block';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });
    
    document.getElementById('taskCount').textContent = `(${visibleCount})`;
}

function navigateWeek(direction) {
    if (direction === 0) {
        // Today
        currentWeekStart = new Date();
        currentWeekStart.setDate(currentWeekStart.getDate() - currentWeekStart.getDay() + 1);
    } else {
        // Previous/Next week
        currentWeekStart.setDate(currentWeekStart.getDate() + (direction * 7));
    }
    loadCalendar();
}

function loadCalendar() {
    const startDate = formatDate(currentWeekStart);
    document.getElementById('currentWeek').textContent = formatDateDisplay(currentWeekStart);
    
    // If no specific adiutor selected, load all adiutors' schedules
    let url = selectedAdiutorId 
        ? `/api/schedule/adiutor/${selectedAdiutorId}/timeline?start_date=${startDate}`
        : `/api/schedule/project/<?php echo e($project->id); ?>/timeline?start_date=${startDate}`;
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            renderCalendar(data.slots);
        })
        .catch(error => {
            console.error('Error loading calendar:', error);
            document.getElementById('calendarGrid').innerHTML = `
                <div class="flex items-center justify-center py-12 text-error-600">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                    Error loading calendar
                </div>
            `;
        });
}

function renderCalendar(slots) {
    const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    const hours = Array.from({length: 17}, (_, i) => i + 6); // 6 AM to 10 PM
    
    let html = '<div class="grid grid-cols-8 border-b border-neutral-200">';
    
    // Header row
    html += '<div class="p-3 bg-neutral-50 font-medium text-neutral-700 text-sm border-r border-neutral-200 sticky top-0">Time</div>';
    days.forEach((day, index) => {
        const date = new Date(currentWeekStart);
        date.setDate(date.getDate() + index);
        html += `<div class="p-3 bg-neutral-50 font-medium text-neutral-700 text-sm border-r border-neutral-200 last:border-r-0 sticky top-0">
            ${day}<br>
            <span class="text-xs text-neutral-500">${date.getMonth() + 1}/${date.getDate()}</span>
        </div>`;
    });
    html += '</div>';
    
    // Time slots
    hours.forEach(hour => {
        html += '<div class="grid grid-cols-8 border-b border-neutral-200 last:border-b-0">';
        
        // Hour label
        const ampm = hour >= 12 ? 'PM' : 'AM';
        const displayHour = hour > 12 ? hour - 12 : hour;
        html += `<div class="p-3 bg-neutral-50 text-sm text-neutral-600 font-medium border-r border-neutral-200">${displayHour}:00 ${ampm}</div>`;
        
        // Day cells
        days.forEach((day, dayIndex) => {
            const date = new Date(currentWeekStart);
            date.setDate(date.getDate() + dayIndex);
            const dateStr = formatDate(date);
            
            // Find slots for this time/day
            const daySlots = slots.filter(slot => slot.date === dateStr && slot.hour === hour);
            
            html += '<div class="p-2 border-r border-neutral-200 last:border-r-0 min-h-[80px] relative bg-success-50">';
            
            if (daySlots.length === 0) {
                // Available slot
                html += '<div class="text-xs text-neutral-400 italic">Available</div>';
            } else {
                daySlots.forEach(slot => {
                    const colorClass = slot.type === 'task' 
                        ? 'bg-primary-100 border-primary-300 text-primary-800' 
                        : 'bg-purple-100 border-purple-300 text-purple-800';
                    
                    // Show full title (with adiutor name) if viewing all adiutors
                    const displayTitle = selectedAdiutorId ? slot.title.split(' - ')[0] : slot.title;
                    
                    // Extract task name and adiutor for tooltip
                    const fullTaskTitle = slot.title.includes(' - ') ? slot.title.split(' - ')[0] : slot.title;
                    const adiutorName = slot.adiutor || (slot.title.includes(' - ') ? slot.title.split(' - ')[1] : 'N/A');
                    const description = slot.description || 'No description';
                    
                    html += `<div class="${colorClass} border rounded px-2 py-1 text-xs mb-1 cursor-pointer hover:shadow-md transition-shadow" 
                                  title="Task: ${fullTaskTitle}&#10;Assigned to: ${adiutorName}&#10;Description: ${description}&#10;Days: ${slot.duration}">
                        <div class="font-semibold truncate">${displayTitle}</div>
                        <div class="text-xs opacity-75">${slot.duration} day/s</div>
                    </div>`;
                });
            }
            
            html += '</div>';
        });
        
        html += '</div>';
    });
    
    document.getElementById('calendarGrid').innerHTML = html;
}

function scheduleTask(taskId, hasConflict) {
    // Get task details from the task card data attributes
    const taskCard = document.querySelector(`.task-card[data-task-id="${taskId}"]`);
    if (!taskCard) return;
    
    const title = taskCard.dataset.taskTitle;
    const description = taskCard.dataset.taskDescription || 'No description';
    const hours = taskCard.dataset.estimatedHours;
    const deadline = taskCard.dataset.deadlineFormatted || 'N/A';
    const priority = taskCard.dataset.taskPriority || 'Medium';
    const assignedTo = taskCard.dataset.assignedToName || 'Unassigned';
    const assignedToId = taskCard.dataset.assignedTo;
    const conflictingWith = taskCard.dataset.conflictingWith || 'Unknown Task';
    
    // If task has a conflict, show conflict modal instead
    if (hasConflict) {
        // Fill conflict modal with task details
        document.getElementById('conflictTaskTitle').textContent = title;
        document.getElementById('conflictAssignedTo').textContent = assignedTo;
        document.getElementById('conflictDeadline').textContent = deadline;
        document.getElementById('conflictHours').textContent = hours;
        document.getElementById('conflictingTaskName').textContent = conflictingWith;
        
        // Store task ID for later use
        document.getElementById('scheduleConflictModal').dataset.taskId = taskId;
        
        // Show conflict modal
        document.getElementById('scheduleConflictModal').classList.remove('hidden');
        return;
    }
    
    // Otherwise, show regular schedule modal for tasks without deadline
    // Fill modal with task details
    document.getElementById('modalTaskTitle').textContent = title;
    document.getElementById('modalTaskDescription').textContent = description;
    document.getElementById('modalTaskHours').textContent = hours;
    document.getElementById('modalTaskDeadline').textContent = deadline;
    document.getElementById('modalTaskPriority').textContent = priority;
    document.getElementById('modalTaskAssignedTo').textContent = assignedTo;
    
    // Pre-select the assigned adiutor if exists
    if (assignedToId) {
        const radio = document.querySelector(`input[name="selected_adiutor"][value="${assignedToId}"]`);
        if (radio) radio.checked = true;
    }
    
    // Set dates based on task deadline
    const taskDeadline = taskCard.dataset.deadline;
    const estimatedHours = parseInt(taskCard.dataset.estimatedHours) || 8;
    
    let startDate, endDate;
    
    if (taskDeadline) {
        // Use the deadline date, set end time to deadline
        endDate = new Date(taskDeadline);
        // If deadline has no time, set to 5 PM
        if (endDate.getHours() === 0 && endDate.getMinutes() === 0) {
            endDate.setHours(17, 0, 0, 0);
        }
        
        // Start on the same day as deadline (9 AM)
        startDate = new Date(endDate);
        startDate.setHours(9, 0, 0, 0);
        
        // If estimated hours is less than 8, calculate actual start time
        if (estimatedHours < 8) {
            const calculatedStart = new Date(endDate);
            calculatedStart.setHours(calculatedStart.getHours() - estimatedHours);
            
            // Use calculated start if it's after 9 AM
            if (calculatedStart.getHours() >= 9) {
                startDate = calculatedStart;
            }
        }
    } else {
        // Fallback: use tomorrow 9 AM to 5 PM
        startDate = new Date();
        startDate.setDate(startDate.getDate() + 1);
        startDate.setHours(9, 0, 0, 0);
        endDate = new Date(startDate);
        endDate.setHours(17, 0, 0, 0);
    }
    
    document.getElementById('scheduledStart').value = formatDateTimeLocal(startDate);
    document.getElementById('scheduledEnd').value = formatDateTimeLocal(endDate);
    
    // Store task ID for later use
    document.getElementById('scheduleTaskModal').dataset.taskId = taskId;
    
    // Show modal
    document.getElementById('scheduleTaskModal').classList.remove('hidden');
}

function closeScheduleModal() {
    document.getElementById('scheduleTaskModal').classList.add('hidden');
}

function confirmSchedule() {
    console.log('===== confirmSchedule called =====');
    
    const modal = document.getElementById('scheduleTaskModal');
    const taskId = modal.dataset.taskId;
    const selectedAdiutor = document.querySelector('input[name="selected_adiutor"]:checked');
    const startTime = document.getElementById('scheduledStart').value;
    const endTime = document.getElementById('scheduledEnd').value;
    
    console.log('Modal:', modal);
    console.log('Task ID:', taskId);
    console.log('Selected Adiutor:', selectedAdiutor);
    console.log('Selected Adiutor Value:', selectedAdiutor ? selectedAdiutor.value : 'NONE');
    console.log('Start Time:', startTime);
    console.log('End Time:', endTime);
    
    if (!selectedAdiutor) {
        alert('Please select an adiutor');
        return;
    }
    
    if (!startTime || !endTime) {
        alert('Please select start and end times');
        return;
    }
    
    console.log('About to send request to /api/schedule/task');
    
    // Send to backend
    fetch('/api/schedule/task', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            task_id: taskId,
            adiutor_id: selectedAdiutor.value,
            scheduled_start: startTime,
            scheduled_end: endTime
        })
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        return response.text().then(text => {
            console.log('Response text:', text);
            try {
                return JSON.parse(text);
            } catch (e) {
                console.error('Failed to parse JSON:', e);
                throw new Error('Server returned invalid JSON: ' + text.substring(0, 200));
            }
        });
    })
    .then(data => {
        console.log('Parsed data:', data);
        if (data.success) {
            alert('Task scheduled successfully!');
            console.log('SUCCESS RESPONSE:', JSON.stringify(data, null, 2));
            closeScheduleModal();
            location.reload(); // Refresh to update the view
        } else {
            alert('Error: ' + (data.message || 'Failed to schedule task'));
            console.error('ERROR RESPONSE:', data);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while scheduling the task: ' + error.message);
    });
}

function formatDateTimeLocal(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
    return `${year}-${month}-${day}T${hours}:${minutes}`;
}

function formatDate(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

function formatDateDisplay(date) {
    const options = { month: 'short', day: 'numeric', year: 'numeric' };
    return date.toLocaleDateString('en-US', options);
}

function closeConflictModal() {
    document.getElementById('scheduleConflictModal').classList.add('hidden');
}

function manuallyScheduleConflict() {
    // Get task ID from conflict modal
    const taskId = document.getElementById('scheduleConflictModal').dataset.taskId;
    
    // Close conflict modal
    closeConflictModal();
    
    // Open regular schedule modal to manually set time
    const taskCard = document.querySelector(`.task-card[data-task-id="${taskId}"]`);
    if (taskCard) {
        // Call scheduleTask with hasConflict=false to show regular modal
        scheduleTask(taskId, false);
    }
}

function reassignTask() {
    const taskId = document.getElementById('scheduleConflictModal').dataset.taskId;
    
    // Close conflict modal
    closeConflictModal();
    
    // Open regular schedule modal with different adiutor selection
    const taskCard = document.querySelector(`.task-card[data-task-id="${taskId}"]`);
    if (taskCard) {
        // Call scheduleTask with hasConflict=false to show regular modal
        // User can select a different adiutor
        scheduleTask(taskId, false);
        
        // Show a hint to select a different adiutor
        setTimeout(() => {
            alert('Please select a different adiutor to avoid the conflict.');
        }, 300);
    }
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\marka\Projects\cms\resources\views/admin/projects/schedule.blade.php ENDPATH**/ ?>