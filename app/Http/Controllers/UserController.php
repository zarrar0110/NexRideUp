<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(User::with(['driver', 'passenger'])->get());
    }

    public function show($id)
    {
        $user = User::with(['driver', 'passenger'])->findOrFail($id);
        return response()->json($user);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);
        $validated['password'] = Hash::make($validated['password']);
        $user = User::create($validated);
        return response()->json($user, 201);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:100',
            'email' => 'sometimes|required|email|unique:users,email,' . $id,
            'password' => 'sometimes|required|string|min:6',
        ]);
        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }
        $user->update($validated);
        return response()->json($user);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }

    public function showRegistrationForm()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:driver,passenger,admin',
        ], [
            'email.unique' => 'This email address is already registered. Please use a different email or try logging in.',
            'password.confirmed' => 'Password confirmation does not match.',
            'role.in' => 'Please select a valid role.',
        ]);
        
        $validated['password'] = Hash::make($validated['password']);
        $user = User::create($validated);
        
        // Log the user in
        Auth::login($user);
        
        // Create success message based on role
        $successMessage = match($validated['role']) {
            'driver' => '🚗 Driver account created successfully! Welcome to our driver community. You can now start accepting trip requests.',
            'passenger' => '🚶 Passenger account created successfully! Welcome to our ride-sharing platform. You can now request trips.',
            'admin' => '⚙️ Admin account created successfully! Welcome to the admin panel.',
            default => 'Account created successfully!'
        };
        
        // Redirect based on role
        switch ($validated['role']) {
            case 'driver':
                return redirect('/driver/dashboard')->with('success', $successMessage);
            case 'passenger':
                return redirect('/passenger/dashboard')->with('success', $successMessage);
            case 'admin':
                return redirect('/admin/dashboard')->with('success', $successMessage);
            default:
                return redirect('/')->with('success', $successMessage);
        }
    }

    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            $intendedRole = $request->query('intended_role');
            
            // Check if user is trying to access a different role's dashboard
            if ($intendedRole && $user->role !== $intendedRole) {
                $currentRole = ucfirst($user->role);
                $requestedRole = ucfirst($intendedRole);
                return redirect('/')->with('warning', "You are logged in as a {$currentRole}, but you clicked 'Login as {$requestedRole}'. Please use the appropriate dashboard for your role.");
            }
            
            // Redirect based on role
            switch ($user->role) {
                case 'driver':
                    return redirect('/driver/dashboard');
                case 'passenger':
                    return redirect('/passenger/dashboard');
                case 'admin':
                    return redirect('/admin/dashboard');
                default:
                    return redirect('/');
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/')->with('success', 'Logged out successfully!');
    }
}
