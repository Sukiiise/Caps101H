<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register | El Nazareno</title>

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{asset('css/bootstrap.css')}}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- Logo -->
                <div class="text-center mb-4">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="img-fluid" style="max-height: 150px">
                </div>

                <div class="card shadow">
                    <div class="card-body p-4">
                        <h4 class="text-center mb-4">Create an Account</h4>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register.store') }}">
                            @csrf

                            <!-- Name Fields -->
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="lastname" class="form-label">Last Name</label>
                                    <input type="text" class="form-control @error('lastname') is-invalid @enderror" 
                                           id="lastname" name="lastname" value="{{ old('lastname') }}" required>
                                    @error('lastname')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="firstname" class="form-label">First Name</label>
                                    <input type="text" class="form-control @error('firstname') is-invalid @enderror" 
                                           id="firstname" name="firstname" value="{{ old('firstname') }}" required>
                                    @error('firstname')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="middlename" class="form-label">Middle Name/Initial</label>
                                    <input type="text" class="form-control @error('middlename') is-invalid @enderror" 
                                           id="middlename" name="middlename" value="{{ old('middlename') }}">
                                    @error('middlename')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Contact Number -->
                            <div class="mb-3">
                                <label for="contact_number" class="form-label">Contact Number</label>
                                <input type="tel" class="form-control @error('contact_number') is-invalid @enderror" 
                                       id="contact_number" name="contact_number" value="{{ old('contact_number') }}" required>
                                @error('contact_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Address Fields -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="street_number" class="form-label">Street Number</label>
                                    <input type="text" class="form-control @error('street_number') is-invalid @enderror" 
                                           id="street_number" name="street_number" value="{{ old('street_number') }}" required>
                                    @error('street_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="barangay" class="form-label">Barangay</label>
                                    <input type="text" class="form-control @error('barangay') is-invalid @enderror" 
                                           id="barangay" name="barangay" value="{{ old('barangay') }}" required>
                                    @error('barangay')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="city" class="form-label">Municipality/City</label>
                                    <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                           id="city" name="city" value="{{ old('city') }}" required>
                                    @error('city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="province" class="form-label">Province</label>
                                    <input type="text" class="form-control @error('province') is-invalid @enderror" 
                                           id="province" name="province" value="{{ old('province') }}" required>
                                    @error('province')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password" required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                                    <input type="password" class="form-control" 
                                           id="password_confirmation" name="password_confirmation" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input @error('terms') is-invalid @enderror" 
                                           id="terms" name="terms" required>
                                    <label class="form-check-label" for="terms">
                                        I agree to the Terms and Conditions
                                    </label>
                                    @error('terms')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Register</button>
                            </div>
                        </form>

                        <div class="text-center mt-3">
                            <p>Already have an account? <a href="{{ route('login') }}" class="text-decoration-none">Sign in</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript Files -->
    <script src="{{asset('js/bootstrap.bundle.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: "{{ session('success') }}"
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: "{{ session('error') }}"
            });
        </script>
    @endif
</body>
</html>
