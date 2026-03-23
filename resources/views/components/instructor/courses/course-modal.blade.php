<div
    x-show="{{ $editMode ? 'openEdit' : 'openCreate' }}"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur"
    style="display: none;"
>
    <div class="bg-white rounded-3xl p-8 w-full max-w-lg shadow-lg">
        <h2 class="text-xl font-bold mb-4">
            {{ $editMode ? 'Edit Course' : 'Create Course' }}
        </h2>
        <form
            action="{{ $editMode ? route('instructor.courses.update', $course->id ?? 0) : route('instructor.courses.store') }}"
            method="POST"
        >
            @csrf
            @if($editMode)
                @method('PUT')
            @endif
            <div class="mb-3">
                <label class="block mb-1">Title</label>
                <input
                    name="title"
                    required
                    class="w-full rounded-2xl border px-3 py-2"
                    value="{{ old('title', $course->title ?? '') }}"
                >
            </div>
            <div class="mb-3">
                <label class="block mb-1">Description</label>
                <textarea
                    name="description"
                    class="w-full rounded-2xl border px-3 py-2"
                >{{ old('description', $course->description ?? '') }}</textarea>
            </div>
            <div class="mb-3">
                <label class="block mb-1">Category</label>
                <select
                    name="category_id"
                    required
                    class="w-full rounded-2xl border px-3 py-2"
                >
                    @foreach($categories as $cat)
                        <option
                            value="{{ $cat->id }}"
                            @if(old('category_id', $course->category_id ?? '') == $cat->id) selected @endif
                        >{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3 flex items-center gap-2">
                <input
                    type="checkbox"
                    name="is_published"
                    value="1"
                    id="{{ $editMode ? 'is_published_edit' : 'is_published' }}"
                    @if(old('is_published', $course->is_published ?? false)) checked @endif
                >
                <label for="{{ $editMode ? 'is_published_edit' : 'is_published' }}">Publish now</label>
            </div>
            <div class="flex gap-2 mt-4">
                <button type="submit" class="bg-[var(--accent-primary)] text-white px-6 py-2 rounded-3xl">
                    {{ $editMode ? 'Update' : 'Create' }}
                </button>
                <button
                    type="button"
                    @click="{{ $editMode ? 'openEdit = false' : 'openCreate = false' }}"
                    class="px-6 py-2 rounded-3xl border"
                >Cancel</button>
            </div>
        </form>
    </div>
</div>
