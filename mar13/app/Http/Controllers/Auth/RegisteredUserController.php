<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('register');
    }

    public function store(Request $request): RedirectResponse
    {
        try {
            // 1. Validate the request
            $validated = $request->validate([
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
                'terms' => ['required', 'accepted']
            ], [
                'terms.required' => 'You must accept the Terms and Conditions.',
                'terms.accepted' => 'You must accept the Terms and Conditions.',
                'password.confirmed' => 'The passwords do not match.'
            ]);

            // 2. Debug validation data
            Log::info('Validation passed', $validated);

            // Check if this is the first user
            $userCount = User::count();

            // 3. Create user
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
                'usertype' => 'admin'
            ]);

            // 4. Debug user creation
            Log::info('User created', [
                'user' => $user,
                'usertype' => 'admin',
                'is_first_user' => $userCount === 0
            ]);

            // 5. Redirect to login
            return redirect('/login')
                ->with('success', 'Admin account created successfully! Please login to continue.');

        } catch (\Exception $e) {
            // 6. Log the specific error
            Log::error('Registration failed: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->withErrors(['error' => $e->getMessage()]);
        }
    }
}
