<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Traits\EmailHelper;

class AjaxAuthController extends Controller
{
    use EmailHelper;

    public function checkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $exists = User::where('email', $request->email)->exists();
        return response()->json(['exists' => $exists]);
    }

    public function register(Request $request)
    {
        $request->validate(['email' => 'required|email|unique:users,email']);

        $name = Str::before($request->email, '@');
        $password = Str::random(10);

        $user = User::create([
            'name' => $name,
            'email' => $request->email,
            'password' => Hash::make($password),
            'user_password' => $password
        ]);
        $user->assignRole('customer');

        // Prepare styled HTML email
        $subject = "Your Account Credentials - " . config('app.name');
        $body = "
            <p>Hi <strong>{$name}</strong>,</p>
            <p>Your account has been created successfully.</p>
            <p><strong>Email:</strong> {$user->email}<br>
            <strong>Password:</strong> {$password}</p>
            <p>Please login and change your password after first login.</p>
            <p><a href='" . url('/login') . "' 
                style='background:#1B5388;color:#fff;padding:10px 20px;
                       text-decoration:none;border-radius:5px;'>Login Now</a></p>
        ";

        // Send email using EmailHelper trait
        $this->sendEmail($user->email, $subject, $body);

        Auth::login($user, true);

        return response()->json(['success' => true]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($request->only('email', 'password'), true)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        return response()->json(['success' => true]);
    }

    public function requestOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['error' => 'Email not found'], 404);
        }

        $otp = rand(100000, 999999);
        cache()->put("otp_" . $user->email, $otp, now()->addMinutes(5));

        // Styled OTP email
        $subject = "Your OTP Code - " . config('app.name');
        $body = "
            <p>Hi <strong>{$user->name}</strong>,</p>
            <p>Your one-time password (OTP) for login is:</p>
            <h2 style='color:#1B5388;'>{$otp}</h2>
            <p>This code will expire in 5 minutes.</p>
        ";

        $this->sendEmail($user->email, $subject, $body);

        return response()->json(['success' => true]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['email' => 'required|email', 'otp' => 'required']);

        $cached = cache()->get("otp_" . $request->email);

        if ($cached && $cached == $request->otp) {
            $user = User::where('email', $request->email)->first();
            Auth::login($user, true);
            cache()->forget("otp_" . $request->email);
            return response()->json(['success' => true]);
        }

        return response()->json(['error' => 'Invalid or expired OTP'], 401);
    }
}
