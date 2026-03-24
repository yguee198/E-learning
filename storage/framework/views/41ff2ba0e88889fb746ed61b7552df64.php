<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-text-primary">Dashboard Overview</h1>
            <p class="text-sm text-text-secondary">Welcome back, here's what's happening today.</p>
        </div>
        <div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-primary/10 text-primary border border-primary/20">
                <span class="w-2 h-2 mr-2 bg-primary rounded-full animate-pulse"></span>
                Live Updates
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <?php if (isset($component)) { $__componentOriginaledb832a7cbbcdfaaf8418911577c95e1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaledb832a7cbbcdfaaf8418911577c95e1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.summary-card','data' => ['title' => 'Total Users','value' => ''.e(number_format($totalUsers)).'','icon' => 'heroicon-o-users','color' => 'accent-primary','trend' => '12% vs last month','trendUp' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.summary-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Total Users','value' => ''.e(number_format($totalUsers)).'','icon' => 'heroicon-o-users','color' => 'accent-primary','trend' => '12% vs last month','trendUp' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaledb832a7cbbcdfaaf8418911577c95e1)): ?>
<?php $attributes = $__attributesOriginaledb832a7cbbcdfaaf8418911577c95e1; ?>
<?php unset($__attributesOriginaledb832a7cbbcdfaaf8418911577c95e1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaledb832a7cbbcdfaaf8418911577c95e1)): ?>
<?php $component = $__componentOriginaledb832a7cbbcdfaaf8418911577c95e1; ?>
<?php unset($__componentOriginaledb832a7cbbcdfaaf8418911577c95e1); ?>
<?php endif; ?>
        <?php if (isset($component)) { $__componentOriginaledb832a7cbbcdfaaf8418911577c95e1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaledb832a7cbbcdfaaf8418911577c95e1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.summary-card','data' => ['title' => 'New Users Today','value' => ''.e(number_format($newUsersToday)).'','icon' => 'heroicon-o-user-plus','color' => 'accent-secondary','trend' => '5% vs yesterday','trendUp' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.summary-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'New Users Today','value' => ''.e(number_format($newUsersToday)).'','icon' => 'heroicon-o-user-plus','color' => 'accent-secondary','trend' => '5% vs yesterday','trendUp' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaledb832a7cbbcdfaaf8418911577c95e1)): ?>
<?php $attributes = $__attributesOriginaledb832a7cbbcdfaaf8418911577c95e1; ?>
<?php unset($__attributesOriginaledb832a7cbbcdfaaf8418911577c95e1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaledb832a7cbbcdfaaf8418911577c95e1)): ?>
<?php $component = $__componentOriginaledb832a7cbbcdfaaf8418911577c95e1; ?>
<?php unset($__componentOriginaledb832a7cbbcdfaaf8418911577c95e1); ?>
<?php endif; ?>
        <?php if (isset($component)) { $__componentOriginaledb832a7cbbcdfaaf8418911577c95e1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaledb832a7cbbcdfaaf8418911577c95e1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.summary-card','data' => ['title' => 'Active Now','value' => ''.e(number_format($activeUsers)).'','icon' => 'heroicon-o-signal','color' => 'success','trend' => 'Stable','trendUp' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.summary-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Active Now','value' => ''.e(number_format($activeUsers)).'','icon' => 'heroicon-o-signal','color' => 'success','trend' => 'Stable','trendUp' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaledb832a7cbbcdfaaf8418911577c95e1)): ?>
<?php $attributes = $__attributesOriginaledb832a7cbbcdfaaf8418911577c95e1; ?>
<?php unset($__attributesOriginaledb832a7cbbcdfaaf8418911577c95e1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaledb832a7cbbcdfaaf8418911577c95e1)): ?>
<?php $component = $__componentOriginaledb832a7cbbcdfaaf8418911577c95e1; ?>
<?php unset($__componentOriginaledb832a7cbbcdfaaf8418911577c95e1); ?>
<?php endif; ?>
        <?php if (isset($component)) { $__componentOriginaledb832a7cbbcdfaaf8418911577c95e1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaledb832a7cbbcdfaaf8418911577c95e1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.summary-card','data' => ['title' => 'Revenue','value' => '$12,450','icon' => 'heroicon-o-currency-dollar','color' => 'warning','trend' => '2% vs last week','trendUp' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.summary-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Revenue','value' => '$12,450','icon' => 'heroicon-o-currency-dollar','color' => 'warning','trend' => '2% vs last week','trendUp' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaledb832a7cbbcdfaaf8418911577c95e1)): ?>
<?php $attributes = $__attributesOriginaledb832a7cbbcdfaaf8418911577c95e1; ?>
<?php unset($__attributesOriginaledb832a7cbbcdfaaf8418911577c95e1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaledb832a7cbbcdfaaf8418911577c95e1)): ?>
<?php $component = $__componentOriginaledb832a7cbbcdfaaf8418911577c95e1; ?>
<?php unset($__componentOriginaledb832a7cbbcdfaaf8418911577c95e1); ?>
<?php endif; ?>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-surface-card rounded-xl shadow-sm border border-subtle p-6">
            <div class="flex items-center justify-between mb-6">
                 <h3 class="text-lg font-semibold text-text-primary">User Registration Trends</h3>
                 <select class="text-xs bg-bg-primary border-subtle rounded-lg text-text-secondary">
                    <option>Last 7 Days</option>
                 </select>
            </div>
            <div class="relative" style="height: 300px;">
                <canvas id="userRegistrationChart"></canvas>
            </div>
        </div>

        <div class="lg:col-span-1">
             <?php if (isset($component)) { $__componentOriginal749bc8a472377cccba418c5c5128c8ff = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal749bc8a472377cccba418c5c5128c8ff = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.system-status','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.system-status'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal749bc8a472377cccba418c5c5128c8ff)): ?>
<?php $attributes = $__attributesOriginal749bc8a472377cccba418c5c5128c8ff; ?>
<?php unset($__attributesOriginal749bc8a472377cccba418c5c5128c8ff); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal749bc8a472377cccba418c5c5128c8ff)): ?>
<?php $component = $__componentOriginal749bc8a472377cccba418c5c5128c8ff; ?>
<?php unset($__componentOriginal749bc8a472377cccba418c5c5128c8ff); ?>
<?php endif; ?>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
         <div class="lg:col-span-2">
             <?php if (isset($component)) { $__componentOriginal58d39198b548c92362d9b5947946f40a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal58d39198b548c92362d9b5947946f40a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.recent-activity','data' => ['activities' => $activities]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.recent-activity'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['activities' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($activities)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal58d39198b548c92362d9b5947946f40a)): ?>
<?php $attributes = $__attributesOriginal58d39198b548c92362d9b5947946f40a; ?>
<?php unset($__attributesOriginal58d39198b548c92362d9b5947946f40a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal58d39198b548c92362d9b5947946f40a)): ?>
<?php $component = $__componentOriginal58d39198b548c92362d9b5947946f40a; ?>
<?php unset($__componentOriginal58d39198b548c92362d9b5947946f40a); ?>
<?php endif; ?>
         </div>
         
          <div class="lg:col-span-1 bg-surface-card rounded-xl shadow-sm border border-subtle p-6">
            <h3 class="text-lg font-semibold text-text-primary mb-4">Quick Actions</h3>
            <div class="space-y-3">
                <button class="w-full flex items-center justify-between p-3 rounded-lg bg-surface-muted hover:bg-accent-primary/10 transition-colors group">
                    <span class="text-sm font-medium">Create New Course</span>
                    <?php echo e(svg('heroicon-m-plus', 'w-5 h-5')); ?>
                </button>
                <button class="w-full flex items-center justify-between p-3 rounded-lg bg-surface-muted hover:bg-accent-primary/10 transition-colors group">
                    <span class="text-sm font-medium">Manage Users</span>
                    <?php echo e(svg('heroicon-m-users', 'w-5 h-5')); ?>
                </button>
            </div>
          </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('userRegistrationChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($chartLabels); ?>,
                datasets: [{
                    label: 'New Users',
                    data: <?php echo json_encode($chartData); ?>,
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { borderDash: [5, 5] } },
                    x: { grid: { display: false } }
                }
            }
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Isaac\Desktop\E-learning\resources\views/dashboard/admin.blade.php ENDPATH**/ ?>