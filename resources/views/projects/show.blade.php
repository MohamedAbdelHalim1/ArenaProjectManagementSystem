<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Project Details') }}
        </h2>
    </x-slot>


    <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Total Projects Card -->
            <a href="#projects" class="no-underline-hover">
                <div class="bg-blue-500 text-white p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold">Total Tasks</h3>
                    <p class="text-3xl font-bold">{{$totalTasks}}</p>
                </div>
            </a>

            <!-- Total Tasks (Started) Card -->
            <a href="#" class="no-underline-hover">
                <div class="bg-green-500 text-white p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold">Total Tasks (Ask To Start)</h3>
                    <p class="text-3xl font-bold">{{$totalAskToStart}}</p>
                </div>
            </a>

                      <!-- Total Tasks (Started) Card -->
                      <a href="#" class="no-underline-hover">
                <div class="bg-green-500 text-white p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold">Total Tasks (Started)</h3>
                    <p class="text-3xl font-bold">{{$totalStart}}</p>
                </div>
            </a>

            <!-- Total Tasks (Not Started) Card -->
            <a href="#" class="no-underline-hover">
                <div class="bg-orange-500 text-white p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold">Total Tasks (Not Started)</h3>
                    <p class="text-3xl font-bold">{{$totalNotStarted}}</p>
                </div>
            </a>

            <!-- Total Tasks (Done) Card -->
            <a href="#" class="no-underline-hover">
                <div class="bg-purple-500 text-white p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold">Total Tasks (Done)</h3>
                    <p class="text-3xl font-bold">{{$totalDone}}</p>
                </div>
            </a>

                        <!-- Total Tasks (Done) Card -->
            <a href="#" class="no-underline-hover">
                <div class="bg-purple-500 text-white p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold">Total Tasks (Refusal Reason)</h3>
                    <p class="text-3xl font-bold">{{$totalRefused}}</p>
                </div>
            </a>


        </div>
    </div>
</div>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold">{{ $project->name }}</h3>
                        <h3 class="text-lg font-semibold">Client: {{ $project->client_name ?? 'UNKOWN' }}</h3>
                        <p class="text-gray-600">Drive URL: <a href="{{ $project->drive_url }}" target="_blank">{{ $project->drive_url }}</a></p>
                        <p class="text-gray-600">{{ $project->description }}</p>
                        <p class="text-gray-600">Assigned User: {{ $project->user->name }}</p>
                        <p class="text-gray-600">Created Date: {{ \Carbon\Carbon::parse($project->created_at)->format('Y-m-d') }}</p>
                        <p class="text-red-600">Deadline: {{ $project->deadline }}</p>
                        <!-- <p class="text-gray-600">Attached Files: <a href="#" data-bs-toggle="modal" data-bs-target="#projectFileUploadModal">Upload Project Files</a></p>
                        <p class="text-gray-600">Attached Photos: <a href="#" data-bs-toggle="modal" data-bs-target="#projectPhotoUploadModal">Upload Project Photos</a></p> -->
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

                                    <div class="mb-4">
                                        <label for="drive_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Drive URL</label>
                                        <input type="url" id="drive_url" name="drive_url" value="{{ old('drive_url') }}" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-800 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
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

                                    <!-- Choose Department -->
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Choose Department</label>
                                        <select id="departmentSelect" name="department_id" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-800 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                            <option value="">Select Department</option>
                                            @foreach($departments as $department)
                                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Assign Task to User -->
                                    <div class="mb-4" id="userSelectContainer">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Assign Task to User</label>
                                        <select id="userSelect" name="user_id" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-800 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                            <option value="">Select User</option>
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
                                <!-- <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Attached Files</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Attached Photos</th> -->
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deadline</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($project->tasks as $task)
                                @if (auth()->user()->role->id === 1 || $task->user_id === auth()->id())
                                    <tr>
                                        
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $task->title }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">{{ $task->description }}</td>
                                        <!-- <td class="px-6 py-4 text-sm text-gray-500"><a href="#" data-bs-toggle="modal" data-bs-target="#taskFileUploadModal">Upload Task Files</a></td>
                                        <td class="px-6 py-4 text-sm text-gray-500"><a href="#" data-bs-toggle="modal" data-bs-target="#taskPhotoUploadModal">Upload Task Photos</a></td> -->
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
                                        <small><td class="px-6 py-4 text-sm text-red-500">{{ $task->deadline ?? 'Unassigned' }}</td></small>

                                        <td class="px-6 py-4 text-sm text-gray-500">{{ $task->user->name ?? 'Unassigned' }}</td>
                                    
                                        <td class="px-6 py-4 text-sm font-medium">
                                            <a href="#" class="text-blue-600 hover:text-blue-900" 
                                            data-bs-toggle="modal" data-bs-target="#showTaskModal" 
                                            data-title="{{ $task->title }}" 
                                            data-drive-url="{{ $task->drive_url ?? '#' }}">
                                                Show
                                            </a>
                                            <a href="{{ route('tasks.edit', $task->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                            @if (auth()->user()->role->id === 1 || auth()->user()->role->name === 'project manager' || auth()->user()->role->name === 'project managers')

                                            <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline-block ml-2"  onsubmit="return confirm('Are you sure you want to delete The Task?');">
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

<!-- Show Task Modal -->
<div class="modal fade" id="showTaskModal" tabindex="-1" aria-labelledby="showTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content dark:bg-gray-800 dark:text-white">
            <div class="modal-header">
                <h5 class="modal-title" id="showTaskModalLabel">Task Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Title:</strong> <span id="showTaskTitle"></span></p>
                <p><strong>Drive URL:</strong> <a href="#" id="showTaskDriveUrl" target="_blank"></a></p>
            </div>
        </div>
    </div>
</div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var departmentSelect = document.getElementById('departmentSelect');
        var userSelect = document.getElementById('userSelect');
        var userSelectContainer = document.getElementById('userSelectContainer');

        departmentSelect.addEventListener('change', function() {
            var departmentId = this.value;

            if (departmentId) {
                $.ajax({
                    url: `/departments/${departmentId}/users`,
                    type: 'GET',
                    success: function(data) {
                        userSelect.innerHTML = '<option value="">Select User</option>'; // Clear existing options
                        if (data.users && Array.isArray(data.users)) {
                            data.users.forEach(function(user) {
                                var option = document.createElement('option');
                                option.value = user.id;
                                option.textContent = user.name;
                                userSelect.appendChild(option);
                            });
                            userSelectContainer.style.display = 'block'; // Show the user select field
                        } else {
                            alert('No users found for this department');
                        }
                    },
                    error: function(xhr) {
                        alert('Error loading users');
                    }
                });

            } else {
                userSelect.innerHTML = '<option value="">Select User</option>'; // Reset the user select field
                userSelectContainer.style.display = 'none'; // Hide the user select field
            }
        });
    });
</script>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var showTaskModal = document.getElementById('showTaskModal');

            showTaskModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget; // Button that triggered the modal
                var title = button.getAttribute('data-title'); // Extract info from data-* attributes
                var driveUrl = button.getAttribute('data-drive-url');

                var modalTitle = showTaskModal.querySelector('#showTaskTitle');
                var modalDriveUrl = showTaskModal.querySelector('#showTaskDriveUrl');

                modalTitle.textContent = title;
                modalDriveUrl.href = driveUrl;
                modalDriveUrl.textContent = driveUrl;
            });
        });
    </script>


    <script>
        document.getElementById('taskForm').addEventListener('submit', function(e) {
            e.preventDefault(); 

            this.submit();
        });

        $('#projectFileUploadForm').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                alert('Files uploaded successfully');
                location.reload(); // Reload the page to reflect new files
            },
            error: function(response) {
                alert('Error uploading files');
            }
        });
    });

    $('#taskFileUploadForm').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                alert('Files uploaded successfully');
                location.reload(); // Reload the page to reflect new files
            },
            error: function(response) {
                alert('Error uploading files');
            }
        });
    });


    </script>
</x-app-layout>
