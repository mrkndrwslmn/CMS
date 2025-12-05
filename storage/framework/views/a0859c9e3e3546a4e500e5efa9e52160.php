<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
    'href' => null,
    'disabled' => false,
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
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
    'href' => null,
    'disabled' => false,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $baseClasses = 'inline-flex items-center justify-center gap-2 font-medium transition-all duration-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed';
    
    $variants = [
        'primary' => 'bg-primary-500 text-white hover:bg-primary-600 focus:ring-primary-500 shadow-sm hover:shadow-md',
        'secondary' => 'bg-neutral-100 text-neutral-700 hover:bg-neutral-200 focus:ring-neutral-400',
        'outline' => 'border border-neutral-200 bg-white text-neutral-700 hover:bg-neutral-50 hover:border-neutral-300 focus:ring-primary-500',
        'ghost' => 'text-neutral-600 hover:bg-neutral-100 hover:text-neutral-900 focus:ring-neutral-400',
        'danger' => 'bg-error-500 text-white hover:bg-error-600 focus:ring-error-500 shadow-sm hover:shadow-md',
        'success' => 'bg-success-500 text-white hover:bg-success-600 focus:ring-success-500 shadow-sm hover:shadow-md',
    ];
    
    $sizes = [
        'xs' => 'px-2.5 py-1.5 text-xs',
        'sm' => 'px-3 py-2 text-sm',
        'md' => 'px-4 py-2.5 text-sm',
        'lg' => 'px-5 py-3 text-base',
        'xl' => 'px-6 py-3.5 text-base',
    ];
    
    $classes = $baseClasses . ' ' . ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md']);
?>

<?php if($href): ?>
    <a href="<?php echo e($href); ?>" <?php echo e($attributes->merge(['class' => $classes])); ?>>
        <?php echo e($slot); ?>

    </a>
<?php else: ?>
    <button type="<?php echo e($type); ?>" <?php echo e($disabled ? 'disabled' : ''); ?> <?php echo e($attributes->merge(['class' => $classes])); ?>>
        <?php echo e($slot); ?>

    </button>
<?php endif; ?>
<?php /**PATH C:\Users\marka\Projects\cms\resources\views/components/ui/button.blade.php ENDPATH**/ ?>