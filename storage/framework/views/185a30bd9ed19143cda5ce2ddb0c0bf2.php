<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'type' => 'text',
    'label' => null,
    'error' => null,
    'hint' => null,
    'required' => false,
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
    'type' => 'text',
    'label' => null,
    'error' => null,
    'hint' => null,
    'required' => false,
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
    $inputClasses = 'block w-full px-4 py-2.5 text-neutral-900 bg-white border rounded-lg shadow-sm transition-all duration-200 placeholder:text-neutral-400 focus:outline-none focus:ring-2 focus:ring-offset-0 disabled:bg-neutral-50 disabled:text-neutral-500 disabled:cursor-not-allowed';
    
    $borderClasses = $error 
        ? 'border-error-300 focus:border-error-500 focus:ring-error-500/20' 
        : 'border-neutral-200 focus:border-primary-500 focus:ring-primary-500/20';
?>

<div <?php echo e($attributes->only('class')->merge(['class' => 'space-y-1.5'])); ?>>
    <?php if($label): ?>
        <label class="block text-sm font-medium text-neutral-700">
            <?php echo e($label); ?>

            <?php if($required): ?>
                <span class="text-error-500">*</span>
            <?php endif; ?>
        </label>
    <?php endif; ?>
    
    <input 
        type="<?php echo e($type); ?>"
        <?php echo e($disabled ? 'disabled' : ''); ?>

        <?php echo e($required ? 'required' : ''); ?>

        <?php echo e($attributes->except('class')->merge(['class' => $inputClasses . ' ' . $borderClasses])); ?>

    />
    
    <?php if($hint && !$error): ?>
        <p class="text-sm text-neutral-500"><?php echo e($hint); ?></p>
    <?php endif; ?>
    
    <?php if($error): ?>
        <p class="text-sm text-error-600"><?php echo e($error); ?></p>
    <?php endif; ?>
</div>
<?php /**PATH C:\Users\marka\Projects\cms\resources\views/components/ui/input.blade.php ENDPATH**/ ?>