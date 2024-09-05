<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <b>{{ explode(' ', auth()->user()->name)[0] }}'s </b>{{ __('Dashboard') }}<br>
            <small><b style="font-size:15px;">Role:</b>{{auth()->user()->role->name}}</small>
        </h2>
    </x-slot>
    <style>
        .no-underline-hover:hover {
            text-decoration: none;
        }
    </style>
    @if(auth()->user()->role->id == 1 )
    <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Total Projects Card -->
            <a href="#projects" class="no-underline-hover">
                <div class="bg-blue-500 text-white p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold">Total Projects</h3>
                    <p class="text-3xl font-bold">{{ $totalProjects }}</p>
                </div>
            </a>

            <!-- Total Tasks (Started) Card -->
            <a href="{{ route('tasks.index') }}" class="no-underline-hover">
                <div class="bg-green-500 text-white p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold">Total Tasks (Started)</h3>
                    <p class="text-3xl font-bold">{{ $totalTasksStarted }}</p>
                </div>
            </a>

            <!-- Total Tasks (Not Started) Card -->
            <a href="{{ route('tasks.index') }}" class="no-underline-hover">
                <div class="bg-orange-500 text-white p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold">Total Tasks (Not Started)</h3>
                    <p class="text-3xl font-bold">{{ $totalTasksNotStarted }}</p>
                </div>
            </a>

            <!-- Total Tasks (Done) Card -->
            <a href="{{ route('tasks.index') }}" class="no-underline-hover">
                <div class="bg-purple-500 text-white p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold">Total Tasks (Done)</h3>
                    <p class="text-3xl font-bold">{{ $totalTasksDone }}</p>
                </div>
            </a>

            <!-- Departments Card -->
            <a href="{{ route('departments.index') }}" class="no-underline-hover">
                <div class="bg-yellow-500 text-white p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold">Department</h3>
                    <p class="text-3xl font-bold">{{ $departments }}</p>
                </div>
            </a>

            <!-- Total Staff Card -->
            <a href="{{ route('staff') }}" class="no-underline-hover">
                <div class="bg-red-500 text-white p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold">Total Staff</h3>
                    <p class="text-3xl font-bold">{{ $totalStaff }}</p>
                </div>
            </a>
        </div>
    </div>
</div>

@endif

    <div class="py-12" id="projects">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if (auth()->user()->role->id === 1 || auth()->user()->role->name === 'Project Manager' || auth()->user()->role->name === 'project manager')
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold">All Projects</h3>
                            <a href="{{ route('projects.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-md shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Add New Project</a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-800">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Client</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Description</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-900 dark:divide-gray-700">
                                    @foreach($projects as $project)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $project->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $project->client_name ?? 'UNKNOWN' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $project->description }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('projects.show', $project->id) }}" class="text-blue-600 dark:text-blue-400 hover:underline">Show</a>
                                                <a href="{{ route('projects.edit', $project->id) }}" class="ml-4 text-green-600 dark:text-green-400 hover:underline">Edit</a>
                                                <a href="{{ route('logs', $project->id) }}" class="ml-4 text-orange-600 dark:text-blue-400 hover:underline">Activity Log</a>
                                                <form action="{{ route('projects.destroy', $project->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete The Project?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="ml-4 text-red-600 dark:text-red-400 hover:underline">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <h3 class="text-lg font-semibold mb-4">Your Project</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-800">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Description</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Number Of Tasks</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-900 dark:divide-gray-700">
                                    @php
                                    $projects = App\Models\Project::whereHas('tasks', function ($query) {
                                        $query->where('user_id', auth()->user()->id);
                                    })->get();
                                    @endphp
                                    @foreach($projects as $project)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $project->name }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $project->description }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                        {{$project->tasks->count()}}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                        <a href="{{ route('projects.show', $project->id) }}" class="text-blue-600 dark:text-blue-400 hover:underline">Show</a>
                                                    </td>
                                                </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
