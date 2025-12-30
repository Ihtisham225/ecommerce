<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Traits\EmailHelper;
use Illuminate\Support\Str;

class RegisteredUserController extends Controller
{
    use EmailHelper;

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
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        ]);

        // Extract name from email
        $name = Str::before($request->email, '@');

        // Generate random password
        $password = Str::random(10);

        // Create user
        $user = User::create([
            'name' => $name,
            'email' => $request->email,
            'password' => Hash::make($password),
            'user_password' => $password
        ]);

        // Assign default role
        $user->assignRole('customer');

        // Prepare email content
        $subject = "Your Account Credentials - " . config('app.name');
        $body = "
            <p>Hi <strong>{$name}</strong>,</p>
            <p>Your account has been created successfully.</p>
            <p><strong>Email:</strong> {$user->email}<br>
            <strong>Password:</strong> {$password}</p>
            <p>Please login and change your password after first login.</p>
            <p><a href='" . url('/login') . "' style='background:#1B5388;color:#fff;padding:10px 20px;text-decoration:none;border-radius:5px;'>Login Now</a></p>
        ";

        // Send email with credentials
        $this->sendEmail($user->email, $subject, $body);

        // Auto-login
        Auth::login($user);

        return redirect()->intended(route('admin.dashboard', absolute: false));
    }
}
