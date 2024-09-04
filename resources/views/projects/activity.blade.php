<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <b>{{ explode(' ', auth()->user()->name)[0] }}'s </b>{{ __('Dashboard') }}<br>
            <small><b style="font-size:15px;">Role:</b> {{ auth()->user()->role->name }}</small>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800">
                    <h4 class="font-semibold text-lg text-gray-800 dark:text-gray-200 mb-4">Activity Log</h4>
                    
                    @if($activities->isEmpty())
                        <p class="text-center text-gray-500 dark:text-gray-400">No activities recorded yet.</p>
                    @else
                        <ul class="timeline">
                            @foreach($activities as $activity)
                            <li class="timeline-item mb-5">
                                <div class="timeline-point bg-blue-500"></div>
                                <div class="timeline-content">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h5 class="font-semibold mb-0 text-gray-800 dark:text-gray-200">{{ $activity->action }}</h5>
                                        <small class="text-gray-500">{{ $activity->created_at->format('M d, Y h:i A') }}</small>
                                    </div>
                                    <p class="text-gray-600 dark:text-gray-400">{{ $activity->description }}</p>
                                    <p class="text-sm text-gray-500">
                                        <small>By {{ $activity->user->name }}</small>
                                    </p>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Timeline styles */
        .timeline {
            list-style: none;
            padding: 0;
        }
        .timeline-item {
            position: relative;
            padding-left: 40px;
        }
        .timeline-item::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 0;
            bottom: 0;
            width: 2px;
            background-color: #e2e8f0; /* Tailwind gray-300 */
        }
        .timeline-point {
            width: 15px;
            height: 15px;
            border-radius: 50%;
            position: absolute;
            left: 7px;
            top: 5px;
        }
        .timeline-content {
            padding: 15px;
            background-color: #f7fafc; /* Tailwind gray-100 */
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .dark .timeline-content {
            background-color: #2d3748; /* Tailwind gray-800 */
        }
    </style>

</x-app-layout>
