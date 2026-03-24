@extends('layouts.app')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-6">Admin Dashboard</h1>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-zinc-800 dark:bg-gray-800 p-4 rounded-lg shadow">
            <div class="text-gray-500 text-sm">Total Users</div>
            <div class="text-2xl font-bold">{{ $totalUsers }}</div>
        </div>
        <div class="bg-zinc-800 dark:bg-gray-800 p-4 rounded-lg shadow">
            <div class="text-gray-500 text-sm">Active Users</div>
            <div class="text-2xl font-bold">{{ $activeUsers }}</div>
        </div>
        <div class="bg-zinc-800 dark:bg-gray-800 p-4 rounded-lg shadow">
            <div class="text-gray-500 text-sm">New Users Today</div>
            <div class="text-2xl font-bold">{{ $newUsersToday }}</div>
        </div>
        <div class="bg-zinc-800 dark:bg-gray-800 p-4 rounded-lg shadow">
            <div class="text-gray-500 text-sm">Total Courses</div>
            <div class="text-2xl font-bold">{{ \App\Models\Course::count() }}</div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-zinc-800 dark:bg-gray-800 p-6 rounded-lg shadow">
        <h2 class="text-lg font-semibold mb-4">Recent Activity</h2>
        @if($activities->count() > 0)
            <div class="space-y-3">
                @foreach($activities as $activity)
                    <div class="border-b pb-2">
                        <p class="font-medium">{{ $activity->action }}</p>
                        <p class="text-sm text-gray-500">{{ $activity->description }}</p>
                        <p class="text-xs text-gray-400">{{ $activity->created_at->diffForHumans() }}</p>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500">No recent activity.</p>
        @endif
    </div>
</div>
@endsection