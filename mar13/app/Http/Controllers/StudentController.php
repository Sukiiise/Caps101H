<?php

namespace App\Http\Controllers;

use app\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    public function store(Request $request)
    {
        $student = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'usertype' => 'student'
        ]);

        $student->studentProfile()->create([
            'student_id' => $request->student_id,
            'course' => $request->course,
            // other student-specific fields
        ]);

        return redirect()->back()->with('success', 'Student created successfully');
    }
}
