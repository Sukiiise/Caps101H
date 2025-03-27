<x-adminlayout :icon="$icon" :button="$button">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="container-fluid">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            @if($school->logo_path)
                                <img src="{{ asset('storage/' . $school->logo_path) }}" 
                                     alt="School Logo" 
                                     class="rounded-circle me-3"
                                     style="width: 60px; height: 60px; object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-3"
                                     style="width: 60px; height: 60px;">
                                    <i class="bi bi-building text-danger" style="font-size: 1.5rem;"></i>
                                </div>
                            @endif
                            <div>
                                <h4 class="mb-0" style="color: var(--primary-color);">{{ $school->name }}</h4>
                                <p class="text-muted mb-0">
                                    <i class="bi bi-geo-alt"></i>
                                    @php
                                        $address_parts = array_filter([
                                            $school->street_number,
                                            $school->barangay,
                                            $school->city,
                                            $school->province
                                        ]);
                                        $full_address = !empty($address_parts) ? implode(', ', $address_parts) : 'No address provided';
                                    @endphp
                                    {{ $full_address }}
                                </p>
                                <p class="text-muted mb-0">{{ $school->contact_number }}</p>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.school.edit', $school->id) }}" 
                               class="btn btn-outline-danger">
                                <i class="bi bi-pencil-square"></i> Edit
                            </a>
                            <form action="{{ route('admin.school.destroy', $school->id) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('Are you sure you want to delete this school?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sector Section -->
        <div class="row mb-4">
            <div class="col-12 mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="section-title">
                        <i class="bi bi-diagram-3 me-2"></i>Industry Sector of Qualification
                    </h5>
                    <button type="button" 
                            class="btn btn-primary" 
                            data-bs-toggle="modal" 
                            data-bs-target="#addSectorModal">
                        <i class="bi bi-plus-lg"></i> Add Sector
                    </button>
                </div>
            </div>

            @if($sectors->where('school_id', $school->id)->count() > 0)
                @foreach($sectors->where('school_id', $school->id) as $sector)
                    <div class="col-12 mb-4">
                        <div class="card shadow-sm">
                            <div class="card-header bg-light">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0" style="color: white;">{{ $sector->name }}</h5>
                                    <div class="dropdown">
                                        <button class="btn btn-link text-dark p-0" 
                                                type="button" 
                                                data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <button class="dropdown-item" 
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editSectorModal{{ $sector->id }}">
                                                    <i class="bi bi-pencil-square me-2"></i>Edit Sector
                                                </button>
                                            </li>
                                            <li>
                                                <button class="dropdown-item" 
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#addCourseModal"
                                                        data-sector-id="{{ $sector->id }}">
                                                    <i class="bi bi-plus-lg me-2"></i>Add Course
                                                </button>
                                            </li>
                                            @if($sector->courses->count() == 0)
                                                <li>
                                                    <button class="dropdown-item text-danger delete-sector" 
                                                            data-sector-id="{{ $sector->id }}"
                                                            data-sector-name="{{ $sector->name }}">
                                                        <i class="bi bi-trash me-2"></i>Delete Sector
                                                    </button>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                @if($sector->courses->count() > 0)
                                    <div class="row">
                                        @foreach($sector->courses as $course)
                                        <div class="col-md-4 mb-3">
                                            <div class="card h-100 border-0 shadow-sm hover-shadow">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between align-items-start">
                                                        <h5 class="card-title text-dark mb-3">{{ $course->name }}</h5>
                                                        <div class="dropdown">
                                                            <button class="btn btn-link text-dark p-0" 
                                                                    type="button" 
                                                                    data-bs-toggle="dropdown">
                                                                <i class="bi bi-three-dots-vertical"></i>
                                                            </button>
                                                            <ul class="dropdown-menu dropdown-menu-end">
                                                                <li>
                                                                <button class="dropdown-item edit-course" 
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#editCourseModal"
                                                                    data-course-id="{{ $course->id }}"
                                                                    data-sector-id="{{ $course->sector_id }}"
                                                                    data-course-name="{{ $course->name }}"
                                                                    data-course-description="{{ $course->description }}"
                                                                    data-course-duration="{{ $course->duration_days }}"
                                                                    data-morning-in="{{ $course->morning_in }}"
                                                                    data-morning-out="{{ $course->morning_out }}"
                                                                    data-afternoon-in="{{ $course->afternoon_in }}"
                                                                    data-afternoon-out="{{ $course->afternoon_out }}">
                                                                    <i class="bi bi-pencil-square me-2"></i>Edit
                                                                </button>
                                                                </li>
                                                                <li>
                                                                    <button class="dropdown-item text-danger delete-course" 
                                                                            type="button"
                                                                            data-course-id="{{ $course->id }}"
                                                                            data-course-name="{{ $course->name }}">
                                                                        <i class="bi bi-trash me-2"></i>Delete
                                                                    </button>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <p class="card-text text-muted small mb-3">
                                                        {{ Str::limit($course->description, 100) }}
                                                    </p>
                                                    <div class="d-flex align-items-center text-muted small">
                                                        <span class="me-3">
                                                            <i class="bi bi-clock me-1"></i>
                                                            {{ $course->duration_days }} {{ Str::plural('day', $course->duration_days) }}
                                                        </span>
                                                        <span>
                                                            <i class="bi bi-people me-1"></i>
                                                            {{ $course->batches_count ?? 0 }} {{ Str::plural('batch', $course->batches_count ?? 0) }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <a href="{{ route('admin.course.batches.index', $course) }}" 
                                                class="stretched-link course-link"></a>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <i class="bi bi-journal-x text-muted" style="font-size: 2rem;"></i>
                                        <p class="text-muted mt-2">No courses in this sector yet.</p>
                                        <button type="button" 
                                                class="btn btn-primary btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#addCourseModal"
                                                data-sector-id="{{ $sector->id }}">
                                            Add Course
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="bi bi-diagram-3 display-4 text-muted"></i>
                        <p class="mt-2 text-muted">No sectors added yet.</p>
                        <button type="button" 
                                class="btn btn-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#addSectorModal">
                            Add First Sector
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Add Sector Modal -->
    <div class="modal fade" id="addSectorModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Sector</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="addSectorForm" action="{{ route('admin.sectors.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="school_id" value="{{ $school->id }}">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="sector_name" class="form-label">Sector Name</label>
                            <input type="text" 
                                class="form-control @error('name') is-invalid @enderror" 
                                id="sector_name" 
                                name="name" 
                                value="{{ old('name') }}"
                                required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="sector_description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                    id="sector_description" 
                                    name="description" 
                                    rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create Sector</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    
    <!-- Add Course Modal -->
    <div class="modal fade" id="addCourseModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Course</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="addCourseForm" action="{{ route('admin.course.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="school_id" value="{{ $school->id }}">
                    <input type="hidden" name="sector_id" id="sector_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="course_name" class="form-label">Course Name</label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="course_name" 
                                   name="name" 
                                   value="{{ old('name') }}"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="course_description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                    id="course_description" 
                                    name="description" 
                                    rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="duration_days" class="form-label">Duration (Days)</label>
                            <input type="number" 
                                   class="form-control @error('duration_days') is-invalid @enderror" 
                                   id="duration_days" 
                                   name="duration_days" 
                                   value="{{ old('duration_days') }}"
                                   min="1" 
                                   required>
                            @error('duration_days')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="mb-3">Morning Schedule</h6>
                                <div class="mb-3">
                                    <label for="morning_in" class="form-label">Time In</label>
                                    <input type="time" 
                                           class="form-control @error('morning_in') is-invalid @enderror" 
                                           id="morning_in" 
                                           name="morning_in" 
                                           value="{{ old('morning_in') }}"
                                           required>
                                    @error('morning_in')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="morning_out" class="form-label">Time Out</label>
                                    <input type="time" 
                                           class="form-control @error('morning_out') is-invalid @enderror" 
                                           id="morning_out" 
                                           name="morning_out" 
                                           value="{{ old('morning_out') }}"
                                           required>
                                    @error('morning_out')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="mb-3">Afternoon Schedule</h6>
                                <div class="mb-3">
                                    <label for="afternoon_in" class="form-label">Time In</label>
                                    <input type="time" 
                                           class="form-control @error('afternoon_in') is-invalid @enderror" 
                                           id="afternoon_in" 
                                           name="afternoon_in" 
                                           value="{{ old('afternoon_in') }}"
                                           required>
                                    @error('afternoon_in')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="afternoon_out" class="form-label">Time Out</label>
                                    <input type="time" 
                                           class="form-control @error('afternoon_out') is-invalid @enderror" 
                                           id="afternoon_out" 
                                           name="afternoon_out" 
                                           value="{{ old('afternoon_out') }}"
                                           required>
                                    @error('afternoon_out')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create Course</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Course Modal -->
    <div class="modal fade" id="editCourseModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Course</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editCourseForm" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="school_id" value="{{ $school->id }}">
                    <input type="hidden" name="sector_id" id="edit_sector_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_course_name" class="form-label">Course Name</label>
                            <input type="text" 
                                class="form-control @error('name') is-invalid @enderror" 
                                id="edit_course_name" 
                                name="name" 
                                required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="edit_course_description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                    id="edit_course_description" 
                                    name="description" 
                                    rows="3"></textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="edit_duration_days" class="form-label">Duration (Days)</label>
                            <input type="number" 
                                class="form-control @error('duration_days') is-invalid @enderror" 
                                id="edit_duration_days" 
                                name="duration_days" 
                                min="1" 
                                required>
                            @error('duration_days')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <!-- Time Schedule Fields -->
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="mb-3">Morning Schedule</h6>
                                <div class="mb-3">
                                    <label for="edit_morning_in" class="form-label">Time In</label>
                                    <input type="time" 
                                        class="form-control" 
                                        id="edit_morning_in" 
                                        name="morning_in" 
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_morning_out" class="form-label">Time Out</label>
                                    <input type="time" 
                                        class="form-control" 
                                        id="edit_morning_out" 
                                        name="morning_out" 
                                        required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="mb-3">Afternoon Schedule</h6>
                                <div class="mb-3">
                                    <label for="edit_afternoon_in" class="form-label">Time In</label>
                                    <input type="time" 
                                        class="form-control" 
                                        id="edit_afternoon_in" 
                                        name="afternoon_in" 
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_afternoon_out" class="form-label">Time Out</label>
                                    <input type="time" 
                                        class="form-control" 
                                        id="edit_afternoon_out" 
                                        name="afternoon_out" 
                                        required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Course</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let isSubmitting = false;
    
            // Set sector ID when adding a course
            $('#addCourseModal').on('show.bs.modal', function (event) {
                const button = $(event.relatedTarget);
                const sectorId = button.data('sector-id');
                $('#sector_id').val(sectorId);
            });
    
            // Time input validation
            function validateTimes() {
                const morningIn = $('#morning_in').val();
                const morningOut = $('#morning_out').val();
                const afternoonIn = $('#afternoon_in').val();
                const afternoonOut = $('#afternoon_out').val();
    
                if (morningIn && morningOut && afternoonIn && afternoonOut) {
                    if (morningOut <= morningIn) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Invalid Time',
                            text: 'Morning time out must be after time in'
                        });
                        return false;
                    }
                    if (afternoonIn <= morningOut) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Invalid Time',
                            text: 'Afternoon time in must be after morning time out'
                        });
                        return false;
                    }
                    if (afternoonOut <= afternoonIn) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Invalid Time',
                            text: 'Afternoon time out must be after time in'
                        });
                        return false;
                    }
                }
                return true;
            }
    
            // Add Course Form Submission
            const addCourseForm = document.getElementById('addCourseForm');
            if (addCourseForm) {
                addCourseForm.addEventListener('submit', handleSubmit);
            }
    
            function handleSubmit(e) {
                e.preventDefault();
    
                if (isSubmitting) {
                    return;
                }
    
                if (!validateTimes()) {
                    return;
                }
    
                isSubmitting = true;
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                }
    
                const formData = new FormData(this);
    
                $.ajax({
                    url: this.action,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: response.message || 'Course added successfully',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: response.message || 'Failed to add course'
                            });
                            isSubmitting = false;
                            if (submitBtn) {
                                submitBtn.disabled = false;
                            }
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Something went wrong!';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: errorMessage
                        });
                        isSubmitting = false;
                        if (submitBtn) {
                            submitBtn.disabled = false;
                        }
                    }
                });
            }
    
            // Reset form and submission state when modal is hidden
            $('#addCourseModal').on('hidden.bs.modal', function () {
                isSubmitting = false;
                if (addCourseForm) {
                    addCourseForm.reset();
                    const submitBtn = addCourseForm.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.disabled = false;
                    }
                }
            });
        });

        if (addSectorForm) {
            addSectorForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // Show loading state
                Swal.fire({
                    title: 'Adding Sector...',
                    text: 'Please wait',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });

                const formData = new FormData(this);

                // Send POST request
                fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Close the modal first
                        const modal = document.getElementById('addSectorModal');
                        if (modal) {
                            const bsModal = bootstrap.Modal.getInstance(modal);
                            if (bsModal) {
                                bsModal.hide();
                            }
                        }

                        // Show success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: data.message,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            // Reload the page
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: data.message || 'Something went wrong!'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Something went wrong!'
                    });
                });
            });
        }

        // Reset form when modal is hidden
        const addSectorModal = document.getElementById('addSectorModal');
        if (addSectorModal) {
            addSectorModal.addEventListener('hidden.bs.modal', function() {
                if (addSectorForm) {
                    addSectorForm.reset();
                }
            });
        }
    </script>
    @endpush

    @push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Prevent course link click when clicking dropdown or its items
    document.querySelectorAll('.dropdown').forEach(dropdown => {
        dropdown.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
        });
    });

    // Handle edit course
    document.querySelectorAll('.edit-course').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const courseId = this.dataset.courseId;
            const sectorId = this.dataset.sectorId;
            const courseName = this.dataset.courseName;
            const courseDescription = this.dataset.courseDescription;
            const courseDuration = this.dataset.courseDuration;
            const morningIn = this.dataset.morningIn;
            const morningOut = this.dataset.morningOut;
            const afternoonIn = this.dataset.afternoonIn;
            const afternoonOut = this.dataset.afternoonOut;

            // Populate the edit modal
            document.getElementById('edit_sector_id').value = sectorId;
            document.getElementById('edit_course_name').value = courseName;
            document.getElementById('edit_course_description').value = courseDescription;
            document.getElementById('edit_duration_days').value = courseDuration;
            document.getElementById('edit_morning_in').value = morningIn;
            document.getElementById('edit_morning_out').value = morningOut;
            document.getElementById('edit_afternoon_in').value = afternoonIn;
            document.getElementById('edit_afternoon_out').value = afternoonOut;

            // Update the form action URL
            const editForm = document.getElementById('editCourseForm');
            editForm.action = `{{ url('admin/course') }}/${courseId}`;
        });
    });

    // Form submission handling
    const editCourseForm = document.getElementById('editCourseForm');
    if (editCourseForm) {
        editCourseForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('editCourseModal'));
                    modal.hide();

                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: data.message || 'Course updated successfully',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        // Redirect to the correct URL
                        if (data.redirect) {
                            window.location.href = data.redirect;
                        } else {
                            window.location.reload();
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: data.message || 'Something went wrong'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Something went wrong!'
                });
            });
        });
    }
    // Handle delete course
    document.querySelectorAll('.delete-course').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const courseId = this.dataset.courseId;
            const courseName = this.dataset.courseName;

            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to delete ${courseName}. This action cannot be undone.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading state
                    Swal.fire({
                        title: 'Deleting...',
                        text: 'Please wait',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Get CSRF token
                    const token = document.querySelector('meta[name="csrf-token"]').content;

                    // Create form data
                    const formData = new FormData();
                    formData.append('_token', token);
                    formData.append('_method', 'DELETE');

                    // Send delete request
                    fetch(`/admin/course/${courseId}/batches/${courseId}`, {
                        method: 'POST',
                        body: formData,
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
                                title: 'Deleted!',
                                text: data.message || 'Course deleted successfully',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            throw new Error(data.message || 'Failed to delete course');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: error.message || 'Something went wrong while deleting the course',
                            confirmButtonColor: '#3085d6'
                        });
                    });
                }
            });
        });
    });
});
</script>
@endpush
    
</x-adminlayout>
