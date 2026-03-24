<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title'); ?> | E-LEARN</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-zinc-900 min-h-screen flex items-center justify-center p-6 text-white">

    <div class="w-full max-w-md">
        <!-- Logo -->
        <div class="text-center mb-8">
            <span class="bg-blue-600 text-white px-3 py-1 font-bold tracking-widest uppercase rounded-lg">E-Learn</span>
        </div>

        <!-- Flash Messages -->
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
            <div class="mb-4 p-4 rounded-xl bg-green-900/50 border border-green-700 text-green-300 text-sm text-center">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
            <div class="mb-4 p-4 rounded-xl bg-red-900/50 border border-red-700 text-red-300 text-sm text-center">
                <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('info')): ?>
            <div class="mb-4 p-4 rounded-xl bg-blue-900/50 border border-blue-700 text-blue-300 text-sm text-center">
                <?php echo e(session('info')); ?>

            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
            <div class="mb-4 p-4 rounded-xl bg-red-900/50 border border-red-700 text-red-300 text-sm">
                <ul class="list-disc list-inside space-y-1">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </ul>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <!-- Form Card -->
        <div class="bg-zinc-900 border border-zinc-800 p-8 rounded-3xl shadow-2xl">
            <h2 class="text-2xl font-bold text-white mb-6 text-center"><?php echo $__env->yieldContent('title'); ?></h2>

            <!-- Form -->
            <?php echo $__env->yieldContent('form'); ?> 

            <!-- Footer -->
            <div class="mt-6 text-center text-gray-400">
                <?php echo $__env->yieldContent('footer'); ?>
            </div>
        </div>
    </div>

</body>
</html>
<?php /**PATH C:\Users\Isaac\Desktop\E-learning\resources\views/layouts/auth.blade.php ENDPATH**/ ?>