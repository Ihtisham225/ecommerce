<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactInquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\InquiryReply;

class ContactController extends Controller
{
    /**
     * Display a listing of the contact inquiries.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = ContactInquiry::query();

        // Role-based access
        if (!$user->hasRole('admin')) {
            // Customer sees only their own inquiries (match email)
            $query->where('email', $user->email);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by name, email, or subject
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        $inquiries = $query->latest()->paginate(20)->appends($request->query());

        return view('admin.contact-inquiries.index', compact('inquiries'));
    }

    /**
     * Display the specified contact inquiry.
     */
    public function show($id)
    {
        $user = Auth::user();
        $inquiry = ContactInquiry::findOrFail($id);

        // Role-based access: customer can only see their own inquiry
        if (!$user->hasRole('admin') && $inquiry->email !== $user->email) {
            abort(403, 'Unauthorized access to this inquiry.');
        }

        // Mark as read if it's unread
        if ($inquiry->status === 'unread') {
            $inquiry->update(['status' => 'read']);
        }

        return view('admin.contact-inquiries.show', compact('inquiry'));
    }

    /**
     * Remove the specified contact inquiry from storage.
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $inquiry = ContactInquiry::findOrFail($id);

        // Role-based access: only admin can delete
        if (!$user->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

        $inquiry->delete();

        return redirect()->route('admin.contact.inquiries')
            ->with('success', 'Inquiry deleted successfully.');
    }

    /**
     * Send a reply to the contact inquiry (admin only).
     */
    public function sendReply(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'reply_message' => 'required|string|min:10',
        ]);

        $inquiry = ContactInquiry::findOrFail($id);

        try {
            // Send reply email
            Mail::to($inquiry->email)
                ->send(new InquiryReply($inquiry, $request->reply_message));

            // Update inquiry status
            $inquiry->update([
                'status' => 'replied',
                'admin_notes' => $request->reply_message
            ]);

            return redirect()->route('admin.contact.inquiries.show', $inquiry->id)
                ->with('success', 'Reply sent successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to send reply: ' . $e->getMessage());
        }
    }

    /**
     * Update the status of a contact inquiry (admin only).
     */
    public function updateStatus(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'status' => 'required|in:unread,read,in_progress,replied,resolved'
        ]);

        $inquiry = ContactInquiry::findOrFail($id);
        $inquiry->update(['status' => $request->status]);

        return redirect()->back()
            ->with('success', 'Status updated successfully.');
    }
}
