@extends('layouts.instructor')

@section('content')
<div class="py-6 max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8"
     x-data="{
        showCreateModal: false,
        showEditModal: false,
        showDeleteModal: false,
        currentCourse: {
            id: null, title: '', description: '', category_id: '', image: '', status: ''
        },
        courseToDelete: null,
        search: '',
        sortField: 'created_at',
        sortDirection: 'desc',
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

    <!-- ── Header ─────────────────────────────────────────────────── -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-3">
        <div>
            <h1 class="text-xl font-extrabold tracking-tight" style="color: var(--text-primary);">
                My Courses
            </h1>
            <p class="mt-0.5 text-xs font-medium" style="color: var(--text-secondary);">
                {{ $courses->total() }} total &bull; Manage your published and draft courses
            </p>
        </div>
        <button @click="
                showCreateModal = true;
                currentCourse = {
                    id: null, title: '', description: '', category_id: '', image: '', status: ''
                }"
                class="flex items-center gap-1.5 px-4 py-2 rounded-lg font-semibold text-xs transition-all duration-200 active:scale-95"
                style="background-color: var(--accent-primary); color: #fff;"
                onmouseover="this.style.opacity='0.88'" onmouseout="this.style.opacity='1'">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            Add Course
        </button>
    </div>

    <!-- ── Search ─────────────────────────────────────────────────── -->
    <div class="mb-5">
        <div class="relative max-w-sm">
            <input type="text" x-model="search" placeholder="Search courses…"
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
                        style="color: var(--text-secondary);" @click="sortBy('title')">
                        Title
                        <span x-show="sortField === 'title'" x-text="sortDirection === 'asc' ? ' ↑' : ' ↓'"></span>
                    </th>
                    <th class="px-4 py-3.5 text-left text-[10px] font-bold uppercase tracking-widest"
                        style="color: var(--text-secondary);">Image</th>
                    <th class="px-4 py-3.5 text-left text-[10px] font-bold uppercase tracking-widest"
                        style="color: var(--text-secondary);">Category</th>
                    <th class="px-4 py-3.5 text-center text-[10px] font-bold uppercase tracking-widest"
                        style="color: var(--text-secondary);" @click="sortBy('lessons_count')">
                        Lessons
                    </th>
                    <th class="px-4 py-3.5 text-center text-[10px] font-bold uppercase tracking-widest"
                        style="color: var(--text-secondary);" @click="sortBy('quizzes_count')">
                        Quizzes
                    </th>
                    <th class="px-4 py-3.5 text-left text-[10px] font-bold uppercase tracking-widest"
                        style="color: var(--text-secondary);">Status</th>
                    <th class="px-5 py-3.5 text-right text-[10px] font-bold uppercase tracking-widest"
                        style="color: var(--text-secondary);">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($courses as $course)
                    <tr class="transition-colors"
                        x-show="search === '' || '{{ addslashes($course->title) }}'.toLowerCase().includes(search.toLowerCase())"
                        x-transition
                        style="border-bottom: 1px solid var(--border-subtle);"
                        onmouseover="this.style.backgroundColor='var(--surface-muted)'"
                        onmouseout="this.style.backgroundColor='transparent'">

                        {{-- title --}}
                        <td class="px-5 py-3.5 text-xs font-bold" style="color: var(--text-primary);">
                            {{ $course->title }}
                        </td>

                        {{-- image --}}
                        <td class="px-4 py-3.5">
                            @if($course->image)
                                <img src="{{ asset('storage/' . $course->image) }}" alt="Course image" class="w-12 h-12 object-cover rounded-lg border border-[var(--border-subtle)]">
                            @else
                                <span class="text-xs text-gray-400">—</span>
                            @endif
                        </td>

                        {{-- category --}}
                        <td class="px-4 py-3.5 text-xs" style="color: var(--text-secondary);">
                            {{ $course->category->name ?? '—' }}
                        </td>

                        {{-- lessons --}}
                        <td class="px-4 py-3.5 text-center">
                            <span class="px-2.5 py-0.5 rounded-full font-mono font-bold text-[10px]"
                                  style="background-color: var(--surface-muted); color: var(--text-secondary); border: 1px solid var(--border-subtle);">
                                {{ $course->lessons()->count() }}
                            </span>
                        </td>

                        {{-- quizzes --}}
                        <td class="px-4 py-3.5 text-center">
                            <span class="px-2.5 py-0.5 rounded-full font-mono font-bold text-[10px]"
                                  style="background-color: var(--surface-muted); color: var(--text-secondary); border: 1px solid var(--border-subtle);">
                                {{ $course->quizzes()->count() }}
                            </span>
                        </td>

                        {{-- status --}}
                        <td class="px-4 py-3.5">
                            @if($course->status === 'published')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold transition-all"
                                      style="background-color: color-mix(in srgb, var(--success) 12%, transparent); color: var(--success); border: 1px solid color-mix(in srgb, var(--success) 25%, transparent);">
                                    <span class="w-1.5 h-1.5 rounded-full"
                                          style="background-color: var(--success);"></span>
                                    Published
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold transition-all"
                                      style="background-color: var(--surface-muted); color: var(--text-secondary); border: 1px solid var(--border-subtle);">
                                    <span class="w-1.5 h-1.5 rounded-full"
                                          style="background-color: var(--border-subtle);"></span>
                                    Draft
                                </span>
                            @endif
                        </td>

                        {{-- actions --}}
                        <td class="px-5 py-3.5 text-right">
                            <div class="flex justify-end gap-3">
                                <a href="{{ route('instructor.courses.show', $course->id) }}"
                                   class="hover:scale-110 transition"
                                   title="View">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                <button @click="
                                    showEditModal = true;
                                    currentCourse = {
                                        id: {{ $course->id }},
                                        title: '{{ addslashes($course->title) }}',
                                        description: `{{ addslashes($course->description ?? '') }}`,
                                        category_id: '{{ $course->category_id }}',
                                        image: '{{ $course->image }}',
                                        status: '{{ $course->status }}'
                                    }"
                                    class="hover:scale-110 transition"
                                    title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M9 13l6-6m2 2l-6 6m-2 2h6"/>
                                    </svg>
                                </button>
                                <button @click="courseToDelete = {{ $course->id }}; showDeleteModal = true"
                                        class="hover:scale-110 transition"
                                        title="Delete">
                                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-16 text-center">
                            <p class="text-xs font-medium" style="color: var(--text-secondary);">No courses found.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($courses->hasPages())
            <div class="px-5 py-4" style="border-top: 1px solid var(--border-subtle);">
                {{ $courses->links() }}
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
                        x-text="showCreateModal ? 'New Course' : 'Update Course'"></h2>

                    <form :action="showCreateModal
                                    ? '{{ route('instructor.courses.store') }}'
                                    : `/instructor/courses/${currentCourse.id}`"
                          method="POST"
                          enctype="multipart/form-data">
                        @csrf
                        <template x-if="showEditModal">
                            <input type="hidden" name="_method" value="PUT">
                        </template>

                        <div class="space-y-4">

                            {{-- title --}}
                            <div>
                                <label class="block text-xs font-bold mb-1.5" style="color: var(--text-primary);">
                                    Title
                                </label>
                                <input type="text" name="title" x-model="currentCourse.title" required

                                       class="w-full rounded-xl border px-3 py-2 text-xs"
                                       style="background-color: var(--surface-muted); border: 1px solid var(--border-subtle); color: var(--text-primary);">
                            </div>

                            {{-- description --}}
                            <div>
                                <label class="block text-xs font-bold mb-1.5" style="color: var(--text-primary);">
                                    Description
                                </label>
                                <textarea name="description" x-model="currentCourse.description"
                                          class="w-full rounded-xl border px-3 py-2 text-xs"
                                          style="background-color: var(--surface-muted); border: 1px solid var(--border-subtle); color: var(--text-primary);"></textarea>
                            </div>

                            {{-- category --}}
                            <div>
                                <label class="block text-xs font-bold mb-1.5" style="color: var(--text-primary);">
                                    Category
                                </label>
                                <select name="category_id" x-model="currentCourse.category_id" required
                                        class="w-full rounded-xl border px-3 py-2 text-xs"
                                        style="background-color: var(--surface-muted); border: 1px solid var(--border-subtle); color: var(--text-primary);">
                                    <option value="">Select category</option>
                                    @foreach(\App\Models\CourseCategory::all() as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- image --}}
                            <div>
                                <label class="block text-xs font-bold mb-1.5" style="color: var(--text-primary);">
                                    Image
                                </label>
                                <input type="file" name="image" accept="image/*"
                                       class="w-full rounded-xl border px-3 py-2 text-xs"
                                       style="background-color: var(--surface-muted); border: 1px solid var(--border-subtle); color: var(--text-primary);">
                                <template x-if="currentCourse.image">
                                    <img :src="'/storage/' + currentCourse.image" alt="Course image" class="mt-2 w-20 h-20 object-cover rounded-lg border border-[var(--border-subtle)]">
                                </template>
                            </div>

                            {{-- status --}}
                            <div>
                                <label class="block text-xs font-bold mb-1.5" style="color: var(--text-primary);">
                                    Status
                                </label>
                                <select name="status" x-model="currentCourse.status"
                                        class="w-full rounded-xl border px-3 py-2 text-xs"
                                        style="background-color: var(--surface-muted); border: 1px solid var(--border-subtle); color: var(--text-primary);">
                                    <option value="draft">Draft</option>
                                    <option value="published">Published</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex gap-2 mt-6">
                            <button type="submit"
                                    class="px-5 py-2 rounded-lg font-bold text-xs transition-all"
                                    style="background-color: var(--accent-primary); color: #fff;">
                                <span x-text="showCreateModal ? 'Create' : 'Update'"></span>
                            </button>
                            <button type="button"
                                    @click="showCreateModal = false; showEditModal = false"
                                    class="px-5 py-2 rounded-lg font-bold text-xs border"
                                    style="color: var(--text-secondary); border-color: var(--border-subtle); background: var(--surface-muted);">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </template>

    <!-- ── Delete Modal ───────────────────────────────────────────── -->
    <template x-if="showDeleteModal">
        <div class="fixed inset-0 z-[120] overflow-y-auto" x-cloak>
            <div class="flex items-center justify-center min-h-screen px-4 py-10">
                <div class="fixed inset-0 backdrop-blur-sm"
                     style="background-color: rgba(2,6,23,0.55);"
                     @click="showDeleteModal = false"></div>
                <div class="relative z-20 max-w-sm w-full rounded-2xl p-8"
                     style="background-color: var(--surface-card); border: 1px solid var(--border-subtle);">
                    <h2 class="text-lg font-black mb-4" style="color: var(--text-primary);">
                        Delete Course
                    </h2>
                    <p class="mb-6 text-xs" style="color: var(--text-secondary);">
                        Are you sure you want to delete this course? This action cannot be undone.
                    </p>
                    <form :action="`/instructor/courses/${courseToDelete}`" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="flex gap-2">
                            <button type="submit"
                                    class="px-5 py-2 rounded-lg font-bold text-xs transition-all"
                                    style="background-color: var(--error); color: #fff;">
                                Delete
                            </button>
                            <button type="button"
                                    @click="showDeleteModal = false"
                                    class="px-5 py-2 rounded-lg font-bold text-xs border"
                                    style="color: var(--text-secondary); border-color: var(--border-subtle); background: var(--surface-muted);">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </template>
</div>
@endsection
