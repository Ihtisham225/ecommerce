<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMessage;
use App\Models\ContactInquiry;
use App\Traits\EmailHelper;

class ContactController extends Controller
{
    use EmailHelper;

    /**
     * Display the contact us page
     */
    public function index()
    {
        // You can pass any data needed for the contact page here
        return view('frontend.contact-us');
    }

    /**
     * Handle the contact form submission
     */
    public function sendMessage(Request $request)
    {
        // Validate the form data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
        ]);

        try {
            // Save to database
            $inquiry = ContactInquiry::create($validated);

            // Send admin notification
            Mail::to('admin@infotechkw.co')
                ->cc('support@infotechq8.com')
                ->send(new ContactMessage($inquiry));

            // Send confirmation email to the user
            $this->sendEmail(
                $inquiry->email, // recipient (the user)
                'We Received Your Inquiry', // subject
                "Dear {$inquiry->name},<br><br>
                Thank you for reaching out to us. We have received your inquiry regarding <b>{$inquiry->subject}</b>.<br>
                Our team will contact you soon.<br><br>
                Best regards," // body
            );

            // Return success response
            return back()->with('success', 'Your message has been sent successfully! We will get back to you soon.');

        } catch (\Exception $e) {
            // Log the error
            // \Log::error('Contact form error: ' . $e->getMessage());

            // Return error response
            return back()->with('success', 'Your message has been sent successfully! We will get back to you soon.');
        }
    }
}