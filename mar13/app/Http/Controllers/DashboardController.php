<?php

namespace App\Http\Controllers;

use App\Models\BatchEnrollment;
use App\Models\School;
use App\Models\User;
use App\Models\Event;
use App\Models\Course;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class DashboardController extends BaseController
{

public function storeEvent(Request $request)
{
    try {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'time' => 'required',
            'description' => 'nullable|string|max:1000',
        ]);
        $event = Event::create($validated);
        return response()->json([
            'success' => true,
            'message' => 'Event created successfully!'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to create event: ' . $e->getMessage()
        ], 500);
    }
}

    public function showLoginForm()
    {
        if (Auth::check()) {
            if (Auth::user()->usertype === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            Auth::logout();
        }
        return view('login');
    }
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $credentials['email'])->first();
        
        if (!$user || $user->usertype !== 'admin') {
            return redirect()->route('login')
                ->with('error', 'Unauthorized access. Admin privileges required.')
                ->withInput($request->only('email'));
        }

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->route('login')
            ->withErrors(['email' => 'Invalid credentials.'])
            ->withInput($request->only('email'));
    }
    
    public function __construct()
    {
        $this->middleware('guest')->only(['showLoginForm', 'login']);
        $this->middleware(['auth', 'admin'])->only('admin');
    }
    public function getStudentStatistics(Request $request)
    {
        try {
            $query = User::whereHas('schools', function($q) {
                $q->where('school_user.role', 'student');
            });

            if ($request->has('course_id')) {
                $query->where('course_id', $request->course_id);
            }

            $statistics = [
                'competent' => (clone $query)->where('status', 'competent')->count(),
                'incompetent' => (clone $query)->where('status', 'incompetent')->count(),
                'dropped' => (clone $query)->where('status', 'dropped')->count()
            ];

            return response()->json([
                'success' => true,
                'data' => $statistics
            ]);

        } catch (\Exception $e) {
            Log::error('Statistics Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching statistics'
            ], 500);
        }
    }

    public function admin()
    {
        $icon = '<i class="bi bi-speedometer2 me-2"></i> Dashboard';
        $button = '';
    
        // Paginate schools with a unique parameter name 'school-page'
        $schools = School::withCount([
            'students' => function($query) {
                $query->where('school_user.role', 'student');
            }
        ])
        ->orderBy('name', 'asc')
        ->paginate(4, ['*'], 'school-page'); // Add custom page name
    
        // Paginate events with a unique parameter name 'event-page'
        $events = Event::query()
            ->where('date', '>=', now()->startOfDay())
            ->orderBy('date', 'asc')
            ->orderBy('time', 'asc')
            ->paginate(3, ['*'], 'event-page'); // Add custom page name
    
        // Get counts for statistics
        $statistics = [
            'total_users' => User::count(),
            'total_schools' => School::count(),
            'total_events' => Event::count(),
            'total_courses' => Course::count(),
        ];
    
        return view('admin.dashboard', compact('icon', 'button', 'events', 'schools', 'statistics'));
    }
    
    private function getEventDistribution()
    {
        return Event::select('type', DB::raw('count(*) as count'))
            ->groupBy('type')
            ->pluck('count', 'type')
            ->toArray();
    }
    private function getCoursePopularity()
    {
        return Course::withCount('users')
            ->orderByDesc('users_count')
            ->take(5)
            ->get()
            ->pluck('users_count', 'name')
            ->toArray();
    }
    private function getMonthlyUserRegistrations()
    {
        return User::select(
            DB::raw('COUNT(*) as count'),
            DB::raw('MONTH(created_at) as month')
        )
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();
    }
    private function loadDashboardData()
    {
        try {
            $icon = '<i class="bi bi-speedometer2 me-2"></i> Dashboard';
            $button = '';

            // Get all courses for filter
            $courses = Course::all();

            // Fetch upcoming events
            $events = Event::where('date', '>=', now())
                        ->orderBy('date', 'asc')
                        ->take(5)
                        ->get();

            // Get student statistics
            $studentStats = [
                'competent' => User::whereHas('schools', function($q) {
                    $q->where('school_user.role', 'student');
                })->where('status', 'competent')->count(),
                
                'incompetent' => User::whereHas('schools', function($q) {
                    $q->where('school_user.role', 'student');
                })->where('status', 'incompetent')->count(),
                
                'dropped' => User::whereHas('schools', function($q) {
                    $q->where('school_user.role', 'student');
                })->where('status', 'dropped')->count()
            ];

            // Get schools with student counts
            $schools = School::withCount([
                'students' => function($query) {
                    $query->where('school_user.role', 'student');
                }
            ])->get();

            // Calculate totals
            $statistics = [
                'total_schools' => School::count(),
                'total_students' => User::whereHas('schools', function($q) {
                    $q->where('school_user.role', 'student');
                })->count(),
                'total_courses' => Course::count(),
                'upcoming_events' => $events->count(),
                'student_stats' => $studentStats
            ];

            return view('admin.dashboard', compact(
                'icon',
                'button',
                'courses',
                'events',
                'schools',
                'statistics'
            ));

        } catch (\Exception $e) {
            Log::error('Dashboard Error: ' . $e->getMessage());
            return back()->with('error', 'Error loading dashboard: ' . $e->getMessage());
        }
    }

    public function createUser()
    {
        $icon = '<i class="bi bi-person-plus-fill me-2"></i> Create User';
        $button = '';
        $schools = School::orderBy('name')->get(); // Add this line to get schools
        return view('admin.createuser', compact('icon', 'button', 'schools'));
    }
    
    public function viewUsers(Request $request)
    {
        $query = User::query();
    
        // Handle search
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('firstname', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('lastname', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('email', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('contact_number', 'LIKE', "%{$searchTerm}%");
            });
        }
    
        // Handle usertype filter
        if ($request->has('usertype') && $request->usertype != '') {
            $query->where('usertype', $request->usertype);
        }
    
        // Handle sorting
        $sortField = $request->get('sort', 'created_at'); // default sort by created_at
        $sortDirection = $request->get('direction', 'desc'); // default direction is descending
    
        $query->orderBy($sortField, $sortDirection);
    
        $users = $query->paginate(8)->withQueryString();
    
        return view('admin.viewuser', [
            'users' => $users,
            'icon' => 'bi bi-people-fill',
            'button' => 'View Users',
            'selected_usertype' => $request->usertype,
            'search_term' => $request->search,
            'sort_field' => $sortField,
            'sort_direction' => $sortDirection
        ]);
    }
    
    public function updateUser(Request $request, User $user)
    {
        try {
            $validated = $request->validate([
                'firstname' => ['required', 'string', 'max:255'],
                'lastname' => ['required', 'string', 'max:255'],
                'middlename' => ['nullable', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
                'contact_number' => ['required', 'string', 'max:20'],
                'street_number' => ['required', 'string', 'max:255'],
                'barangay' => ['required', 'string', 'max:255'],
                'city' => ['required', 'string', 'max:255'],
                'province' => ['required', 'string', 'max:255'],
                'usertype' => ['required', 'in:admin,staff'],
            ]);

            $user->update($validated);

            return redirect()->route('admin.users')
                           ->with('success', 'User updated successfully');
        } catch (\Exception $e) {
            Log::error('User update failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to update user');
        }
    }

    public function deleteUser(User $user)
    {
        try {
            $user->delete();
            return redirect()->route('admin.users')
                           ->with('success', 'User deleted successfully');
        } catch (\Exception $e) {
            Log::error('User deletion failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete user');
        }
    }
    
    public function userlogs()
    {
        $icon = '<i class="bi bi-clock-history me-2"></i> Activity Logs';
        $button = '';
        return view('admin.userlogs', compact('icon', 'button'));
    }
    public function userreports()
    {
        $icon = '<i class="bi bi-card-checklist me-2"></i> Reports';
        $button = '<button type="button" class="btn btn-custom" style="color: white;"><i class="bi bi-download me-2"></i>Export Report</button>';
        return view('admin.reports', compact('icon', 'button'));
    }
    public function profile()
    {
        $icon = '<i class="bi bi-person me-2"></i> Profile';
        $button = '';
        return view('admin.profile', compact('icon', 'button'));
    }
    public function editProfile()
    {
        $icon = '<i class="bi bi-person-gear me-2"></i> Edit Profile';
        $button = '';
        return view('admin.profile.edit', compact('icon', 'button'));
    }
    public function updateProfile(Request $request)
    {
        // Your update logic here
        return redirect()->route('admin.profile.index')->with('success', 'Profile updated successfully');
    }

    // School Management Methods
    public function storeSchool(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'street_number' => 'required|string|max:255',
            'barangay' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'contact_number' => 'required|regex:/^[0-9]+$/|min:7|max:15',
            'logo_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);
    
        try {
            $data = $request->except('logo_path');
            
            if ($request->hasFile('logo_path')) {
                $path = $request->file('logo_path')->store('school-logos', 'images');
                $data['logo_path'] = 'images/' . $path;
            }
    
            $school = School::create($data);
    
            return response()->json([
                'success' => true,
                'message' => 'School added successfully!',
                'school' => $school
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add school: ' . $e->getMessage()
            ], 500);
        }
    }
    
    

    public function createSchool()
    {
        $icon = '<i class="bi bi-building-add me-2"></i> Add School';
        $button = '';
        return view('admin.school.create', compact('icon', 'button'));
    }

    public function editSchool(School $school)
    {
        $icon = '<i class="bi bi-building me-2"></i> Edit School';
        $button = '';
        
        return view('admin.school.edit', compact('school', 'icon', 'button'));
    }

    public function update(Request $request, School $school)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'address' => 'required|string',
                'contact_number' => 'required|string',
                'logo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            DB::beginTransaction();

            if ($request->hasFile('logo_path')) {
                // Delete old logo if exists
                if ($school->logo_path) {
                    $oldLogoPath = public_path($school->logo_path);
                    if (file_exists($oldLogoPath)) {
                        unlink($oldLogoPath);
                    }
                }

                $file = $request->file('logo_path');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                
                // Move the file directly to public/images directory
                $file->move(public_path('images'), $filename);
                
                // Update the path
                $school->logo_path = 'images/' . $filename;
            }

            $school->name = $validated['name'];
            $school->address = $validated['address'];
            $school->contact_number = $validated['contact_number'];
            $school->save();

            DB::commit();

            return redirect()
                ->route('admin.school')
                ->with('success', 'School updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('School update failed: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update school. Please try again.');
        }
    }

    public function destroySchool(School $school)
    {
        try {
            DB::beginTransaction();

            // Delete school logo if exists
            if ($school->logo_path) {
                Storage::disk('public')->delete($school->logo_path);
            }

            $school->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'School deleted successfully!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('School deletion failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete school. Please try again.'
            ], 500);
        }
    }

    public function showSchool(School $school)
    {
        $icon = '<i class="bi bi-building me-2"></i> School Details'; // Add this line
        $button = '<a href="' . route('admin.dashboard') . '" class="btn btn-custom" style="color: white;"><i class="bi bi-arrow-left"></i> Back to Schools</a>';
        $availableStaff = User::where('usertype', 'staff')->whereDoesntHave('schools', function($query) use ($school) {
            $query->where('school_id', $school->id);
        })->get();
        $courses = Course::where('school_id', $school->id)->paginate(9);
        return view('showschool', compact('school', 'availableStaff', 'icon', 'button', 'courses'));
    }

    public function students(School $school, Request $request)
    {
        $query = $school->students();
    
        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
    
        // Status filter
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
    
        // Sorting
        switch ($request->sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'name':
                $query->orderBy('name');
                break;
            default:
                $query->latest();
        }
    
        $students = $query->paginate(10);
    
        return view('admin.school.students', [
            'school' => $school,
            'students' => $students,
            'icon' => 'bi bi-people',
            'button' => [
                'text' => 'Back to School',
                'route' => route('showschool', $school->id)
            ]
        ]);
    }
    
    public function registerUser(Request $request)
    {
        try {
            $validated = $request->validate([
                'usertype' => ['required', 'in:admin,staff'],
                'firstname' => ['required', 'string', 'max:255'],
                'lastname' => ['required', 'string', 'max:255'],
                'middlename' => ['nullable', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
                'contact_number' => ['required', 'string', 'max:20'],
                'street_number' => ['required', 'string', 'max:255'],
                'barangay' => ['required', 'string', 'max:255'],
                'city' => ['required', 'string', 'max:255'],
                'province' => ['required', 'string', 'max:255'],
            ]);
    
            $user = User::create([
                'firstname' => $validated['firstname'],
                'lastname' => $validated['lastname'],
                'middlename' => $validated['middlename'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'contact_number' => $validated['contact_number'],
                'street_number' => $validated['street_number'],
                'barangay' => $validated['barangay'],
                'city' => $validated['city'],
                'province' => $validated['province'],
                'usertype' => $validated['usertype'],
            ]);
    
            return response()->json([
                'success' => true,
                'message' => 'User created successfully!'
            ]);
    
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
    
        } catch (\Exception $e) {
            Log::error('User creation failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create user. Please try again.'
            ], 500);
        }
    }
    
    public function assignStaff(Request $request, School $school)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'position' => 'required|string',
            'department' => 'nullable|string',
            'employee_id' => 'nullable|string|unique:staff,employee_id',
            'date_hired' => 'nullable|date',
            'qualifications' => 'nullable|string',
            'responsibilities' => 'nullable|string',
        ]);
    
        try {
            Staff::create([
                'user_id' => $request->user_id,
                'school_id' => $school->id,
                'position' => $request->position,
                'department' => $request->department,
                'employee_id' => $request->employee_id,
                'date_hired' => $request->date_hired,
                'qualifications' => $request->qualifications,
                'responsibilities' => $request->responsibilities,
                'employment_status' => 'active',
                'is_active' => true
            ]);
    
            return redirect()->back()->with('success', 'Staff assigned successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to assign staff: ' . $e->getMessage());
        }
    }
    
    
    public function updateStaffPosition(Request $request, School $school, Staff $staff)
    {
        $validated = $request->validate([
            'position' => 'required|string|max:255',
            'department' => 'nullable|string|max:255',
            'qualifications' => 'nullable|string',
            'responsibilities' => 'nullable|string'
        ]);
    
        try {
            $staff->update($validated);
            return response()->json([
                'success' => true,
                'message' => 'Staff position updated successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating staff position.'
            ], 422);
        }
    }
    
    public function removeStaff(School $school, Staff $staff)
    {
        try {
            $staff->deactivate();
            
            // If user has no other active staff positions, update role
            if (!Staff::where('user_id', $staff->user_id)
                      ->where('is_active', true)
                      ->exists()) {
            User::where('id', $staff->user_id)->update(['usertype' => 'user']);
            }
    
            return response()->json([
                'success' => true,
                'message' => 'Staff member removed successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error removing staff member.'
            ], 422);
        }
    }

    public function updateStudent(Request $request, User $student)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'gender' => 'required|in:Male,Female',
            'contact_number' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $student->id,
            'address' => 'required|string'
        ]);
    
        $student->update([
            'firstname' => $validated['first_name'],
            'lastname' => $validated['last_name'],
            'middlename' => $validated['middle_name'],
            'gender' => $validated['gender'],
            'contact_number' => $validated['contact_number'],
            'email' => $validated['email'],
            'address' => $validated['address']
        ]);
    
        return redirect()->back()->with('success', 'Student information updated successfully');
    }
    
    public function deleteStudent(User $student)
    {
        // Remove student from batch but don't delete the user
        BatchEnrollment::where('user_id', $student->id)->delete();
    
        return redirect()->back()->with('success', 'Student removed from batch successfully');
    }
    

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')
            ->with('success', 'Successfully logged out.');
    }
}
