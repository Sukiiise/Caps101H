<x-adminlayout>
    <div class="container-fluid px-4">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-0">{{ $course->name }}</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.course.batches.index', ['course' => $course->id]) }}">Courses</a></li>
                        
                        <li class="breadcrumb-item active">Batch #{{ $batch->id }}</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('admin.course.batches.index', $course) }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left"></i> Back to Batches
            </a>
        </div>

        <!-- Content Grid -->
        <div class="row">
            <!-- Left Column - Batch Details -->
            <div class="col-md-4">
                <!-- Batch Information Card -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">Batch Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="text-muted small">Training Period</label>
                            <p class="mb-1">
                                <i class="bi bi-calendar3"></i> 
                                {{ $batch->start_date->format('M d, Y') }} - {{ $batch->end_date->format('M d, Y') }}
                            </p>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small">Student Capacity</label>
                            <p class="mb-1">
                                <i class="bi bi-people"></i>
                                {{ $enrollments->count() }}/{{ $batch->max_students }} Students
                            </p>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar" role="progressbar" 
                                    @style([
                                        'width' => ($enrollments->count() / $batch->max_students) * 100 . '%'
                                    ])
                                    aria-valuenow="{{ $enrollments->count() }}" 
                                    aria-valuemin="0" 
                                    aria-valuemax="{{ $batch->max_students }}">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small">Status</label>
                            <p class="mb-1">
                                @if($batch->start_date->isFuture())
                                    <span class="badge bg-info">Upcoming</span>
                                @elseif($batch->end_date->isPast())
                                    <span class="badge bg-secondary">Completed</span>
                                @else
                                    <span class="badge bg-success">Active</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- School Information Card -->
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">School Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="text-muted small">School Name</label>
                            <p class="mb-1">{{ $school->name }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small">Region</label>
                            <p class="mb-1">{{ $school->region }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small">Province</label>
                            <p class="mb-1">{{ $school->province }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small">Municipality</label>
                            <p class="mb-1">{{ $school->municipality }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Enrolled Students -->
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Enrolled Students</h5>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#enrollStudentModal">
                            <i class="bi bi-plus-lg"></i> Add Student
                        </button>
                    </div>
                    <div class="card-body">
                        @if($enrollments->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle" id="enrollmentsTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Student Name</th>
                                            <th>Contact Number</th>
                                            <th>Enrollment Date</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($enrollments as $enrollment)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div>
                                                            <div class="fw-semibold">
                                                                {{ $enrollment->lastname }}, {{ $enrollment->firstname }} 
                                                                {{ $enrollment->middlename ? substr($enrollment->middlename, 0, 1) . '.' : '' }}
                                                            </div>
                                                            <div class="small text-muted">{{ $enrollment->email }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $enrollment->contact_number }}</td>
                                                <td>{{ $enrollment->created_at->format('M d, Y') }}</td>
                                                <td>
                                                    <span class="badge bg-success">Active</span>
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-sm btn-outline-primary" title="Edit">
                                                            <i class="bi bi-pencil"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-danger" title="Delete">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-people display-1 text-muted"></i>
                                <p class="text-muted mt-3">No students enrolled in this batch yet.</p>
                                <button class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#enrollStudentModal">
                                    <i class="bi bi-plus-lg"></i> Enroll First Student
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Student Modal -->
    <div class="modal fade" id="enrollStudentModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Enroll New Student</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.course.batches.enroll', ['batch' => $batch]) }}" method="POST" id="enrollmentForm">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <!-- Learner Profile Section -->
                            <div class="col-12">
                                <h6 class="fw-bold text-primary mb-3">Learner Profile</h6>
                                <div class="row g-3">
                                    <!-- Personal Information -->
                                    <div class="col-md-4">
                                        <label class="form-label">Last Name</label>
                                        <input type="text" class="form-control" name="lastname" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">First Name</label>
                                        <input type="text" class="form-control" name="firstname" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Middle Name</label>
                                        <input type="text" class="form-control" name="middlename">
                                    </div>
                    
                                    <!-- Account Credentials -->
                                    <div class="col-md-6">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" name="email" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Contact Number</label>
                                        <input type="tel" class="form-control" name="contact_number" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Password</label>
                                        <input type="password" class="form-control" name="password" id="password" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Confirm Password</label>
                                        <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" required>
                                    </div>
                    
                                    <!-- Complete Address -->
                                    <div class="col-12">
                                        <h6 class="text-muted mt-3 mb-2">Complete Address</h6>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Street Number & Street Address</label>
                                        <input type="text" class="form-control" name="street_address" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Barangay</label>
                                        <input type="text" class="form-control" name="barangay" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Municipality/City</label>
                                        <input type="text" class="form-control" name="municipality" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">District</label>
                                        <input type="text" class="form-control" name="district" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Province</label>
                                        <input type="text" class="form-control" name="province" required>
                                    </div>
                    
                                    <!-- Personal Details -->
                                    <div class="col-md-6">
                                        <label class="form-label">Gender</label>
                                        <select class="form-select" name="gender" required>
                                            <option value="">Select Gender</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Birthdate</label>
                                        <input type="date" class="form-control" name="birthdate" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Age</label>
                                        <input type="number" class="form-control" name="age" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Civil Status</label>
                                        <select class="form-select" name="civil_status" required>
                                            <option value="">Select Civil Status</option>
                                            <option value="Single">Single</option>
                                            <option value="Married">Married</option>
                                            <option value="Widowed">Widowed</option>
                                            <option value="Separated">Separated</option>
                                            <option value="Divorced">Divorced</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Classification</label>
                                        <input type="text" class="form-control" name="classification" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Nationality</label>
                                        <input type="text" class="form-control" name="nationality" required>
                                    </div>
                    
                                    <!-- Educational Background -->
                                    <div class="col-12">
                                        <h6 class="text-muted mt-3 mb-2">Educational Background</h6>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Highest Grade Completed</label>
                                        <select class="form-select" name="highest_grade" required>
                                            <option value="">Select Highest Education</option>
                                            <option value="Elementary Undergraduate">Elementary Undergraduate</option>
                                            <option value="Elementary Graduate">Elementary Graduate</option>
                                            <option value="High School Undergraduate">High School Undergraduate</option>
                                            <option value="High School Graduate">High School Graduate</option>
                                            <option value="Senior High School Undergraduate">Senior High School Undergraduate</option>
                                            <option value="Senior High School Graduate">Senior High School Graduate</option>
                                            <option value="College Undergraduate">College Undergraduate</option>
                                            <option value="College Graduate">College Graduate</option>
                                            <option value="Post Graduate">Post Graduate</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Course/Program (if applicable)</label>
                                        <input type="text" class="form-control" name="course_program" placeholder="Optional">
                                    </div>
                                </div>
                            </div>
                    
                            <!-- TVET Provider Profile Section -->
                            <div class="col-12 mt-4">
                                <h6 class="fw-bold text-primary mb-3">TVET Provider Profile</h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Region</label>
                                        <input type="text" class="form-control" name="region" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Province</label>
                                        <input type="text" class="form-control" name="provider_province" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Congressional District</label>
                                        <input type="text" class="form-control" name="congressional_district" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Municipality</label>
                                        <input type="text" class="form-control" name="provider_municipality" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Type of Provider</label>
                                        <input type="text" class="form-control" name="provider_type" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">School Name</label>
                                        <input type="text" class="form-control" name="school_name" value="{{ $school->name }}" readonly>
                                    </div>
                                </div>
                            </div>
                    
                            <!-- Program Profile Section -->
                            <div class="col-12 mt-4">
                                <h6 class="fw-bold text-primary mb-3">Program Profile</h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Sector</label>
                                        <input type="text" class="form-control" name="sector" value="{{ $course->sector->name }}" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">TVET Program Registration Status</label>
                                        <input type="text" class="form-control" name="registration_status" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Qualification/Program Title</label>
                                        <input type="text" class="form-control" name="program_title" value="{{ $course->name }}" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Delivery Mode</label>
                                        <input type="text" class="form-control" name="delivery_mode" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Enroll Student</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @push('styles')
    <style>
            /* Section Headers */
        .fw-bold.text-primary.mb-3 {
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #e9ecef;
            margin-bottom: 1.5rem !important;
        }

        /* Subsection Headers */
        .text-muted.mt-3.mb-2 {
            font-size: 0.9rem;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 0.5rem;
        }

        /* Section Spacing */
        .col-12.mt-4 {
            margin-top: 2rem !important;
        }

        /* Form Group Spacing */
        .row.g-3 {
            margin-bottom: 1rem;
        }

        /* Required Field Indicator */
        .form-label:not([for$="middlename"]):not([for="course_program"])::after {
            content: " *";
            color: #dc3545;
        }

        /* Readonly Fields */
        .form-control[readonly] {
            background-color: #f8f9fa;
            cursor: not-allowed;
        }
        /* Card Styling */
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .card-header {
            border-bottom: 1px solid rgba(0, 0, 0, 0.125);
        }

        /* Progress Bar */
        .progress {
            background-color: #e9ecef;
            border-radius: 0.5rem;
        }

        .progress-bar {
            background-color: #0d6efd;
            border-radius: 0.5rem;
        }

        /* Table Styling */
        .table thead th {
            font-size: 0.875rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-top: none;
            background-color: #f8f9fa;
        }

        .table tbody tr {
            transition: all 0.3s ease;
        }

        .table tbody tr:hover {
            background-color: rgba(13, 110, 253, 0.05);
        }

        /* Avatar Styling */
        .avatar-initial {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.875rem;
        }

        /* Badge Styling */
        .badge {
            padding: 0.5em 0.75em;
            font-weight: 500;
        }

        /* Button Group */
        .btn-group .btn {
            padding: 0.25rem 0.5rem;
        }

        .btn-group .btn i {
            font-size: 0.875rem;
        }

        /* Empty State */
        .text-center.py-5 i {
            opacity: 0.5;
        }

        /* Breadcrumb */
        .breadcrumb {
            margin-bottom: 0;
        }

        .breadcrumb-item a {
            color: #6c757d;
            text-decoration: none;
        }

        .breadcrumb-item a:hover {
            color: #0d6efd;
        }

        /* Modal Styling */
        .modal-xl {
            max-width: 1140px;
        }

        .nav-tabs .nav-link {
            color: #6c757d;
        }

        .nav-tabs .nav-link.active {
            color: #0d6efd;
            font-weight: 500;
        }

        /* Form Styling */
        .form-label {
            font-size: 0.875rem;
            font-weight: 500;
            color: #6c757d;
        }

        .form-control:disabled,
        .form-control[readonly] {
            background-color: #f8f9fa;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .card-body {
                padding: 1rem;
            }

            .table {
                font-size: 0.875rem;
            }

            .avatar-initial {
                width: 32px;
                height: 32px;
            }

            .modal-xl {
                margin: 0.5rem;
            }
        }

            /* Add custom scrollbar styling */
    .tab-content::-webkit-scrollbar {
        width: 8px;
    }

    .tab-content::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }

    .tab-content::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 4px;
    }

    .tab-content::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    /* Ensure form elements are properly spaced */
    .form-label {
        margin-bottom: 0.3rem;
    }

    .form-control, .form-select {
        margin-bottom: 0.5rem;
    }
    </style>
    @endpush
    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-calculate age based on birthdate
        const birthdateInput = document.querySelector('input[name="birthdate"]');
        const ageInput = document.querySelector('input[name="age"]');
        
        if (birthdateInput && ageInput) {
            birthdateInput.addEventListener('change', function() {
                const birthdate = new Date(this.value);
                const today = new Date();
                let age = today.getFullYear() - birthdate.getFullYear();
                const monthDiff = today.getMonth() - birthdate.getMonth();
                
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthdate.getDate())) {
                    age--;
                }
                
                ageInput.value = age;
            });
        }
    
        // Form validation and submission
        const form = document.querySelector('form');
        const password = document.getElementById('password');
        const passwordConfirm = document.getElementById('password_confirmation');
        const navTabs = document.querySelectorAll('.nav-tabs .nav-link');
    
        form.addEventListener('submit', function(e) {
            e.preventDefault();
    
            // Check all required fields
            const requiredFields = form.querySelectorAll('[required]');
            let emptyFields = [];
            let firstEmptyTab = null;
    
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    emptyFields.push(field);
                    
                    // Find which tab contains this empty field
                    const tabPane = field.closest('.tab-pane');
                    if (tabPane && !firstEmptyTab) {
                        firstEmptyTab = tabPane.id;
                    }
    
                    // Add visual feedback
                    field.classList.add('is-invalid');
                } else {
                    field.classList.remove('is-invalid');
                }
            });
    
            // Check password confirmation
            if (password.value !== passwordConfirm.value) {
                Swal.fire({
                    icon: 'error',
                    title: 'Password Mismatch',
                    text: 'The passwords you entered do not match. Please try again.',
                });
                return;
            }
    
            // If there are empty fields
            if (emptyFields.length > 0) {
                // Switch to the tab containing the first empty field
                if (firstEmptyTab) {
                    const targetTab = document.querySelector(`[href="#${firstEmptyTab}"]`);
                    const tab = new bootstrap.Tab(targetTab);
                    tab.show();
                }
    
                Swal.fire({
                    icon: 'error',
                    title: 'Incomplete Form',
                    text: 'Please fill in all required fields before submitting.',
                });
                return;
            }
    
            // If all validations pass, show loading state and submit
            Swal.fire({
                title: 'Enrolling Student...',
                text: 'Please wait',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
    
            // Submit the form
            fetch(this.action, {
                method: 'POST',
                body: new FormData(this),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: data.message || 'Student enrolled successfully',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    throw new Error(data.message || 'Failed to enroll student');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: error.message || 'Something went wrong while enrolling the student',
                    confirmButtonColor: '#3085d6'
                });
            });
        });
    
        // Add real-time validation feedback
        const inputs = form.querySelectorAll('input, select');
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                if (this.hasAttribute('required')) {
                    if (!this.value.trim()) {
                        this.classList.add('is-invalid');
                    } else {
                        this.classList.remove('is-invalid');
                    }
                }
            });
        });
    });
    </script>
    @endpush
    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-calculate age based on birthdate
        const birthdateInput = document.querySelector('input[name="birthdate"]');
        const ageInput = document.querySelector('input[name="age"]');
        
        if (birthdateInput && ageInput) {
            birthdateInput.addEventListener('change', function() {
                const birthdate = new Date(this.value);
                const today = new Date();
                let age = today.getFullYear() - birthdate.getFullYear();
                const monthDiff = today.getMonth() - birthdate.getMonth();
                
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthdate.getDate())) {
                    age--;
                }
                
                ageInput.value = age;
            });
        }
    
        // Form submission handling
        const form = document.getElementById('enrollmentForm');
        
        form.addEventListener('submit', function(e) {
            e.preventDefault();
    
            // Check all required fields
            const requiredFields = form.querySelectorAll('[required]');
            let emptyFields = [];
            let firstEmptyTab = null;
    
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    emptyFields.push(field);
                    field.classList.add('is-invalid');
                    
                    // Find which tab contains this empty field
                    const tabPane = field.closest('.tab-pane');
                    if (tabPane && !firstEmptyTab) {
                        firstEmptyTab = tabPane.id;
                    }
                } else {
                    field.classList.remove('is-invalid');
                }
            });
    
            // If there are empty fields
            if (emptyFields.length > 0) {
                // Switch to the tab containing the first empty field
                if (firstEmptyTab) {
                    const targetTab = document.querySelector(`[href="#${firstEmptyTab}"]`);
                    const tab = new bootstrap.Tab(targetTab);
                    tab.show();
                }
    
                Swal.fire({
                    icon: 'error',
                    title: 'Incomplete Form',
                    text: 'Please fill in all required fields before submitting.',
                });
                return;
            }
    
            // Show loading state
            Swal.fire({
                title: 'Enrolling Student...',
                text: 'Please wait',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
    
            // Submit the form
            fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: data.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    throw new Error(data.message || 'Failed to enroll student');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: error.message,
                    confirmButtonColor: '#3085d6'
                });
            });
        });
    });
    </script>
    @endpush
    
    @push('styles')
    <style>
        /* Styling for invalid fields */
        .is-invalid {
            border-color: #dc3545;
            padding-right: calc(1.5em + 0.75rem);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }
    
        .is-invalid:focus {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
        }
    
        /* Required field indicator */
        .form-label::after {
            content: " *";
            color: #dc3545;
        }
    
        .form-label:not([for$="middlename"])::after {
            content: " *";
            color: #dc3545;
        }
    </style>
    @endpush
    
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-calculate age based on birthdate
            const birthdateInput = document.querySelector('input[name="birthdate"]');
            const ageInput = document.querySelector('input[name="age"]');
            
            if (birthdateInput && ageInput) {
                birthdateInput.addEventListener('change', function() {
                    const birthdate = new Date(this.value);
                    const today = new Date();
                    let age = today.getFullYear() - birthdate.getFullYear();
                    const monthDiff = today.getMonth() - birthdate.getMonth();
                    
                    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthdate.getDate())) {
                        age--;
                    }
                    
                    ageInput.value = age;
                });
            }

            // Convert middle name to initial
            const middleNameInput = document.querySelector('input[name="middlename"]');
            if (middleNameInput) {
                middleNameInput.addEventListener('blur', function() {
                    if (this.value) {
                        this.value = this.value.charAt(0).toUpperCase() + '.';
                    }
                });
            }

            // Initialize tooltips
            const tooltips = document.querySelectorAll('[title]');
            tooltips.forEach(tooltip => {
                new bootstrap.Tooltip(tooltip);
            });

            // Initialize DataTable
            if ($.fn.DataTable) {
                $('#enrollmentsTable').DataTable({
                    "pageLength": 8, // Default page length
                    "lengthMenu": [[8, 10, 15, 20], [8, 10, 15, 20]], // Pagination options
                    "ordering": true,
                    "info": true,
                    "responsive": true,
                    "language": {
                        "emptyTable": "No students enrolled",
                        "lengthMenu": "Show _MENU_ entries", // Customize the length menu text
                        "info": "Showing _START_ to _END_ of _TOTAL_ entries", // Customize the info text
                        "paginate": {
                            "first": "First",
                            "last": "Last",
                            "next": "Next",
                            "previous": "Previous"
                        }
                    },
                    "dom": '<"top"lf>rt<"bottom"ip><"clear">', // Controls the layout
                    "stateSave": true, // Saves the state of the table (including pagination length)
                    "drawCallback": function(settings) {
                        // Optional: Add any custom styling after table draw
                        $('.dataTables_length select').addClass('form-select form-select-sm');
                        $('.dataTables_filter input').addClass('form-control form-control-sm');
                    }
                });
            }
        });
    </script>
    @endpush
</x-adminlayout>
