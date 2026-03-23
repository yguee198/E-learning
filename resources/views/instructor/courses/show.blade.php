@extends('layouts.instructor')

@section('content')

<!-- Floating Success Toast - Glassmorphic -->
<div class="fixed top-4 right-4 z-[100]">
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
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
    tab: '{{ session('tab', 'lessons') }}',

    showLessonModal: false,
    showLessonEditModal: false,
    showLessonDeleteModal: false,
    currentLesson: { id: null, title: '', content: '', order: '', duration: '', is_preview: false },
    lessonToDelete: null,
    expandedLessons: {},

    showQuizModal: false,
    showQuizEditModal: false,
    showQuizDeleteModal: false,
    currentQuiz: { id: null, title: '', description: '', time_limit_minutes: '', max_attempts: '', passing_score: '' },
    quizToDelete: null,
    expandedQuizzes: {},

    showQuestionModal: false,
    showQuestionEditModal: false,
    showQuestionDeleteModal: false,
    currentQuestion: { id: null, text: '', type: 'multiple_choice', options: ['','','',''], correct_answer: '' },
    questionToDelete: null,
    quizForQuestion: null,

    showAnswerModal: false,
    showAnswerEditModal: false,
    showAnswerDeleteModal: false,
    currentAnswer: { id: null, text: '', is_correct: false },
    answerToDelete: null,
    questionForAnswer: null
}">

    {{-- ══════════════════════════════════════════════════════════════ --}}
    {{-- Header                                                         --}}
    {{-- ══════════════════════════════════════════════════════════════ --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-3">
        <div class="flex items-center gap-3 flex-wrap">
            <a href="{{ route('instructor.courses.index') }}"
               class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold transition"
               style="background: var(--surface-muted); color: var(--accent-primary);">
                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Courses
            </a>
            <h1 class="text-xl font-extrabold tracking-tight" style="color: var(--text-primary);">
                {{ $course->title }}
            </h1>
        </div>

        @if($course->status === 'published')
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold"
                  style="background: color-mix(in srgb, var(--success) 12%, transparent); color: var(--success); border: 1px solid color-mix(in srgb, var(--success) 25%, transparent);">
                <span class="w-1.5 h-1.5 rounded-full" style="background: var(--success);"></span>
                Published
            </span>
        @else
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold"
                  style="background: var(--surface-muted); color: var(--text-secondary); border: 1px solid var(--border-subtle);">
                <span class="w-1.5 h-1.5 rounded-full" style="background: var(--text-secondary);"></span>
                Draft
            </span>
        @endif
    </div>

    {{-- ══════════════════════════════════════════════════════════════ --}}
    {{-- Course info card                                               --}}
    {{-- ══════════════════════════════════════════════════════════════ --}}
    <div class="flex flex-col md:flex-row gap-6 mb-8 p-5 rounded-2xl"
         style="background: var(--surface-card); border: 1px solid var(--border-subtle);">
        <div class="w-36 h-36 flex-shrink-0">
            @if($course->image)
                <img src="{{ asset('storage/' . $course->image) }}" alt="{{ $course->title }}"
                     class="w-full h-full object-cover rounded-xl" style="border: 1px solid var(--border-subtle);">
            @else
                <div class="w-full h-full flex items-center justify-center rounded-xl text-xs"
                     style="background: var(--surface-muted); border: 1px dashed var(--border-subtle); color: var(--text-secondary);">
                    No image
                </div>
            @endif
        </div>
        <div class="flex-1 space-y-2">
            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider" style="color: var(--text-secondary);">Category</span>
                <p class="text-sm font-semibold mt-0.5" style="color: var(--accent-primary);">
                    {{ $course->category->name ?? 'Uncategorized' }}
                </p>
            </div>
            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider" style="color: var(--text-secondary);">Description</span>
                <p class="text-xs mt-0.5 leading-relaxed" style="color: var(--text-primary);">
                    {{ $course->description ?: 'No description provided.' }}
                </p>
            </div>
            <div class="flex gap-5 pt-1">
                {{-- Lessons count — clicking jumps to lessons tab --}}
                <button @click="tab = 'lessons'" class="text-left transition-all"
                        onmouseover="this.style.opacity='0.7'" onmouseout="this.style.opacity='1'">
                    <span class="text-[10px] font-bold uppercase tracking-wider" style="color: var(--text-secondary);">Lessons</span>
                    <p class="text-sm font-bold" style="color: var(--accent-primary);">{{ $course->lessons->count() }}</p>
                </button>
                {{-- Quizzes count — clicking jumps to quizzes tab --}}
                <button @click="tab = 'quizzes'" class="text-left transition-all"
                        onmouseover="this.style.opacity='0.7'" onmouseout="this.style.opacity='1'">
                    <span class="text-[10px] font-bold uppercase tracking-wider" style="color: var(--text-secondary);">Quizzes</span>
                    <p class="text-sm font-bold" style="color: var(--accent-primary);">{{ $course->quizzes->count() }}</p>
                </button>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════ --}}
    {{-- Tabs pill switcher                                             --}}
    {{-- ══════════════════════════════════════════════════════════════ --}}
    <div class="flex gap-1 mb-6 p-1 rounded-xl w-fit"
         style="background: var(--surface-muted); border: 1px solid var(--border-subtle);">
        <button @click="tab = 'lessons'"
                :style="tab === 'lessons'
                    ? 'background: var(--surface-card); color: var(--accent-primary); box-shadow: 0 1px 4px rgba(0,0,0,0.08);'
                    : 'color: var(--text-secondary);'"
                class="px-5 py-2 rounded-lg text-xs font-bold transition-all">
            Lessons <span class="opacity-60">({{ $course->lessons->count() }})</span>
        </button>
        <button @click="tab = 'quizzes'"
                :style="tab === 'quizzes'
                    ? 'background: var(--surface-card); color: var(--accent-primary); box-shadow: 0 1px 4px rgba(0,0,0,0.08);'
                    : 'color: var(--text-secondary);'"
                class="px-5 py-2 rounded-lg text-xs font-bold transition-all">
            Quizzes &amp; Questions <span class="opacity-60">({{ $course->quizzes->count() }})</span>
        </button>
    </div>

    {{-- ══════════════════════════════════════════════════════════════ --}}
    {{-- LESSONS TAB                                                    --}}
    {{-- ══════════════════════════════════════════════════════════════ --}}
    <div x-show="tab === 'lessons'" x-transition>
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-sm font-bold" style="color: var(--text-primary);">All Lessons</h2>
            <div class="flex items-center gap-2">
                {{-- Quick jump to quizzes --}}
                <button @click="tab = 'quizzes'"
                        class="flex items-center gap-1.5 px-3 py-2 rounded-lg text-xs font-semibold transition-all"
                        style="background: var(--surface-muted); color: var(--text-secondary); border: 1px solid var(--border-subtle);"
                        onmouseover="this.style.color='var(--accent-primary)'; this.style.borderColor='var(--accent-primary)'"
                        onmouseout="this.style.color='var(--text-secondary)'; this.style.borderColor='var(--border-subtle)'">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Manage Quizzes
                </button>
                <button @click="showLessonModal = true; currentLesson = {
                            id: null, title: '', content: '',
                            order: '{{ ($course->lessons->max('order') ?? 0) + 1 }}',
                            duration: '', is_preview: false
                        }"
                        class="flex items-center gap-1.5 px-4 py-2 rounded-lg text-xs font-bold transition-all active:scale-95"
                        style="background: var(--accent-primary); color: #fff;">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Lesson
                </button>
            </div>
        </div>

        <div class="rounded-xl overflow-hidden" style="background: var(--surface-card); border: 1px solid var(--border-subtle);">
            @forelse($course->lessons->sortBy('order') as $lesson)
                {{-- ── Lesson row ─────────────────────────────────── --}}
                <div style="border-bottom: 1px solid var(--border-subtle);">

                    {{-- Summary row --}}
                    <div class="flex items-center gap-3 px-5 py-3.5 transition-colors"
                         onmouseover="this.style.backgroundColor='var(--surface-muted)'"
                         onmouseout="this.style.backgroundColor='transparent'">

                        {{-- Expand toggle --}}
                        <button @click="expandedLessons[{{ $lesson->id }}] = !expandedLessons[{{ $lesson->id }}]"
                                class="w-6 h-6 rounded-lg flex items-center justify-center shrink-0 transition-all"
                                style="background: var(--surface-muted); border: 1px solid var(--border-subtle); color: var(--text-secondary);">
                            <svg class="w-3 h-3 transition-transform"
                                 :style="expandedLessons[{{ $lesson->id }}] ? 'transform:rotate(90deg)' : ''"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>

                        {{-- Order badge --}}
                        <span class="font-mono font-bold text-xs shrink-0 w-6 text-center" style="color: var(--accent-primary);">
                            {{ $lesson->order }}
                        </span>

                        {{-- Title + content snippet --}}
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold truncate" style="color: var(--text-primary);">
                                {{ $lesson->title }}
                            </p>
                            @if($lesson->content)
                                <p class="text-[10px] font-normal mt-0.5 truncate" style="color: var(--text-secondary);">
                                    {{ Str::limit(strip_tags($lesson->content), 70) }}
                                </p>
                            @endif
                        </div>

                        {{-- Duration — always shown (required in controller) --}}
                        <span class="text-xs font-mono shrink-0 px-2 py-0.5 rounded-full"
                              style="background: var(--surface-muted); color: var(--text-secondary); border: 1px solid var(--border-subtle);">
                            {{ $lesson->duration ?? '—' }} min
                        </span>

                        {{-- Video indicator --}}
                        @if($lesson->video_url)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold shrink-0"
                                  style="background: color-mix(in srgb, var(--accent-primary) 12%, transparent); color: var(--accent-primary); border: 1px solid color-mix(in srgb, var(--accent-primary) 25%, transparent);">
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.723v6.554a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>
                                </svg>
                                Video
                            </span>
                        @endif

                        {{-- Preview badge --}}
                        @if($lesson->is_preview)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold shrink-0"
                                  style="background: color-mix(in srgb, var(--success) 12%, transparent); color: var(--success); border: 1px solid color-mix(in srgb, var(--success) 25%, transparent);">
                                Free
                            </span>
                        @endif

                        {{-- Actions --}}
                        <div class="flex items-center gap-4 shrink-0">
                            <button @click="showLessonEditModal = true; currentLesson = {
                                        id: {{ $lesson->id }},
                                        title: '{{ addslashes($lesson->title) }}',
                                        content: `{{ addslashes($lesson->content ?? '') }}`,
                                        order: {{ $lesson->order }},
                                        duration: {{ $lesson->duration ?? 'null' }},
                                        is_preview: {{ $lesson->is_preview ? 'true' : 'false' }}
                                    }"
                                    class="text-xs font-bold transition-all"
                                    style="color: var(--accent-primary);"
                                    onmouseover="this.style.opacity='0.7'" onmouseout="this.style.opacity='1'">
                                Edit
                            </button>
                            <button @click="lessonToDelete = {{ $lesson->id }}; showLessonDeleteModal = true"
                                    class="text-xs font-bold transition-all"
                                    style="color: var(--error);"
                                    onmouseover="this.style.opacity='0.7'" onmouseout="this.style.opacity='1'">
                                Delete
                            </button>
                        </div>
                    </div>

                    {{-- ── Expanded: full content + media player ─────── --}}
                    <div x-show="expandedLessons[{{ $lesson->id }}]"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         class="px-5 pb-5 pt-1"
                         style="border-top: 1px solid var(--border-subtle); background: var(--bg-primary);">

                        @if($lesson->content)
                            <div class="mb-4">
                                <p class="text-[10px] font-bold uppercase tracking-widest mb-1.5" style="color: var(--text-secondary);">Content</p>
                                <p class="text-xs leading-relaxed whitespace-pre-line" style="color: var(--text-primary);">{{ $lesson->content }}</p>
                            </div>
                        @endif

                        @if($lesson->video_url)
                            @php
                                $ext = strtolower(pathinfo($lesson->video_url, PATHINFO_EXTENSION));
                                $mediaUrl = asset('storage/' . $lesson->video_url);
                                $isAudio = in_array($ext, ['mp3', 'ogg', 'wav', 'aac', 'm4a']);
                            @endphp
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-widest mb-1.5" style="color: var(--text-secondary);">
                                    {{ $isAudio ? 'Audio' : 'Video' }}
                                </p>
                                @if($isAudio)
                                    <audio controls class="w-full rounded-xl" style="accent-color: var(--accent-primary);">
                                        <source src="{{ $mediaUrl }}" type="audio/{{ $ext === 'mp3' ? 'mpeg' : $ext }}">
                                        Your browser does not support audio playback.
                                    </audio>
                                @else
                                    <video controls class="w-full rounded-xl max-h-72"
                                           style="background: #000; border: 1px solid var(--border-subtle);">
                                        <source src="{{ $mediaUrl }}" type="video/{{ $ext }}">
                                        Your browser does not support video playback.
                                    </video>
                                @endif
                            </div>
                        @endif

                        @if(!$lesson->content && !$lesson->video_url)
                            <p class="text-xs italic" style="color: var(--text-secondary);">No content or media attached.</p>
                        @endif
                    </div>
                </div>
            @empty
                <div class="py-14 text-center">
                    <p class="text-xs" style="color: var(--text-secondary);">No lessons yet. Add your first lesson above.</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════ --}}
    {{-- QUIZZES TAB                                                    --}}
    {{-- ══════════════════════════════════════════════════════════════ --}}
    <div x-show="tab === 'quizzes'" x-transition>
        <div class="flex justify-between items-center mb-4">
            <div>
                <h2 class="text-sm font-bold" style="color: var(--text-primary);">Quizzes &amp; Questions</h2>
                <p class="text-[10px] mt-0.5" style="color: var(--text-secondary);">
                    Click the arrow on any quiz to expand and manage its questions and answers.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <button @click="tab = 'lessons'"
                        class="flex items-center gap-1.5 px-3 py-2 rounded-lg text-xs font-semibold transition-all"
                        style="background: var(--surface-muted); color: var(--text-secondary); border: 1px solid var(--border-subtle);"
                        onmouseover="this.style.color='var(--accent-primary)'; this.style.borderColor='var(--accent-primary)'"
                        onmouseout="this.style.color='var(--text-secondary)'; this.style.borderColor='var(--border-subtle)'">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    View Lessons
                </button>
                <button @click="showQuizModal = true; currentQuiz = {
                            id: null, title: '', description: '',
                            time_limit_minutes: '', max_attempts: '', passing_score: ''
                        }"
                        class="flex items-center gap-1.5 px-4 py-2 rounded-lg text-xs font-bold transition-all active:scale-95"
                        style="background: var(--accent-primary); color: #fff;">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Quiz
                </button>
            </div>
        </div>

        @forelse($course->quizzes as $quiz)
            <div class="mb-4 rounded-xl overflow-hidden"
                 style="background: var(--surface-card); border: 1px solid var(--border-subtle);">

                {{-- Quiz header --}}
                <div class="flex items-center justify-between px-5 py-4"
                     style="background: var(--surface-muted); border-bottom: 1px solid var(--border-subtle);">
                    <div class="flex items-center gap-3">
                        <button @click="expandedQuizzes[{{ $quiz->id }}] = !expandedQuizzes[{{ $quiz->id }}]"
                                class="w-7 h-7 rounded-lg flex items-center justify-center transition-all shrink-0"
                                style="background: var(--surface-card); border: 1px solid var(--border-subtle); color: var(--text-secondary);"
                                :title="expandedQuizzes[{{ $quiz->id }}] ? 'Collapse questions' : 'Expand to manage questions'">
                            <svg class="w-3.5 h-3.5 transition-transform"
                                 :style="expandedQuizzes[{{ $quiz->id }}] ? 'transform:rotate(90deg)' : ''"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                        <div>
                            <div class="flex items-center gap-2">
                                <p class="text-sm font-bold" style="color: var(--text-primary);">{{ $quiz->title }}</p>
                                <span class="text-[9px] px-1.5 py-0.5 rounded font-bold"
                                      style="background: color-mix(in srgb, var(--accent-primary) 12%, transparent); color: var(--accent-primary);">
                                    {{ $quiz->questions->count() }} Q
                                </span>
                            </div>
                            <p class="text-[10px] mt-0.5" style="color: var(--text-secondary);">
                                <span class="font-semibold" style="color: var(--text-primary);">{{ $quiz->time_limit_minutes ?? '—' }}</span> min
                                &bull; <span class="font-semibold" style="color: var(--text-primary);">{{ $quiz->max_attempts ?? '∞' }}</span> attempts
                                &bull; <span class="font-semibold" style="color: var(--text-primary);">{{ $quiz->passing_score ?? '—' }}%</span> to pass
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        {{-- Prominent "Manage Questions" CTA --}}
                        <button @click="expandedQuizzes[{{ $quiz->id }}] = true"
                                x-show="!expandedQuizzes[{{ $quiz->id }}]"
                                class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[10px] font-bold transition-all"
                                style="background: color-mix(in srgb, var(--accent-primary) 12%, transparent); color: var(--accent-primary); border: 1px solid color-mix(in srgb, var(--accent-primary) 25%, transparent);"
                                onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Questions
                        </button>
                        <button @click="showQuizEditModal = true; currentQuiz = {
                                    id: {{ $quiz->id }},
                                    title: '{{ addslashes($quiz->title) }}',
                                    description: '{{ addslashes($quiz->description ?? '') }}',
                                    time_limit_minutes: {{ $quiz->time_limit_minutes ?? 'null' }},
                                    max_attempts: {{ $quiz->max_attempts ?? 'null' }},
                                    passing_score: {{ $quiz->passing_score ?? 'null' }}
                                }"
                                class="text-xs font-bold transition-all"
                                style="color: var(--accent-primary);"
                                onmouseover="this.style.opacity='0.7'" onmouseout="this.style.opacity='1'">
                            Edit
                        </button>
                        <button @click="quizToDelete = {{ $quiz->id }}; showQuizDeleteModal = true"
                                class="text-xs font-bold transition-all"
                                style="color: var(--error);"
                                onmouseover="this.style.opacity='0.7'" onmouseout="this.style.opacity='1'">
                            Delete
                        </button>
                    </div>
                </div>

                {{-- ── Questions expanded panel ─────────────────────── --}}
                <div x-show="expandedQuizzes[{{ $quiz->id }}]"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     class="px-5 py-4">

                    <div class="flex items-center justify-between mb-3">
                        <p class="text-[10px] font-bold uppercase tracking-widest" style="color: var(--text-secondary);">
                            Questions ({{ $quiz->questions->count() }})
                        </p>
                        <button @click="showQuestionModal = true; quizForQuestion = {{ $quiz->id }}; currentQuestion = {
                                    id: null, text: '', type: 'multiple_choice',
                                    options: ['','','',''], correct_answer: ''
                                }"
                                class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[10px] font-bold transition-all active:scale-95"
                                style="background: var(--accent-primary); color: #fff;">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                            </svg>
                            Add Question
                        </button>
                    </div>

                    @forelse($quiz->questions as $question)
                        <div class="mb-3 rounded-lg overflow-hidden"
                             style="background: var(--bg-primary); border: 1px solid var(--border-subtle);">

                            {{-- Question header --}}
                            <div class="flex items-start justify-between px-4 py-3"
                                 style="border-bottom: 1px solid var(--border-subtle);">
                                <div class="flex-1 pr-4">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-[9px] px-1.5 py-0.5 rounded font-bold uppercase tracking-wide"
                                              style="background: var(--surface-muted); color: var(--accent-secondary);">
                                            {{ str_replace('_', ' ', $question->type) }}
                                        </span>
                                    </div>
                                    <p class="text-xs font-semibold" style="color: var(--text-primary);">{{ $question->text }}</p>
                                    @if($question->correct_answer)
                                        <p class="text-[10px] mt-0.5 font-medium" style="color: var(--success);">
                                            ✓ {{ is_array(json_decode($question->correct_answer, true))
                                                    ? implode(', ', json_decode($question->correct_answer, true))
                                                    : $question->correct_answer }}
                                        </p>
                                    @endif
                                </div>
                                <div class="flex items-center gap-3 shrink-0">
                                    <button @click="showQuestionEditModal = true; quizForQuestion = {{ $quiz->id }}; currentQuestion = {
                                                id: {{ $question->id }},
                                                text: '{{ addslashes($question->text) }}',
                                                type: '{{ $question->type }}',
                                                options: {{ $question->options ? $question->options : '[\'\',\'\',\'\',\'\']' }},
                                                correct_answer: '{{ addslashes($question->correct_answer ?? '') }}'
                                            }"
                                            class="text-[10px] font-bold transition-all"
                                            style="color: var(--accent-primary);"
                                            onmouseover="this.style.opacity='0.7'" onmouseout="this.style.opacity='1'">
                                        Edit
                                    </button>
                                    <button @click="questionToDelete = {{ $question->id }}; quizForQuestion = {{ $quiz->id }}; showQuestionDeleteModal = true"
                                            class="text-[10px] font-bold transition-all"
                                            style="color: var(--error);"
                                            onmouseover="this.style.opacity='0.7'" onmouseout="this.style.opacity='1'">
                                        Delete
                                    </button>
                                </div>
                            </div>

                            {{-- Answers — only for multiple_choice --}}
                            @if($question->type === 'multiple_choice')
                                <div class="px-4 py-3">
                                    <div class="flex items-center justify-between mb-2">
                                        <p class="text-[9px] font-bold uppercase tracking-widest" style="color: var(--text-secondary);">
                                            Answers ({{ $question->answers->count() }})
                                        </p>
                                        <button @click="showAnswerModal = true; questionForAnswer = {{ $question->id }}; quizForQuestion = {{ $quiz->id }}; currentAnswer = { id: null, text: '', is_correct: false }"
                                                class="flex items-center gap-1 px-2.5 py-1 rounded text-[9px] font-bold transition-all"
                                                style="background: color-mix(in srgb, var(--accent-secondary) 12%, transparent); color: var(--accent-secondary); border: 1px solid color-mix(in srgb, var(--accent-secondary) 25%, transparent);">
                                            <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                            </svg>
                                            Add Answer
                                        </button>
                                    </div>

                                    @forelse($question->answers as $answer)
                                        <div class="flex items-center justify-between py-1.5 px-3 mb-1 rounded-lg"
                                             style="background: var(--surface-muted); border: 1px solid var(--border-subtle);">
                                            <div class="flex items-center gap-2">
                                                <span class="w-3.5 h-3.5 rounded-full flex items-center justify-center shrink-0"
                                                      style="background: {{ $answer->is_correct ? 'var(--success)' : 'var(--border-subtle)' }};">
                                                    @if($answer->is_correct)
                                                        <svg class="w-2 h-2" fill="none" stroke="white" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                                        </svg>
                                                    @endif
                                                </span>
                                                <span class="text-[10px]" style="color: var(--text-primary);">{{ $answer->text }}</span>
                                                @if($answer->is_correct)
                                                    <span class="text-[9px] font-bold" style="color: var(--success);">Correct</span>
                                                @endif
                                            </div>
                                            <div class="flex items-center gap-3">
                                                <button @click="showAnswerEditModal = true; questionForAnswer = {{ $question->id }}; quizForQuestion = {{ $quiz->id }}; currentAnswer = {
                                                            id: {{ $answer->id }},
                                                            text: '{{ addslashes($answer->text) }}',
                                                            is_correct: {{ $answer->is_correct ? 'true' : 'false' }}
                                                        }"
                                                        class="text-[10px] font-bold"
                                                        style="color: var(--accent-primary);"
                                                        onmouseover="this.style.opacity='0.7'" onmouseout="this.style.opacity='1'">
                                                    Edit
                                                </button>
                                                <button @click="answerToDelete = {{ $answer->id }}; questionForAnswer = {{ $question->id }}; quizForQuestion = {{ $quiz->id }}; showAnswerDeleteModal = true"
                                                        class="text-[10px] font-bold"
                                                        style="color: var(--error);"
                                                        onmouseover="this.style.opacity='0.7'" onmouseout="this.style.opacity='1'">
                                                    Delete
                                                </button>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-[10px] italic py-1" style="color: var(--text-secondary);">
                                            No answers yet — add one above.
                                        </p>
                                    @endforelse
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="py-8 text-center rounded-xl" style="border: 1px dashed var(--border-subtle);">
                            <p class="text-xs mb-3" style="color: var(--text-secondary);">No questions yet.</p>
                            <button @click="showQuestionModal = true; quizForQuestion = {{ $quiz->id }}; currentQuestion = {
                                        id: null, text: '', type: 'multiple_choice',
                                        options: ['','','',''], correct_answer: ''
                                    }"
                                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-xs font-bold transition-all"
                                    style="background: var(--accent-primary); color: #fff;">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                </svg>
                                Add First Question
                            </button>
                        </div>
                    @endforelse
                </div>
            </div>
        @empty
            <div class="py-14 text-center rounded-xl" style="background: var(--surface-card); border: 1px solid var(--border-subtle);">
                <p class="text-xs mb-3" style="color: var(--text-secondary);">No quizzes yet.</p>
                <button @click="showQuizModal = true; currentQuiz = {
                            id: null, title: '', description: '',
                            time_limit_minutes: '', max_attempts: '', passing_score: ''
                        }"
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-xs font-bold transition-all"
                        style="background: var(--accent-primary); color: #fff;">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add First Quiz
                </button>
            </div>
        @endforelse
    </div>


    {{-- ══════════════════════════════════════════════════════════════════ --}}
    {{-- MODALS                                                            --}}
    {{-- ══════════════════════════════════════════════════════════════════ --}}

    {{-- ── LESSON Create/Edit ──────────────────────────────────────── --}}
    <template x-if="showLessonModal || showLessonEditModal">
        <div class="fixed inset-0 z-[110] overflow-y-auto" x-cloak>
            <div class="flex items-center justify-center min-h-screen px-4 py-10">
                <div class="fixed inset-0 backdrop-blur-sm" style="background: rgba(2,6,23,0.55);"
                     @click="showLessonModal = false; showLessonEditModal = false"></div>

                <div x-transition:enter="ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="relative z-20 max-w-lg w-full rounded-2xl p-7"
                     style="background: var(--surface-card); border: 1px solid var(--border-subtle);">
                    <h2 class="text-base font-black mb-5" style="color: var(--text-primary);"
                        x-text="showLessonModal ? 'Add Lesson' : 'Edit Lesson'"></h2>

                    {{--
                        enctype required for video_url file upload.
                        Fields: title, content, order, duration, video_url (file), is_preview
                    --}}
                    <form :action="showLessonModal
                                    ? '{{ route('instructor.courses.lessons.store', $course) }}'
                                    : `/instructor/courses/{{ $course->id }}/lessons/${currentLesson.id}`"
                          method="POST"
                          enctype="multipart/form-data">
                        @csrf
                        <template x-if="showLessonEditModal">
                            <input type="hidden" name="_method" value="PUT">
                        </template>

                        <div class="space-y-4">
                            {{-- title | required|string|max:255 --}}
                            <div>
                                <label class="block text-xs font-bold mb-1.5" style="color: var(--text-primary);">
                                    Title <span style="color:var(--error)">*</span>
                                </label>
                                <input type="text" name="title" x-model="currentLesson.title" required maxlength="255"
                                       class="w-full px-3 py-2.5 rounded-xl text-xs outline-none transition-all"
                                       style="background: var(--bg-primary); border: 1px solid var(--border-subtle); color: var(--text-primary);"
                                       onfocus="this.style.borderColor='var(--accent-primary)'"
                                       onblur="this.style.borderColor='var(--border-subtle)'">
                            </div>

                            {{-- content | nullable|string --}}
                            <div>
                                <label class="block text-xs font-bold mb-1.5" style="color: var(--text-primary);">Content</label>
                                <textarea name="content" x-model="currentLesson.content" rows="4"
                                          class="w-full px-3 py-2.5 rounded-xl text-xs outline-none transition-all resize-y"
                                          style="background: var(--bg-primary); border: 1px solid var(--border-subtle); color: var(--text-primary);"
                                          onfocus="this.style.borderColor='var(--accent-primary)'"
                                          onblur="this.style.borderColor='var(--border-subtle)'"></textarea>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                {{-- order | required|integer|unique per course --}}
                                <div>
                                    <label class="block text-xs font-bold mb-1.5" style="color: var(--text-primary);">
                                        Order <span style="color:var(--error)">*</span>
                                    </label>
                                    <input type="number" name="order" x-model="currentLesson.order" required min="1"
                                           class="w-full px-3 py-2.5 rounded-xl text-xs outline-none transition-all"
                                           style="background: var(--bg-primary); border: 1px solid var(--border-subtle); color: var(--text-primary);"
                                           onfocus="this.style.borderColor='var(--accent-primary)'"
                                           onblur="this.style.borderColor='var(--border-subtle)'">
                                </div>
                                {{-- duration | required|integer|min:1 --}}
                                <div>
                                    <label class="block text-xs font-bold mb-1.5" style="color: var(--text-primary);">
                                        Duration (min) <span style="color:var(--error)">*</span>
                                    </label>
                                    <input type="number" name="duration" x-model="currentLesson.duration" required min="1"
                                           class="w-full px-3 py-2.5 rounded-xl text-xs outline-none transition-all"
                                           style="background: var(--bg-primary); border: 1px solid var(--border-subtle); color: var(--text-primary);"
                                           onfocus="this.style.borderColor='var(--accent-primary)'"
                                           onblur="this.style.borderColor='var(--border-subtle)'">
                                </div>
                            </div>

                            {{--
                                video_url | nullable|file|mimes:mp4,webm,ogg|max:51200
                                Stored via Storage::disk('public') — served at asset('storage/...')
                                On edit, leaving blank keeps the existing file.
                            --}}
                            <div>
                                <label class="block text-xs font-bold mb-1.5" style="color: var(--text-primary);">
                                    Video / Audio File
                                    <span class="font-normal ml-1" style="color: var(--text-secondary);">mp4, webm, ogg — max 50 MB</span>
                                </label>
                                <input type="file" name="video_url" accept="video/mp4,video/webm,video/ogg,audio/*"
                                       class="w-full px-3 py-2 rounded-xl text-xs transition-all file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:cursor-pointer"
                                       style="background: var(--bg-primary); border: 1px solid var(--border-subtle); color: var(--text-secondary);">
                                <template x-if="showLessonEditModal && currentLesson.video_url">
                                    <p class="mt-1.5 text-[10px]" style="color: var(--text-secondary);">
                                        Current file: <span class="font-mono" x-text="currentLesson.video_url"></span>
                                        — leave blank to keep it.
                                    </p>
                                </template>
                            </div>

                            {{-- is_preview | boolean --}}
                            <div class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl"
                                 style="background: var(--surface-muted); border: 1px solid var(--border-subtle);">
                                <input type="checkbox" name="is_preview" id="is_preview_modal"
                                       value="1" x-model="currentLesson.is_preview"
                                       class="w-4 h-4 rounded" style="accent-color: var(--accent-primary);">
                                <label for="is_preview_modal" class="text-xs font-bold cursor-pointer"
                                       style="color: var(--text-primary);">Available as free preview</label>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end gap-3">
                            <button type="button" @click="showLessonModal = false; showLessonEditModal = false"
                                    class="px-5 py-2.5 text-xs font-bold rounded-xl"
                                    style="color: var(--text-secondary);"
                                    onmouseover="this.style.color='var(--text-primary)'"
                                    onmouseout="this.style.color='var(--text-secondary)'">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="px-6 py-2.5 rounded-xl text-xs font-bold"
                                    style="background: var(--accent-primary); color: #fff;"
                                    onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                                <span x-text="showLessonModal ? 'Create Lesson' : 'Update Lesson'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </template>

    {{-- ── QUIZ Create/Edit ─────────────────────────────────────────── --}}
    <template x-if="showQuizModal || showQuizEditModal">
        <div class="fixed inset-0 z-[110] overflow-y-auto" x-cloak>
            <div class="flex items-center justify-center min-h-screen px-4 py-10">
                <div class="fixed inset-0 backdrop-blur-sm" style="background: rgba(2,6,23,0.55);"
                     @click="showQuizModal = false; showQuizEditModal = false"></div>

                <div x-transition:enter="ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="relative z-20 max-w-lg w-full rounded-2xl p-7"
                     style="background: var(--surface-card); border: 1px solid var(--border-subtle);">
                    <h2 class="text-base font-black mb-5" style="color: var(--text-primary);"
                        x-text="showQuizModal ? 'Add Quiz' : 'Edit Quiz'"></h2>

                    <form :action="showQuizModal
                                    ? '{{ route('instructor.courses.quizzes.store', $course) }}'
                                    : `/instructor/courses/{{ $course->id }}/quizzes/${currentQuiz.id}`"
                          method="POST">
                        @csrf
                        <template x-if="showQuizEditModal">
                            <input type="hidden" name="_method" value="PUT">
                        </template>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold mb-1.5" style="color: var(--text-primary);">
                                    Quiz Title <span style="color:var(--error)">*</span>
                                </label>
                                <input type="text" name="title" x-model="currentQuiz.title" required maxlength="255"
                                       class="w-full px-3 py-2.5 rounded-xl text-xs outline-none transition-all"
                                       style="background: var(--bg-primary); border: 1px solid var(--border-subtle); color: var(--text-primary);"
                                       onfocus="this.style.borderColor='var(--accent-primary)'"
                                       onblur="this.style.borderColor='var(--border-subtle)'">
                            </div>
                            <div>
                                <label class="block text-xs font-bold mb-1.5" style="color: var(--text-primary);">Description</label>
                                <textarea name="description" x-model="currentQuiz.description" rows="2"
                                          class="w-full px-3 py-2.5 rounded-xl text-xs outline-none transition-all resize-none"
                                          style="background: var(--bg-primary); border: 1px solid var(--border-subtle); color: var(--text-primary);"
                                          onfocus="this.style.borderColor='var(--accent-primary)'"
                                          onblur="this.style.borderColor='var(--border-subtle)'"></textarea>
                            </div>
                            <div class="grid grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-bold mb-1.5" style="color: var(--text-primary);">Time (min) <span style="color:var(--error)">*</span></label>
                                    <input type="number" name="time_limit_minutes" x-model="currentQuiz.time_limit_minutes" required min="1"
                                           class="w-full px-3 py-2.5 rounded-xl text-xs outline-none"
                                           style="background: var(--bg-primary); border: 1px solid var(--border-subtle); color: var(--text-primary);"
                                           onfocus="this.style.borderColor='var(--accent-primary)'"
                                           onblur="this.style.borderColor='var(--border-subtle)'">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold mb-1.5" style="color: var(--text-primary);">Attempts <span style="color:var(--error)">*</span></label>
                                    <input type="number" name="max_attempts" x-model="currentQuiz.max_attempts" required min="1"
                                           class="w-full px-3 py-2.5 rounded-xl text-xs outline-none"
                                           style="background: var(--bg-primary); border: 1px solid var(--border-subtle); color: var(--text-primary);"
                                           onfocus="this.style.borderColor='var(--accent-primary)'"
                                           onblur="this.style.borderColor='var(--border-subtle)'">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold mb-1.5" style="color: var(--text-primary);">Pass % <span style="color:var(--error)">*</span></label>
                                    <input type="number" name="passing_score" x-model="currentQuiz.passing_score" required min="0" max="100"
                                           class="w-full px-3 py-2.5 rounded-xl text-xs outline-none"
                                           style="background: var(--bg-primary); border: 1px solid var(--border-subtle); color: var(--text-primary);"
                                           onfocus="this.style.borderColor='var(--accent-primary)'"
                                           onblur="this.style.borderColor='var(--border-subtle)'">
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end gap-3">
                            <button type="button" @click="showQuizModal = false; showQuizEditModal = false"
                                    class="px-5 py-2.5 text-xs font-bold rounded-xl"
                                    style="color: var(--text-secondary);"
                                    onmouseover="this.style.color='var(--text-primary)'"
                                    onmouseout="this.style.color='var(--text-secondary)'">Cancel</button>
                            <button type="submit"
                                    class="px-6 py-2.5 rounded-xl text-xs font-bold"
                                    style="background: var(--accent-primary); color: #fff;"
                                    onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                                <span x-text="showQuizModal ? 'Create Quiz' : 'Update Quiz'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </template>

    {{-- ── QUESTION Create/Edit ─────────────────────────────────────── --}}
    <template x-if="showQuestionModal || showQuestionEditModal">
        <div class="fixed inset-0 z-[110] overflow-y-auto" x-cloak>
            <div class="flex items-center justify-center min-h-screen px-4 py-10">
                <div class="fixed inset-0 backdrop-blur-sm" style="background: rgba(2,6,23,0.55);"
                     @click="showQuestionModal = false; showQuestionEditModal = false"></div>

                <div x-transition:enter="ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="relative z-20 max-w-lg w-full rounded-2xl p-7"
                     style="background: var(--surface-card); border: 1px solid var(--border-subtle);">
                    <h2 class="text-base font-black mb-5" style="color: var(--text-primary);"
                        x-text="showQuestionModal ? 'Add Question' : 'Edit Question'"></h2>

                    <form :action="showQuestionModal
                                    ? `/instructor/courses/{{ $course->id }}/quizzes/${quizForQuestion}/questions`
                                    : `/instructor/courses/{{ $course->id }}/quizzes/${quizForQuestion}/questions/${currentQuestion.id}`"
                          method="POST">
                        @csrf
                        <template x-if="showQuestionEditModal">
                            <input type="hidden" name="_method" value="PUT">
                        </template>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold mb-1.5" style="color: var(--text-primary);">
                                    Question Text <span style="color:var(--error)">*</span>
                                </label>
                                <textarea name="text" x-model="currentQuestion.text" required rows="3" maxlength="1000"
                                          class="w-full px-3 py-2.5 rounded-xl text-xs outline-none resize-none"
                                          style="background: var(--bg-primary); border: 1px solid var(--border-subtle); color: var(--text-primary);"
                                          onfocus="this.style.borderColor='var(--accent-primary)'"
                                          onblur="this.style.borderColor='var(--border-subtle)'"></textarea>
                            </div>

                            <div>
                                <label class="block text-xs font-bold mb-1.5" style="color: var(--text-primary);">
                                    Type <span style="color:var(--error)">*</span>
                                </label>
                                <select name="type" x-model="currentQuestion.type" required
                                        class="w-full px-3 py-2.5 rounded-xl text-xs outline-none"
                                        style="background: var(--bg-primary); border: 1px solid var(--border-subtle); color: var(--text-primary);"
                                        onfocus="this.style.borderColor='var(--accent-primary)'"
                                        onblur="this.style.borderColor='var(--border-subtle)'">
                                    <option value="multiple_choice">Multiple Choice</option>
                                    <option value="true_false">True / False</option>
                                    <option value="short_answer">Short Answer</option>
                                </select>
                            </div>

                            {{-- options[] for multiple_choice --}}
                            <template x-if="currentQuestion.type === 'multiple_choice'">
                                <div>
                                    <label class="block text-xs font-bold mb-1.5" style="color: var(--text-primary);">Options</label>
                                    <template x-for="(opt, i) in currentQuestion.options" :key="i">
                                        <div class="flex items-center gap-2 mb-1.5">
                                            <span class="text-[10px] font-mono w-4 text-center shrink-0" style="color: var(--text-secondary);" x-text="i + 1"></span>
                                            <input type="text" :name="`options[]`" x-model="currentQuestion.options[i]" maxlength="255"
                                                   class="flex-1 px-3 py-2 rounded-xl text-xs outline-none"
                                                   style="background: var(--bg-primary); border: 1px solid var(--border-subtle); color: var(--text-primary);"
                                                   onfocus="this.style.borderColor='var(--accent-primary)'"
                                                   onblur="this.style.borderColor='var(--border-subtle)'"
                                                   placeholder="Option text…">
                                        </div>
                                    </template>
                                    <button type="button" @click="currentQuestion.options.push('')"
                                            class="mt-1 text-[10px] font-bold" style="color: var(--accent-primary);">
                                        + Add option
                                    </button>
                                </div>
                            </template>

                            {{-- correct_answer for true_false --}}
                            <template x-if="currentQuestion.type === 'true_false'">
                                <div>
                                    <label class="block text-xs font-bold mb-1.5" style="color: var(--text-primary);">Correct Answer</label>
                                    <select name="correct_answer" x-model="currentQuestion.correct_answer"
                                            class="w-full px-3 py-2.5 rounded-xl text-xs outline-none"
                                            style="background: var(--bg-primary); border: 1px solid var(--border-subtle); color: var(--text-primary);"
                                            onfocus="this.style.borderColor='var(--accent-primary)'"
                                            onblur="this.style.borderColor='var(--border-subtle)'">
                                        <option value="">Select…</option>
                                        <option value="True">True</option>
                                        <option value="False">False</option>
                                    </select>
                                </div>
                            </template>

                            {{-- correct_answer for short_answer --}}
                            <template x-if="currentQuestion.type === 'short_answer'">
                                <div>
                                    <label class="block text-xs font-bold mb-1.5" style="color: var(--text-primary);">Expected Answer</label>
                                    <input type="text" name="correct_answer" x-model="currentQuestion.correct_answer"
                                           class="w-full px-3 py-2.5 rounded-xl text-xs outline-none"
                                           style="background: var(--bg-primary); border: 1px solid var(--border-subtle); color: var(--text-primary);"
                                           onfocus="this.style.borderColor='var(--accent-primary)'"
                                           onblur="this.style.borderColor='var(--border-subtle)'"
                                           placeholder="Model answer…">
                                </div>
                            </template>
                        </div>

                        <div class="mt-6 flex justify-end gap-3">
                            <button type="button" @click="showQuestionModal = false; showQuestionEditModal = false"
                                    class="px-5 py-2.5 text-xs font-bold rounded-xl"
                                    style="color: var(--text-secondary);"
                                    onmouseover="this.style.color='var(--text-primary)'"
                                    onmouseout="this.style.color='var(--text-secondary)'">Cancel</button>
                            <button type="submit"
                                    class="px-6 py-2.5 rounded-xl text-xs font-bold"
                                    style="background: var(--accent-primary); color: #fff;"
                                    onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                                <span x-text="showQuestionModal ? 'Save Question' : 'Update Question'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </template>

    {{-- ── ANSWER Create/Edit ───────────────────────────────────────── --}}
    <template x-if="showAnswerModal || showAnswerEditModal">
        <div class="fixed inset-0 z-[110] overflow-y-auto" x-cloak>
            <div class="flex items-center justify-center min-h-screen px-4 py-10">
                <div class="fixed inset-0 backdrop-blur-sm" style="background: rgba(2,6,23,0.55);"
                     @click="showAnswerModal = false; showAnswerEditModal = false"></div>

                <div x-transition:enter="ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="relative z-20 max-w-sm w-full rounded-2xl p-7"
                     style="background: var(--surface-card); border: 1px solid var(--border-subtle);">
                    <h2 class="text-base font-black mb-5" style="color: var(--text-primary);"
                        x-text="showAnswerModal ? 'Add Answer' : 'Edit Answer'"></h2>

                    <form :action="showAnswerModal
                                    ? `/instructor/courses/{{ $course->id }}/quizzes/${quizForQuestion}/questions/${questionForAnswer}/answers`
                                    : `/instructor/courses/{{ $course->id }}/quizzes/${quizForQuestion}/questions/${questionForAnswer}/answers/${currentAnswer.id}`"
                          method="POST">
                        @csrf
                        <template x-if="showAnswerEditModal">
                            <input type="hidden" name="_method" value="PUT">
                        </template>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold mb-1.5" style="color: var(--text-primary);">
                                    Answer Text <span style="color:var(--error)">*</span>
                                </label>
                                <input type="text" name="text" x-model="currentAnswer.text" required maxlength="255"
                                       class="w-full px-3 py-2.5 rounded-xl text-xs outline-none"
                                       style="background: var(--bg-primary); border: 1px solid var(--border-subtle); color: var(--text-primary);"
                                       onfocus="this.style.borderColor='var(--accent-primary)'"
                                       onblur="this.style.borderColor='var(--border-subtle)'">
                            </div>
                            <div class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl"
                                 style="background: var(--surface-muted); border: 1px solid var(--border-subtle);">
                                <input type="checkbox" name="is_correct" id="is_correct_modal"
                                       value="1" x-model="currentAnswer.is_correct"
                                       class="w-4 h-4 rounded" style="accent-color: var(--success);">
                                <label for="is_correct_modal" class="text-xs font-bold cursor-pointer"
                                       style="color: var(--text-primary);">Mark as correct answer</label>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end gap-3">
                            <button type="button" @click="showAnswerModal = false; showAnswerEditModal = false"
                                    class="px-5 py-2.5 text-xs font-bold rounded-xl"
                                    style="color: var(--text-secondary);"
                                    onmouseover="this.style.color='var(--text-primary)'"
                                    onmouseout="this.style.color='var(--text-secondary)'">Cancel</button>
                            <button type="submit"
                                    class="px-6 py-2.5 rounded-xl text-xs font-bold"
                                    style="background: var(--accent-primary); color: #fff;"
                                    onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                                <span x-text="showAnswerModal ? 'Save Answer' : 'Update Answer'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </template>

    {{-- ── Unified Delete Modal ─────────────────────────────────────── --}}
    <div x-show="showLessonDeleteModal || showQuizDeleteModal || showQuestionDeleteModal || showAnswerDeleteModal"
         class="fixed inset-0 z-[120] overflow-y-auto" x-cloak>
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 backdrop-blur-sm" style="background: rgba(2,6,23,0.55);"
                 @click="showLessonDeleteModal = false; showQuizDeleteModal = false; showQuestionDeleteModal = false; showAnswerDeleteModal = false"></div>

            <div x-transition:enter="ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="relative z-20 max-w-sm w-full rounded-2xl p-7 text-center"
                 style="background: var(--surface-card); border: 1px solid var(--border-subtle);">

                <div class="w-12 h-12 mx-auto mb-4 rounded-full flex items-center justify-center"
                     style="background: color-mix(in srgb, var(--error) 12%, transparent);">
                    <svg class="w-6 h-6" style="color: var(--error);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
                <h2 class="text-sm font-black mb-2" style="color: var(--text-primary);">Confirm Delete</h2>
                <p class="text-xs mb-6" style="color: var(--text-secondary);">This action cannot be undone.</p>

                <div class="flex justify-center gap-3">
                    <button type="button"
                            @click="showLessonDeleteModal = false; showQuizDeleteModal = false; showQuestionDeleteModal = false; showAnswerDeleteModal = false"
                            class="px-5 py-2.5 text-xs font-bold rounded-xl"
                            style="color: var(--text-secondary); background: var(--surface-muted); border: 1px solid var(--border-subtle);">
                        Cancel
                    </button>

                    <template x-if="showLessonDeleteModal">
                        <form :action="`/instructor/courses/{{ $course->id }}/lessons/${lessonToDelete}`" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-bold" style="background: var(--error); color: #fff;">
                                Delete Lesson
                            </button>
                        </form>
                    </template>

                    <template x-if="showQuizDeleteModal">
                        <form :action="`/instructor/courses/{{ $course->id }}/quizzes/${quizToDelete}`" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-bold" style="background: var(--error); color: #fff;">
                                Delete Quiz
                            </button>
                        </form>
                    </template>

                    <template x-if="showQuestionDeleteModal">
                        <form :action="`/instructor/courses/{{ $course->id }}/quizzes/${quizForQuestion}/questions/${questionToDelete}`" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-bold" style="background: var(--error); color: #fff;">
                                Delete Question
                            </button>
                        </form>
                    </template>

                    <template x-if="showAnswerDeleteModal">
                        <form :action="`/instructor/courses/{{ $course->id }}/quizzes/${quizForQuestion}/questions/${questionForAnswer}/answers/${answerToDelete}`" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-bold" style="background: var(--error); color: #fff;">
                                Delete Answer
                            </button>
                        </form>
                    </template>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
