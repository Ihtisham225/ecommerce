<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Traits\EmailHelper;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    use EmailHelper;

    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard', absolute: false));
    }

    public function requestOtp(Request $request)
    {
        // --- Validate request ---
        $request->validate([
            'email' => 'required|email',
        ]);

        // --- Check if user exists ---
        $user = User::where('email', $request->email)->first();
        if (! $user) {
            return back()->withErrors(['email' => 'This email address is not registered.']);
        }

        // --- Generate OTP and store in cache for 5 minutes ---
        $otp = rand(100000, 999999);
        cache()->put('otp_' . $user->email, $otp, now()->addMinutes(5));

        // --- Prepare email content ---
        $subject = 'Your One-Time Password (OTP) for Login';
        $body = __(
            'Dear :name,<br><br>
            Your one-time password (OTP) for login is <strong>:otp</strong>.<br><br>
            This code will expire in 5 minutes. Please do not share it with anyone.<br><br>
            Regards,<br>
            <strong>Support Team</strong>',
            [
                'name' => e($user->name ?? 'User'),
                'otp' => $otp,
            ]
        );

        // --- Send email using EmailHelper ---
        $this->sendEmail($user->email, $subject, $body);

        // --- Redirect to OTP verification page ---
        return view('auth.verify-otp', ['email' => $user->email]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['email' => 'required|email', 'otp' => 'required']);

        $cachedOtp = cache()->get('otp_' . $request->email);
        if ($cachedOtp && $cachedOtp == $request->otp) {
            $user = User::where('email', $request->email)->first();
            Auth::login($user, true);
            cache()->forget('otp_' . $request->email);
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors(['otp' => 'Invalid or expired OTP']);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
