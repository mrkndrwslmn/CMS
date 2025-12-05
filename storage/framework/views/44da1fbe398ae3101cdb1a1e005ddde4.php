<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['paginator', 'simple' => false]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['paginator', 'simple' => false]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php if($paginator->hasPages()): ?>
    <?php
        $elements = $paginator->getUrlRange(1, $paginator->lastPage());
        
        // Build proper elements array like Laravel pagination does
        $window = 3; // Number of pages to show on each side of current page
        $currentPage = $paginator->currentPage();
        $lastPage = $paginator->lastPage();
        
        // Calculate the start and end of the window
        $start = max(1, $currentPage - $window);
        $end = min($lastPage, $currentPage + $window);
        
        // Adjust if we're near the beginning or end
        if ($currentPage <= $window) {
            $end = min($lastPage, ($window * 2) + 1);
        }
        if ($currentPage > $lastPage - $window) {
            $start = max(1, $lastPage - ($window * 2));
        }
        
        $paginationElements = [];
        
        // Add first page if not in window
        if ($start > 1) {
            $paginationElements[] = [1 => $paginator->url(1)];
            if ($start > 2) {
                $paginationElements[] = '...';
            }
        }
        
        // Add pages in window
        $windowPages = [];
        for ($i = $start; $i <= $end; $i++) {
            $windowPages[$i] = $paginator->url($i);
        }
        $paginationElements[] = $windowPages;
        
        // Add last page if not in window
        if ($end < $lastPage) {
            if ($end < $lastPage - 1) {
                $paginationElements[] = '...';
            }
            $paginationElements[] = [$lastPage => $paginator->url($lastPage)];
        }
    ?>

    <nav role="navigation" aria-label="<?php echo e(__('Pagination Navigation')); ?>" <?php echo e($attributes->merge(['class' => 'flex items-center justify-between'])); ?>>
        
        <div class="flex justify-between flex-1 sm:hidden">
            <?php if($paginator->onFirstPage()): ?>
                <span class="inline-flex items-center px-4 py-2 text-sm font-medium text-neutral-400 bg-white border border-neutral-200 rounded-lg cursor-not-allowed">
                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-chevron-left'); ?>
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
                    Previous
                </span>
            <?php else: ?>
                <a href="<?php echo e($paginator->previousPageUrl()); ?>" class="inline-flex items-center px-4 py-2 text-sm font-medium text-neutral-600 bg-white border border-neutral-200 rounded-lg hover:bg-neutral-50 hover:border-neutral-300 transition-all duration-200">
                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-chevron-left'); ?>
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
                    Previous
                </a>
            <?php endif; ?>

            <span class="inline-flex items-center px-3 py-2 text-sm text-neutral-500">
                <?php echo e($paginator->currentPage()); ?> / <?php echo e($paginator->lastPage()); ?>

            </span>

            <?php if($paginator->hasMorePages()): ?>
                <a href="<?php echo e($paginator->nextPageUrl()); ?>" class="inline-flex items-center px-4 py-2 text-sm font-medium text-neutral-600 bg-white border border-neutral-200 rounded-lg hover:bg-neutral-50 hover:border-neutral-300 transition-all duration-200">
                    Next
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
            <?php else: ?>
                <span class="inline-flex items-center px-4 py-2 text-sm font-medium text-neutral-400 bg-white border border-neutral-200 rounded-lg cursor-not-allowed">
                    Next
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
                </span>
            <?php endif; ?>
        </div>

        
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
            
            <div>
                <p class="text-sm text-neutral-500">
                    Showing
                    <?php if($paginator->firstItem()): ?>
                        <span class="font-medium text-neutral-700"><?php echo e($paginator->firstItem()); ?></span>
                        to
                        <span class="font-medium text-neutral-700"><?php echo e($paginator->lastItem()); ?></span>
                    <?php else: ?>
                        <span class="font-medium text-neutral-700">0</span>
                    <?php endif; ?>
                    of
                    <span class="font-medium text-neutral-700"><?php echo e($paginator->total()); ?></span>
                    results
                </p>
            </div>

            
            <div class="flex items-center gap-1">
                
                <?php if($paginator->onFirstPage()): ?>
                    <span class="inline-flex items-center justify-center w-9 h-9 text-neutral-300 bg-white border border-neutral-200 rounded-lg cursor-not-allowed" aria-disabled="true">
                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-chevron-left'); ?>
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
                    </span>
                <?php else: ?>
                    <a href="<?php echo e($paginator->previousPageUrl()); ?>" rel="prev" class="inline-flex items-center justify-center w-9 h-9 text-neutral-500 bg-white border border-neutral-200 rounded-lg hover:bg-neutral-50 hover:border-neutral-300 hover:text-neutral-700 transition-all duration-200" aria-label="<?php echo e(__('pagination.previous')); ?>">
                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-chevron-left'); ?>
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
                <?php endif; ?>

                <?php if(!$simple): ?>
                    
                    <?php $__currentLoopData = $paginationElements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        
                        <?php if(is_string($element)): ?>
                            <span class="inline-flex items-center justify-center w-9 h-9 text-neutral-400 text-sm">
                                <?php echo e($element); ?>

                            </span>
                        <?php endif; ?>

                        
                        <?php if(is_array($element)): ?>
                            <?php $__currentLoopData = $element; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($page == $paginator->currentPage()): ?>
                                    <span aria-current="page" class="inline-flex items-center justify-center w-9 h-9 text-sm font-medium text-white bg-primary-500 border border-primary-500 rounded-lg shadow-sm">
                                        <?php echo e($page); ?>

                                    </span>
                                <?php else: ?>
                                    <a href="<?php echo e($url); ?>" class="inline-flex items-center justify-center w-9 h-9 text-sm font-medium text-neutral-600 bg-white border border-neutral-200 rounded-lg hover:bg-neutral-50 hover:border-neutral-300 hover:text-neutral-800 transition-all duration-200" aria-label="<?php echo e(__('Go to page :page', ['page' => $page])); ?>">
                                        <?php echo e($page); ?>

                                    </a>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    
                    <span class="inline-flex items-center px-3 py-2 text-sm text-neutral-500">
                        Page <span class="font-medium text-neutral-700 mx-1"><?php echo e($paginator->currentPage()); ?></span> of <span class="font-medium text-neutral-700 mx-1"><?php echo e($paginator->lastPage()); ?></span>
                    </span>
                <?php endif; ?>

                
                <?php if($paginator->hasMorePages()): ?>
                    <a href="<?php echo e($paginator->nextPageUrl()); ?>" rel="next" class="inline-flex items-center justify-center w-9 h-9 text-neutral-500 bg-white border border-neutral-200 rounded-lg hover:bg-neutral-50 hover:border-neutral-300 hover:text-neutral-700 transition-all duration-200" aria-label="<?php echo e(__('pagination.next')); ?>">
                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-chevron-right'); ?>
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
                <?php else: ?>
                    <span class="inline-flex items-center justify-center w-9 h-9 text-neutral-300 bg-white border border-neutral-200 rounded-lg cursor-not-allowed" aria-disabled="true">
                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-chevron-right'); ?>
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
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </nav>
<?php endif; ?>
<?php /**PATH C:\Users\marka\Projects\cms\resources\views/components/ui/pagination.blade.php ENDPATH**/ ?>