<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="h-full bg-zinc-900">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'E-learn')); ?></title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300..700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>

<body
    class="h-full font-sans antialiased text-gray-100 bg-zinc-900"
    x-data="{
        sidebarOpen: window.innerWidth >= 1024,
        notificationOpen: false,
        chatOpen: false,
        searchOpen: false,
        chatbotOpen: false,
        isMobile: window.innerWidth < 1024,
        darkMode: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),

        init() {
            this.$watch('darkMode', val => {
                localStorage.setItem('theme', val ? 'dark' : 'light');
                if (val) {
                    document.documentElement.classList.add('dark');
                    document.documentElement.classList.remove('light');
                } else {
                    document.documentElement.classList.remove('dark');
                    document.documentElement.classList.add('light');
                }
            });

            if (this.darkMode) {
                document.documentElement.classList.add('dark');
                document.documentElement.classList.remove('light');
            } else {
                document.documentElement.classList.remove('dark');
                document.documentElement.classList.add('light');
            }

            window.addEventListener('resize', () => {
                this.isMobile = window.innerWidth < 1024;
                if (!this.isMobile) {
                    this.sidebarOpen = true;
                    this.searchOpen = false;
                } else {
                    this.sidebarOpen = false;
                }
            });
        },

        toggleTheme() {
            this.darkMode = !this.darkMode;
        },

        toggleSidebar() {
            this.sidebarOpen = !this.sidebarOpen;
        },

        toggleNotification() {
            if (this.isMobile) {
                window.location.href = '/notifications';
            } else {
                this.notificationOpen = !this.notificationOpen;
                if (this.notificationOpen) {
                    this.chatOpen = false;
                    this.chatbotOpen = false;
                }
            }
        },

        toggleChat() {
            if (this.isMobile) {
                window.location.href = '/messages';
            } else {
                this.chatOpen = !this.chatOpen;
                if (this.chatOpen) {
                    this.notificationOpen = false;
                    this.chatbotOpen = false;
                }
            }
        },

        toggleChatbot() {
            if (this.isMobile) {
                window.location.href = '<?php echo e(route("chatbot")); ?>';
            } else {
                this.chatbotOpen = !this.chatbotOpen;
                if (this.chatbotOpen) {
                    this.notificationOpen = false;
                    this.chatOpen = false;
                }
            }
        }
    }"
>

<div class="flex h-screen overflow-hidden">

    <!-- Mobile overlay -->
    <div
        x-show="sidebarOpen && isMobile"
        x-cloak
        class="fixed inset-0 z-40 bg-gray-900/50 backdrop-blur-sm"
        @click="sidebarOpen = false"
    ></div>

    <!-- Sidebar -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($sidebar)): ?>
        <?php echo e($sidebar); ?>

    <?php else: ?>
        <?php if (isset($component)) { $__componentOriginal3623d0faebbae10085f2828f046806b2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3623d0faebbae10085f2828f046806b2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layout.sidebar','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layout.sidebar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3623d0faebbae10085f2828f046806b2)): ?>
<?php $attributes = $__attributesOriginal3623d0faebbae10085f2828f046806b2; ?>
<?php unset($__attributesOriginal3623d0faebbae10085f2828f046806b2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3623d0faebbae10085f2828f046806b2)): ?>
<?php $component = $__componentOriginal3623d0faebbae10085f2828f046806b2; ?>
<?php unset($__componentOriginal3623d0faebbae10085f2828f046806b2); ?>
<?php endif; ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Main content -->
    <div
        class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden transition-all duration-300"
        :class="{ 'mr-80': (notificationOpen || chatOpen || chatbotOpen) && !isMobile }"
    >
        <?php if (isset($component)) { $__componentOriginale30b2855ee1e4ae30e50fcbbc76a33ff = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale30b2855ee1e4ae30e50fcbbc76a33ff = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layout.header','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layout.header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale30b2855ee1e4ae30e50fcbbc76a33ff)): ?>
<?php $attributes = $__attributesOriginale30b2855ee1e4ae30e50fcbbc76a33ff; ?>
<?php unset($__attributesOriginale30b2855ee1e4ae30e50fcbbc76a33ff); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale30b2855ee1e4ae30e50fcbbc76a33ff)): ?>
<?php $component = $__componentOriginale30b2855ee1e4ae30e50fcbbc76a33ff; ?>
<?php unset($__componentOriginale30b2855ee1e4ae30e50fcbbc76a33ff); ?>
<?php endif; ?>

        <main class="w-full grow p-4 sm:p-6">
            <?php echo $__env->yieldContent('content'); ?>

            <div 
                class="fixed bottom-6 z-50 transition-all duration-300"
                :class="(notificationOpen || chatOpen || chatbotOpen) && !isMobile ? 'right-[21.5rem]' : 'right-6'"
            >

                <button
                    @click="toggleChatbot()"
                    class="
                        relative
                        w-14 h-14
                        rounded-full
                        bg-accent
                        text-white
                        shadow-lg
                        flex items-center justify-center
                        transition
                        duration-300
                        hover:scale-110
                        focus:outline-none
                        animate-pulse
                    "
                    aria-label="Open chatbot"
                >
                    <?php echo e(svg('heroicon-o-chat-bubble-left-right', 'w-7 h-7')); ?>
                </button>

            </div>
        </main>
    </div>

    <!-- Right panels -->


    <div class="hidden lg:block">

        <?php if (isset($component)) { $__componentOriginala9ef69720c7e5c66e3bb8c47c9dfa31c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala9ef69720c7e5c66e3bb8c47c9dfa31c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layout.right-panel','data' => ['title' => 'Notifications','trigger' => 'notificationOpen','@close' => 'notificationOpen = false']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layout.right-panel'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Notifications','trigger' => 'notificationOpen','@close' => 'notificationOpen = false']); ?>
            <div class="p-4 h-full overflow-y-auto custom-scrollbar">
                <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('notifications');

$__key = null;

$__key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-3371989010-0', $__key);

$__html = app('livewire')->mount($__name, $__params, $__key);

echo $__html;

unset($__html);
unset($__key);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
            </div>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala9ef69720c7e5c66e3bb8c47c9dfa31c)): ?>
<?php $attributes = $__attributesOriginala9ef69720c7e5c66e3bb8c47c9dfa31c; ?>
<?php unset($__attributesOriginala9ef69720c7e5c66e3bb8c47c9dfa31c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala9ef69720c7e5c66e3bb8c47c9dfa31c)): ?>
<?php $component = $__componentOriginala9ef69720c7e5c66e3bb8c47c9dfa31c; ?>
<?php unset($__componentOriginala9ef69720c7e5c66e3bb8c47c9dfa31c); ?>
<?php endif; ?>

        <?php if (isset($component)) { $__componentOriginala9ef69720c7e5c66e3bb8c47c9dfa31c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala9ef69720c7e5c66e3bb8c47c9dfa31c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layout.right-panel','data' => ['title' => 'Messages','trigger' => 'chatOpen','@close' => 'chatOpen = false']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layout.right-panel'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Messages','trigger' => 'chatOpen','@close' => 'chatOpen = false']); ?>
            <div class="h-full flex flex-col items-center justify-center p-6 text-center text-secondary opacity-70">
                <div class="w-16 h-16 bg-surface-muted rounded-full flex items-center justify-center mb-4">
                    <?php echo e(svg('heroicon-o-chat-bubble-oval-left', 'w-8 h-8 text-secondary')); ?>
                </div>
                <h4 class="text-base font-medium text-primary">No messages yet</h4>
                <p class="text-sm mt-1">Start a conversation with your students or instructors.</p>
            </div>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala9ef69720c7e5c66e3bb8c47c9dfa31c)): ?>
<?php $attributes = $__attributesOriginala9ef69720c7e5c66e3bb8c47c9dfa31c; ?>
<?php unset($__attributesOriginala9ef69720c7e5c66e3bb8c47c9dfa31c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala9ef69720c7e5c66e3bb8c47c9dfa31c)): ?>
<?php $component = $__componentOriginala9ef69720c7e5c66e3bb8c47c9dfa31c; ?>
<?php unset($__componentOriginala9ef69720c7e5c66e3bb8c47c9dfa31c); ?>
<?php endif; ?>

        <?php if (isset($component)) { $__componentOriginala9ef69720c7e5c66e3bb8c47c9dfa31c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala9ef69720c7e5c66e3bb8c47c9dfa31c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layout.right-panel','data' => ['title' => 'AI Assistant','trigger' => 'chatbotOpen','@close' => 'chatbotOpen = false']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layout.right-panel'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'AI Assistant','trigger' => 'chatbotOpen','@close' => 'chatbotOpen = false']); ?>
            <div class="h-full flex flex-col items-center justify-center p-6 text-center text-secondary opacity-70">
                <div class="w-16 h-16 surface-muted rounded-full flex items-center justify-center mb-4">
                    <?php echo e(svg('heroicon-o-chat-bubble-left-right', 'w-8 h-8 text-secondary')); ?>
                </div>
                <h4 class="text-base font-medium text-primary">How can I help?</h4>
                <p class="text-sm mt-1 mb-4">Ask me anything about your courses or the platform.</p>
                <a href="<?php echo e(route('chatbot')); ?>" class="text-sm font-medium text-accent hover:text-accent-secondary">
                    Open Full Chat
                </a>
            </div>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala9ef69720c7e5c66e3bb8c47c9dfa31c)): ?>
<?php $attributes = $__attributesOriginala9ef69720c7e5c66e3bb8c47c9dfa31c; ?>
<?php unset($__attributesOriginala9ef69720c7e5c66e3bb8c47c9dfa31c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala9ef69720c7e5c66e3bb8c47c9dfa31c)): ?>
<?php $component = $__componentOriginala9ef69720c7e5c66e3bb8c47c9dfa31c; ?>
<?php unset($__componentOriginala9ef69720c7e5c66e3bb8c47c9dfa31c); ?>
<?php endif; ?>

    </div>
</div>
</body>
</html>
<?php /**PATH C:\Users\Isaac\Desktop\E-learning\resources\views/layouts/app.blade.php ENDPATH**/ ?>