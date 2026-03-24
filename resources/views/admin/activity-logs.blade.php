@extends('layouts.admin')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-6">Activity Logs</h1>

    <div class="bg-zinc-800 dark:bg-gray-800 p-6 rounded-lg shadow">
        @if($activities->count() > 0)
            <div class="space-y-4">
                @foreach($activities as $activity)
                    <div class="border-b pb-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-semibold">{{ $activity->action }}</p>
                                <p class="text-sm text-gray-600">{{ $activity->description }}</p>
                                @if($activity->meta)
                                    <pre class="text-xs bg-gray-100 p-2 mt-2 rounded">{{ json_encode(json_decode($activity->meta), JSON_PRETTY_PRINT) }}</pre>
                                @endif
                            </div>
                            <span class="text-sm text-gray-500">{{ $activity->created_at->format('Y-m-d H:i:s') }}</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $activities->links() }}
            </div>
        @else
            <p class="text-gray-500">No activity logs found.</p>
        @endif
    </div>
</div>
@endsection