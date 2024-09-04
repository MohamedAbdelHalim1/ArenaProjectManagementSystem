<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Project Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold">{{ $project->name }}</h3>
                        <h3 class="text-lg font-semibold">Client: {{ $project->client_name ?? 'UNKOWN' }}</h3>
                        <p class="text-gray-600">{{ $project->description }}</p>
                        <p class="text-gray-600">Status: {{ ucfirst($project->status) }}</p>
                        @if(ucfirst($project->status) === 'Refused')<p class="text-gray-600">Refusal Reason: {{ $project->refusal_reason }}</p>@endif
                        <p class="text-gray-600">Assigned User: {{ $project->user->name }}</p>
                        <p class="text-gray-600">Created Date: {{ \Carbon\Carbon::parse($project->created_at)->format('Y-m-d') }}</p>
                        <p class="text-red-600">Deadline: {{ $project->deadline }}</p>

                    </div>
                    
 
                    <div class="d-flex justify-content-arround">
                        <h4 class="text-lg font-semibold mb-4 me-2">Tasks</h4>
                        @if (auth()->user()->role->id === 1 || auth()->user()->role->name === 'project manager' || auth()->user()->role->name === 'project managers')
                        <a href="#" style="height:40px;" class="bg-blue-500 text-white px-4 py-2 rounded-md shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" data-bs-toggle="modal" data-bs-target="#taskModal" data-project-id="{{ $project->id }}">
                            Add New Task
                        </a>
                        @endif
                    </div>


                    <!-- Modal Structure -->
                    <div class="modal fade" id="taskModal" tabindex="-1" aria-labelledby="taskModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content dark:bg-gray-800 dark:text-white">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="taskModalLabel">Add New Task</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="taskForm" method="POST" action="{{ route('tasks.store') }}">
                                        @csrf

                                        <!-- Hidden input for project ID -->
                                        <input type="hidden" name="project_id" value="{{ $project->id }}">

                                        <!-- Task Name -->
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Task Name</label>
                                            <input type="text" name="title" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-800 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                            @error('name')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Task Description -->
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Task Note</label>
                                            <textarea name="description" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-800 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                                            @error('description')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Task Status -->
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Task Status</label>
                                            <select name="status" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-800 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                                <option value="not_started">Not Started</option>
                                                <option value="started">Started</option>
                                                <option value="done">Done</option>
                                            </select>
                                            @error('status')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Assign Task to User -->
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Assign Task to User</label>
                                            <select name="user_id" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-800 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('user_id')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary" onclick="document.getElementById('taskForm').submit();">Save Task</button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Note</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deadline</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned User</th>
                                @if(auth()->user()->role->id === 1)<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>@endif
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($project->tasks as $task)
                                @if (auth()->user()->role->id === 1 || $task->user_id === auth()->id())
                                    <tr>
                                        
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $task->title }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">{{ $task->description }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                <form action="{{ route('tasks.updateStatus', $task->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <select name="status" class="form-select mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-800 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" onchange="this.form.submit()">
                                                        <option value="ask_to_start" {{ $task->status === 'ask_to_start' ? 'selected' : '' }}>Ask To Start</option>
                                                        <option value="started" {{ $task->status === 'started' ? 'selected' : '' }}>Start</option>
                                                        <option value="not_started" {{ $task->status === 'not_started' ? 'selected' : '' }}>Not Started</option>
                                                        <option value="done" {{ $task->status === 'done' ? 'selected' : '' }}>Done</option>
                                                        <option value="refused" {{ $task->status === 'refused' ? 'selected' : '' }}>Refused</option>
                                                    </select>
                                                    @if($task->status === 'refused')
                                                    <textarea name="refusal_reason" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-800 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('refusal_reason', $task->refusal_reason) }}</textarea>
                                                        <div class="flex items-center justify-between">
                                                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Update Task</button>
                                                        </div>
                                                    @endif

                                                </form>
                                            </td>
                                        <td class="px-6 py-4 text-sm text-red-500">{{ $task->deadline ?? 'Unassigned' }}</td>

                                        <td class="px-6 py-4 text-sm text-gray-500">{{ $task->user->name ?? 'Unassigned' }}</td>
                                    
                                        <td class="px-6 py-4 text-sm font-medium">
                                            <a href="{{ route('tasks.edit', $task) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                            @if (auth()->user()->role->id === 1 || auth()->user()->role->name === 'project manager' || auth()->user()->role->name === 'project managers')

                                            <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline-block ml-2">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                            </form>
                                            @endif

                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table><br>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('taskForm').addEventListener('submit', function(e) {
            e.preventDefault(); 

            this.submit();
        });

    </script>
</x-app-layout>
