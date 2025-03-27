<x-adminlayout :icon="$icon" :button="$button" :user="auth()->user()">
    <div class="container-fluid">
        <div class="row">
            <!-- Profile Information Card -->
            <div class="col-xl-4 col-lg-5 mb-4">
                <div class="card shadow h-100">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold" style="color: var(--primary-color)">Profile Information</h6>
                    </div>
                    <div class="card-body text-center">
                        <div class="mb-4">
                            <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" 
                                 style="width: 100px; height: 100px; background-color: var(--primary-light);">
                                <i class="bi bi-person-circle" style="font-size: 3rem; color: var(--primary-color)"></i>
                            </div>
                            <h5 class="font-weight-bold" style="color: var(--primary-color)">{{ auth()->user()->name }}</h5>
                            <p class="text-muted mb-2">{{ auth()->user()->email }}</p>
                            <span class="badge" style="background-color: var(--pastel-maroon)">Administrator</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Update Forms -->
            <div class="col-xl-8 col-lg-7">
                <!-- Update Profile Information -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold" style="color: var(--primary-color)">Update Profile Information</h6>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
                            @csrf
                            @method('patch')
                            
                            <div class="mb-3">
                                <label for="name" class="form-label" style="color: var(--primary-color)">Name</label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       value="{{ old('name', auth()->user()->name) }}" required>
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label" style="color: var(--primary-color)">Email</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="{{ old('email', auth()->user()->email) }}" required>
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <button type="submit" class="btn" 
                                    style="background-color: var(--primary-color); color: white;">
                                <i class="bi bi-save me-2"></i>Save Changes
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Update Password -->
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold" style="color: var(--primary-color)">Change Password</h6>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('password.update') }}" class="space-y-6">
                            @csrf
                            @method('put')

                            <div class="mb-3">
                                <label for="update_password_current_password" class="form-label" style="color: var(--primary-color)">Current Password</label>
                                <input type="password" class="form-control" id="update_password_current_password" name="current_password" autocomplete="current-password">
                                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                            </div>

                            <div class="mb-3">
                                <label for="update_password_password" class="form-label" style="color: var(--primary-color)">New Password</label>
                                <input type="password" class="form-control" id="update_password_password" name="password" autocomplete="new-password">
                                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                            </div>

                            <div class="mb-3">
                                <label for="update_password_password_confirmation" class="form-label" style="color: var(--primary-color)">Confirm Password</label>
                                <input type="password" class="form-control" id="update_password_password_confirmation" name="password_confirmation" autocomplete="new-password">
                                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                            </div>

                            <button type="submit" class="btn" style="background-color: var(--primary-color); color: white;">
                                <i class="bi bi-key me-2"></i>Update Password
                                @if (session('status') === 'password-updated')
                                    <p
                                        x-data="{ show: true }"
                                        x-show="show"
                                        x-transition
                                        x-init="setTimeout(() => show = false, 2000)"
                                        class="text-sm text-gray-600"
                                    >   <i class="bi bi-key me-2"></i>Update Password
                                    </p>
                                @endif
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-adminlayout>
