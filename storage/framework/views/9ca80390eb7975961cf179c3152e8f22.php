<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'type' => 'default',
    'size' => 'md',
    'dot' => false,
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
    'type' => 'default',
    'size' => 'md',
    'dot' => false,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $baseClasses = 'inline-flex items-center font-medium rounded-full';
    
    $types = [
        'default' => 'bg-neutral-100 text-neutral-700',
        'primary' => 'bg-primary-50 text-primary-700',
        'success' => 'bg-success-50 text-success-700',
        'warning' => 'bg-warning-50 text-warning-700',
        'error' => 'bg-error-50 text-error-700',
    ];
    
    $sizes = [
        'sm' => 'px-2 py-0.5 text-xs',
        'md' => 'px-2.5 py-1 text-xs',
        'lg' => 'px-3 py-1.5 text-sm',
    ];
    
    $dotColors = [
        'default' => 'bg-neutral-400',
        'primary' => 'bg-primary-400',
        'success' => 'bg-success-400',
        'warning' => 'bg-warning-400',
        'error' => 'bg-error-400',
    ];
    
    $classes = $baseClasses . ' ' . ($types[$type] ?? $types['default']) . ' ' . ($sizes[$size] ?? $sizes['md']);
?>

<span <?php echo e($attributes->merge(['class' => $classes])); ?>>
    <?php if($dot): ?>
        <span class="mr-1.5 h-1.5 w-1.5 rounded-full <?php echo e($dotColors[$type] ?? $dotColors['default']); ?>"></span>
    <?php endif; ?>
    <?php echo e($slot); ?>

</span>
<?php /**PATH C:\Users\marka\Projects\cms\resources\views/components/ui/badge.blade.php ENDPATH**/ ?>