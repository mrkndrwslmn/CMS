

<?php $__env->startSection('title', 'Budget Change Requests'); ?>
<?php $__env->startSection('page-title', 'Budget Change Requests'); ?>
<?php $__env->startSection('content'); ?>
<div class="p-6 lg:p-8">
    <!-- Breadcrumb -->
    <?php if (isset($component)) { $__componentOriginal045477955e5b1d8c9df01934ca3836c0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal045477955e5b1d8c9df01934ca3836c0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.breadcrumb','data' => ['items' => [
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
        ['label' => 'Budget Requests', 'icon' => 'wallet'],
    ],'class' => 'mb-6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
        ['label' => 'Budget Requests', 'icon' => 'wallet'],
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

    <!-- Page Header -->
    <?php if (isset($component)) { $__componentOriginal91a231a9270579fa1ae9246bd51fb785 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal91a231a9270579fa1ae9246bd51fb785 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.page-header','data' => ['title' => 'Budget Change Requests','description' => 'Review and manage budget modification requests from adiutors','class' => 'mb-6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Budget Change Requests','description' => 'Review and manage budget modification requests from adiutors','class' => 'mb-6']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal91a231a9270579fa1ae9246bd51fb785)): ?>
<?php $attributes = $__attributesOriginal91a231a9270579fa1ae9246bd51fb785; ?>
<?php unset($__attributesOriginal91a231a9270579fa1ae9246bd51fb785); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal91a231a9270579fa1ae9246bd51fb785)): ?>
<?php $component = $__componentOriginal91a231a9270579fa1ae9246bd51fb785; ?>
<?php unset($__componentOriginal91a231a9270579fa1ae9246bd51fb785); ?>
<?php endif; ?>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <?php if (isset($component)) { $__componentOriginal51ca8f9b5f2319ee98f72ec28ea4da42 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal51ca8f9b5f2319ee98f72ec28ea4da42 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.stat-card','data' => ['label' => 'Pending','value' => number_format($stats['pending']),'icon' => 'clock','iconColor' => 'warning']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Pending','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(number_format($stats['pending'])),'icon' => 'clock','icon-color' => 'warning']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal51ca8f9b5f2319ee98f72ec28ea4da42)): ?>
<?php $attributes = $__attributesOriginal51ca8f9b5f2319ee98f72ec28ea4da42; ?>
<?php unset($__attributesOriginal51ca8f9b5f2319ee98f72ec28ea4da42); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal51ca8f9b5f2319ee98f72ec28ea4da42)): ?>
<?php $component = $__componentOriginal51ca8f9b5f2319ee98f72ec28ea4da42; ?>
<?php unset($__componentOriginal51ca8f9b5f2319ee98f72ec28ea4da42); ?>
<?php endif; ?>
        <?php if (isset($component)) { $__componentOriginal51ca8f9b5f2319ee98f72ec28ea4da42 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal51ca8f9b5f2319ee98f72ec28ea4da42 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.stat-card','data' => ['label' => 'Approved','value' => number_format($stats['approved']),'icon' => 'check-circle','iconColor' => 'success']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Approved','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(number_format($stats['approved'])),'icon' => 'check-circle','icon-color' => 'success']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal51ca8f9b5f2319ee98f72ec28ea4da42)): ?>
<?php $attributes = $__attributesOriginal51ca8f9b5f2319ee98f72ec28ea4da42; ?>
<?php unset($__attributesOriginal51ca8f9b5f2319ee98f72ec28ea4da42); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal51ca8f9b5f2319ee98f72ec28ea4da42)): ?>
<?php $component = $__componentOriginal51ca8f9b5f2319ee98f72ec28ea4da42; ?>
<?php unset($__componentOriginal51ca8f9b5f2319ee98f72ec28ea4da42); ?>
<?php endif; ?>
        <?php if (isset($component)) { $__componentOriginal51ca8f9b5f2319ee98f72ec28ea4da42 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal51ca8f9b5f2319ee98f72ec28ea4da42 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.stat-card','data' => ['label' => 'Rejected','value' => number_format($stats['rejected']),'icon' => 'x-circle','iconColor' => 'error']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Rejected','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(number_format($stats['rejected'])),'icon' => 'x-circle','icon-color' => 'error']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal51ca8f9b5f2319ee98f72ec28ea4da42)): ?>
<?php $attributes = $__attributesOriginal51ca8f9b5f2319ee98f72ec28ea4da42; ?>
<?php unset($__attributesOriginal51ca8f9b5f2319ee98f72ec28ea4da42); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal51ca8f9b5f2319ee98f72ec28ea4da42)): ?>
<?php $component = $__componentOriginal51ca8f9b5f2319ee98f72ec28ea4da42; ?>
<?php unset($__componentOriginal51ca8f9b5f2319ee98f72ec28ea4da42); ?>
<?php endif; ?>
        <?php if (isset($component)) { $__componentOriginal51ca8f9b5f2319ee98f72ec28ea4da42 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal51ca8f9b5f2319ee98f72ec28ea4da42 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.stat-card','data' => ['label' => 'Total Requests','value' => number_format($stats['total']),'icon' => 'wallet','iconColor' => 'primary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Total Requests','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(number_format($stats['total'])),'icon' => 'wallet','icon-color' => 'primary']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal51ca8f9b5f2319ee98f72ec28ea4da42)): ?>
<?php $attributes = $__attributesOriginal51ca8f9b5f2319ee98f72ec28ea4da42; ?>
<?php unset($__attributesOriginal51ca8f9b5f2319ee98f72ec28ea4da42); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal51ca8f9b5f2319ee98f72ec28ea4da42)): ?>
<?php $component = $__componentOriginal51ca8f9b5f2319ee98f72ec28ea4da42; ?>
<?php unset($__componentOriginal51ca8f9b5f2319ee98f72ec28ea4da42); ?>
<?php endif; ?>
    </div>

    <!-- Filters -->
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
        <div class="p-6">
            <form method="GET" action="<?php echo e(route('admin.budget-requests.index')); ?>" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <?php if (isset($component)) { $__componentOriginal65bd7e7dbd93cec773ad6501ce127e46 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.input','data' => ['name' => 'search','value' => request('search'),'placeholder' => 'Search by task or adiutor...','label' => 'Search']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'search','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request('search')),'placeholder' => 'Search by task or adiutor...','label' => 'Search']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46)): ?>
<?php $attributes = $__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46; ?>
<?php unset($__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal65bd7e7dbd93cec773ad6501ce127e46)): ?>
<?php $component = $__componentOriginal65bd7e7dbd93cec773ad6501ce127e46; ?>
<?php unset($__componentOriginal65bd7e7dbd93cec773ad6501ce127e46); ?>
<?php endif; ?>
                
                <?php if (isset($component)) { $__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.select','data' => ['name' => 'status','label' => 'Status']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'status','label' => 'Status']); ?>
                    <option value="">All Statuses</option>
                    <option value="pending" <?php echo e(request('status') === 'pending' ? 'selected' : ''); ?>>Pending</option>
                    <option value="approved" <?php echo e(request('status') === 'approved' ? 'selected' : ''); ?>>Approved</option>
                    <option value="rejected" <?php echo e(request('status') === 'rejected' ? 'selected' : ''); ?>>Rejected</option>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862)): ?>
<?php $attributes = $__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862; ?>
<?php unset($__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862)): ?>
<?php $component = $__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862; ?>
<?php unset($__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862); ?>
<?php endif; ?>
                
                <div class="flex items-end gap-2">
                    <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['type' => 'submit','icon' => 'search']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit','icon' => 'search']); ?>
                        Filter
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
                    
                    <?php if(request()->hasAny(['search', 'status'])): ?>
                        <a href="<?php echo e(route('admin.budget-requests.index')); ?>">
                            <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['type' => 'button','variant' => 'ghost','icon' => 'x']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','variant' => 'ghost','icon' => 'x']); ?>
                                Clear
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
                        </a>
                    <?php endif; ?>
                </div>
            </form>
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

    <!-- Budget Requests Table -->
    <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm overflow-hidden">
        <?php if($budgetRequests->count() > 0): ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-neutral-100">
                    <thead class="bg-neutral-50">
                        <tr>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-medium uppercase tracking-wider text-neutral-500">Task / Project</th>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-medium uppercase tracking-wider text-neutral-500">Adiutor</th>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-medium uppercase tracking-wider text-neutral-500">Current Budget</th>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-medium uppercase tracking-wider text-neutral-500">Requested Budget</th>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-medium uppercase tracking-wider text-neutral-500">Change</th>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-medium uppercase tracking-wider text-neutral-500">Status</th>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-medium uppercase tracking-wider text-neutral-500">Date Requested</th>
                            <th scope="col" class="px-6 py-3.5 text-right text-xs font-medium uppercase tracking-wider text-neutral-500">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-neutral-100">
                        <?php $__currentLoopData = $budgetRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="hover:bg-neutral-50 transition-colors <?php echo e($request->status === 'pending' ? 'bg-warning-50/30' : ''); ?>">
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="text-sm font-medium text-neutral-800"><?php echo e($request->task->taskTitle ?? 'N/A'); ?></p>
                                        <p class="text-xs text-neutral-500 flex items-center gap-1 mt-1">
                                            <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-folder-kanban'); ?>
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
                                            <?php echo e($request->task->project->title ?? 'No Project'); ?>

                                        </p>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8">
                                            <img src="<?php echo e($request->adiutor->profilePic ?? 'https://ui-avatars.com/api/?name=' . urlencode($request->adiutor->fullName)); ?>" 
                                                 alt="<?php echo e($request->adiutor->fullName); ?>"
                                                 class="w-8 h-8 rounded-full object-cover">
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-neutral-800"><?php echo e($request->adiutor->fullName); ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-neutral-700">
                                    ₱<?php echo e(number_format($request->current_budget, 2)); ?>

                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-neutral-800">
                                    ₱<?php echo e(number_format($request->requested_budget, 2)); ?>

                                </td>
                                <td class="px-6 py-4">
                                    <?php
                                        $difference = $request->requested_budget - $request->current_budget;
                                        $percentage = $request->current_budget > 0 ? (($difference / $request->current_budget) * 100) : 100;
                                    ?>
                                    <div class="text-sm">
                                        <span class="font-medium <?php echo e($difference > 0 ? 'text-error-600' : 'text-success-600'); ?>">
                                            <?php echo e($difference > 0 ? '+' : ''); ?>₱<?php echo e(number_format(abs($difference), 2)); ?>

                                        </span>
                                        <span class="text-xs text-neutral-500 block">
                                            (<?php echo e($difference > 0 ? '+' : ''); ?><?php echo e(number_format($percentage, 1)); ?>%)
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if($request->status === 'pending'): ?>
                                        <?php if (isset($component)) { $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.badge','data' => ['type' => 'warning']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'warning']); ?>
                                            Pending
                                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $attributes = $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $component = $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?>
                                    <?php elseif($request->status === 'approved'): ?>
                                        <?php if (isset($component)) { $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.badge','data' => ['type' => 'success']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'success']); ?>
                                            Approved
                                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $attributes = $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $component = $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?>
                                    <?php else: ?>
                                        <?php if (isset($component)) { $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.badge','data' => ['type' => 'error']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'error']); ?>
                                            Rejected
                                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $attributes = $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $component = $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-neutral-700">
                                    <div class="text-sm font-medium text-neutral-800"><?php echo e($request->created_at->format('M d, Y')); ?></div>
                                    <div class="text-xs text-neutral-500"><?php echo e($request->created_at->format('h:i A')); ?></div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end items-center gap-1">
                                        <a href="<?php echo e(route('admin.budget-requests.show', $request->id)); ?>" 
                                           class="p-2 text-neutral-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors"
                                           title="View Details">
                                            <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-eye'); ?>
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
                                        </a>
                                        
                                        <?php if($request->status === 'pending'): ?>
                                            <button onclick="openApproveModal(<?php echo e($request->id); ?>, '<?php echo e($request->task->taskTitle ?? 'N/A'); ?>', '<?php echo e($request->adiutor->fullName); ?>')" 
                                                    class="p-2 text-neutral-400 hover:text-success-600 hover:bg-success-50 rounded-lg transition-colors"
                                                    title="Approve">
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
                                            </button>
                                            
                                            <button onclick="openRejectModal(<?php echo e($request->id); ?>, '<?php echo e($request->task->taskTitle ?? 'N/A'); ?>', '<?php echo e($request->adiutor->fullName); ?>')" 
                                                    class="p-2 text-neutral-400 hover:text-error-600 hover:bg-error-50 rounded-lg transition-colors"
                                                    title="Reject">
                                                <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-x-circle'); ?>
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
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if($budgetRequests->hasPages()): ?>
                <div class="px-6 py-4 border-t border-neutral-100">
                    <?php if (isset($component)) { $__componentOriginal4d04f29578652eb91560cfbf2ab48c57 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4d04f29578652eb91560cfbf2ab48c57 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.pagination','data' => ['paginator' => $budgetRequests]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.pagination'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['paginator' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($budgetRequests)]); ?>
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
        <?php else: ?>
            <?php if (isset($component)) { $__componentOriginal3607a477fdef7402bc742abad5df9c51 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3607a477fdef7402bc742abad5df9c51 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.empty-state','data' => ['icon' => 'wallet','title' => 'No budget requests found','description' => request()->hasAny(['search', 'status']) 
                    ? 'No requests match your current filters. Try adjusting your search criteria.' 
                    : 'No budget change requests have been submitted yet.','class' => 'py-12']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'wallet','title' => 'No budget requests found','description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->hasAny(['search', 'status']) 
                    ? 'No requests match your current filters. Try adjusting your search criteria.' 
                    : 'No budget change requests have been submitted yet.'),'class' => 'py-12']); ?>
                <?php if(request()->hasAny(['search', 'status'])): ?>
                    <a href="<?php echo e(route('admin.budget-requests.index')); ?>" class="mt-4 inline-block">
                        <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['variant' => 'secondary','icon' => 'x']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'secondary','icon' => 'x']); ?>
                            Clear Filters
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
                    </a>
                <?php endif; ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3607a477fdef7402bc742abad5df9c51)): ?>
<?php $attributes = $__attributesOriginal3607a477fdef7402bc742abad5df9c51; ?>
<?php unset($__attributesOriginal3607a477fdef7402bc742abad5df9c51); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3607a477fdef7402bc742abad5df9c51)): ?>
<?php $component = $__componentOriginal3607a477fdef7402bc742abad5df9c51; ?>
<?php unset($__componentOriginal3607a477fdef7402bc742abad5df9c51); ?>
<?php endif; ?>
        <?php endif; ?>
    </div>

    <!-- Approve Modal -->
    <div id="approveModal" x-data="{ show: false }" x-show="show" x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-neutral-900/50 backdrop-blur-sm" @click="show = false"></div>
            
            <div class="relative bg-white rounded-2xl shadow-lg max-w-md w-full p-6">
                <form id="approveForm" method="POST" action="">
                    <?php echo csrf_field(); ?>
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-semibold text-neutral-800">Approve Budget Request</h3>
                            <p class="text-sm text-neutral-500 mt-1">Confirm approval for this budget change</p>
                        </div>
                        <button type="button" onclick="closeApproveModal()" class="p-2 text-neutral-400 hover:text-neutral-600 hover:bg-neutral-100 rounded-lg transition-colors">
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
                    
                    <p class="text-sm text-neutral-600 mb-4">
                        Approve budget change request for <strong id="approveTaskName" class="text-neutral-800"></strong> 
                        by <strong id="approveAdiutorName" class="text-neutral-800"></strong>?
                    </p>
                    
                    <!-- Form Content -->
                    <div class="mb-6">
                        <?php if (isset($component)) { $__componentOriginal65bd7e7dbd93cec773ad6501ce127e46 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.input','data' => ['type' => 'textarea','label' => 'Review Notes (Optional)','name' => 'review_notes','rows' => '3','placeholder' => 'Add any notes about this approval...']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'textarea','label' => 'Review Notes (Optional)','name' => 'review_notes','rows' => '3','placeholder' => 'Add any notes about this approval...']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46)): ?>
<?php $attributes = $__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46; ?>
<?php unset($__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal65bd7e7dbd93cec773ad6501ce127e46)): ?>
<?php $component = $__componentOriginal65bd7e7dbd93cec773ad6501ce127e46; ?>
<?php unset($__componentOriginal65bd7e7dbd93cec773ad6501ce127e46); ?>
<?php endif; ?>
                    </div>
                    
                    <!-- Modal Actions -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-neutral-200">
                        <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['type' => 'button','variant' => 'ghost','onclick' => 'closeApproveModal()']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','variant' => 'ghost','onclick' => 'closeApproveModal()']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['type' => 'submit','variant' => 'success','icon' => 'check-circle']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit','variant' => 'success','icon' => 'check-circle']); ?>
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
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" x-data="{ show: false }" x-show="show" x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-neutral-900/50 backdrop-blur-sm" @click="show = false"></div>
            
            <div class="relative bg-white rounded-2xl shadow-lg max-w-md w-full p-6">
                <form id="rejectForm" method="POST" action="">
                    <?php echo csrf_field(); ?>
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-semibold text-neutral-800">Reject Budget Request</h3>
                            <p class="text-sm text-neutral-500 mt-1">Provide a reason for rejection</p>
                        </div>
                        <button type="button" onclick="closeRejectModal()" class="p-2 text-neutral-400 hover:text-neutral-600 hover:bg-neutral-100 rounded-lg transition-colors">
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
                    
                    <p class="text-sm text-neutral-600 mb-4">
                        Reject budget change request for <strong id="rejectTaskName" class="text-neutral-800"></strong> 
                        by <strong id="rejectAdiutorName" class="text-neutral-800"></strong>?
                    </p>
                    
                    <!-- Form Content -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-neutral-700 mb-1.5">
                            Rejection Reason <span class="text-error-500">*</span>
                        </label>
                        <textarea name="review_notes" 
                                  rows="3" 
                                  required
                                  class="w-full px-4 py-2.5 text-sm text-neutral-700 bg-white border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all"
                                  placeholder="Explain why this request is being rejected..."></textarea>
                    </div>
                    
                    <!-- Modal Actions -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-neutral-200">
                        <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['type' => 'button','variant' => 'ghost','onclick' => 'closeRejectModal()']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','variant' => 'ghost','onclick' => 'closeRejectModal()']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['type' => 'submit','variant' => 'danger','icon' => 'x-circle']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit','variant' => 'danger','icon' => 'x-circle']); ?>
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
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function openApproveModal(id, taskName, adiutorName) {
    document.getElementById('approveTaskName').textContent = taskName;
    document.getElementById('approveAdiutorName').textContent = adiutorName;
    document.getElementById('approveForm').action = `/admin/budget-requests/${id}/approve`;
    document.getElementById('approveModal').classList.remove('hidden');
    document.getElementById('approveModal').__x.$data.show = true;
}

function closeApproveModal() {
    document.getElementById('approveModal').__x.$data.show = false;
}

function openRejectModal(id, taskName, adiutorName) {
    document.getElementById('rejectTaskName').textContent = taskName;
    document.getElementById('rejectAdiutorName').textContent = adiutorName;
    document.getElementById('rejectForm').action = `/admin/budget-requests/${id}/reject`;
    document.getElementById('rejectModal').classList.remove('hidden');
    document.getElementById('rejectModal').__x.$data.show = true;
}

function closeRejectModal() {
    document.getElementById('rejectModal').__x.$data.show = false;
}

// Close modals on ESC key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeApproveModal();
        closeRejectModal();
    }
});
</script>

<?php if(session('success')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.showSuccess) {
                window.showSuccess('Success', '<?php echo e(session('success')); ?>');
            }
        });
    </script>
<?php endif; ?>

<?php if(session('error')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.showError) {
                window.showError('Error', '<?php echo e(session('error')); ?>');
            }
        });
    </script>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\marka\Projects\cms\resources\views/admin/budget-requests/index.blade.php ENDPATH**/ ?>