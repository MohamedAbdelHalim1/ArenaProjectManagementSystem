<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <b>{{ explode(' ', auth()->user()->name)[0] }}'s </b>{{ __('Dashboard') }}<br>
            <small><b style="font-size:15px;">Role:</b>{{auth()->user()->role->name}}</small>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if (auth()->user()->role->id === 1  || auth()->user()->role->name === "project manager")
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold">All Roles -- Add '<span style="color:red">project manager</span>' role to give him all permissions</h3>
                            <a href="#" class="bg-blue-500 text-white px-4 py-2 rounded-md shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" data-bs-toggle="modal" data-bs-target="#roleModal">Add New Role</a>
                        </div>

                        <!-- Modal Structure -->
                        <div class="modal fade" id="roleModal" tabindex="-1" aria-labelledby="roleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content dark:bg-gray-800 dark:text-white">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="roleModalLabel">Add New Role</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="roleForm" method="POST" action="{{ route('role.store') }}">
                                            @csrf

                                            <!-- Name -->
                                            <div>
                                                <x-input-label for="name" :value="__('Name')" />
                                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" form="roleForm" class="btn btn-primary">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-800">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Number Of Members</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-900 dark:divide-gray-700">
                                    @foreach($roles as $role)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $role->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                <a href="#" class="text-blue-500 hover:underline" data-bs-toggle="modal" data-bs-target="#rolememberModal" data-role-id="{{ $role->id }}" data-role-name="{{ $role->name }}">
                                                    {{ $role->users->count() }}
                                                </a>
                                            </td>
                                            @if (auth()->user()->role->id === 1)
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <form action="{{ route('role.destroy', $role->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this Role?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="ml-4 text-red-600 dark:text-red-400 hover:underline">Delete</button>
                                                </form>
                                            </td>
                                            @endif
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

    <div class="modal fade" id="rolememberModal" tabindex="-1" aria-labelledby="roleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content dark:bg-gray-800 dark:text-white">
                <div class="modal-header">
                    <h5 class="modal-title" id="roleModalLabel">Users in <span id="roleName"></span></h5>
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
                        <tbody id="roleUsersList">
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-4nTE8BmoAm2B3LLD2DmnQYZ3A5P7z5b3kZB4Mk4dNGy9p6zb6sG9g48NkS1/ytzR" crossorigin="anonymous"></script>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var rolememberModal = document.getElementById('rolememberModal');
            rolememberModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                var roleId = button.getAttribute('data-role-id');
                var roleName = button.getAttribute('data-role-name');

                // Set the role name in the modal title
                document.getElementById('roleName').innerText = roleName;

                // Fetch the users associated with the role
                fetch(`/roles/${roleId}/users`)
                    .then(response => response.json())
                    .then(users => {
                        var roleUsersList = document.getElementById('roleUsersList');
                        roleUsersList.innerHTML = ''; // Clear any previous content

                        users.forEach(user => {
                            var row = document.createElement('tr');
                            row.innerHTML = `<td>${user.name}</td><td>${user.email}</td>`;
                            roleUsersList.appendChild(row);
                        });
                    });
            });
        });
    </script>
</x-app-layout>
