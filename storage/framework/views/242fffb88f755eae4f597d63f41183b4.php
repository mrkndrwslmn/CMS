<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'title' => null,
    'description' => null,
    'subtitle' => null,
    'actions' => null,
]));

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

foreach (array_filter(([
    'title' => null,
    'description' => null,
    'subtitle' => null,
    'actions' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>



<?php
    // Support both 'description' and 'subtitle' props
    $desc = $description ?? $subtitle;
?>

<div <?php echo e($attributes->merge(['class' => 'mb-8'])); ?>>
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <?php if($title): ?>
                <h1 class="text-2xl font-semibold text-neutral-900"><?php echo e($title); ?></h1>
            <?php endif; ?>
            <?php if($desc): ?>
                <p class="mt-1 text-sm text-neutral-500"><?php echo e($desc); ?></p>
            <?php endif; ?>
        </div>
        
        <?php if($actions): ?>
            <div class="flex items-center gap-3">
                <?php echo e($actions); ?>

            </div>
        <?php endif; ?>
    </div>
</div>
<?php /**PATH C:\Users\marka\Projects\cms\resources\views/components/ui/page-header.blade.php ENDPATH**/ ?>