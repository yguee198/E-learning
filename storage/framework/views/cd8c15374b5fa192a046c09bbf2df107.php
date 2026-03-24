<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['title', 'trigger']));

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

foreach (array_filter((['title', 'trigger']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div x-cloak
     class="fixed inset-y-0 right-0 w-80 surface-card border-l border-subtle shadow-lg transform transition-transform duration-300 ease-in-out z-40"
     :class="{ 'translate-x-0': <?php echo e($trigger); ?>, 'translate-x-full': !<?php echo e($trigger); ?> }">

    <div class="flex items-center justify-between p-4 border-b border-subtle">
        <h3 class="font-semibold p-2 text-primary"><?php echo e($title); ?></h3>
        <button @click="$emit('close')" class="p-1 text-secondary hover:text-primary rounded-md hover:surface-muted transition-colors">
            <?php echo e(svg('heroicon-o-x-mark', 'w-5 h-5')); ?>
        </button>
    </div>

    <div class="h-full overflow-y-auto pb-20">
        <?php echo e($slot); ?>

    </div>
</div>
<?php /**PATH C:\Users\Isaac\Desktop\E-learning\resources\views/components/layout/right-panel.blade.php ENDPATH**/ ?>