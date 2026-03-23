@extends('layouts.instructor')

@section('content')

<!-- ── Floating Success Toast (glass) ────────────────────────────── -->
<div class="fixed top-4 right-4 z-[100]">
    @if(session('success'))
        <div x-data="{ show: true }"
             x-show="show"
             x-init="setTimeout(() => show = false, 4000)"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="translate-x-full opacity-0"
             x-transition:enter-end="translate-x-0 opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="translate-x-0 opacity-100"
             x-transition:leave-end="translate-x-full opacity-0"
             class="flex items-center gap-2 px-4 py-2.5 rounded-lg border-l-[3px] backdrop-blur-md text-xs font-semibold"
             style="background-color: color-mix(in srgb, var(--success) 15%, transparent);
                    border-left-color: var(--success);
                    border-top: 1px solid color-mix(in srgb, var(--success) 25%, transparent);
                    border-right: 1px solid color-mix(in srgb, var(--success) 25%, transparent);
                    border-bottom: 1px solid color-mix(in srgb, var(--success) 25%, transparent);
                    color: var(--success);">
            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif
</div>

<div class="py-6 max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8" x-data="{
    showCreateModal: false,
    showEditModal:   false,
    showDeleteModal: false,
    currentCategory: { id: null, name: '', icon: '', description: '', display_order: '' },
    categoryToDelete: null,
    /* live status toggle */
    togglingId: null,
    search: '',
    sortField: 'display_order',
    sortDirection: 'asc',
    sortBy(field) {
        let currentUrl = new URL(window.location.href);
        let currentSort = currentUrl.searchParams.get('sort') || this.sortField;
        let currentDirection = currentUrl.searchParams.get('direction') || this.sortDirection;
        let newDirection = (currentSort === field && currentDirection === 'asc') ? 'desc' : 'asc';
        currentUrl.searchParams.set('sort', field);
        currentUrl.searchParams.set('direction', newDirection);
        window.location.search = currentUrl.search;
    }
}">

    <!-- ── Header ─────────────────────────────────────────────────── -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-3">
        <div>
            <h1 class="text-xl font-extrabold tracking-tight" style="color: var(--text-primary);">
                Course Categories
            </h1>
            <p class="mt-0.5 text-xs font-medium" style="color: var(--text-secondary);">
                {{ $categories->total() }} total &bull; Organize your curriculum
            </p>
        </div>

        <button @click="
                showCreateModal = true;
                currentCategory = {
                    id: null, name: '', icon: '', description: '',
                    display_order: {{ ($categories->max('display_order') ?? 0) + 1 }}
                }"
                class="flex items-center gap-1.5 px-4 py-2 rounded-lg font-semibold text-xs transition-all duration-200 active:scale-95"
                style="background-color: var(--accent-primary); color: #fff;"
                onmouseover="this.style.opacity='0.88'" onmouseout="this.style.opacity='1'">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            Add Category
        </button>
    </div>

    <!-- ── Search ─────────────────────────────────────────────────── -->
    <div class="mb-5">
        <div class="relative max-w-sm">
            <input type="text" x-model="search" placeholder="Search categories…"
                   class="w-full pl-9 pr-4 py-2.5 rounded-xl outline-none transition-all text-xs font-medium"
                   style="background-color: var(--surface-card); border: 1px solid var(--border-subtle); color: var(--text-primary);"
                   onfocus="this.style.borderColor='var(--accent-primary)'; this.style.boxShadow='0 0 0 3px color-mix(in srgb, var(--accent-primary) 12%, transparent)'"
                   onblur="this.style.borderColor='var(--border-subtle)'; this.style.boxShadow='none'">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-3.5 h-3.5" style="color: var(--text-secondary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- ── Table ──────────────────────────────────────────────────── -->
    <div class="rounded-xl overflow-hidden" style="background-color: var(--surface-card); border: 1px solid var(--border-subtle);">
        <table class="min-w-full">
            <thead>
                <tr style="background-color: var(--surface-muted); border-bottom: 1px solid var(--border-subtle);">
                    <th class="px-5 py-3.5 text-left text-[10px] font-bold uppercase tracking-widest cursor-pointer select-none"
                        style="color: var(--text-secondary);" @click="sortBy('name')">
                        Name
                        <span x-show="sortField === 'name'" x-text="sortDirection === 'asc' ? ' ↑' : ' ↓'"></span>
                    </th>
                    <th class="px-4 py-3.5 text-center text-[10px] font-bold uppercase tracking-widest cursor-pointer select-none"
                        style="color: var(--text-secondary);" @click="sortBy('display_order')">
                        Order
                        <span x-show="sortField === 'display_order'" x-text="sortDirection === 'asc' ? ' ↑' : ' ↓'"></span>
                    </th>
                    <th class="px-4 py-3.5 text-left text-[10px] font-bold uppercase tracking-widest"
                        style="color: var(--text-secondary);">Description</th>
                    <th class="px-4 py-3.5 text-left text-[10px] font-bold uppercase tracking-widest"
                        style="color: var(--text-secondary);">Icon</th>
                    <th class="px-4 py-3.5 text-left text-[10px] font-bold uppercase tracking-widest"
                        style="color: var(--text-secondary);">Status</th>
                    <th class="px-5 py-3.5 text-right text-[10px] font-bold uppercase tracking-widest"
                        style="color: var(--text-secondary);">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr class="transition-colors"
                        x-show="search === '' || '{{ addslashes($category->name) }}'.toLowerCase().includes(search.toLowerCase())"
                        x-transition
                        style="border-bottom: 1px solid var(--border-subtle);"
                        onmouseover="this.style.backgroundColor='var(--surface-muted)'"
                        onmouseout="this.style.backgroundColor='transparent'">

                        {{-- name --}}
                        <td class="px-5 py-3.5 text-xs font-bold" style="color: var(--text-primary);">
                            {{ $category->name }}
                        </td>

                        {{-- display_order --}}
                        <td class="px-4 py-3.5 text-center">
                            <span class="px-2.5 py-0.5 rounded-full font-mono font-bold text-[10px]"
                                  style="background-color: var(--surface-muted); color: var(--text-secondary); border: 1px solid var(--border-subtle);">
                                {{ $category->display_order }}
                            </span>
                        </td>

                        {{-- description --}}
                        <td class="px-4 py-3.5 text-[11px] italic max-w-[200px] truncate" style="color: var(--text-secondary);">
                            {{ $category->description ?? '—' }}
                        </td>

                        {{-- icon: show the actual Heroicon SVG if value exists, else em dash --}}
                        <td class="px-4 py-3.5 text-[11px]">
                            @if($category->icon)
                                <span class="inline-flex items-center gap-1.5">
                                    {{-- Render as a named icon via Blade Heroicons if available,
                                         otherwise fall back to a labelled pill --}}
                                    @if(class_exists(\BladeUI\Icons\Components\Icon::class))
                                        <x-dynamic-component :component="'heroicon-o-' . $category->icon"
                                                              class="w-4 h-4"
                                                              style="color: var(--accent-primary);" />
                                    @endif
                                    <span class="font-mono text-[10px]" style="color: var(--accent-primary);">{{ $category->icon }}</span>
                                </span>
                            @else
                                <span style="color: var(--text-secondary);">—</span>
                            @endif
                        </td>

                        {{-- status --}}
                        <td class="px-4 py-3.5">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold transition-all"
                                  style="background-color: color-mix(in srgb, var(--success) 12%, transparent); color: var(--success); border: 1px solid color-mix(in srgb, var(--success) 25%, transparent);">
                                <span class="w-1.5 h-1.5 rounded-full"
                                      style="background-color: var(--success);"></span>
                                Active
                            </span>
                        </td>

                        {{-- actions --}}
                        <td class="px-5 py-3.5 text-right">
                            <div class="flex justify-end gap-4">
                                <button @click="
                                    currentCategory = {
                                        id: {{ $category->id }},
                                        name: '{{ addslashes($category->name) }}',
                                        icon: '{{ addslashes($category->icon ?? '') }}',
                                        description: '{{ addslashes($category->description ?? '') }}',
                                        display_order: {{ $category->display_order }}
                                    };
                                    showEditModal = true;"
                                        class="font-semibold text-xs underline-offset-2 hover:underline transition-all"
                                        style="color: var(--accent-primary);"
                                        onmouseover="this.style.opacity='0.7'" onmouseout="this.style.opacity='1'">
                                    Edit
                                </button>
                                <button @click="categoryToDelete = {{ $category->id }}; showDeleteModal = true"
                                        class="font-semibold text-xs underline-offset-2 hover:underline transition-all"
                                        style="color: var(--error);"
                                        onmouseover="this.style.opacity='0.7'" onmouseout="this.style.opacity='1'">
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-16 text-center">
                            <p class="text-xs font-medium" style="color: var(--text-secondary);">No categories found.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($categories->hasPages())
            <div class="px-5 py-4" style="border-top: 1px solid var(--border-subtle);">
                {{ $categories->links() }}
            </div>
        @endif
    </div>

    <!-- ── Create / Edit Modal ────────────────────────────────────── -->
    <template x-if="showCreateModal || showEditModal">
        <div class="fixed inset-0 z-[110] overflow-y-auto" x-cloak>
            <div class="flex items-center justify-center min-h-screen px-4 py-10">

                <div x-transition:enter="ease-out duration-250"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-150"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 backdrop-blur-sm"
                     style="background-color: rgba(2,6,23,0.55);"
                     @click="showCreateModal = false; showEditModal = false"></div>

                <div x-transition:enter="ease-out duration-250"
                     x-transition:enter-start="opacity-0 translate-y-3 scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     x-transition:leave="ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                     x-transition:leave-end="opacity-0 translate-y-3 scale-95"
                     class="relative z-20 max-w-lg w-full rounded-2xl p-8"
                     style="background-color: var(--surface-card); border: 1px solid var(--border-subtle);">

                    <h2 class="text-lg font-black mb-6" style="color: var(--text-primary);"
                        x-text="showCreateModal ? 'New Category' : 'Update Category'"></h2>

                    <form :action="showCreateModal
                                    ? '{{ route('instructor.categories.store') }}'
                                    : `/instructor/categories/${currentCategory.id}`"
                          method="POST">
                        @csrf
                        <template x-if="showEditModal">
                            <input type="hidden" name="_method" value="PUT">
                        </template>

                        <div class="space-y-4">

                            {{-- name --}}
                            <div>
                                <label class="block text-xs font-bold mb-1.5" style="color: var(--text-primary);">
                                    Category Name <span style="color: var(--error);">*</span>
                                </label>
                                <input type="text" name="name" x-model="currentCategory.name"
                                       required maxlength="255"
                                       class="block w-full rounded-lg p-3 outline-none transition-all text-xs"
                                       style="background-color: var(--bg-primary); border: 1px solid var(--border-subtle); color: var(--text-primary);"
                                       onfocus="this.style.borderColor='var(--accent-primary)'; this.style.boxShadow='0 0 0 3px color-mix(in srgb, var(--accent-primary) 12%, transparent)'"
                                       onblur="this.style.borderColor='var(--border-subtle)'; this.style.boxShadow='none'">
                            </div>

                            {{-- order + icon --}}
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold mb-1.5" style="color: var(--text-primary);">
                                        Display Order <span style="color: var(--error);">*</span>
                                    </label>
                                    <input type="number" name="display_order" x-model="currentCategory.display_order"
                                           required min="0"
                                           class="block w-full rounded-lg p-3 outline-none transition-all text-xs"
                                           style="background-color: var(--bg-primary); border: 1px solid var(--border-subtle); color: var(--text-primary);"
                                           onfocus="this.style.borderColor='var(--accent-primary)'; this.style.boxShadow='0 0 0 3px color-mix(in srgb, var(--accent-primary) 12%, transparent)'"
                                           onblur="this.style.borderColor='var(--border-subtle)'; this.style.boxShadow='none'">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold mb-1.5" style="color: var(--text-primary);">Icon ID</label>
                                    <input type="text" name="icon" x-model="currentCategory.icon"
                                           maxlength="100" placeholder="e.g. book-open"
                                           class="block w-full rounded-lg p-3 outline-none transition-all text-xs"
                                           style="background-color: var(--bg-primary); border: 1px solid var(--border-subtle); color: var(--text-primary);"
                                           onfocus="this.style.borderColor='var(--accent-primary)'; this.style.boxShadow='0 0 0 3px color-mix(in srgb, var(--accent-primary) 12%, transparent)'"
                                           onblur="this.style.borderColor='var(--border-subtle)'; this.style.boxShadow='none'">
                                </div>
                            </div>

                            {{-- description --}}
                            <div>
                                <label class="block text-xs font-bold mb-1.5" style="color: var(--text-primary);">Description</label>
                                <textarea name="description" x-model="currentCategory.description"
                                          rows="3"
                                          class="block w-full rounded-lg p-3 outline-none transition-all text-xs resize-none"
                                          style="background-color: var(--bg-primary); border: 1px solid var(--border-subtle); color: var(--text-primary);"
                                          onfocus="this.style.borderColor='var(--accent-primary)'; this.style.boxShadow='0 0 0 3px color-mix(in srgb, var(--accent-primary) 12%, transparent)'"
                                          onblur="this.style.borderColor='var(--border-subtle)'; this.style.boxShadow='none'"></textarea>
                            </div>



                        </div>

                        <div class="mt-7 flex flex-col-reverse sm:flex-row justify-end gap-3">
                            <button type="button" @click="showCreateModal = false; showEditModal = false"
                                    class="px-6 py-2.5 font-bold text-xs transition-colors rounded-lg"
                                    style="color: var(--text-secondary);"
                                    onmouseover="this.style.color='var(--text-primary)'"
                                    onmouseout="this.style.color='var(--text-secondary)'">
                                Discard
                            </button>
                            <button type="submit"
                                    class="px-7 py-2.5 rounded-xl font-bold text-xs transition-all"
                                    style="background-color: var(--accent-primary); color: #fff;"
                                    onmouseover="this.style.opacity='0.88'" onmouseout="this.style.opacity='1'">
                                <span x-text="showCreateModal ? 'Save Category' : 'Update Category'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </template>

    <!-- ── Delete Modal ───────────────────────────────────────────── -->
    <div x-show="showDeleteModal" class="fixed inset-0 z-[120] overflow-y-auto" x-cloak>
        <div class="flex items-center justify-center min-h-screen px-4">

            <div x-transition:enter="ease-out duration-250"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 backdrop-blur-sm"
                 style="background-color: rgba(2,6,23,0.55);"
                 @click="showDeleteModal = false"></div>

            <div x-transition:enter="ease-out duration-250"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="relative z-20 max-w-sm w-full rounded-2xl p-8 text-center"
                 style="background-color: var(--surface-card); border: 1px solid var(--border-subtle);
                        box-shadow: 0 20px 60px rgba(0,0,0,0.18);">

                <div class="w-14 h-14 rounded-full flex items-center justify-center mx-auto mb-4"
                     style="background-color: color-mix(in srgb, var(--error) 10%, transparent);">
                    <svg class="w-7 h-7" style="color: var(--error);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>

                <h2 class="text-base font-black mb-2" style="color: var(--text-primary);">Delete category?</h2>
                <p class="text-xs font-medium mb-7 leading-relaxed" style="color: var(--text-secondary);">
                    This is permanent and may affect courses currently assigned to it.
                </p>

                <div class="flex flex-col-reverse sm:flex-row justify-center gap-3">
                    <button type="button" @click="showDeleteModal = false"
                            class="px-6 py-2.5 font-bold text-xs transition-colors"
                            style="color: var(--text-secondary);"
                            onmouseover="this.style.color='var(--text-primary)'"
                            onmouseout="this.style.color='var(--text-secondary)'">
                        Go Back
                    </button>
                    <form :action="`/instructor/categories/${categoryToDelete}`" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-full px-7 py-2.5 rounded-xl font-bold text-xs transition-all"
                                style="background-color: var(--error); color: #fff;
                                       box-shadow: 0 3px 12px color-mix(in srgb, var(--error) 28%, transparent);"
                                onmouseover="this.style.opacity='0.88'" onmouseout="this.style.opacity='1'">
                            Delete Permanently
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
