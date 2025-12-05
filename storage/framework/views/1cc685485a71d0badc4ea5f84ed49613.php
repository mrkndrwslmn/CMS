<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'padding' => 'md',
    'shadow' => 'sm',
    'hover' => false,
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
    'padding' => 'md',
    'shadow' => 'sm',
    'hover' => false,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $baseClasses = 'bg-white rounded-2xl border border-neutral-100';
    
    $paddings = [
        'none' => '',
        'sm' => 'p-4',
        'md' => 'p-6',
        'lg' => 'p-8',
    ];
    
    $shadows = [
        'none' => '',
        'sm' => 'shadow-sm',
        'md' => 'shadow-md',
        'lg' => 'shadow-lg',
    ];
    
    $hoverClass = $hover ? 'transition-all duration-300 hover:shadow-md hover:-translate-y-1' : '';
    
    $classes = $baseClasses . ' ' . ($paddings[$padding] ?? $paddings['md']) . ' ' . ($shadows[$shadow] ?? $shadows['sm']) . ' ' . $hoverClass;
?>

<div <?php echo e($attributes->merge(['class' => $classes])); ?>>
    <?php echo e($slot); ?>

</div>
<?php /**PATH C:\Users\marka\Projects\cms\resources\views/components/ui/card.blade.php ENDPATH**/ ?>