<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | This option defines the default authentication "guard" and password
    | reset "broker" for your application. You may change these values
    | as required, but they're a perfect start for most applications.
    |
    */

    'defaults' => [
    // 'guard' decides how users are logged in (session, token, etc.)
    // env('AUTH_GUARD', 'web') means:
    // → check if AUTH_GUARD is defined in .env file
    // → if yes, use that value
    // → if not, use "web" as default
    'guard' => env('AUTH_GUARD', 'web'),

    // 'passwords' decides which password reset settings to use
    // env('AUTH_PASSWORD_BROKER', 'users') means:
    // → check if AUTH_PASSWORD_BROKER is defined in .env file
    // → if yes, use that value
    // → if not, use "users" as default
    'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
],
    /* Explanation in simple terms:
    | Example of guards:
    | "web" guard = uses session & cookies for login (normal websites)
    | "api" guard = uses tokens for login (API authentication)

    | Example of passwords:
    | "users" = password reset rules for normal users
    | "admins" = password reset rules for admins (if defined)

    | In short:
    | guard → how login works
    | passwords → how password reset works
    */


    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Next, you may define every authentication guard for your application.
    | Of course, a great default configuration has been defined for you
    | which utilizes session storage plus the Eloquent user provider.
    |
    | All authentication guards have a user provider, which defines how the
    | users are actually retrieved out of your database or other storage
    | system used by the application. Typically, Eloquent is utilized.
    |
    | Supported: "session"
    |
    */

    'guards' => [
    // "web" guard setup
    'web' => [
        // "driver" defines how login is handled
        // "session" means use sessions & cookies (for normal web apps)
        'driver' => 'session',

        // "provider" tells which user table/model to use
        // here "users" provider → uses users table/model
        'provider' => 'users',
    ],

    // "admin" guard setup
    'admin' => [
        // still using "session" driver (same as web)
        // but could be different if needed (like "token" for APIs)
        'driver' => 'session',

        // this time it uses "admins" provider
        // meaning login will check the "admins" table/model instead of "users"
        'provider' => 'admins',
    ],
],
    /*
    | Explanation in simple terms:
    | - Guards = how login works for each user type (web users, admins, API users, etc.)
    | - You can create many guards if you have different kinds of users
    | - "driver" = method of authentication (session = cookies, token = API tokens)
    | - "provider" = which database table/model is used to fetch the user
    */


    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | All authentication guards have a user provider, which defines how the
    | users are actually retrieved out of your database or other storage
    | system used by the application. Typically, Eloquent is utilized.
    |
    | If you have multiple user tables or models you may configure multiple
    | providers to represent the model / table. These providers may then
    | be assigned to any extra authentication guards you have defined.
    |
    | Supported: "database", "eloquent"
    |
    */

    'providers' => [
        // "users" provider
        'users' => [
            // "driver" tells how to fetch user data from DB
            // "eloquent" means use Eloquent ORM with a model
            'driver' => 'eloquent',

            // "model" tells which Eloquent model represents this provider
            // env('AUTH_MODEL', App\Models\User::class) means:
            // → check .env for AUTH_MODEL
            // → if not found, use App\Models\User by default
            'model' => env('AUTH_MODEL', App\Models\User::class),
        ],

        // "admins" provider
        'admins' => [
            // still using "eloquent" driver
            'driver' => 'eloquent',

            // but this time using the Admin model
            // so Laravel will fetch data from "admins" table via App\Models\Admin
            'model' => env('AUTH_MODEL', App\Models\Admin::class),
        ],


        // Alternative way (using database driver directly without models)
        // Example: fetch from "users" table directly instead of using a model
        // 'users' => [
        //     'driver' => 'database',
        //     'table' => 'users',
        // ],
],
    /*
    |  Explanation:
    | - Providers = how to fetch user records from DB
    | - "eloquent" driver = uses Eloquent models (recommended way)
    | - "database" driver = direct DB query without a model
    | - Providers are linked with guards (guard → provider → model/table)
    */


    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | These configuration options specify the behavior of Laravel's password
    | reset functionality, including the table utilized for token storage
    | and the user provider that is invoked to actually retrieve users.
    |
    | The expiry time is the number of minutes that each reset token will be
    | considered valid. This security feature keeps tokens short-lived so
    | they have less time to be guessed. You may change this as needed.
    |
    | The throttle setting is the number of seconds a user must wait before
    | generating more password reset tokens. This prevents the user from
    | quickly generating a very large amount of password reset tokens.
    |
    */

    'passwords' => [
    // Password reset settings for normal users
    'users' => [
        // "provider" must match one of the providers defined above
        // here → it uses the "users" provider (App\Models\User)
        'provider' => 'users',

        // "table" stores password reset tokens
        // env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens') means:
        // → check .env for AUTH_PASSWORD_RESET_TOKEN_TABLE
        // → if not found, use "password_reset_tokens" by default
        'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),

        // "expire" is how many minutes the reset token is valid
        // here = 60 minutes (1 hour)
        'expire' => 60,

        // "throttle" is how many minutes a user must wait 
        // before requesting another reset link
        'throttle' => 60,
    ],

    // Password reset settings for admins
    'admins' => [
        // uses the "admins" provider (App\Models\Admin)
        'provider' => 'admins',

        // same table for reset tokens (could also be different if needed)
        'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),

        // token valid for 60 minutes
        'expire' => 60,

        // new request allowed after 60 minutes
        'throttle' => 60,
    ],
],
    /*
    |  Explanation in simple words:
    | - "passwords" defines how password reset works for each user type
    | - You can have separate reset settings for different guards (users, admins, etc.)
    | - "provider" → which user group to reset (linked to providers above)
    | - "table" → where reset tokens are stored
    | - "expire" → token lifetime
    | - "throttle" → waiting time between reset requests
    */


    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    |
    | Here you may define the number of seconds before a password confirmation
    | window expires and users are asked to re-enter their password via the
    | confirmation screen. By default, the timeout lasts for three hours.
    |
    */

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

    // This setting controls how long (in seconds) a user can stay authenticated
    // before being asked to re-enter their password for sensitive actions
    // Example: updating profile, changing email, deleting account, etc.

    // env('AUTH_PASSWORD_TIMEOUT', 10800) means:
    // → check .env for AUTH_PASSWORD_TIMEOUT
    // → if not found, default = 10800 seconds (3 hours)

    // 10800 seconds = 60 * 60 * 3 = 3 hours

];
