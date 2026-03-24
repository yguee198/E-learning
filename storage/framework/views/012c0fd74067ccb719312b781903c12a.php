<div class="space-y-4">
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $this->notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="relative group flex items-start p-4 rounded-xl surface-card border border-subtle hover:surface-muted transition-colors <?php echo e($notification->read_at ? 'opacity-75' : 'surface-card ring-1 ring-[var(--accent-primary)]/10'); ?>">
            
            <!-- Icon -->
            <div class="flex-shrink-0 mr-4">
                <div class="h-10 w-10 rounded-full flex items-center justify-center <?php echo e($notification->read_at ? 'surface-muted text-secondary' : 'bg-[var(--accent-primary)]/10 text-accent'); ?>">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($notification->data['type']) && $notification->data['type'] == 'alert'): ?>
                         <?php echo e(svg('heroicon-o-exclamation-triangle', 'w-5 h-5')); ?>
                    <?php elseif(isset($notification->data['type']) && $notification->data['type'] == 'success'): ?>
                        <?php echo e(svg('heroicon-o-check-circle', 'w-5 h-5')); ?>
                    <?php else: ?>
                        <?php echo e(svg('heroicon-o-bell', 'w-5 h-5')); ?>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>

            <!-- Content -->
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between mb-1">
                    <p class="text-sm font-semibold text-primary truncate">
                        <?php echo e($notification->data['title'] ?? 'Notification'); ?>

                    </p>
                    <span class="text-xs text-secondary">
                        <?php echo e($notification->created_at->diffForHumans()); ?>

                    </span>
                </div>
                <p class="text-sm text-secondary line-clamp-2">
                    <?php echo e($notification->data['message'] ?? 'You have a new notification.'); ?>

                </p>
                
                <!-- Actions -->
                <div class="mt-2 flex space-x-3 opacity-0 group-hover:opacity-100 transition-opacity">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (! ($notification->read_at)): ?>
                        <button wire:click="markAsRead('<?php echo e($notification->id); ?>')" class="text-xs font-medium text-accent hover:text-accent-secondary">
                            Mark as Read
                        </button>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <button wire:click="deleteNotification('<?php echo e($notification->id); ?>')" class="text-xs font-medium text-secondary hover:text-error">
                        Remove
                    </button>
                </div>
            </div>

            <!-- Unread Indicator -->
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (! ($notification->read_at)): ?>
                <div class="absolute top-4 right-4 h-2 w-2 rounded-full bg-accent"></div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="flex flex-col items-center justify-center p-8 text-center text-secondary opacity-70">
            <div class="w-16 h-16 surface-muted rounded-full flex items-center justify-center mb-4">
                <?php echo e(svg('heroicon-o-bell-slash', 'w-8 h-8 text-secondary')); ?>
            </div>
            <h4 class="text-base font-medium text-primary">No new notifications</h4>
            <p class="text-sm mt-1">Waiting for system events...</p>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH C:\Users\Isaac\Desktop\E-learning\resources\views/livewire/notifications.blade.php ENDPATH**/ ?>