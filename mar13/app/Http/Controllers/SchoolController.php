<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class SchoolController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'address' => 'required|string',
                'contact_number' => 'required|string',
                'logo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);
    
            DB::beginTransaction();
    
            $school = new School();
            $school->name = $validated['name'];
            $school->address = $validated['address'];
            $school->contact_number = $validated['contact_number'];
    
            if ($request->hasFile('logo_path')) {
                $file = $request->file('logo_path');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                
                // Move the file directly to public/images directory
                $file->move(public_path('images'), $filename);
                
                // Save the path relative to public/images
                $school->logo_path = 'images/' . $filename;
            }
    
            $school->save();
            
            DB::commit();
    
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'School created successfully',
                    'school' => $school
                ]);
            }
    
            return redirect()->route('admin.dashboard')
                ->with('success', 'School created successfully!');
    
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('School creation failed: ' . $e->getMessage());
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create school. Please try again.'
                ], 500);
            }
    
            return back()
                ->withInput()
                ->with('error', 'Failed to create school. Please try again.');
        }
    }
    
    public function storeStudent(School $school, Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8'
        ]);
    
        $student = $school->students()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'status' => 'active'
        ]);
    
        return redirect()->back()->with('success', 'Student added successfully');
    }
    
    public function destroyStudent(School $school, User $student)
    {
        $student->delete();
        return redirect()->back()->with('success', 'Student removed successfully');
    }
    

    public function trainee(School $school)
    {
        $trainee = $school->trainee()->paginate(10);
        return view('school.trainee', compact('school', 'trainee'));
    }
}
