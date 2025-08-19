<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AdminLoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */


    public function authenticate(): void
    {
        // Check if the user has exceeded the maximum login attempts
        // If too many attempts, this will throw an exception and prevent further login attempts temporarily
        $this->ensureIsNotRateLimited();

        // Attempt to log in the user with the provided email and password
        // Auth::guard('admin') → use the "admin" guard for authentication
        // This means it will check the "admins" table/model instead of "users"
        // $this->only('email', 'password') → get only these fields from the request
        // $this->boolean('remember') → check if "remember me" checkbox is checked
        if (! Auth::guard('admin')->attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            // If login fails:
            // Increment the login attempts counter for this user
            RateLimiter::hit($this->throttleKey());

            // Throw a validation exception with a friendly error message
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'), // "auth.failed" message from lang files
            ]);
        }

        // If login succeeds:
        // Clear the login attempts counter (user successfully authenticated)
        RateLimiter::clear($this->throttleKey());
    }


    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')) . '|' . $this->ip());
    }
}
