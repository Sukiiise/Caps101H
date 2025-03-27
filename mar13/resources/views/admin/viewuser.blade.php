<x-adminlayout :icon="$icon" :button="$button">
    <div class="container-fluid">
        <!-- Search and Filter Section -->
        <div class="card mb-4">
            <div class="card-body">
                <form class="row g-3" method="GET" action="{{ route('admin.users') }}" id="searchForm">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" 
                                   class="form-control" 
                                   name="search" 
                                   placeholder="Search by name, email or contact..."
                                   value="{{ $search_term ?? '' }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" name="usertype" id="userTypeFilter">
                            <option value="">All User Types</option>
                            <option value="admin" {{ (isset($selected_usertype) && $selected_usertype == 'admin') ? 'selected' : '' }}>Admin</option>
                            <option value="staff" {{ (isset($selected_usertype) && $selected_usertype == 'staff') ? 'selected' : '' }}>Staff</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search"></i> Search
                            </button>
                            @if($search_term || $selected_usertype)
                                <a href="{{ route('admin.users') }}" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i> Clear
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Active Filters -->
        @if($search_term || $selected_usertype)
            <div class="alert alert-info mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <strong>Active Filters:</strong>
                        @if($search_term)
                            <span class="badge bg-primary ms-2">Search: {{ $search_term }}</span>
                        @endif
                        @if($selected_usertype)
                            <span class="badge bg-primary ms-2">Type: {{ ucfirst($selected_usertype) }}</span>
                        @endif
                    </div>
                    <a href="{{ route('admin.users') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-x-circle"></i> Clear All Filters
                    </a>
                </div>
            </div>
        @endif

        <!-- Results Summary -->
        <div class="mb-3">
            <small class="text-muted">
                Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} users
            </small>
        </div>

        <!-- Users Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>
                                    <a href="{{ route('admin.users', array_merge(request()->query(), [
                                        'sort' => 'id',
                                        'direction' => ($sort_field === 'id' && $sort_direction === 'asc') ? 'desc' : 'asc'
                                    ])) }}" class="text-decoration-none text-dark">
                                        ID
                                        @if($sort_field === 'id')
                                            <i class="bi bi-arrow-{{ $sort_direction === 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Contact Number</th>
                                <th>Address</th>
                                <th>User Type</th>
                                <th>
                                    <a href="{{ route('admin.users', array_merge(request()->query(), [
                                        'sort' => 'created_at',
                                        'direction' => ($sort_field === 'created_at' && $sort_direction === 'asc') ? 'desc' : 'asc'
                                    ])) }}" class="text-decoration-none text-dark">
                                        Created At
                                        @if($sort_field === 'created_at')
                                            <i class="bi bi-arrow-{{ $sort_direction === 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->lastname }}, {{ $user->firstname }} {{ $user->middlename }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->contact_number }}</td>
                                    <td>
                                        {{ $user->street_number }}, {{ $user->barangay }}, 
                                        {{ $user->city }}, {{ $user->province }}
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $user->usertype === 'admin' ? 'danger' : 'primary' }}">
                                            {{ ucfirst($user->usertype) }}
                                        </span>
                                    </td>
                                    <td>{{ $user->created_at->format('M d, Y h:ia') }}</td>
                                    <td>
                                        <!-- Your existing action buttons -->
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bi bi-search display-6"></i>
                                            <p class="mt-2">No users found</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mb-3">
                        <small class="text-muted">
                            Sorted by: {{ ucfirst(str_replace('_', ' ', $sort_field)) }} 
                            ({{ $sort_direction === 'asc' ? 'Ascending' : 'Descending' }})
                        </small>
                    </div>
                    <!-- Pagination -->
                    <div class="d-flex justify-content-end mt-3">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Keep your existing modals here -->

    @push('scripts')
    <script>
        // Instant search and filter functionality
        const searchForm = document.getElementById('searchForm');
        const userTypeFilter = document.getElementById('userTypeFilter');
        let searchTimeout;

        // Handle search input with debounce
        document.querySelector('input[name="search"]').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                searchForm.submit();
            }, 500); // Wait 500ms after user stops typing
        });

        // Handle usertype filter change
        userTypeFilter.addEventListener('change', function() {
            searchForm.submit();
        });

        // SweetAlert2 delete confirmation
        document.querySelectorAll('.delete-user-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This action cannot be undone!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });
        });

        
    </script>
    @endpush
</x-adminlayout>
