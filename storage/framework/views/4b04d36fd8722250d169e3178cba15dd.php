<?php $__env->startSection('content'); ?>
<div class="p-6">
    <h1 class="text-2xl font-bold mb-6">Admin Dashboard</h1>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-zinc-800 dark:bg-gray-800 p-4 rounded-lg shadow">
            <div class="text-gray-500 text-sm">Total Users</div>
            <div class="text-2xl font-bold"><?php echo e($totalUsers); ?></div>
        </div>
        <div class="bg-zinc-800 dark:bg-gray-800 p-4 rounded-lg shadow">
            <div class="text-gray-500 text-sm">Active Users</div>
            <div class="text-2xl font-bold"><?php echo e($activeUsers); ?></div>
        </div>
        <div class="bg-zinc-800 dark:bg-gray-800 p-4 rounded-lg shadow">
            <div class="text-gray-500 text-sm">New Users Today</div>
            <div class="text-2xl font-bold"><?php echo e($newUsersToday); ?></div>
        </div>
        <div class="bg-zinc-800 dark:bg-gray-800 p-4 rounded-lg shadow">
            <div class="text-gray-500 text-sm">Total Courses</div>
            <div class="text-2xl font-bold"><?php echo e(\App\Models\Course::count()); ?></div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-zinc-800 dark:bg-gray-800 p-6 rounded-lg shadow">
        <h2 class="text-lg font-semibold mb-4">Recent Activity</h2>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activities->count() > 0): ?>
            <div class="space-y-3">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="border-b pb-2">
                        <p class="font-medium"><?php echo e($activity->action); ?></p>
                        <p class="text-sm text-gray-500"><?php echo e($activity->description); ?></p>
                        <p class="text-xs text-gray-400"><?php echo e($activity->created_at->diffForHumans()); ?></p>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        <?php else: ?>
            <p class="text-gray-500">No recent activity.</p>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Isaac\Desktop\E-learning\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>