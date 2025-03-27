<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>El Nazareno | Sign in</title>

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{asset('css/bootstrap.css')}}">
    <link rel="stylesheet" href="{{asset('css/bootstrap-grid.css')}}">
    <link rel="stylesheet" href="{{asset('css/bootstrap-reboot.css')}}">
    <link rel="stylesheet" href="{{asset('css/bootstrap-utilities.css')}}">
    <link rel="stylesheet" href="{{asset('css/loginline.css')}}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css">

    <style>
        html, body {
            height: 100%;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .footer {
            margin-top: auto;
        }
    </style>
</head>
<body>
    <main class="py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-5">
                    <div class="text-center mb-4">
                        <img src="{{ asset('images/logo.png') }}" alt="El Nazareno Logo" class="img-fluid w-auto" style="max-height: min(180px, 25vh); width: auto;">
                    </div>
                    <div class="card shadow-lg">
                        <div class="card-body p-4">
                            <h4 class="text-center mb-1">Welcome Back</h4>
                            <p class="text-muted text-center mb-4">Sign in to continue</p>

                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('login.post') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email') }}" required autofocus>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password" required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                        <label class="form-check-label" for="remember">Remember me</label>
                                    </div>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn" style="background-color: rgb(179, 68, 68); color: white">
                                        Sign in
                                    </button>
                                </div>
                            </form>
                            <div class="text-center mt-3">
                                <a href="#" class="text-decoration-none">Forgot Password?</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="footer py-3 bg-white border-top">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <small class="text-muted">Copyright &copy; {{ date('Y') }} El Nazareno. All rights reserved.</small>
                </div>
            </div>
        </div>
    </footer>

    <!-- JavaScript Files -->
    <script src="{{asset('js/bootstrap.bundle.js')}}"></script>
    <script src="{{asset('js/bootstrap.esm.js')}}"></script>
    <script src="{{asset('js/bootstrap.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/login.js') }}"></script>

    <!-- Handle session messages -->
    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: "{{ session('error') }}"
                });
            });
        </script>
    @endif

    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: "{{ session('success') }}"
                });
            });
        </script>
    @endif
</body>
</html>
