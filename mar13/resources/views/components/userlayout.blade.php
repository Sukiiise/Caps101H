<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    
    <title>El Nazareno | Admin</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css">
    <style>
:root {
        --primary-color:rgb(179, 51, 51);
        --primary-hover:rgb(170, 111, 111);
        --primary-light: rgba(138, 21, 56, 0.1);
        --pastel-maroon:rgb(163, 57, 57);
    }

    /* Common Button Outline Styles */
    .btn {
        border-radius: 5px;
        transition: all 0.3s ease;
    }

    /* Primary Custom Button with Outline */
    .btn-custom {
        background-color: var(--pastel-maroon);
        color: var(--primary-color);
        border: 2px solid var(--primary-color);
    }

    .btn-custom:hover {
        background-color: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }

    /* Sidebar Navigation Links Outline */
    .nav-link {
        background-color: white;
        color: var(--primary-color) !important;
        border: 1px solid var(--primary-color);
        margin-bottom: 5px;
        border-radius: 5px;
        transition: all 0.3s ease;
    }

    .nav-link:hover,
    .nav-link:focus,
    .nav-link.active,
    .nav-link[aria-expanded="true"] {
        background-color: var(--pastel-maroon) !important;
        color: white !important;
        border-color: var(--primary-color);
    }

    /* Sub Navigation Links Outline */
    .sub-nav .nav-link {
        background-color: white;
        border: 1px solid var(--primary-color);
        color: var(--primary-color) !important;
        padding-left: 2.5rem;
    }

    .sub-nav .nav-link:hover {
        background-color: var(--pastel-maroon) !important;
        color: white !important;
    }

    /* Sidebar Toggle Button Outline */
    #sidebarToggle {
        color: var(--primary-color);
        border: 1px solid var(--primary-color);
        padding: 5px 10px;
        border-radius: 5px;
        text-decoration: none;
    }

    #sidebarToggle:hover {
        background-color: var(--pastel-maroon);
        color: white;
    }

    /* Search Button Outline */
    .btn-outline-danger {
        border: 2px solid;
        color: white;
        background-color: rgb(179, 68, 68);
    }

    .btn-outline-danger:hover {
        background-color: var(--pastel-maroon);
        border-color: var(--primary-color);
        color: white;
    }

    /* Admin Dropdown Button Outline */
    .dropdown .btn-custom {
        background-color: white;
        border: 2px solid var(--primary-color);
        color: var(--primary-color);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .dropdown .btn-custom:hover,
    .dropdown .btn-custom:focus {
        background-color: var(--primary-color);
        color: white;
    }

    /* Dropdown Items Outline */
    .dropdown-item {
        transition: all 0.3s ease;
        color: var(--primary-color);
    }

    .dropdown-item:hover {
        background-color: var(--pastel-maroon);
        color: var(--primary-color);
    }

    /* Logout Button Outline */
    .dropdown-item.text-danger {
        color: var(--primary-color) !important;
        border: none;
        width: 100%;
        text-align: left;
        background-color: transparent;
    }

    .dropdown-item.text-danger:hover {
        background-color: var(--pastel-maroon);
    }

    /* Focus States for All Buttons */
    .btn:focus,
    .nav-link:focus {
        box-shadow: 0 0 0 0.25rem rgba(138, 21, 56, 0.25);
    }

    /* Sidebar and Navigation */
    .sidebar {
        background-color: #f8f9fa;
        border-right: 1px solid #dee2e6;
    }

    /* Brand Text */
    .brand-text {
        color: var(--primary-color);
        font-weight: 600;
    }

    /* Collapse Icons */
    .bi-chevron-down {
        transition: transform 0.3s ease;
    }

    .collapsed .bi-chevron-down {
        transform: rotate(-90deg);
    }

    .form-control::placeholder {
        color: #999;
    }

    /* Remove default blue focus outline */
    .nav-link:focus,
    .btn:focus {
        outline: none !important;
        box-shadow: none !important;
    }

    /* Mobile Responsive Sidebar */
    @media (max-width: 991.98px) {
        .sidebar {
            position: fixed;
            top: 0;
            left: -280px;
            height: 100vh;
            width: 280px;
            z-index: 1045;
            transition: left 0.3s ease;
            background-color: #f8f9fa;
        }

        .sidebar.show {
            left: 0;
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0,0,0,0.5);
            z-index: 1040;
        }

        .sidebar-overlay.show {
            display: block;
        }
    }

    /* Override any Bootstrap default focus styles */
    .btn:focus, 
    .nav-link:focus,
    .dropdown-item:focus {
        box-shadow: none !important;
    }

    /* Form control focus */
    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.25rem rgba(138, 21, 56, 0.25);
    }

    .hover-shadow:hover {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        transform: translateY(-2px);
        cursor: pointer;
    }
    
    /* Updated Event Item Styles */
    .events-list {
        background-color: #f8f9fa; /* Light gray background */
        padding: 15px;
        border-radius: 8px;
    }

    .event-item {
        background-color: white;
        border: 1px solid #dee2e6;
        border-radius: 5px;
        margin-bottom: 10px;
        transition: all 0.3s ease;
    }

    .event-item:hover {
        background-color:rgb(255, 255, 255); /* Pastel maroon on hover */
        border-color:rgb(138, 21, 21);
    }

    .school-link {
        display: block;
        transition: transform 0.3s ease;
    }
    
    .school-link:hover {
        transform: scale(1.05);
    }
    
    .school-link img {
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    /* Additional Event Item Styling */
    .event-item .badge {
        background-color:rgb(173, 55, 55);
        color: white;
    }

    .event-item:hover .text-muted {
        color:rgb(138, 37, 37) !important;
    }

    .event-item .dropdown-toggle:after {
        display: none;
    }
    .bottom-nav-item {
        position: absolute;
        bottom: 15px;
        left: 15px;
        padding: 0;
        margin: 0;
    }
    
    .nav-circle-link {
        width: 45px !important;
        height: 45px !important;
        border-radius: 50% !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        background-color: white !important;
        border: 2px solid rgb(138, 37, 37) !important;
        padding: 0 !important;
        margin: 0 !important;
        transition: all 0.3s ease;
    }
    
    .nav-circle-link i {
        font-size: 1.5rem;
        color: rgb(179, 54, 54);
        transition: all 0.3s ease;
    }
    
    .nav-circle-link:hover, 
    .nav-circle-link:focus {
        background-color: rgb(163, 57, 57) !important;
    }
    
    .nav-circle-link:hover i, 
    .nav-circle-link:focus i {
        color: white !important;
    }
    
    /* Updated Dropup Menu Styles */
    .bottom-nav-item .dropup .dropdown-menu {
        bottom: 100%;
        margin-bottom: 10px;
        left: 0 !important;
        min-width: 200px;
        border-radius: 8px;
        border: 1px solid rgba(0,0,0,0.1);
        background-color: white;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .bottom-nav-item .dropdown-item {
        padding: 8px 16px;
        transition: all 0.3s ease;
    }
    
    /* Updated hover states */
    .bottom-nav-item .dropdown-item:hover {
        background-color: rgba(138, 37, 37, 0.1);
        color: rgb(196, 75, 75);
    }
    
    .bottom-nav-item .dropdown-item.text-danger:hover {
        background-color: rgba(220, 53, 69, 0.1);
        color:rgb(184, 65, 65);
    }
    
    .bottom-nav-item .dropdown-divider {
        margin: 0.5rem 0;
        border-color: rgba(0,0,0,0.1);
    }
    
    /* Arrow styling */
    .bottom-nav-item .dropdown-menu::before {
        content: '';
        position: absolute;
        bottom: -6px;
        left: 20px;
        width: 12px;
        height: 12px;
        background-color: white;
        border-right: 1px solid rgba(0,0,0,0.1);
        border-bottom: 1px solid rgba(0,0,0,0.1);
        transform: rotate(45deg);
    }
    
    </style>
</head>
<body>
    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay"></div>

    @if(auth()->check() && auth()->user()->usertype === 'user')
    <div class="d-flex">
        <!-- Vertical Navigation -->
        <nav class="sidebar" style="width: 280px; min-height: 100vh;">
            <!-- Sidebar Header -->
            <div class="p-3 border-bottom">
                <div class="d-flex align-items-center">
                    <img src="{{ asset('images/elogo.png') }}" alt="El Nazareno Logo" class="me-2" style="height: 40px;">
                    <span class="fs-5 brand-text">El Nazareno</span>
                </div>
            </div>

            <!-- Navigation Items -->
            <div class="p-3">
                <ul class="nav flex-column">
                    <!-- Dashboard -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.dashboard')}}">
                            <i class="bi bi-speedometer2 me-2"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <!-- User Management -->
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
                                    <a class="nav-link" href="#">
                                        <i class="bi bi-person-plus me-2"></i>
                                        <span>Create User</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">
                                        <i class="bi bi-person-lines-fill me-2"></i>
                                        <span>View Users</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <!-- Monitoring -->
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
                                    <a class="nav-link" href="#">
                                        <i class="bi bi-clock-history me-2"></i>
                                        <span>Activity Logs</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">
                                        <i class="bi bi-card-checklist me-2"></i>
                                        <span>Reports</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    
                    <li class="nav-item bottom-nav-item">
                        <div class="dropup">
                            <a class="nav-link nav-circle-link" 
                               data-bs-toggle="dropdown" 
                               href="#" 
                               role="button">
                                <i class="bi bi-person-circle"></i>
                            </a>
                            <ul class="dropdown-menu shadow">
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="bi bi-person me-2"></i> Profile
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

        <!-- Main Content -->
        <div class="flex-grow-1">
            <!-- Top Header -->
            <!-- Top Header -->
            <header class="bg-white border-bottom py-2">
                <div class="container-fluid">
                    <div class="d-flex justify-content-between align-items-center">
                        <!-- Toggle Sidebar Button -->
                        <button class="btn btn-link d-lg-none" id="sidebarToggle">
                            <i class="bi bi-list fs-4"></i>
                        </button>
            
                        <!-- Search Form -->
                        <form class="d-flex ms-auto">
                            <div class="input-group">
                                <input class="form-control" type="search" placeholder="Search" aria-label="Search">
                                <button class="btn btn-outline-danger" type="submit">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </header>
            
            <!-- Page Content -->
            <main class="p-4">
                {{ $slot }}
            </main>

            <!-- Footer -->
            <footer class="py-3">
                <div class="container">
                    <div class="row">
                        <div class="col-12 text-center">
                            <small class="text-muted">Copyright &copy; {{ date('Y') }} El Nazareno. All rights reserved.</small>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Sidebar Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.querySelector('.sidebar');
            const overlay = document.querySelector('.sidebar-overlay');

            // Toggle sidebar
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    sidebar.classList.toggle('show');
                    overlay.classList.toggle('show');
                });
            }

            // Close sidebar when clicking overlay
            overlay.addEventListener('click', function() {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
            });

            // Close sidebar when clicking outside
            document.addEventListener('click', function(event) {
                if (window.innerWidth < 992) {
                    if (!sidebar.contains(event.target) && 
                        !sidebarToggle.contains(event.target)) {
                        sidebar.classList.remove('show');
                        overlay.classList.remove('show');
                    }
                }
            });

            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 992) {
                    sidebar.classList.remove('show');
                    overlay.classList.remove('show');
                }
            });

            // Prevent clicks within sidebar from closing it
            sidebar.addEventListener('click', function(e) {
                e.stopPropagation();
            });

            // Add animation for dropdown arrows
            const dropdownToggles = document.querySelectorAll('[data-bs-toggle="collapse"]');
            dropdownToggles.forEach(toggle => {
                toggle.addEventListener('click', function() {
                    this.classList.toggle('collapsed');
                });
            });
        });
    </script>

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
</body>
</html>
