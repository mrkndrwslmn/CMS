<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'label' => null,
    'error' => null,
    'hint' => null,
    'required' => false,
    'disabled' => false,
    'placeholder' => 'Select an option',
    'options' => [],
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
    'label' => null,
    'error' => null,
    'hint' => null,
    'required' => false,
    'disabled' => false,
    'placeholder' => 'Select an option',
    'options' => [],
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $selectClasses = 'block w-full px-4 py-2.5 text-neutral-900 bg-white border rounded-lg shadow-sm transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-0 disabled:bg-neutral-50 disabled:text-neutral-500 disabled:cursor-not-allowed appearance-none bg-no-repeat';
    
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
    
    <div class="relative">
        <select 
            <?php echo e($disabled ? 'disabled' : ''); ?>

            <?php echo e($required ? 'required' : ''); ?>

            <?php echo e($attributes->except('class')->merge(['class' => $selectClasses . ' ' . $borderClasses . ' pr-10'])); ?>

        >
            <?php if($placeholder): ?>
                <option value="" disabled selected><?php echo e($placeholder); ?></option>
            <?php endif; ?>
            
            <?php if(count($options) > 0): ?>
                <?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $optionLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($value); ?>"><?php echo e($optionLabel); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
                <?php echo e($slot); ?>

            <?php endif; ?>
        </select>
        
        
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
            <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-chevron-down'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-4 w-4 text-neutral-400']); ?>
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
    
    <?php if($hint && !$error): ?>
        <p class="text-sm text-neutral-500"><?php echo e($hint); ?></p>
    <?php endif; ?>
    
    <?php if($error): ?>
        <p class="text-sm text-error-600"><?php echo e($error); ?></p>
    <?php endif; ?>
</div>
<?php /**PATH C:\Users\marka\Projects\cms\resources\views/components/ui/select.blade.php ENDPATH**/ ?>