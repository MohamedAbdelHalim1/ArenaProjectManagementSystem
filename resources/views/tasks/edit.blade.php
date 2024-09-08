<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Task') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('tasks.update', $task) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Task Title</label>
                            <input type="text" id="title" name="title" value="{{ old('title', $task->title) }}" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-800 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                            @error('title')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="drive_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Drive URL</label>
                            <input type="url" id="drive_url" name="drive_url" value="{{ old('drive_url') }}" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-800 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <div class="mb-4">
                            <label for="deadline" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deadline</label>
                            <input type="date" id="deadline" name="deadline" value="{{$task->deadline}}" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-800 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Add Note</label>
                            <textarea id="description" name="description" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-800 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('description', $task->description) }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="user_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Assign Task to User</label>
                            <select id="user_id" name="user_id" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-800 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id', $task->user_id) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex items-center justify-between">
                            <a href="{{ route('projects.show', $task->project_id) }}" class="text-blue-600 dark:text-blue-400 hover:underline">Back to Project</a>
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Update Task</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- List and Progress Bar Section -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Task List</h3>
                <!-- Button to add a new parent task -->
                <button id="addParentListBtn" type="button" class="bg-green-500 text-white px-4 py-2 rounded-md shadow-sm hover:bg-green-600">Add New Task List</button>
                <div id="parentListContainer" class="flex mt-4">
                    <!-- Parent lists will go here -->
                </div>
                <div class="mt-4">
                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Overall Progress</h4>
                    <div id="overallProgressBarContainer" class="relative pt-1">
                        <div id="overallProgressBar" class="bg-blue-500 text-xs font-semibold text-blue-100 text-center p-0.5 leading-none rounded-full" style="width: 0%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Task Modal -->
<div class="modal fade" id="taskModal" tabindex="-1" aria-labelledby="taskModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="taskModalLabel">Assign Task List</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="departmentSelect" class="form-label">Select Department</label>
                    <select id="departmentSelect" class="form-select">
                        <option value="">Select Department</option>
                        <!-- Options will be populated via AJAX -->
                    </select>
                </div>
                <div id="userSelectContainer" class="mb-3" style="display: none;">
                    <label for="userSelect" class="form-label">Select User</label>
                    <select id="userSelect" class="form-select">
                        <option value="">Select User</option>
                        <!-- Options will be populated via AJAX -->
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button id="assignBtn" type="button" class="btn btn-primary">Assign</button>
            </div>
        </div>
    </div>
</div>





    <!-- Comment Section -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Comments</h3>
                <textarea id="commentBody" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-800 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Write a comment..."></textarea>
                <button id="addCommentBtn" class="bg-blue-500 text-white px-4 py-2 rounded-md shadow-sm hover:bg-blue-600 mt-2 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Add Comment</button>

                <div id="commentsContainer" class="mt-4">
                    <!-- Comments will be dynamically inserted here -->
                </div>
            </div>
        </div>
    </div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const taskId = {{ $task->id }}; // Ensure this outputs a valid number or string
    const addCommentBtn = document.getElementById('addCommentBtn');
    const commentBody = document.getElementById('commentBody');
    const commentsContainer = document.getElementById('commentsContainer');

    // Fetch existing comments on page load
    fetch(`/tasks/${taskId}/comments`)
        .then(response => response.json())
        .then(data => {
            data.forEach(comment => {
                addCommentToDOM(comment.body, comment.user.name, comment.created_at);
            });
        })
        .catch(error => {
            console.error('Error fetching comments:', error);
        });

    // Add new comment
    addCommentBtn.addEventListener('click', function () {
        const body = commentBody.value;
        if (body.trim() === '') {
            alert('Please write a comment.');
            return;
        }

        fetch(`/tasks/${taskId}/comments`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({ body }),
        })
        .then(response => response.json())
        .then(data => {
            addCommentToDOM(data.body, data.user, data.date);
            commentBody.value = ''; // Clear the textarea
        })
        .catch(error => {
            console.error('Error adding comment:', error);
        });
    });

    // Function to add comment to the DOM
    function addCommentToDOM(body, user, date) {
        const commentDiv = document.createElement('div');
        commentDiv.className = 'mb-4 p-2 bg-gray-200 dark:bg-gray-700 rounded-md';

        // Validate and format the date
        let formattedDate = '';
        if (date && !isNaN(Date.parse(date))) {
            const dateObj = new Date(date);
            formattedDate = new Intl.DateTimeFormat('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: 'numeric',
                minute: 'numeric',
                hour12: true
            }).format(dateObj);
        } else {
            formattedDate = 'Invalid date';
            console.warn('Invalid date value:', date); // Log invalid date values for debugging
        }

        const commentHeader = document.createElement('div');
        commentHeader.className = 'text-sm font-bold text-gray-900 dark:text-gray-100';
        commentHeader.textContent = `${user} • ${formattedDate}`;

        const commentBody = document.createElement('p');
        commentBody.className = 'text-gray-700 dark:text-gray-300 mt-1';
        commentBody.textContent = body;

        commentDiv.appendChild(commentHeader);
        commentDiv.appendChild(commentBody);
        commentsContainer.appendChild(commentDiv);
    }
});

</script>


<script>
   document.addEventListener('DOMContentLoaded', function () {
    const taskId = {{ $task->id }};
    const addParentListBtn = document.getElementById('addParentListBtn');
    const parentListContainer = document.getElementById('parentListContainer');
    const overallProgressBar = document.getElementById('overallProgressBar');
    let totalParentItems = 0;
    let completedParentItems = 0;

    function addParentList(text, id, assignedUser = null) {
        console.log(assignedUser);
        
        const parentDiv = document.createElement('div');
        parentDiv.className = 'bg-gray-100 dark:bg-gray-700 p-4 m-2 rounded-md shadow-sm w-1/4';
        parentDiv.dataset.id = id;

        const titleContainer = document.createElement('div');
        titleContainer.className = 'd-flex items-center';
        
        const title = document.createElement('h4');
        title.className = 'text-lg font-medium text-gray-900 dark:text-gray-100 mb-2';
        title.textContent = text;
        
        const assignLink = document.createElement('a');
        assignLink.className = 'text-blue-500 hover:underline cursor-pointer text-sm';
        assignLink.href = '#';
        assignLink.textContent = 'Assign to';
        assignLink.addEventListener('click', function () {
            const modal = new bootstrap.Modal(document.getElementById('taskModal'));
            modal.show();
            document.getElementById('departmentSelect').value = '';
            document.getElementById('userSelect').innerHTML = '<option value="">Select User</option>';
            document.getElementById('userSelectContainer').style.display = 'none';
            // Set task id in modal if needed
            document.getElementById('taskModal').dataset.taskId = id;
        });

        titleContainer.appendChild(title);
        titleContainer.appendChild(assignLink);
        parentDiv.appendChild(titleContainer);

        const assignedUserContainer = document.createElement('div');
        assignedUserContainer.className = 'mt-4';
        if (assignedUser) {
            const assignedUserText = document.createElement('p');
            assignedUserText.className = 'text-gray-600 dark:text-gray-300';
            assignedUserText.textContent = `Assigned to: ${assignedUser}`;
            assignedUserContainer.appendChild(assignedUserText);
        }
        parentDiv.appendChild(assignedUserContainer);

        const progressBarContainer = document.createElement('div');
        progressBarContainer.className = 'relative pt-1';
        const progressBar = document.createElement('div');
        progressBar.className = 'bg-blue-500 text-xs font-semibold text-blue-100 text-center p-0.5 leading-none rounded-full';
        progressBar.style.width = '0%';
        progressBarContainer.appendChild(progressBar);

        const subtaskContainer = document.createElement('div');
        subtaskContainer.className = 'mt-2';

        const addSubtaskBtn = document.createElement('button');
        addSubtaskBtn.className = 'bg-green-500 text-white px-2 py-1 mt-2 rounded-md shadow-sm';
        addSubtaskBtn.textContent = 'Add Subtask';
        addSubtaskBtn.addEventListener('click', function () {
            const subtaskText = prompt('Enter the subtask:');
            if (subtaskText) {
                fetch(`/tasks/${taskId}/list-items`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify({ text: subtaskText, parent_id: id }),
                })
                .then(response => response.json())
                .then(data => {
                    addSubtask(subtaskText, data.id, false, id);
                });
            }
        });

        parentDiv.appendChild(subtaskContainer);
        parentDiv.appendChild(addSubtaskBtn);
        parentDiv.appendChild(progressBarContainer);

        parentListContainer.appendChild(parentDiv);
        totalParentItems++;
        updateOverallProgressBar();
    }

    function addSubtask(text, subtaskId, isChecked = false, parentId) {
        const subtaskDiv = document.createElement('div');
        subtaskDiv.className = 'flex items-center mt-2';

        const checkbox = document.createElement('input');
        checkbox.type = 'checkbox';
        checkbox.className = 'mr-2';
        checkbox.checked = isChecked;
        checkbox.addEventListener('change', function () {
            updateCheckbox(subtaskId, checkbox.checked);
        });

        const label = document.createElement('label');
        label.textContent = text;
        label.className = isChecked ? 'line-through' : '';

        subtaskDiv.appendChild(checkbox);
        subtaskDiv.appendChild(label);

        const parentDiv = document.querySelector(`div[data-id='${parentId}'] .mt-2`);
        if (parentDiv) {
            parentDiv.appendChild(subtaskDiv);
            updateParentProgressBar(parentDiv.closest('div[data-id]').querySelector('.bg-blue-500'));
        } else {
            console.error(`Parent div for subtask with parent_id ${parentId} not found`);
        }
    }

    function updateCheckbox(subtaskId, isChecked) {
        fetch(`/list-items/${subtaskId}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({ is_checked: isChecked }),
        })
        .then(response => response.json())
        .then(data => {
            const parentDiv = document.querySelector(`div[data-id='${data.parent_id}'] .mt-2`);
            if (parentDiv) {
                updateParentProgressBar(parentDiv.closest('div[data-id]').querySelector('.bg-blue-500'));
                updateOverallProgressBar();
            } else {
                console.error(`Parent div for subtask with parent_id ${data.parent_id} not found`);
            }
        })
        .catch(error => {
            console.error('Error updating checkbox:', error);
        });
    }

    function updateOverallProgressBar() {
        // Recalculate completedParentItems
        completedParentItems = 0;
        const parentItems = parentListContainer.querySelectorAll('div[data-id]');
        parentItems.forEach(parentDiv => {
            const progressBar = parentDiv.querySelector('.bg-blue-500');
            if (progressBar) {
                const percentage = parseFloat(progressBar.style.width);
                if (percentage === 100) {
                    completedParentItems++;
                }
            }
        });

        const progressPercentage = totalParentItems === 0 ? 0 : (completedParentItems / totalParentItems) * 100;
        overallProgressBar.style.width = `${progressPercentage}%`;
        overallProgressBar.textContent = `${Math.round(progressPercentage)}%`;
    }

    function updateParentProgressBar(progressBar) {
        if (!progressBar) {
            console.error('Progress bar not found');
            return;
        }

        const parentDiv = progressBar.closest('div[data-id]');
        if (!parentDiv) {
            console.error('Parent div with data-id not found');
            return;
        }

        const checkboxes = parentDiv.querySelectorAll('input[type="checkbox"]');
        const checkedBoxes = parentDiv.querySelectorAll('input[type="checkbox"]:checked');
        const progressPercentage = checkboxes.length === 0 ? 0 : (checkedBoxes.length / checkboxes.length) * 100;

        progressBar.style.width = `${progressPercentage}%`;
        progressBar.textContent = `${Math.round(progressPercentage)}%`;
    }

    addParentListBtn.addEventListener('click', function () {
        const parentTaskText = prompt('Enter the task list name:');
        if (parentTaskText) {
            fetch(`/tasks/${taskId}/list-items`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({ text: parentTaskText, parent_id: null }),
            })
            .then(response => response.json())
            .then(data => {
                addParentList(parentTaskText, data.id);
            });
        }
    });

    fetch(`/tasks/${taskId}/list-items`)
    .then(response => response.json())
    .then(data => {
        console.log(data);
        data.forEach(item => {
            if (item.parent_id === null) {
                addParentList(item.text, item.id, item.staff ? item.staff.name : null); // Pass the staff's name if available
            }else {
                const parentDiv = document.querySelector(`div[data-id='${item.parent_id}'] .mt-2`);
                if (parentDiv) {
                    addSubtask(item.text, item.id, item.is_checked, item.parent_id);
                } else {
                    console.error(`Parent div for subtask with parent_id ${item.parent_id} not found`);
                }
            }
        });
        // Ensure overall progress is updated after loading existing items
        updateOverallProgressBar();
    });
});

document.addEventListener('DOMContentLoaded', function () {
    fetch('/api/departments')
        .then(response => response.json())
        .then(data => {
            const departmentSelect = document.getElementById('departmentSelect');
            departmentSelect.innerHTML = '<option value="">Select Department</option>'; // Clear existing options
            data.forEach(department => {
                const option = document.createElement('option');
                option.value = department.id;
                option.textContent = department.name;
                departmentSelect.appendChild(option);
            });

            // Add event listener for department selection
            departmentSelect.addEventListener('change', function () {
                const departmentId = this.value;
                const userSelect = document.getElementById('userSelect');
                const userSelectContainer = document.getElementById('userSelectContainer');

                if (departmentId) {
                    // Show user select container
                    userSelectContainer.style.display = 'block';
                    
                    // Fetch users based on selected department
                    fetch(`/api/users?department_id=${departmentId}`)
                        .then(response => response.json())
                        .then(users => {
                            userSelect.innerHTML = '<option value="">Select User</option>'; // Clear existing options
                            users.forEach(user => {
                                const option = document.createElement('option');
                                option.value = user.id;
                                option.textContent = user.name; // Adjust based on your actual user data structure
                                userSelect.appendChild(option);
                            });
                        })
                        .catch(error => console.error('Error fetching users:', error));
                } else {
                    // Hide user select container if no department is selected
                    userSelectContainer.style.display = 'none';
                    userSelect.innerHTML = '<option value="">Select User</option>'; // Clear existing options
                }
            });
        })
        .catch(error => console.error('Error fetching departments:', error));
});

document.getElementById('assignBtn').addEventListener('click', function () {
    const taskModal = document.getElementById('taskModal');
    const taskId = taskModal.dataset.taskId;
    const userId = document.getElementById('userSelect').value;

    if (userId) {
        fetch('/assign-tasklist', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({
                tasklist_id: taskId,
                user_id: userId
            }),
        })
        .then(response => response.json())
        .then(data => {
            console.log('Assign User Response:', data); // Debugging response
            window.location.reload();

            if (data.assignedUser) {
                console.log("HERE");
                
                // Update parent task with the assigned user
                const parentTaskText = document.querySelector(`div[data-id='${taskId}'] h4`).textContent;
                addParentList(parentTaskText, taskId, data.assignedUser); // Pass assignedUser to addParentList
            }

            alert('User assigned successfully');
            const modal = bootstrap.Modal.getInstance(taskModal);
            modal.hide();
            // Refresh or update the task list as needed
            // For example, you might want to fetch updated list items
        })
        .catch(error => {
            console.error('Error assigning user:', error);
        });
    } else {
        alert('Please select a user');
    }
});


</script>

</x-app-layout>
