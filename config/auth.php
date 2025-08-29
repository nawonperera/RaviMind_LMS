<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | These values determine the default guard and password broker (reset
    | settings) that the framework will use when no explicit guard/broker
    | is specified. They pull values from the environment (.env) if set,
    | otherwise they fall back to the provided defaults.
    |
    */

    'defaults' => [
        // 'guard' selects which authentication guard to use by default.
        // env('AUTH_GUARD', 'web') => return value of AUTH_GUARD from .env,
        // otherwise use 'web'.
        // Example guards: 'web' (session/cookie), 'api' (token), 'admin', etc.
        'guard' => env('AUTH_GUARD', 'web'),

        // 'passwords' selects the default password broker configuration
        // used for password resets. This key must match an entry in
        // the 'passwords' array below (commonly 'users' or 'admins').
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],
    /*
    | Quick summary:
    | - guard -> how users are authenticated (session, token, third-party)
    | - passwords -> which password reset configuration to use by default
    |
    | env($key, $default) reads the value from the .env file (if present).
    | If the .env value is missing, it returns the $default provided.
    */


    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Guards define how users are authenticated for each request. A guard
    | maps a request to a user provider (below) and specifies the mechanism
    | used to maintain the authentication state (driver).
    |
    | Typical drivers: 'session' (web apps using cookies/sessions),
    | 'token' (simple API tokens), or other drivers provided by packages
    | like 'passport' or 'sanctum'.
    |
    */

    'guards' => [
        // Default web guard (for regular website users)
        'web' => [
            // Use session/cookie based authentication
            'driver' => 'session',

            // Use the 'users' provider defined in 'providers' below.
            'provider' => 'users',
        ],

        // Additional guard for admins (separates admin authentication)
        'admin' => [
            // Also session-based here — admins will be authenticated via sessions
            'driver' => 'session',

            // Use the 'admins' provider (configured below) so Laravel will
            // look for admin records instead of normal users.
            'provider' => 'admins',
        ],
    ],
    /*
    | Notes:
    | - You can add as many guards as you need (e.g., API guards, admin guards).
    | - A guard refers to a provider; the provider defines where/how to
    |   retrieve the user record (Eloquent model, DB table, etc.).
    */


    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | Providers define how to fetch users from storage. You can use Eloquent
    | models or raw database queries. Multiple providers allow multiple
    | user tables/models (e.g., users, admins, customers).
    |
    | Supported drivers (by default): 'eloquent', 'database'.
    |
    */

    'providers' => [
        // Provider used for regular users
        'users' => [
            // 'eloquent' means Laravel will use an Eloquent model to fetch users
            'driver' => 'eloquent',

            // The model class that represents users. env('AUTH_MODEL', ...)
            // lets you override the model from .env if needed; otherwise
            // it defaults to App\Models\User::class.
            'model' => env('AUTH_MODEL', App\Models\User::class),
        ],

        // Provider used for admins
        'admins' => [
            'driver' => 'eloquent',

            // NOTE: This file uses env('AUTH_MODEL', App\Models\Admin::class)
            // for the Admin model. That re-uses the same AUTH_MODEL env key
            // as the users provider. Usually you want different env keys
            // (or hardcode different classes), for example:
            //   'model' => env('AUTH_ADMIN_MODEL', App\Models\Admin::class)
            // Otherwise both providers may end up using the same model.
            'model' => env('AUTH_MODEL', App\Models\Admin::class),
        ],

        // Alternative (commented-out) approach: use the 'database' driver
        // to query a table directly without an Eloquent model:
        // 'users' => [
        //     'driver' => 'database',
        //     'table' => 'users',
        // ],
    ],
    /*
    | Quick notes:
    | - 'eloquent' provider expects a model class and uses Eloquent to fetch users.
    | - 'database' provider expects a table name and runs direct DB queries.
    | - Providers are referenced by guards to know where to load users from.
    */


    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | Configuration for password reset tokens. Each key under 'passwords'
    | represents a password broker for a particular user/provider type.
    | It defines which provider to use, which table stores tokens, token
    | expiration, and throttle limits.
    |
    | 'expire' is in minutes (how long a reset token is valid).
    | 'throttle' is in seconds (how long to wait between successive requests).
    |
    */

    'passwords' => [
        // Password reset config for normal users
        'users' => [
            // Must match a provider defined above
            'provider' => 'users',

            // Table that stores password reset tokens. You can change the
            // table name via .env if needed.
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),

            // How many minutes the reset token is valid for (e.g., 60 = 1 hour).
            'expire' => 60,

            // How many seconds a user must wait before requesting another
            // password reset token. Default here is 60 seconds.
            // NOTE: Some comments earlier say "minutes" — that is incorrect.
            'throttle' => 60,
        ],

        // Password reset config for admins
        'admins' => [
            // Use the admins provider so admin accounts use this broker
            'provider' => 'admins',

            // You can use the same token table or a different one if you want
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),

            // Token lifetime in minutes
            'expire' => 60,

            // Throttle in seconds
            'throttle' => 60,
        ],
    ],
    /*
    | Summary:
    | - You can have separate reset settings (tokens/lifetimes) per user type.
    | - 'expire' → minutes (token lifetime)
    | - 'throttle' → seconds (wait time between requests)
    */


    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    |
    | Defines how many seconds a user remains "recently confirmed" after
    | entering their password for sensitive actions (like changing email,
    | deleting account). After this time they must re-enter their password.
    |
    */

    // Read from .env (AUTH_PASSWORD_TIMEOUT) or default to 10800 seconds (3 hours).
    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

    /*
    | Example: 10800 seconds = 3 hours.
    |
    | This setting is used by the "password.confirm" middleware to require
    | users to re-enter their password for sensitive operations after the
    | configured timeout.
    */

];
