<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\SectorController;
use Illuminate\Support\Facades\Auth;


// Root route
Route::get('/', function () {
    if (Auth::check()) {
        if (Auth::user()->usertype === 'admin') {
            return redirect('admin.dashboard');
        }
        Auth::logout();
    }
    return redirect('/login');
});
// Login routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [DashboardController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [DashboardController::class, 'login'])->name('login.post');
});

// Register Routes
Route::get('/register', [RegisteredUserController::class, 'create'])->name('register.user');
Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.store');


Route::middleware(['auth', 'admin'])->group(function () {
    // Dashboard Routes
    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');

    // Admin Routes Group
    Route::prefix('admin')->name('admin.')->group(function () {
        // User Management Routes
        Route::get('/create-user', [DashboardController::class, 'createUser'])->name('createuser');
        Route::post('/register-user', [DashboardController::class, 'registerUser'])->name('register-user');
        Route::get('/admin/users', [DashboardController::class, 'viewUsers'])->name('users');
        Route::put('/admin/users/{user}/update', [DashboardController::class, 'updateUser'])->name('updateuser');
        Route::delete('/admin/users/{user}/delete', [DashboardController::class, 'deleteUser'])->name('deleteuser');
        Route::get('/user-logs', [DashboardController::class, 'userlogs'])->name('userlogs');
        Route::get('/user-reports', [DashboardController::class, 'userreports'])->name('reports');
        Route::get('/schools/{school}', [CourseController::class, 'show'])->name('show');
        // Students 
        Route::put('/students/{student}', [DashboardController::class, 'updateStudent'])->name('students.update');
        Route::delete('/students/{student}', [DashboardController::class, 'deleteStudent'])->name('students.destroy');
        // Sector Routes
        Route::prefix('sectors')->name('sectors.')->group(function () {
            Route::get('/', [SectorController::class, 'index'])->name('index');
            Route::get('/create', [SectorController::class, 'create'])->name('create');
            Route::post('/', [SectorController::class, 'store'])->name('store');
            Route::get('/{sector}', [SectorController::class, 'show'])->name('show');
            Route::get('/{sector}/edit', [SectorController::class, 'edit'])->name('edit');
            Route::put('/{sector}', [SectorController::class, 'update'])->name('update');
            Route::delete('/{sector}', [SectorController::class, 'destroy'])->name('destroy');
        });

        // Events Routes
        Route::prefix('events')->name('events.')->group(function () {
            Route::get('/', [DashboardController::class, 'events'])->name('index');
            Route::post('/', [DashboardController::class, 'storeEvent'])->name('store');
            Route::get('/create', [DashboardController::class, 'createEvent'])->name('create');
            Route::get('/{event}/edit', [DashboardController::class, 'editEvent'])->name('edit');
            Route::put('/{event}', [DashboardController::class, 'updateEvent'])->name('update');
            Route::delete('/{event}', [DashboardController::class, 'destroyEvent'])->name('destroy');
        });

        // Profile Routes
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', [DashboardController::class, 'profile'])->name('index');
            Route::get('/edit', [DashboardController::class, 'editProfile'])->name('edit');
            Route::post('/update', [DashboardController::class, 'updateProfile'])->name('update');
        });

        // School Management
        Route::prefix('school')->name('school.')->group(function () {
            Route::get('/create', [DashboardController::class, 'createSchool'])->name('create');
            Route::post('/', [DashboardController::class, 'storeSchool'])->name('store');
            Route::get('/{school}/edit', [DashboardController::class, 'editSchool'])->name('edit');
            Route::put('/{school}', [DashboardController::class, 'update'])->name('update');
            Route::delete('/{school}', [DashboardController::class, 'destroySchool'])->name('destroy');
            Route::get('/show/{school}', [DashboardController::class, 'showSchool'])->name('show');
            Route::get('/{school}/students', [DashboardController::class, 'students'])->name('students');

            // Staff management routes
            Route::post('/{school}/staff', [DashboardController::class, 'assignStaff'])->name('assign-staff');
            Route::put('/{school}/staff/{staff}', [DashboardController::class, 'updateStaffPosition'])->name('update-staff');
            Route::delete('/{school}/staff/{staff}', [DashboardController::class, 'removeStaff'])->name('remove-staff');
        });
        // Course Routes
        Route::prefix('course')->name('course.')->group(function () {
            Route::get('/', [CourseController::class, 'index'])->name('index');
            Route::get('/create', [CourseController::class, 'create'])->name('create');
            Route::post('/', [CourseController::class, 'store'])->name('store');
            Route::get('/{course}', [CourseController::class, 'show'])->name('show'); // This was missing
            Route::get('/{course}/edit', [CourseController::class, 'edit'])->name('edit');
            Route::put('/{course}', [CourseController::class, 'update'])->name('update');
            Route::delete('/{course}', [CourseController::class, 'destroy'])->name('destroy');
        
            // Batch Routes
            Route::get('/{course}/batches', [CourseController::class, 'showBatches'])->name('batches.index');
            Route::get('/{course}/batches/create', [CourseController::class, 'createBatch'])->name('batches.create');
            Route::post('/{course}/batches', [CourseController::class, 'storeBatch'])->name('batches.store');
            Route::get('/{course}/batches/{batch}', [CourseController::class, 'showBatch'])->name('batches.show');
            Route::get('/{course}/batches/{batch}/edit', [CourseController::class, 'editBatch'])->name('batches.edit');
            Route::put('/{course}/batches/{batch}', [CourseController::class, 'updateBatch'])->name('batches.update');
            Route::delete('/{course}/batches/{batch}', [CourseController::class, 'destroyBatch'])->name('batches.destroy');
            Route::post('/batches/{batch}/enroll', [CourseController::class, 'enrollStudent'])->name('batches.enroll');

        });
        
    });
});


Route::post('/logout', [DashboardController::class, 'logout'])->name('logout');

// Fallback route
Route::fallback(function () {
    return Auth::check() && Auth::user()->usertype === 'admin'
        ? redirect()->route('admin.dashboard')->with('error', 'Page not found.')
        : redirect()->route('login')->with('error', 'Page not found.');
});
