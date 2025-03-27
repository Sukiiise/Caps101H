<x-adminlayout :icon="$icon" :button="$button">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.school.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">School Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white">
                                            <i class="bi bi-building text-danger"></i>
                                        </span>
                                        <input type="text" 
                                               class="form-control @error('name') is-invalid @enderror" 
                                               id="name"
                                               name="name" 
                                               value="{{ old('name') }}" 
                                               placeholder="Enter school name"
                                               required>
                                    </div>
                                    @error('name')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="contact_number" class="form-label">Contact Number</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white">
                                            <i class="bi bi-telephone text-danger"></i>
                                        </span>
                                        <input type="text" 
                                               class="form-control @error('contact_number') is-invalid @enderror" 
                                               id="contact_number"
                                               name="contact_number" 
                                               value="{{ old('contact_number') }}"
                                               placeholder="Enter contact number">
                                    </div>
                                    @error('contact_number')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white">
                                        <i class="bi bi-geo-alt text-danger"></i>
                                    </span>
                                    <input type="text" 
                                           class="form-control @error('address') is-invalid @enderror" 
                                           id="address"
                                           name="address" 
                                           value="{{ old('address') }}" 
                                           placeholder="Enter school address"
                                           required>
                                </div>
                                @error('address')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="logo" class="form-label">School Logo</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white">
                                        <i class="bi bi-image text-danger"></i>
                                    </span>
                                    <input type="file" 
                                           class="form-control @error('logo') is-invalid @enderror" 
                                           id="logo"
                                           name="logo" 
                                           accept="image/*">
                                </div>
                                <small class="text-muted">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Accepted formats: JPG, PNG, GIF (Max size: 2MB)
                                </small>
                                @error('logo')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-md btn-outline-danger w-100">
                                        <i class="bi bi-plus-circle me-1"></i>Create 
                                    </button>
                                </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-adminlayout>
