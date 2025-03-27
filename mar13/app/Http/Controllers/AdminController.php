<?php

namespace App\Http\Controllers;

use app\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function store(Request $request)
    {
        $admin = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'usertype' => 'admin'
        ]);

        $admin->adminProfile()->create([
            'department' => $request->department,
            'role' => $request->role,
            // other admin-specific fields
        ]);

        return redirect()->back()->with('success', 'Admin created successfully');
    }
}
