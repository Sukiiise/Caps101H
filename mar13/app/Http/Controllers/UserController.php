<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller

{

    public function index()
    {
        // Fetch only users with usertype 'user' and their profiles
        $users = User::where('usertype', 'user')
            ->with('profile')
            ->paginate(10);

        $icon = 'bi bi-people-fill';
        $button = 'View Users';

        return view('admin.viewuser', compact('users', 'icon', 'button'));
    }

    public function create()
    {
        $icon = 'bi bi-person-plus-fill'; // Bootstrap icon class
        $button = 'Create New User'; // Button text
        return view('admin.createuser', compact('icon', 'button'));
    }

    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'contact_number' => 'required|string|max:20',
            'street_address' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Create the user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'usertype' => 'user', // This will use the default value
            ]);

            // Create the user profile
            $user->profile()->create([
                'contact_number' => $request->contact_number,
                'street_address' => $request->street_address,
            ]);

            return redirect()->back()->with('success', 'User created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error creating user: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'contact_number' => 'required|string|max:20',
            'street_address' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $user = User::findOrFail($id);

            // Update user data
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            // Update or create profile data
            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'contact_number' => $request->contact_number,
                    'street_address' => $request->street_address,
                ]
            );

            return redirect()->back()->with('success', 'User updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating user: ' . $e->getMessage())
                ->withInput();
        }
    }


    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);

            // Delete profile first due to foreign key relationship
            if ($user->profile) {
                $user->profile->delete();
            }

            // Delete the user
            $user->delete();

            return redirect()->back()->with('success', 'User deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error deleting user: ' . $e->getMessage());
        }
    }
}
