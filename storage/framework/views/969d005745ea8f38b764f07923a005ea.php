<?php $__env->startSection('content'); ?>
    <?php echo $__env->yieldContent('content'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->slot('sidebar'); ?>
    <?php if (isset($component)) { $__componentOriginal62bc8b4fb99dd2cfe7a7dadf94c9b153 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal62bc8b4fb99dd2cfe7a7dadf94c9b153 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layout.admin-sidebar','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layout.admin-sidebar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal62bc8b4fb99dd2cfe7a7dadf94c9b153)): ?>
<?php $attributes = $__attributesOriginal62bc8b4fb99dd2cfe7a7dadf94c9b153; ?>
<?php unset($__attributesOriginal62bc8b4fb99dd2cfe7a7dadf94c9b153); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal62bc8b4fb99dd2cfe7a7dadf94c9b153)): ?>
<?php $component = $__componentOriginal62bc8b4fb99dd2cfe7a7dadf94c9b153; ?>
<?php unset($__componentOriginal62bc8b4fb99dd2cfe7a7dadf94c9b153); ?>
<?php endif; ?>
<?php $__env->endSlot(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Isaac\Desktop\E-learning\resources\views/layouts/admin.blade.php ENDPATH**/ ?>