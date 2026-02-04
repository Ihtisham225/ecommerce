<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Traits\EmailHelper;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    use EmailHelper;

    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {

            return redirect()->intended($request->user()->dashboardRoute() . '?verified=1');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));

            // Send welcome email
            if ($request->user()->email) {
                $subject = __('Welcome to Infotechq8');
                $body = __('Hello :name,<br><br>Welcome to Infotechq8! Your account has been successfully created.<br><br>We are excited to have you on board.<br><br>Best regards,<br>Team', [
                    'name' => $request->user()->name,
                ]);

                $this->sendEmail($request->user()->email, $subject, $body);
            }
        }

        return redirect()->intended($request->user()->dashboardRoute() . '?verified=1');
    }
}
