<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // ✅ Validate the request data
        $request->validate([
            // "name" must be provided, must be a string, and max 255 chars
            'name' => ['required', 'string', 'max:255'],

            // "email" must be provided, lowercase, valid email format,
            // max 255 chars, and must be unique in the users table
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],

            // "password" must be provided, must match "password_confirmation" (confirmed rule),
            // and follow default Laravel password rules (min length, etc.)
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // ✅ Create a new user record in the database
        $user = User::create([
            'name' => $request->name, // take from form input
            'email' => $request->email, // take from form input
            // hash the password before saving (important for security!)
            'password' => Hash::make($request->password),
        ]);

        // ✅ Fire the "Registered" event (can trigger actions like sending welcome email)
        event(new Registered($user));

        // ✅ Log the user in automatically after registration
        Auth::login($user);

        // ✅ Redirect user to dashboard after successful registration
        // "absolute: false" means generate a relative URL instead of absolute one
        return redirect(route('dashboard', absolute: false));
    }
}
