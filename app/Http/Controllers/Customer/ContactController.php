<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\ContactInquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        return view('customer.contact-inquiries.index', compact('inquiries'));
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

        return view('customer.contact-inquiries.show', compact('inquiry'));
    }
}