<table class="w-full bg-white/80 rounded-3xl shadow-lg">
    <thead>
        <tr class="text-left text-[var(--text-primary)]">
            <th class="px-4 py-3">Title</th>
            <th class="px-4 py-3">Category</th>
            <th class="px-4 py-3">Lessons</th>
            <th class="px-4 py-3">Quizzes</th>
            <th class="px-4 py-3">Published</th>
            <th class="px-4 py-3">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($courses as $course)
        <tr class="border-t border-gray-100 hover:bg-indigo-50/30">
            <td class="px-4 py-3 font-semibold">{{ $course->title }}</td>
            <td class="px-4 py-3">{{ $course->category->name ?? '-' }}</td>
            <td class="px-4 py-3">{{ $course->lessons()->count() }}</td>
            <td class="px-4 py-3">{{ $course->quizzes()->count() }}</td>
            <td class="px-4 py-3">
                @if($course->is_published)
                    <span class="inline-block px-3 py-1 bg-green-100 text-green-700 rounded-2xl text-xs">Published</span>
                @else
                    <span class="inline-block px-3 py-1 bg-gray-100 text-gray-500 rounded-2xl text-xs">Draft</span>
                @endif
            </td>
            <td class="px-4 py-3 flex gap-2">
                <a href="{{ route('instructor.courses.show', $course->id) }}" class="text-[var(--accent-primary)] hover:underline">View</a>
                <button @click="editCourse = {{ $course->id }}, openEdit = true" class="text-indigo-700 hover:underline">Edit</button>
                <form action="{{ route('instructor.courses.destroy', $course->id) }}" method="POST" onsubmit="return confirm('Delete this course?')" class="inline">
                    @csrf @method('DELETE')
                    <button class="text-red-600 hover:underline">Delete</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="px-4 py-6 text-center text-gray-400">No courses found.</td>
        </tr>
        @endforelse
    </tbody>
</table>
