<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['title', 'value', 'icon', 'color' => 'accent-primary', 'trend' => null, 'trendUp' => true]));

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

foreach (array_filter((['title', 'value', 'icon', 'color' => 'accent-primary', 'trend' => null, 'trendUp' => true]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="surface-card rounded-xl p-6 shadow-sm border border-subtle hover:shadow-md transition-shadow duration-300">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-sm font-medium text-secondary"><?php echo e($title); ?></h3>
        <div class="p-2 rounded-lg bg-[var(--<?php echo e($color); ?>)]/10 text-<?php echo e($color == 'accent-primary' ? 'accent' : ($color == 'accent-secondary' ? 'accent-secondary' : $color)); ?>">
            <?php echo e(svg($icon, 'w-6 h-6')); ?>
        </div>
    </div>
    
    <div class="flex items-end justify-between">
        <div class="text-2xl font-bold text-primary">
            <?php echo e($value); ?>

        </div>
        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($trend): ?>
            <div class="flex items-center text-sm font-medium <?php echo e($trendUp ? 'text-success' : 'text-error'); ?>">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($trendUp): ?>
                    <?php echo e(svg('heroicon-m-arrow-trending-up', 'w-4 h-4 mr-1')); ?>
                <?php else: ?>
                    <?php echo e(svg('heroicon-m-arrow-trending-down', 'w-4 h-4 mr-1')); ?>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php echo e($trend); ?>

            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>
<?php /**PATH C:\Users\Isaac\Desktop\E-learning\resources\views/components/admin/summary-card.blade.php ENDPATH**/ ?>