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
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if (auth()->user()->role->id === 1 || auth()->user()->role->name === "project manager")
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold">All Departments</h3>
                            <a href="#" class="bg-blue-500 text-white px-4 py-2 rounded-md shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" data-bs-toggle="modal" data-bs-target="#departmentModal">Add New Department</a>
                        </div>

                        <!-- Add Department Modal -->
                        <div class="modal fade" id="departmentModal" tabindex="-1" aria-labelledby="departmentModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content dark:bg-gray-800 dark:text-white">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="departmentModalLabel">Add New Department</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="departmentForm" method="POST" action="{{ route('departments.store') }}">
                                            @csrf

                                            <!-- Name -->
                                            <div>
                                                <x-input-label for="name" :value="__('Name')" />
                                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                            </div>

                                            <!-- Staff Assignment -->
                                            <div class="mt-4">
                                                <x-input-label for="staffs" :value="__('Assign Staff')" />
                                                <select id="staffs" name="staffs[]" class="block mt-1 w-full" multiple>
                                                    @foreach($staffs as $staff)
                                                        <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                                                    @endforeach
                                                </select>
                                                <x-input-error :messages="$errors->get('staffs')" class="mt-2" />
                                            </div>

                                            <div class="modal-footer mt-4">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Save Department</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Edit Department Modal -->
                        <div class="modal fade" id="editDepartmentModal" tabindex="-1" aria-labelledby="editDepartmentModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content dark:bg-gray-800 dark:text-white">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editDepartmentModalLabel">Edit Department</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="editDepartmentForm" method="POST" action="">
                                            @csrf
                                            @method('PUT')

                                            <!-- Name -->
                                            <div>
                                                <x-input-label for="edit_name" :value="__('Name')" />
                                                <x-text-input id="edit_name" class="block mt-1 w-full" type="text" name="name" required autofocus />
                                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                            </div>

                                            <!-- Staff Assignment -->
                                            <div class="mt-4">
                                                <x-input-label for="edit_staffs" :value="__('Assign Staff')" />
                                                <select id="edit_staffs" name="staffs[]" class="block mt-1 w-full" multiple>
                                                    @foreach($staffs as $staff)
                                                        <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                                                    @endforeach
                                                </select>
                                                <x-input-error :messages="$errors->get('staffs')" class="mt-2" />
                                            </div>

                                            <div class="modal-footer mt-4">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Update Department</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="overflow-x-auto mt-4">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-800">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Number Of Members</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-900 dark:divide-gray-700">
                                    @foreach($departments as $department)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $department->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                <a href="#" class="text-blue-500 hover:underline" data-bs-toggle="modal" data-bs-target="#departmentmemberModal" data-department-id="{{ $department->id }}" data-department-name="{{ $department->name }}">
                                                    {{ $department->users->count() }}
                                                </a>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <button class="text-green-600 dark:text-green-400 hover:underline" data-bs-toggle="modal" data-bs-target="#editDepartmentModal" data-department-id="{{ $department->id }}" data-department-name="{{ $department->name }}" data-department-staffs="{{ $department->users->pluck('id')->join(',') }}">Edit</button>
                                                <form action="{{ route('departments.destroy', $department->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this Department?');">
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
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Users in Department Modal -->
    <div class="modal fade" id="departmentmemberModal" tabindex="-1" aria-labelledby="departmentmemberModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content dark:bg-gray-800 dark:text-white">
                <div class="modal-header">
                    <h5 class="modal-title" id="departmentmemberModalLabel">Users in <span id="departmentName"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody id="departmentUsersList">
                            <!-- User rows will be appended here -->
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Include Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
    <!-- Include Select2 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0/js/select2.min.js"></script>

    <!-- Initialize Select2 -->
    <script>
        $(document).ready(function() {
            $('#staffs, #edit_staffs').select2({
                placeholder: 'Select staff',
                width: '100%'
            });

            $('#editDepartmentModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var departmentId = button.data('department-id');
                var departmentName = button.data('department-name');
                var departmentStaffs = button.data('department-staffs').split(',');

                var modal = $(this);
                modal.find('#edit_name').val(departmentName);

                var $editStaffs = modal.find('#edit_staffs');
                $editStaffs.val(departmentStaffs).trigger('change');
                modal.find('form').attr('action', '/departments/' + departmentId);
            });

            $('#departmentmemberModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var departmentId = button.data('department-id');
                var departmentName = button.data('department-name');

                var modal = $(this);
                modal.find('#departmentName').text(departmentName);

                $.ajax({
                    url: '/departments/' + departmentId + '/users',
                    method: 'GET',
                    success: function(data) {
                        var usersList = $('#departmentUsersList');
                        usersList.empty();

                        data.users.forEach(function(user) {
                            usersList.append('<tr><td>' + user.name + '</td><td>' + user.email + '</td></tr>');
                        });
                    }
                });
            });
        });
    </script>
</x-app-layout>
