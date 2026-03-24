<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['activities' => []]));

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

foreach (array_filter((['activities' => []]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="surface-card rounded-xl shadow-sm border border-subtle h-full">
    <div class="p-6 border-b border-subtle flex items-center justify-between">
        <h3 class="text-lg font-semibold text-primary">Recent Activity</h3>
        <a href="#" class="text-sm text-accent hover:text-accent-secondary font-medium">View All</a>
    </div>

    <div class="p-0 overflow-hidden">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($activities) > 0): ?>
            <ul class="divide-y divide-subtle">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="p-4 hover:surface-muted/50 transition-colors">
                        <div class="flex space-x-3">
                            <div class="flex-shrink-0">
                                <div class="h-8 w-8 rounded-full bg-[var(--accent-primary)]/10 flex items-center justify-center text-accent">
                                    <?php echo e(svg('heroicon-o-bolt', 'w-4 h-4')); ?>
                                </div>
                            </div>
                            <div class="flex-1 space-y-1">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-sm font-medium text-primary">
                                        <?php echo e($activity->description); ?>

                                    </h3>
                                    <p class="text-xs text-secondary">
                                        <?php echo e($activity->created_at->diffForHumans()); ?>

                                    </p>
                                </div>
                                <p class="text-xs text-secondary">
                                    by <span class="font-medium text-primary"><?php echo e($activity->causer->name ?? 'System'); ?></span>
                                </p>
                            </div>
                        </div>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </ul>
        <?php else: ?>
             <div class="p-8 text-center text-secondary h-full flex flex-col items-center justify-center">
                <?php echo e(svg('heroicon-o-clipboard-document-list', 'w-12 h-12 mb-3 opacity-50')); ?>
                <p>Waiting for activity logs...</p>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>
<?php /**PATH C:\Users\Isaac\Desktop\E-learning\resources\views/components/admin/recent-activity.blade.php ENDPATH**/ ?>