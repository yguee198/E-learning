@if(session($type ?? 'success'))
    <div class="fixed top-6 right-6 z-50 bg-white/80 backdrop-blur rounded-3xl px-6 py-4 shadow-lg border border-indigo-200 text-[var(--accent-primary)] font-semibold glassmorphic-toast">
        {{ session($type ?? 'success') }}
    </div>
@endif
