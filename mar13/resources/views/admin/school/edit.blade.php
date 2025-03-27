<x-adminlayout :icon="$icon" :button="$button">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Edit School</h5>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.school.update', $school->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                        
                            <div class="mb-3">
                                <label class="form-label">School Name</label>
                                <input type="text" 
                                       name="name" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       value="{{ old('name', $school->name) }}" 
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        
                            <div class="mb-3">
                                <label class="form-label">Address</label>
                                <input type="text" 
                                    name="address" 
                                    class="form-control @error('address') is-invalid @enderror" 
                                    value="{{ old('address', $school->address) }}" 
                                    required>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        
                            <div class="mb-3">
                                <label class="form-label">Contact Number</label>
                                <input type="text" 
                                       name="contact_number" 
                                       class="form-control @error('contact_number') is-invalid @enderror" 
                                       value="{{ old('contact_number', $school->contact_number) }}"required>
                                @error('contact_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">School Logo</label>
                                @if($school->logo_path)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $school->logo_path) }}" 
                                             alt="Current Logo" 
                                             class="img-thumbnail" 
                                             style="height: 100px;">
                                    </div>
                                @endif
                                <input type="file" 
                                       name="logo" 
                                       class="form-control @error('logo') is-invalid @enderror" 
                                       accept="image/*">
                                <small class="text-muted">Leave empty to keep current logo</small>
                                @error('logo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">Update School</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .card {
            border: 1px solid rgba(0,0,0,0.08);
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.03);
        }

        .card-header {
            background-color: transparent;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding: 1rem;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0;
        }

        .form-label {
            font-weight: 500;
            color: #2c3e50;
        }

        .btn {
            padding: 0.5rem 1rem;
            font-weight: 500;
        }

        .img-thumbnail {
            border: 1px solid rgba(0,0,0,0.1);
        }
    </style>
</x-adminlayout>
