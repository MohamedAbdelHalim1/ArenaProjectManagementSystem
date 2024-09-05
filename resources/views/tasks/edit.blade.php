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
                <button id="addItemBtn" type="button" class="bg-green-500 text-white px-4 py-2 rounded-md shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">Add to My List</button>
                <div id="listContainer" class="mt-4">
                    <!-- Existing list items will go here -->
                </div>
                <div class="mt-4">
                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Progress</h4>
                    <div id="progressBarContainer" class="relative pt-1">
                        <div id="progressBar" class="bg-blue-500 text-xs font-semibold text-blue-100 text-center p-0.5 leading-none rounded-full" style="width: 0%;"></div>
                    </div>
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
        const taskId = {{ $task->id }};
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
        const taskId = {{ $task->id }};  // Make sure the task ID is available in the JavaScript
        const addItemBtn = document.getElementById('addItemBtn');
        const listContainer = document.getElementById('listContainer');
        const progressBar = document.getElementById('progressBar');
        let totalItems = 0;
        let completedItems = 0;

        // Function to add a list item (checkbox) dynamically to the DOM
        function addListItem(text, isChecked = false, id = null) {
            const listItem = document.createElement('div');
            listItem.className = 'flex items-center mb-2';
            
            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.className = 'mr-2';
            checkbox.checked = isChecked;
            checkbox.dataset.id = id;  // Store the list item ID for future reference
            checkbox.addEventListener('change', function () {
                updateCheckbox(id, checkbox.checked);
            });
            
            const label = document.createElement('label');
            label.textContent = text;
            label.className = isChecked ? 'line-through' : '';

            listItem.appendChild(checkbox);
            listItem.appendChild(label);
            listContainer.appendChild(listItem);

            totalItems++;
            if (isChecked) {
                completedItems++;
            }
            updateProgressBar();
        }

        // Function to update the checkbox status in the database
        function updateCheckbox(itemId, isChecked) {
            fetch(`/list-items/${itemId}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({
                    is_checked: isChecked,
                }),
            })
            .then(response => response.json())
            .then(data => {
                if (isChecked) {
                    completedItems++;
                } else {
                    completedItems--;
                }
                updateProgressBar();
            });
        }

        // Function to update the progress bar based on the number of completed items
        function updateProgressBar() {
            const progressPercentage = totalItems === 0 ? 0 : (completedItems / totalItems) * 100;
            progressBar.style.width = `${progressPercentage}%`;
            progressBar.textContent = `${Math.round(progressPercentage)}%`;
        }

        // Fetch items from the database for this task on page load
        fetch(`/tasks/${taskId}/list-items`)
            .then(response => response.json())
            .then(data => {
                data.forEach(item => {
                    addListItem(item.text, item.is_checked, item.id);
                });
                updateProgressBar();
            });

        // Add new list item when the "Add" button is clicked
        addItemBtn.addEventListener('click', function () {
            const itemText = prompt('Enter the item:');
            if (itemText) {
                // Save the new item to the database via AJAX
                fetch(`/tasks/${taskId}/list-items`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify({ text: itemText }),
                })
                .then(response => response.json())
                .then(data => {
                    addListItem(data.text, false, data.id);
                    updateProgressBar();
                });
            }
        });
    });
</script>

</x-app-layout>
