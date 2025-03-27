<?php

namespace App\Http\Controllers;

use App\Models\BatchEnrollment;
use App\Models\Course;
use App\Models\CourseBatch;
use App\Models\School;
use App\Models\Sector;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Batch;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with(['sector', 'school'])->paginate(10);
        $sectors = Sector::all();
        $icon = 'bi bi-book';
        $button = [
            'text' => 'Add New Course',
            'route' => route('admin.courses.create')
        ];
        return view('admin.course.index', compact('courses', 'sectors', 'icon', 'button'));
    }

    public function create()
    {
        $schools = School::all();
        $sectors = Sector::all();
        $icon = 'bi bi-book';
        $button = [
            'text' => 'Back to Courses',
            'route' => route('admin.course.index')
        ];
        return view('admin.courses.create', compact('schools', 'sectors', 'icon', 'button'));
    }
    
    public function store(Request $request)
    {
        // Validate all form inputs
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'duration_days' => 'required|integer|min:1',
                'school_id' => 'required|exists:schools,id',
                'sector_id' => 'required|exists:sectors,id',
                'morning_in' => 'required|date_format:H:i',
                'morning_out' => 'required|date_format:H:i|after:morning_in',
                'afternoon_in' => 'required|date_format:H:i|after:morning_out',
                'afternoon_out' => 'required|date_format:H:i|after:afternoon_in',
            ]);
    
            // Check for existing course
            $existingCourse = Course::where('name', $validated['name'])
                ->where('school_id', $validated['school_id'])
                ->first();
    
            if ($existingCourse) {
                return response()->json([
                    'success' => false,
                    'message' => 'A course with this name already exists in this school'
                ], 422);
            }
    
            DB::beginTransaction();
    
            // Create the course with all validated data
            $course = Course::create([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'duration_days' => $validated['duration_days'],
                'school_id' => $validated['school_id'],
                'sector_id' => $validated['sector_id'],
                'morning_schedule' => [
                    'in' => $validated['morning_in'],
                    'out' => $validated['morning_out']
                ],
                'afternoon_schedule' => [
                    'in' => $validated['afternoon_in'],
                    'out' => $validated['afternoon_out']
                ],
            ]);
    
            DB::commit();
    
            return response()->json([
                'success' => true,
                'message' => 'Course added successfully'
            ]);
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Course creation failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to add course: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function show(Course $course): View
    {
        $sectors = Sector::all();
        $course->load(['sector', 'school', 'batches']);
        $icon = 'bi bi-book';
        $button = [
            'text' => 'Back to Courses',
            'route' => route('admin.course.index')
        ];
        return view('admin.course.show', compact('course', 'sectors', 'icon', 'button'));
    }

    public function edit(Course $course)
    {
        $schools = School::all();
        $sectors = Sector::all();
        $icon = 'bi bi-book';
        $button = [
            'text' => 'Back to Course',
            'route' => route('admin.course.show', $course)
        ];
        return view('admin.courses.edit', compact('course', 'schools', 'sectors', 'icon', 'button'));
    }

    public function update(Request $request, Course $course)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'duration_days' => 'required|integer|min:1',
                'school_id' => 'required|exists:schools,id',
                'sector_id' => 'required|exists:sectors,id',
                'morning_in' => 'required|date_format:H:i',
                'morning_out' => 'required|date_format:H:i|after:morning_in',
                'afternoon_in' => 'required|date_format:H:i|after:morning_out',
                'afternoon_out' => 'required|date_format:H:i|after:afternoon_in',
            ]);
    
            // Check for existing course with same name in same school (excluding current course)
            $existingCourse = Course::where('name', $validated['name'])
                ->where('school_id', $validated['school_id'])
                ->where('id', '!=', $course->id)
                ->first();
    
            if ($existingCourse) {
                return response()->json([
                    'success' => false,
                    'message' => 'A course with this name already exists in this school'
                ], 422);
            }
    
            DB::beginTransaction();
    
            $course->update([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'duration_days' => $validated['duration_days'],
                'school_id' => $validated['school_id'],
                'sector_id' => $validated['sector_id'],
                'morning_schedule' => [
                    'in' => $validated['morning_in'],
                    'out' => $validated['morning_out']
                ],
                'afternoon_schedule' => [
                    'in' => $validated['afternoon_in'],
                    'out' => $validated['afternoon_out']
                ],
            ]);
    
            DB::commit();
    
            return response()->json([
                'success' => true,
                'message' => 'Course updated successfully'
            ]);
    
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Course update failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update course: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function destroy(Course $course, CourseBatch $batch)
    {
        try {
            if ($batch->enrollments()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete batch with existing enrollments'
                ]);
            }

            $batch->delete();

            return response()->json([
                'success' => true,
                'message' => 'Batch deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete batch'
            ]);
        }
    }

    public function showBatches(Course $course)
    {
        $batches = $course->batches()->paginate(10);
        $sectors = Sector::all();
        $icon = 'bi bi-collection';
        $button = [
            'text' => 'Back to Course',
            'route' => route('admin.course.show', $course)
        ];
        return view('admin.courses.batches.index', compact('course', 'batches', 'sectors', 'icon', 'button'));
    }

    public function createBatch(Course $course)
    {
        $sectors = Sector::all();
        $icon = 'bi bi-collection';
        $button = [
            'text' => 'Back to Batches',
            'route' => route('admin.course.batches.index', $course)
        ];
        return view('admin.courses.batches.create', compact('course', 'sectors', 'icon', 'button'));
    }

    public function storeBatch(Request $request, Course $course)
    {
        try {
            $validated = $request->validate([
                'batch_name' => 'required|string|max:255',
                'start_date' => 'required|date|after_or_equal:today',
                'max_students' => 'required|integer|min:1',
            ]);
    
            // Calculate end date based on course duration
            $endDate = Carbon::parse($validated['start_date'])
                ->addDays($course->duration_days - 1);
    
            DB::beginTransaction();
    
            $batch = $course->batches()->create([
                'batch_name' => $validated['batch_name'],
                'start_date' => $validated['start_date'],
                'end_date' => $endDate,
                'max_students' => $validated['max_students']
            ]);
    
            DB::commit();
    
            return response()->json([
                'success' => true,
                'message' => 'Batch created successfully'
            ]);
    
        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Batch creation failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create batch'
            ], 500);
        }
    }
    
    public function showBatch(Course $course, CourseBatch $batch)
    {
        // Get enrollments with user data
        $enrollments = $batch->enrollments()->with('user')->get()->map(function($enrollment) {
            return (object)[
                'id' => $enrollment->id,
                'lastname' => $enrollment->user->lastname,
                'firstname' => $enrollment->user->firstname,
                'middlename' => $enrollment->user->middlename,
                'email' => $enrollment->user->email,
                'contact_number' => $enrollment->user->contact_number,
                'created_at' => $enrollment->created_at,
                'status' => $enrollment->status
            ];
        });

        // Get the school from the course relationship
        $school = $course->school;
        // Get sectors specific to this school
        $sectors = Sector::where('school_id', $school->id)->get();
        
        $icon = 'bi bi-collection';
        $button = [
            'text' => 'Back to Batches',
            'route' => route('admin.course.batches.index', $course)
        ];

        return view('admin.courses.batches.show', compact(
            'course',
            'batch',
            'enrollments',
            'sectors',
            'icon',
            'button',
            'school'
        ));
    }
    
    public function editBatch(Request $request, Course $course, CourseBatch $batch)
    {
        try {
            $validated = $request->validate([
                'batch_name' => 'required|string|max:255',
                'start_date' => 'required|date',
                'max_students' => 'required|integer|min:' . $batch->enrollments()->count(),
            ]);
    
            // Check if the batch exists and belongs to the course
            if ($batch->course_id !== $course->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Batch does not belong to this course'
                ], 404);
            }
    
            // Calculate end date based on course duration
            $endDate = Carbon::parse($validated['start_date'])
                ->addDays($course->duration_days - 1);
    
            DB::beginTransaction();
    
            $batch->update([
                'batch_name' => $validated['batch_name'],
                'start_date' => $validated['start_date'],
                'end_date' => $endDate,
                'max_students' => $validated['max_students']
            ]);
    
            DB::commit();
    
            return response()->json([
                'success' => true,
                'message' => 'Batch updated successfully'
            ]);
    
        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Batch update failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update batch'
            ], 500);
        }
    }
    
    public function updateBatch(Request $request, Course $course, CourseBatch $batch)
    {
        try {
            $validated = $request->validate([
                'batch_name' => 'required|string|max:255',
                'start_date' => 'required|date',
                'max_students' => 'required|integer|min:' . $batch->enrollments()->count(),
            ]);
    
            // Calculate end date based on course duration
            $endDate = Carbon::parse($validated['start_date'])
                ->addDays($course->duration_days - 1);
    
            DB::beginTransaction();
    
            $batch->update([
                'batch_name' => $validated['batch_name'],
                'start_date' => $validated['start_date'],
                'end_date' => $endDate,
                'max_students' => $validated['max_students']
            ]);
    
            DB::commit();
    
            return response()->json([
                'success' => true,
                'message' => 'Batch updated successfully'
            ]);
    
        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Batch update failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update batch'
            ], 500);
        }
    }

    public function destroyBatch(Course $course)
    {
        try {
            DB::beginTransaction();
    
            // Optional: Check if the course has any batches
            if ($course->batches()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete course with existing batches'
                ], 422);
            }
    
            // Delete the course
            $course->delete();
    
            DB::commit();
    
            return response()->json([
                'success' => true,
                'message' => 'Course deleted successfully'
            ]);
    
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Course deletion failed: ' . $e->getMessage());
    
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete course: ' . $e->getMessage()
            ], 500);
        }
    }
    
    
    public function enroll(Request $request, Batch $batch)
    {
        $validated = $request->validate([
            // TVET Provider Profile
            'region' => 'required|string',
            'province' => 'required|string',
            'congressional_district' => 'required|string',
            'municipality' => 'required|string',
            'provider_type' => 'required|string',
            
            // Program Profile
            'registration_status' => 'required|string',
            'delivery_mode' => 'required|string',
            
            // Learner Profile
            'lastname' => 'required|string',
            'firstname' => 'required|string',
            'middlename' => 'nullable|string',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(8)],
            'contact_number' => 'required|string',
            'street_address' => 'required|string',
            'barangay' => 'required|string',
            'municipality' => 'required|string',
            'province' => 'required|string',
            'gender' => 'required|in:Male,Female',
            'birthdate' => 'required|date',
            'civil_status' => 'required|string',
            'nationality' => 'required|string',
            'classification' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            // Create user with student profile
            $user = User::create([
                'lastname' => $validated['lastname'],
                'firstname' => $validated['firstname'],
                'middlename' => $validated['middlename'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'usertype' => 'Student',
                'contact_number' => $validated['contact_number'],
                'street_address' => $validated['street_address'],
                'barangay' => $validated['barangay'],
                'municipality' => $validated['municipality'],
                'province' => $validated['province'],
                'gender' => $validated['gender'],
                'birthdate' => $validated['birthdate'],
                'civil_status' => $validated['civil_status'],
                'nationality' => $validated['nationality'],
                'classification' => $validated['classification']
            ]);

            // Create enrollment record
            $enrollment = BatchEnrollment::create([
                'batch_id' => $batch->id,
                'user_id' => $user->id,
                'registration_status' => $validated['registration_status'],
                'delivery_mode' => $validated['delivery_mode'],
                'provider_type' => $validated['provider_type'],
                'region' => $validated['region'],
                'province' => $validated['province'],
                'congressional_district' => $validated['congressional_district'],
                'municipality' => $validated['municipality'],
                'status' => 'Active'
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Student enrolled successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Enrollment Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to enroll student: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getBatchStats(CourseBatch $batch)
    {
        $stats = [
            'total_enrollments' => $batch->enrollments()->count(),
            'active_enrollments' => $batch->enrollments()
                ->where('status', 'enrolled')
                ->count(),
            'completed_enrollments' => $batch->enrollments()
                ->where('status', 'completed')
                ->count(),
            'dropped_enrollments' => $batch->enrollments()
                ->where('status', 'dropped')
                ->count(),
            'available_slots' => $batch->max_students - $batch->enrollments()->count()
        ];

        return response()->json($stats);
    }
    public function enrollStudent(Request $request, CourseBatch $batch)
    {
        $validated = $request->validate([
            // TVET Provider Profile
            'region' => 'required|string',
            'province' => 'required|string',
            'congressional_district' => 'required|string',
            'municipality' => 'required|string',
            'provider_type' => 'required|string',
            
            // Program Profile
            'registration_status' => 'required|string',
            'delivery_mode' => 'required|string',
            
            // Learner Profile
            'lastname' => 'required|string',
            'firstname' => 'required|string',
            'middlename' => 'nullable|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed', // Changed to standard validation rules
            'contact_number' => 'required|string',
            'street_address' => 'required|string',
            'barangay' => 'required|string',
            'municipality' => 'required|string',
            'province' => 'required|string',
            'gender' => 'required|in:Male,Female',
            'birthdate' => 'required|date',
            'civil_status' => 'required|string',
            'nationality' => 'required|string',
            'classification' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            // Create user with student profile
            $user = User::create([
                'lastname' => $validated['lastname'],
                'firstname' => $validated['firstname'],
                'middlename' => $validated['middlename'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'usertype' => 'Student',
                'contact_number' => $validated['contact_number'],
                'street_address' => $validated['street_address'],
                'barangay' => $validated['barangay'],
                'municipality' => $validated['municipality'],
                'province' => $validated['province'],
                'gender' => $validated['gender'],
                'birthdate' => $validated['birthdate'],
                'civil_status' => $validated['civil_status'],
                'nationality' => $validated['nationality'],
                'classification' => $validated['classification']
            ]);

            // Create batch enrollment
            $enrollment = BatchEnrollment::create([
                'batch_id' => $batch->id,
                'user_id' => $user->id,
                'registration_status' => $validated['registration_status'],
                'delivery_mode' => $validated['delivery_mode'],
                'provider_type' => $validated['provider_type'],
                'region' => $validated['region'],
                'province' => $validated['province'],
                'congressional_district' => $validated['congressional_district'],
                'municipality' => $validated['municipality'],
                'status' => 'Active'
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Student enrolled successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Enrollment Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to enroll student: ' . $e->getMessage()
            ], 500);
        }
    }
}
