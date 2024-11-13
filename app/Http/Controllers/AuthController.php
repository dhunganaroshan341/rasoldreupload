<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
    // Register Business Logic Start

    public function register()
    {
        return view('Auth.Register');
    }

    public function storeRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:3',
            'email' => 'required|email|unique:users',
            'password' => ['required', Rules\Password::Default(), 'confirmed'],
            'role' => 'required',
        ]);
        // print_r($request->toArray());
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'role' => $request->role,
        ]);

        return redirect()->route('login')->with(['message' => 'Registered Successfully']);

    }

    // Register Business Logic Start

    // Register Business Login Start

    public function login()
    {
        return view('Auth.Login');
    }

    public function storeLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email|string',
            'password' => 'required|string|min:8',
        ]);
        $user = User::where('email', $request->email)->get();
        if ($user->count() > 0) {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                session()->put(['email' => $request->email]);

                return redirect()->route('dashboard')->with(['welcome' => $user[0]['name']]);
            } else {
                return back()->with(['error' => 'Invalid Login Crediantials']);
            }
        } else {
            return back()->with(['error' => 'Email not register yet ?']);
        }
    }
    // Register Business Login Start

    public function logout()
    {
        session()->flush();

        return redirect()->route('login');
    }

    public function edit($id)
    {
        $user = User::find($id);
        if (! $user) {
            return redirect()->route('dashboard')->with(['error' => 'User not found']);
        }

        return view('Auth.Edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Build validation rules
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$id,  // Exclude current user's email from unique check
            'role' => 'required|string|max:50',
        ];

        // If password is provided, apply the validation rules
        if ($request->filled('password')) {
            $rules['password'] = 'required|min:6|confirmed';
            $rules['password_confirmation'] = 'required|min:6';  // Ensures the confirmation field is required if password is set
        }

        // Validate the request with the defined rules
        $request->validate($rules);

        // Update user data
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->role = $request->input('role');

        // If password is provided, hash and update the password
        if ($request->filled('password')) {
            $user->password = bcrypt($request->input('password'));
        }

        $user->save();

        return redirect()->route('profile')->with('success', 'User updated successfully');
    }
}
