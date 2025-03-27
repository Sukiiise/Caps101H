<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>El Nazareno | Admin</title>

    <!-- CSS -->
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <!-- Your custom CSS -->
    <link rel="stylesheet" href="{{asset('css/adminlayout.css')}}">
</head>
<body>
    <div class="sidebar-overlay"></div>
    @if(auth()->check() && auth()->user()->usertype === 'admin')
        <div class="d-flex">
            <!-- Vertical Navigation -->
            <nav class="sidebar" style="width: 280px;">
                <div class="p-3 border-bottom">
                    <div class="d-flex align-items-center">
                        <img src="{{ asset('images/logo.png') }}" alt="El Nazareno Logo" class="me-2" style="height: 40px;">
                        <span class="fs-5 brand-text">El Nazareno</span>
                    </div>
                </div>
                <div class="p-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.dashboard')}}">
                                <i class="bi bi-speedometer2 me-2"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link d-flex justify-content-between align-items-center" 
                               data-bs-toggle="collapse" 
                               href="#userManagement" 
                               role="button" 
                               aria-expanded="false">
                                <span>
                                    <i class="bi bi-people me-2"></i>
                                    <span>User Management</span>
                                </span>
                                <i class="bi bi-chevron-down"></i>
                            </a>
                            <div class="collapse" id="userManagement">
                                <ul class="nav flex-column ms-3 sub-nav">
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('admin.createuser') }}">
                                            <i class="bi bi-person-plus me-2"></i>
                                            <span>Create User</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('admin.users') }}">
                                            <i class="bi bi-person-lines-fill me-2"></i>
                                            <span>View Users</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link d-flex justify-content-between align-items-center" 
                               data-bs-toggle="collapse" 
                               href="#monitoring" 
                               role="button" 
                               aria-expanded="false">
                                <span>
                                    <i class="bi bi-graph-up me-2"></i>
                                    <span>Monitoring</span>
                                </span>
                                <i class="bi bi-chevron-down"></i>
                            </a>
                            <div class="collapse" id="monitoring">
                                <ul class="nav flex-column ms-3 sub-nav">
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('admin.userlogs') }}">
                                            <i class="bi bi-clock-history me-2"></i>
                                            <span>Activity Logs</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('admin.reports') }}">
                                            <i class="bi bi-card-checklist me-2"></i>
                                            <span>Reports</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item mt-auto">
                            <div class="dropup position-fixed bottom-0" style="z-index: 1000; padding-bottom: 1%; color: white;">
                                <a class="nav-link nav-circle-link" 
                                   style="color: white;"
                                   data-bs-toggle="dropdown" 
                                   href="#" 
                                   role="button"
                                   onclick="event.preventDefault(); event.stopPropagation();">
                                    <i class="bi bi-person-circle"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow" 
                                    style="position: absolute; bottom: 100%; margin-bottom: 10px;">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.profile.index') }}">
                                            <i class="bi bi-person me-2"></i> Profile
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.profile.edit') }}">
                                            <i class="bi bi-person-gear me-2"></i> Edit Profile
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('logout') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="bi bi-box-arrow-right me-2"></i> Logout
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content Area -->
             <div class="flex-grow-1">
                <button id="sidebarToggle" class="btn btn-link">
                    <i class="bi bi-chevron-left" id="toggleIcon"></i>
                </button>
                 <header class="d-flex align-items-center justify-content-between p-3 header-section">
                     @if(isset($icon))
                         <div class="header-icon">
                             {!! $icon !!}
                         </div>
                     @endif
             
                     @if(isset($button))
                         <div class="header-button">
                             {!! $button !!}
                         </div>
                     @endif
                 </header>
                 
                 <!-- Main Content -->
                 <div class="p-3 main-content-section">
                     {{ $slot }}
                 </div>
             </div>
             
        </div>
    @else
        <!-- Unauthorized access -->
        <div class="container mt-5">
            <div class="alert alert-danger text-center">
                <h4>Please log in to access this area</h4>
                <p>You will be redirected to the login page...</p>
            </div>
        </div>
        <script>
            setTimeout(function() {
                window.location.href = "/";
            }, 3000);
        </script>
    @endif

    <!-- JavaScript -->
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <!-- Your custom JavaScript -->
    <script src="{{asset('js/adminlayout.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @stack('scripts')
</body>
</html>
