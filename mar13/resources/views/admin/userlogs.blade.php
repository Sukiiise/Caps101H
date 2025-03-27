<x-adminlayout :icon="$icon" :button="$button">
    <div class="container-fluid">
        <!-- Page Header -->

        <!-- Search and Filter Section -->
        <div class="card mb-4">
            <div class="card-body">
                <form class="row g-3">
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="search" placeholder="Search logs...">
                    </div>
                    <div class="col-md-3">
                        <input type="date" class="form-control" name="date_filter">
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" name="activity_type">
                            <option value="">All Activities</option>
                            <option value="login">Login</option>
                            <option value="logout">Logout</option>
                            <option value="create_student">Create Student</option>
                            <option value="update_student">Update Student</option>
                            <option value="archive_student">Archive Student</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-custom w-100" style="color: white;">
                            <i class="bi bi-search"></i> Search
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Logs Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Activity</th>
                                <th>Description</th>
                                <th>Date & Time</th>
                                <th>Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs ?? [] as $log)
                            <tr>
                                <td>{{ $log->id }}</td>
                                <td>{{ $log->user->username ?? 'System' }}</td>
                                <td>
                                    <span class="badge bg-{{ 
                                        $log->activity_type === 'login' ? 'success' : 
                                        ($log->activity_type === 'logout' ? 'warning' : 
                                        ($log->activity_type === 'create_student' ? 'primary' : 
                                        ($log->activity_type === 'update_student' ? 'info' : 
                                        ($log->activity_type === 'archive_student' ? 'secondary' : 'secondary')))) 
                                    }}">
                                        {{ ucfirst(str_replace('_', ' ', $log->activity_type)) }}
                                    </span>
                                </td>
                                <td>{{ $log->description }}</td>
                                <td>
                                    <div>{{ $log->created_at->format('M d, Y') }}</div>
                                    <small class="text-muted">{{ $log->created_at->format('h:i:s A') }}</small>
                                </td>
                                <td>
                                    <button type="button" 
                                            class="btn btn-sm btn-custom" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#logDetails{{ $log->id }}">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <i class="bi bi-journal-x display-4 d-block"></i>
                                    No activity logs found
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if(isset($logs) && $logs->hasPages())
                <div class="d-flex justify-content-end mt-3">
                    {{ $logs->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Log Details Modal -->
    @foreach($logs ?? [] as $log)
    <div class="modal fade" id="logDetails{{ $log->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Activity Log Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <h6 class="fw-bold">User Information</h6>
                        <p class="mb-1">Username: {{ $log->user->username ?? 'System' }}</p>
                        <p class="mb-1">Email: {{ $log->user->email ?? 'N/A' }}</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="fw-bold">Activity Details</h6>
                        <p class="mb-1">Type: {{ ucfirst(str_replace('_', ' ', $log->activity_type)) }}</p>
                        <p class="mb-1">Description: {{ $log->description }}</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="fw-bold">Timestamp</h6>
                        <p class="mb-1">Date: {{ $log->created_at->format('F d, Y') }}</p>
                        <p class="mb-1">Time: {{ $log->created_at->format('h:i:s A') }}</p>
                    </div>
                    @if($log->additional_details)
                    <div class="mb-3">
                        <h6 class="fw-bold">Student Information</h6>
                        <pre class="bg-light p-2 rounded">{{ json_encode($log->additional_details, JSON_PRETTY_PRINT) }}</pre>
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</x-adminlayout>
