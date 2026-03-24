@php
<<<<<<< HEAD
    $user = auth()->user();
    $role = $user->role;
    $links = [];

    // Define links based on actual Auth Role
    if ($role === 'admin') {
        $links = [
             ['label' => 'Overview', 'icon' => 'chart-bar', 'url' => route('admin.dashboard')],
             ['label' => 'Users', 'icon' => 'users', 'url' => '#'], // Add routes as you create them
             ['label' => 'Activity Logs', 'icon' => 'clipboard-document-list', 'url' => route('admin.logs')],
             ['label' => 'System Settings', 'icon' => 'cog-6-tooth', 'url' => '#'],
        ];
    } elseif ($role === 'instructor') {
         $links = [
             ['label' => 'Dashboard', 'icon' => 'chart-pie', 'url' => route('instructor.dashboard')],
             ['label' => 'My Courses', 'icon' => 'book-open', 'url' => '#'],
             ['label' => 'My Students', 'icon' => 'users', 'url' => route('instructor.students')],
             ['label' => 'Assignments', 'icon' => 'document-check', 'url' => route('instructor.assignments')],
        ];
    } else {
         // Default: Student
         $links = [
             ['label' => 'Dashboard', 'icon' => 'home', 'url' => route('student.dashboard')],
             ['label' => 'My Learning', 'icon' => 'academic-cap', 'url' => route('student.learning')],
             ['label' => 'Certificates', 'icon' => 'trophy', 'url' => '#'],
             ['label' => 'Profile', 'icon' => 'user-circle', 'url' => route('profile.edit')],
=======
    $role = session('mock_role', 'admin');
    $links = [];

    if ($role === 'admin') {
        $links = [
             ['label' => 'Overview', 'icon' => 'chart-bar', 'url' => '#'],
             ['label' => 'Users', 'icon' => 'users', 'url' => '#'],
             ['label' => 'Courses', 'icon' => 'book-open', 'url' => '#'],
             ['label' => 'System Logs', 'icon' => 'clipboard-document-list', 'url' => '#'],
        ];
    } elseif ($role === 'instructor') {
         $links = [
             ['label' => 'Dashboard', 'icon' => 'home', 'url' => '#'],
             ['label' => 'My Courses', 'icon' => 'book-open', 'url' => '#'],
             ['label' => 'Assignments', 'icon' => 'clipboard-document-check', 'url' => '#'],
             ['label' => 'Students', 'icon' => 'users', 'url' => '#'],
        ];
    } else {
         $links = [
             ['label' => 'Dashboard', 'icon' => 'home', 'url' => '#'],
             ['label' => 'My Learning', 'icon' => 'academic-cap', 'url' => '#'],
             ['label' => 'Catalog', 'icon' => 'squares-2x2', 'url' => '#'],
             ['label' => 'Achievements', 'icon' => 'trophy', 'url' => '#'],
>>>>>>> 3863a09b394c58216ab17e6ce1358e41955aa5e3
        ];
    }
@endphp

<aside class="fixed inset-y-0 left-0 z-50 w-64 h-full px-5 py-8 overflow-y-auto surface-card border-r border-subtle transition-all duration-300 transform -translate-x-full lg:static lg:transform-none lg:z-auto"
<<<<<<< HEAD
       x-data="{ sidebarOpen: true }"
       :class="{
           'translate-x-0': sidebarOpen,
           '-translate-x-full': !sidebarOpen
       }">
    
    <div class="flex items-center justify-between mb-10 px-2">
        <a href="/" class="flex items-center gap-2 font-bold text-xl tracking-tight text-primary">
            <div class="w-8 h-8 bg-accent rounded-lg flex items-center justify-center text-white">
                @svg('heroicon-o-academic-cap', 'w-5 h-5')
            </div>
            <span>E-Learn</span>
        </a>
    </div>

    <div class="mb-8 px-2 py-3 bg-surface-muted rounded-xl border border-subtle">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-primary/20 flex items-center justify-center text-primary font-bold">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div class="overflow-hidden">
                <p class="text-sm font-bold text-text-primary truncate">{{ $user->name }}</p>
                <p class="text-xs text-text-secondary capitalize">{{ $role }}</p>
            </div>
        </div>
    </div>

    <div class="flex flex-col justify-between h-[calc(100%-180px)]">
        <nav class="space-y-1">
            <div class="text-[10px] font-bold text-text-secondary uppercase tracking-widest mb-4 px-2 opacity-50">Main Menu</div>
            
            @foreach($links as $link)
            <a href="{{ $link['url'] }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->url() == $link['url'] ? 'bg-accent/10 text-accent' : 'text-text-secondary hover:bg-surface-muted hover:text-text-primary' }} group">
                @svg('heroicon-o-'.$link['icon'], 'w-5 h-5 transition-colors')
                <span class="font-medium text-sm">{{ $link['label'] }}</span>
            </a>
            @endforeach
        </nav>

        <div class="border-t border-subtle pt-6">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-text-secondary hover:text-error hover:bg-error/10 transition-colors w-full text-left">
                    @svg('heroicon-o-arrow-left-on-rectangle', 'w-5 h-5')
                    <span class="font-medium text-sm">Logout</span>
                </button>
            </form>
        </div>
    </div>
</aside>
=======
       :class="{
           'translate-x-0': sidebarOpen && isMobile,
           '-translate-x-full': !sidebarOpen && isMobile,
           'lg:-ml-64': !sidebarOpen && !isMobile,
           'lg:ml-0': sidebarOpen && !isMobile
       }">
    <div class="flex items-center justify-between mb-10 px-2">
        <a href="/" class="flex items-center gap-2 font-bold text-xl tracking-tight text-primary">
            <div class="w-8 h-8 bg-accent rounded-lg flex items-center justify-center text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
            <span>E-Learn</span>
        </a>
        <button @click="sidebarOpen = false" class="lg:hidden text-secondary hover:text-primary">
            @svg('heroicon-o-x-mark', 'w-6 h-6')
        </button>
    </div>

    <div class="flex flex-col justify-between flex-1">
        <nav class="space-y-4">
            <div class="text-xs font-semibold text-secondary uppercase tracking-wider mb-4 px-2">Menu</div>
            @foreach($links as $link)
            <a href="{{ $link['url'] }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors duration-200 hover:surface-muted text-secondary hover:text-accent group">
                @if(isset($link['icon']))
                    @svg('heroicon-o-'.$link['icon'], 'w-5 h-5 text-secondary group-hover:text-accent transition-colors')
                @endif
                <span class="font-medium">{{ $link['label'] }}</span>
            </a>
            @endforeach

            <div x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center justify-between w-full px-3 py-2.5 rounded-lg transition-colors duration-200 hover:surface-muted text-secondary hover:text-accent group">
                    <div class="flex items-center gap-3">
                        @svg('heroicon-o-folder', 'w-5 h-5 text-secondary group-hover:text-accent transition-colors')
                        <span class="font-medium">Resources</span>
                    </div>
                    <svg class="w-4 h-4 text-secondary transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="open" x-collapse class="pl-10 mt-1 space-y-1">
                    <a href="#" class="block px-3 py-2 text-sm text-secondary hover:text-accent rounded-md hover:surface-muted">Documents</a>
                    <a href="#" class="block px-3 py-2 text-sm text-secondary hover:text-accent rounded-md hover:surface-muted">Videos</a>
                </div>
            </div>
        </nav>

        <div class="border-t border-subtle pt-6">
             <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-secondary hover:text-error hover:bg-error/10 transition-colors">
                @svg('heroicon-o-arrow-left-on-rectangle', 'w-5 h-5')
                <span class="font-medium">Logout</span>
             </a>
        </div>
    </div>
</aside>
>>>>>>> 3863a09b394c58216ab17e6ce1358e41955aa5e3
