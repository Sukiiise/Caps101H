<x-adminlayout :icon="$icon" :button="$button">
    <div class="container-fluid">
        <!-- Page Header -->

        <!-- Date Range Filter -->
        <div class="card mb-4">
            <div class="card-body">
                <form class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Date Range</label>
                        <select class="form-select" id="dateRange">
                            <option value="today">Today</option>
                            <option value="week">This Week</option>
                            <option value="month" selected>This Month</option>
                            <option value="year">This Year</option>
                            <option value="custom">Custom Range</option>
                        </select>
                    </div>
                    <div class="col-md-6" id="customDateInputs" style="display: none;">
                        <label class="form-label">Custom Date Range</label>
                        <div class="input-group">
                            <input type="date" class="form-control" name="start_date">
                            <span class="input-group-text">to</span>
                            <input type="date" class="form-control" name="end_date">
                        </div>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-custom w-100" style="color: white;">
                            <i class="bi bi-filter"></i> Apply Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Statistics Cards -->
<div class="row g-4 mb-4">
    <!-- Total Students Card -->
    <div class="col-md-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-muted mb-2">Total Students</h6>
                        <h3 class="mb-0">{{ $totalStudents ?? '0' }}</h3>
                        <small class="text-success">
                            <i class="bi bi-arrow-up"></i> 12% increase
                        </small>
                    </div>
                    <div class="p-2 rounded" style="background-color: var(--primary-light)">
                        <i class="bi bi-people" style="color: var(--primary-color)"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Students Card -->
    <div class="col-md-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-muted mb-2">Active Students</h6>
                        <h3 class="mb-0">{{ $activeStudents ?? '0' }}</h3>
                        <small style="color: var(--primary-color)">
                            <i class="bi bi-arrow-up"></i> 5% increase
                        </small>
                    </div>
                    <div class="p-2 rounded" style="background-color: var(--primary-light)">
                        <i class="bi bi-person-check" style="color: var(--primary-color)"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Archived Students Card -->
    <div class="col-md-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-muted mb-2">Archived Students</h6>
                        <h3 class="mb-0">{{ $archivedStudents ?? '0' }}</h3>
                        <small class="text-muted">
                            <i class="bi bi-dash"></i> No change
                        </small>
                    </div>
                    <div class="p-2 rounded" style="background-color: var(--primary-light)">
                        <i class="bi bi-archive" style="color: var(--primary-color)"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Users Card -->
    <div class="col-md-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-muted mb-2">System Users</h6>
                        <h3 class="mb-0">{{ $totalUsers ?? '0' }}</h3>
                        <small style="color: var(--primary-color)">
                            <i class="bi bi-arrow-up"></i> 2 new users
                        </small>
                    </div>
                    <div class="p-2 rounded" style="background-color: var(--primary-light)">
                        <i class="bi bi-person-gear" style="color: var(--primary-color)"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
        <!-- Activity Summary -->
        <div class="row g-4 mb-4">
            <!-- Recent Activities -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-transparent border-0">
                        <h5 class="mb-0">Recent Activities</h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            @forelse($recentActivities ?? [] as $activity)
                            <div class="timeline-item">
                                <div class="timeline-icon bg-{{ 
                                    $activity->type === 'create_student' ? 'primary' : 
                                    ($activity->type === 'update_student' ? 'info' : 
                                    ($activity->type === 'archive_student' ? 'warning' : 'secondary')) 
                                }}">
                                    <i class="bi bi-{{ 
                                        $activity->type === 'create_student' ? 'person-plus' : 
                                        ($activity->type === 'update_student' ? 'pencil' : 
                                        ($activity->type === 'archive_student' ? 'archive' : 'circle')) 
                                    }}"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">{{ $activity->description }}</h6>
                                    <small class="text-muted">
                                        <i class="bi bi-clock me-1"></i>
                                        {{ $activity->created_at->diffForHumans() }}
                                    </small>
                                </div>
                            </div>
                            @empty
                            <p class="text-center text-muted my-4">No recent activities</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activity Statistics -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-transparent border-0">
                        <h5 class="mb-0">Activity Statistics</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <h6 class="text-muted mb-3">Activity Distribution</h6>
                            <div class="progress" style="height: 25px;">
                                <div class="progress-bar" 
                                    style="width: 45%; background-color: rgba(40, 167, 69, 0.85);" 
                                    title="Created: 45%">45%</div>
                                <div class="progress-bar" 
                                    style="width: 35%; background-color: rgba(255, 193, 7, 0.85);" 
                                    title="Updated: 35%">35%</div>
                                <div class="progress-bar" 
                                    style="width: 20%; background-color: rgba(220, 53, 69, 0.85);" 
                                    title="Archived: 20%">20%</div>
                            </div>
                        </div>
                        <div class="activity-stats">
                            <div class="row g-3">
                                <div class="col-4">
                                    <div class="text-center p-3 border rounded">
                                        <h3 class="mb-1" style="color: rgb(40, 167, 69)">{{ $createdCount ?? '0' }}</h3>
                                        <small class="text-muted d-block">Created</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="text-center p-3 border rounded">
                                        <h3 class="mb-1" style="color: rgb(255, 193, 7)">{{ $updatedCount ?? '0' }}</h3>
                                        <small class="text-muted d-block">Updated</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="text-center p-3 border rounded">
                                        <h3 class="mb-1" style="color: rgb(220, 53, 69)">{{ $archivedCount ?? '0' }}</h3>
                                        <small class="text-muted d-block">Archived</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Custom Styling -->
        <style>
            .timeline {
                position: relative;
                padding: 0;
                list-style: none;
            }

            .timeline-item {
                position: relative;
                padding-left: 40px;
                margin-bottom: 25px;
            }

            .timeline-icon {
                position: absolute;
                left: 0;
                top: 0;
                width: 30px;
                height: 30px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .timeline-icon i {
                color: white;
                font-size: 14px;
            }

            .timeline-content {
                position: relative;
                padding-bottom: 15px;
                border-bottom: 1px dashed #dee2e6;
            }

            .timeline-item:last-child .timeline-content {
                border-bottom: none;
                padding-bottom: 0;
            }

            .card {
                transition: transform 0.2s ease-in-out;
            }

            .card:hover {
                transform: translateY(-5px);
            }

            .progress {
                border-radius: 50px;
                background-color: #f8f9fa;
            }

            .progress-bar {
                transition: width 1s ease-in-out;
            }

            .activity-stats .col-4:hover .border {
                border-color: rgba(40, 167, 69, 0.85) !important;
                background-color: rgba(40, 167, 69, 0.1);
            }
            .timeline-icon {
        background-color: var(--primary-color);
    }
    .activity-stats .col-4:nth-child(2):hover .border {
        border-color: rgba(255, 193, 7, 0.85) !important;
        background-color: rgba(255, 193, 7, 0.1);
    }
    .activity-stats .col-4:nth-child(3):hover .border {
        border-color: rgba(220, 53, 69, 0.85) !important;
        background-color: rgba(220, 53, 69, 0.1);
    }

    .card:hover {
        transform: translateY(-5px);
        border-color: var(--primary-color) !important;
    }

    .progress {
        border-radius: 50px;
        background-color: #f8f9fa;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .progress-bar {
        transition: width 1s ease-in-out;
    }

    .activity-stats .col-4:hover .border {
        border-color: var(--primary-color) !important;
        background-color: var(--primary-light);
    }

    .btn-custom {
        background-color: var(--pastel-maroon);
        border-color: var(--primary-color);
    }

    .btn-custom:hover {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .timeline-content {
        border-bottom-color: var(--primary-light);
    }
        </style>

        <!-- Date Range Toggle Script -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const dateRange = document.getElementById('dateRange');
                const customDateInputs = document.getElementById('customDateInputs');

                dateRange.addEventListener('change', function() {
                    if (this.value === 'custom') {
                        customDateInputs.style.display = 'block';
                    } else {
                        customDateInputs.style.display = 'none';
                    }
                });
            });
        </script>
    </div>
</x-adminlayout>
