<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Task Tracker') }} - {{ $staff->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <h3 class="text-lg font-semibold mb-4">Tasks for {{ $staff->name }}</h3>

                    @if($staff->tasks->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($staff->tasks as $task)
                                <div class="bg-gray-100 dark:bg-gray-900 p-4 rounded-lg shadow-lg">
                                    <h4 class="font-semibold text-blue-600 dark:text-blue-400">{{ $task->title }}</h4>
                                    <p class="mt-2 text-gray-600 dark:text-gray-400">{{ $task->description }}</p>

                                    @if($task->deadline)
                                        @php
                                            // Convert deadline string to Carbon instance
                                            $deadline = \Carbon\Carbon::parse($task->deadline);
                                            $daysRemaining = now()->diffInDays($deadline, false);
                                        @endphp
                                        <p class="mt-4 text-sm">
                                            <strong>Deadline:</strong> {{ $deadline->format('M d, Y') }}
                                        </p>
                                        <p class="mt-2 text-sm {{ $daysRemaining > 0 ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $daysRemaining > 0 ? $daysRemaining . ' days left' : abs($daysRemaining) . ' days overdue' }}
                                        </p>
                                    @else
                                        <p class="mt-4 text-sm text-yellow-600">No deadline set for this task.</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p>No tasks assigned to this user yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
