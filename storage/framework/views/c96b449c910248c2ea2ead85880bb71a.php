

<?php $__env->startSection('title', 'Payout History'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .chart-container {
        position: relative;
        height: 200px;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="w-full">
    <div class="w-full px-4 py-5">
        <!-- Breadcrumb -->
        <?php if (isset($component)) { $__componentOriginal045477955e5b1d8c9df01934ca3836c0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal045477955e5b1d8c9df01934ca3836c0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.breadcrumb','data' => ['items' => [
            ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
            ['label' => 'Earnings Analytics', 'route' => 'admin.earnings-analytics.index', 'icon' => 'bar-chart-2'],
            ['label' => 'Payout History', 'icon' => 'wallet'],
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
            ['label' => 'Payout History', 'icon' => 'wallet'],
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
                        Payout History
                    </h1>
                    <p class="text-sm text-neutral-500 mt-1">Complete history of all adiutor payouts</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <a href="<?php echo e(route('admin.earnings-analytics.export', ['type' => 'payouts', 'period' => request('period', 90)])); ?>" 
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
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-neutral-500 uppercase">Total Payouts</p>
                        <p class="text-xl font-semibold text-neutral-800 mt-1"><?php echo e(number_format($stats['total_payouts'])); ?></p>
                    </div>
                    <div class="p-2 bg-primary-50 rounded-lg">
                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-list'); ?>
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
                </div>
            </div>
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-neutral-500 uppercase">Completed</p>
                        <p class="text-xl font-semibold text-success-600 mt-1"><?php echo e(number_format($stats['completed_payouts'])); ?></p>
                    </div>
                    <div class="p-2 bg-success-50 rounded-lg">
                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-check-circle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-4 h-4 text-success-500']); ?>
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
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-neutral-500 uppercase">Pending</p>
                        <p class="text-xl font-semibold text-warning-600 mt-1"><?php echo e(number_format($stats['pending_payouts'])); ?></p>
                    </div>
                    <div class="p-2 bg-warning-50 rounded-lg">
                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-clock'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-4 h-4 text-warning-500']); ?>
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
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-neutral-500 uppercase">Total Paid</p>
                        <p class="text-xl font-semibold text-primary-600 mt-1">₱<?php echo e(number_format($stats['total_paid'], 0)); ?></p>
                    </div>
                    <div class="p-2 bg-primary-50 rounded-lg">
                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-banknote'); ?>
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
                </div>
            </div>
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-neutral-500 uppercase">Pending Amount</p>
                        <p class="text-xl font-semibold text-neutral-600 mt-1">₱<?php echo e(number_format($stats['pending_amount'], 0)); ?></p>
                    </div>
                    <div class="p-2 bg-neutral-50 rounded-lg">
                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-hourglass'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-4 h-4 text-neutral-400']); ?>
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
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-neutral-500 uppercase">Avg Payout</p>
                        <p class="text-xl font-semibold text-neutral-600 mt-1">₱<?php echo e(number_format($stats['average_payout'] ?? 0, 0)); ?></p>
                    </div>
                    <div class="p-2 bg-neutral-50 rounded-lg">
                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-calculator'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-4 h-4 text-neutral-400']); ?>
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

        <!-- Monthly Trend Chart -->
        <?php if($monthlyTrend->count() > 0): ?>
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-5 mb-6">
            <h5 class="text-lg font-medium text-neutral-700 mb-4 flex items-center gap-2">
                <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-bar-chart-2'); ?>
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
                Monthly Payout Trend
            </h5>
            <div class="chart-container">
                <canvas id="monthlyTrendChart"></canvas>
            </div>
        </div>
        <?php endif; ?>

        <!-- Filters -->
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-5 mb-6">
            <form method="GET" class="flex flex-wrap items-end gap-4">
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-sm font-medium text-neutral-700 mb-1">Period</label>
                    <select name="period" class="w-full px-4 py-2.5 text-sm text-neutral-700 bg-white border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all">
                        <option value="30" <?php echo e(request('period') == '30' ? 'selected' : ''); ?>>Last 30 days</option>
                        <option value="90" <?php echo e(request('period', '90') == '90' ? 'selected' : ''); ?>>Last 90 days</option>
                        <option value="180" <?php echo e(request('period') == '180' ? 'selected' : ''); ?>>Last 6 months</option>
                        <option value="365" <?php echo e(request('period') == '365' ? 'selected' : ''); ?>>Last year</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-sm font-medium text-neutral-700 mb-1">Status</label>
                    <select name="status" class="w-full px-4 py-2.5 text-sm text-neutral-700 bg-white border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all">
                        <option value="">All Statuses</option>
                        <option value="pending" <?php echo e($status == 'pending' ? 'selected' : ''); ?>>Pending</option>
                        <option value="processing" <?php echo e($status == 'processing' ? 'selected' : ''); ?>>Processing</option>
                        <option value="completed" <?php echo e($status == 'completed' ? 'selected' : ''); ?>>Completed</option>
                        <option value="cancelled" <?php echo e($status == 'cancelled' ? 'selected' : ''); ?>>Cancelled</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-sm font-medium text-neutral-700 mb-1">Adiutor</label>
                    <select name="adiutor_id" class="w-full px-4 py-2.5 text-sm text-neutral-700 bg-white border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all">
                        <option value="">All Adiutors</option>
                        <?php $__currentLoopData = $adiutors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $adiutor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($adiutor->id); ?>" <?php echo e($adiutorId == $adiutor->id ? 'selected' : ''); ?>>
                                <?php echo e($adiutor->fullName); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-primary-700 hover:shadow-md transition-all">
                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-filter'); ?>
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
<?php endif; ?> Apply
                    </button>
                </div>
                <div>
                    <a href="<?php echo e(route('admin.earnings-analytics.payout-history')); ?>" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-neutral-700 text-sm font-medium rounded-lg border border-neutral-200 shadow-sm hover:bg-neutral-50 hover:shadow-md transition-all">
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
<?php endif; ?> Clear
                    </a>
                </div>
            </form>
        </div>

        <!-- Payouts Table -->
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-neutral-50 border-b border-neutral-100">
                        <tr>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-5 text-left">Payout #</th>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-5 text-left">Adiutor</th>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-5 text-right">Amount</th>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-5 text-center">Status</th>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-5 text-left">Method</th>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-5 text-left">Reference</th>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-5 text-left">Requested</th>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-5 text-left">Processed By</th>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100">
                        <?php $__empty_1 = true; $__currentLoopData = $payouts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payout): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-neutral-50 transition-colors">
                                <td class="py-3 px-5">
                                    <span class="font-medium font-mono text-neutral-700"><?php echo e($payout->payout_number); ?></span>
                                </td>
                                <td class="py-3 px-5">
                                    <div class="font-medium text-neutral-700"><?php echo e($payout->adiutor?->fullName ?? 'N/A'); ?></div>
                                    <div class="text-xs text-neutral-500"><?php echo e($payout->adiutor?->email); ?></div>
                                </td>
                                <td class="py-3 px-5 text-right font-bold text-success-600">
                                    <?php echo e($payout->getFormattedAmount()); ?>

                                </td>
                                <td class="py-3 px-5 text-center">
                                    <?php
                                        $statusConfig = [
                                            'pending' => ['icon' => 'clock', 'class' => 'bg-warning-100 text-warning-700'],
                                            'processing' => ['icon' => 'loader', 'class' => 'bg-primary-100 text-primary-700'],
                                            'completed' => ['icon' => 'check-circle', 'class' => 'bg-success-100 text-success-700'],
                                            'cancelled' => ['icon' => 'x-circle', 'class' => 'bg-error-100 text-error-700'],
                                            'failed' => ['icon' => 'alert-circle', 'class' => 'bg-error-100 text-error-700'],
                                        ];
                                        $config = $statusConfig[$payout->status] ?? ['icon' => 'help-circle', 'class' => 'bg-neutral-100 text-neutral-700'];
                                    ?>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium <?php echo e($config['class']); ?>">
                                        <?php if (isset($component)) { $__componentOriginal511d4862ff04963c3c16115c05a86a9d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal511d4862ff04963c3c16115c05a86a9d = $attributes; } ?>
<?php $component = Illuminate\View\DynamicComponent::resolve(['component' => 'lucide-' . $config['icon']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dynamic-component'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\DynamicComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-3 h-3']); ?>
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
                                        <?php echo e(ucfirst($payout->status)); ?>

                                    </span>
                                </td>
                                <td class="py-3 px-5 text-neutral-600">
                                    <?php echo e($payout->payout_method ?? '-'); ?>

                                </td>
                                <td class="py-3 px-5">
                                    <?php if($payout->reference_number): ?>
                                        <span class="font-mono text-xs bg-neutral-100 px-2 py-1 rounded"><?php echo e($payout->reference_number); ?></span>
                                    <?php else: ?>
                                        <span class="text-neutral-400">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3 px-5 text-xs text-neutral-600">
                                    <?php if($payout->requested_at): ?>
                                        <div><?php echo e($payout->requested_at->format('M d, Y')); ?></div>
                                        <div class="text-neutral-400"><?php echo e($payout->requested_at->format('H:i')); ?></div>
                                    <?php else: ?>
                                        <span class="text-neutral-400">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3 px-5">
                                    <?php if($payout->processedBy): ?>
                                        <div class="text-sm text-neutral-600"><?php echo e($payout->processedBy->fullName); ?></div>
                                        <?php if($payout->completed_at): ?>
                                            <div class="text-xs text-neutral-400"><?php echo e($payout->completed_at->format('M d, Y')); ?></div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-neutral-400">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3 px-5 text-right">
                                    <a href="<?php echo e(route('admin.payouts.show', $payout->id)); ?>" 
                                       class="inline-flex items-center gap-1 px-3 py-1.5 bg-neutral-50 text-neutral-600 rounded-lg hover:bg-neutral-100 transition-colors text-xs font-medium">
                                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-eye'); ?>
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
<?php endif; ?> View
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="9" class="py-12 text-center text-neutral-500">
                                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-wallet'); ?>
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
                                    <p class="text-sm">No payouts found for the selected filters</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <?php if($payouts->hasPages()): ?>
                <div class="p-5 border-t border-neutral-100">
                    <?php if (isset($component)) { $__componentOriginal4d04f29578652eb91560cfbf2ab48c57 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4d04f29578652eb91560cfbf2ab48c57 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.pagination','data' => ['paginator' => $payouts->withQueryString()]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.pagination'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['paginator' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($payouts->withQueryString())]); ?>
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

<?php $__env->startPush('scripts'); ?>
<?php if($monthlyTrend->count() > 0): ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('monthlyTrendChart').getContext('2d');
    const trendData = <?php echo json_encode($monthlyTrend, 15, 512) ?>;
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: trendData.map(d => d.month),
            datasets: [{
                label: 'Payout Amount',
                data: trendData.map(d => d.total),
                backgroundColor: 'rgba(14, 165, 233, 0.7)',
                borderColor: '#0ea5e9',
                borderWidth: 1,
                borderRadius: 4,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false,
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const data = trendData[context.dataIndex];
                            return [
                                'Amount: ₱' + context.raw.toLocaleString('en-US', {minimumFractionDigits: 2}),
                                'Payouts: ' + data.count
                            ];
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '₱' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });
});
</script>
<?php endif; ?>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\marka\Projects\cms\resources\views/admin/earnings-analytics/payout-history.blade.php ENDPATH**/ ?>