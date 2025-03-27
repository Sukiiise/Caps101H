<x-adminlayout :icon="$icon" :button="$button">
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Create New User</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.register-user') }}" method="POST" id="createUserForm">
                    @csrf
                    
                    <!-- User Type Selection -->
                    <div class="form-group mb-3">
                        <label for="usertype" class="form-label">User Type</label>
                        <select class="form-select @error('usertype') is-invalid @enderror" 
                                id="usertype" 
                                name="usertype" 
                                required>
                            <option value="" selected disabled>Select User Type</option>
                            <option value="admin" {{ old('usertype') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="staff" {{ old('usertype') == 'staff' ? 'selected' : '' }}>Staff</option>
                        </select>
                        @error('usertype')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Name Fields -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="lastname" class="form-label">Last Name</label>
                                <input type="text" 
                                       class="form-control @error('lastname') is-invalid @enderror"
                                       id="lastname" 
                                       name="lastname" 
                                       value="{{ old('lastname') }}" 
                                       required>
                                @error('lastname')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="firstname" class="form-label">First Name</label>
                                <input type="text" 
                                       class="form-control @error('firstname') is-invalid @enderror"
                                       id="firstname" 
                                       name="firstname" 
                                       value="{{ old('firstname') }}" 
                                       required>
                                @error('firstname')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="middlename" class="form-label">Middle Name/Initial</label>
                                <input type="text" 
                                       class="form-control @error('middlename') is-invalid @enderror"
                                       id="middlename" 
                                       name="middlename" 
                                       value="{{ old('middlename') }}">
                                @error('middlename')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Contact Number -->
                    <div class="form-group mb-3">
                        <label for="contact_number" class="form-label">Contact Number</label>
                        <input type="tel" 
                               class="form-control @error('contact_number') is-invalid @enderror"
                               id="contact_number" 
                               name="contact_number" 
                               value="{{ old('contact_number') }}" 
                               required>
                        @error('contact_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Address Fields -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="street_number" class="form-label">Street Number</label>
                                <input type="text" 
                                       class="form-control @error('street_number') is-invalid @enderror"
                                       id="street_number" 
                                       name="street_number" 
                                       value="{{ old('street_number') }}" 
                                       required>
                                @error('street_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="barangay" class="form-label">Barangay</label>
                                <input type="text" 
                                       class="form-control @error('barangay') is-invalid @enderror"
                                       id="barangay" 
                                       name="barangay" 
                                       value="{{ old('barangay') }}" 
                                       required>
                                @error('barangay')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="city" class="form-label">Municipality/City</label>
                                <input type="text" 
                                       class="form-control @error('city') is-invalid @enderror"
                                       id="city" 
                                       name="city" 
                                       value="{{ old('city') }}" 
                                       required>
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="province" class="form-label">Province</label>
                                <input type="text" 
                                       class="form-control @error('province') is-invalid @enderror"
                                       id="province" 
                                       name="province" 
                                       value="{{ old('province') }}" 
                                       required>
                                @error('province')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="form-group mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" 
                               class="form-control @error('email') is-invalid @enderror"
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror"
                                       id="password" 
                                       name="password" 
                                       required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <input type="password" 
                                       class="form-control"
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       required>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-3 text-end">
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="bi bi-person-plus-fill me-2"></i>Create User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
</x-adminlayout>
