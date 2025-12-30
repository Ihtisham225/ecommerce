<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\StoreSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = Auth::user();
        $storeSetting = StoreSetting::firstOrCreate(
            ['user_id' => $user->id],
            [
                'store_name' => '',
                'store_email' => '',
                'store_phone' => '',
                'currency_code' => 'KWD',
                'timezone' => config('app.timezone', 'UTC'),
            ]
        );

        return view('profile.edit', [
            'user' => $request->user(),
            'storeSetting' => $storeSetting
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function updateStore(Request $request)
    {
        $user = Auth::user();
        $storeSetting = StoreSetting::firstOrCreate(['user_id' => $user->id]);

        $validated = $request->validate([
            'store_name' => 'nullable|string|max:255',
            'store_email' => 'nullable|email|max:255',
            'store_phone' => 'nullable|string|max:50',
            'currency_code' => 'nullable|string|max:10',
            'timezone' => 'nullable|string|max:100',
            'logo' => 'nullable|image|max:2048',
            // Shipping Methods
            'shipping_methods' => 'nullable|array',
            'shipping_methods.*.name' => 'required|string|max:100',
            'shipping_methods.*.cost' => 'required|numeric|min:0',
            'shipping_methods.*.description' => 'nullable|string|max:255',
            'shipping_methods.*.is_active' => 'boolean',
            // Payment Methods
            'payment_methods' => 'nullable|array',
            'payment_methods.*.name' => 'required|string|max:100',
            'payment_methods.*.is_active' => 'boolean',
            // Bank Details
            'bank_name' => 'nullable|string|max:100',
            'account_name' => 'nullable|string|max:100',
            'account_number' => 'nullable|string|max:50',
            'iban' => 'nullable|string|max:34',
            'swift_code' => 'nullable|string|max:11',
            'branch_name' => 'nullable|string|max:100',
            'branch_code' => 'nullable|string|max:20',
            // Store Address
            'address_line1' => 'nullable|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            // Tax Settings
            'tax_enabled' => 'boolean',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'tax_inclusive' => 'boolean',
            // Notification Settings
            'email_notifications' => 'boolean',
            'order_confirmations' => 'boolean',
            'low_stock_alerts' => 'boolean',
            // Store Hours
            'store_hours' => 'nullable|array',
            'store_hours.*.day' => 'required|string',
            'store_hours.*.open' => 'required|string',
            'store_hours.*.close' => 'required|string',
            'store_hours.*.is_closed' => 'boolean',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            if ($storeSetting->logo) {
                Storage::disk('public')->delete($storeSetting->logo);
            }
            $validated['logo'] = $request->file('logo')->store('logos', 'public');
        }

        // Prepare settings array
        $settings = [
            'shipping_methods' => $request->input('shipping_methods', []),
            'payment_methods' => $request->input('payment_methods', []),
            'bank_details' => [
                'bank_name' => $request->input('bank_name'),
                'account_name' => $request->input('account_name'),
                'account_number' => $request->input('account_number'),
                'iban' => $request->input('iban'),
                'swift_code' => $request->input('swift_code'),
                'branch_name' => $request->input('branch_name'),
                'branch_code' => $request->input('branch_code'),
            ],
            'store_address' => [
                'address_line1' => $request->input('address_line1'),
                'address_line2' => $request->input('address_line2'),
                'city' => $request->input('city'),
                'state' => $request->input('state'),
                'country' => $request->input('country'),
                'postal_code' => $request->input('postal_code'),
            ],
            'tax_settings' => [
                'tax_enabled' => $request->boolean('tax_enabled', false),
                'tax_rate' => $request->input('tax_rate', 0),
                'tax_inclusive' => $request->boolean('tax_inclusive', false),
            ],
            'notification_settings' => [
                'email_notifications' => $request->boolean('email_notifications', true),
                'order_confirmations' => $request->boolean('order_confirmations', true),
                'low_stock_alerts' => $request->boolean('low_stock_alerts', true),
            ],
            'store_hours' => $request->input('store_hours', []),
        ];

        // Update the store setting
        $storeSetting->update(array_merge(
            collect($validated)->except([
                'shipping_methods', 'payment_methods', 'bank_name', 'account_name',
                'account_number', 'iban', 'swift_code', 'branch_name', 'branch_code',
                'address_line1', 'address_line2', 'city', 'state', 'country', 'postal_code',
                'tax_enabled', 'tax_rate', 'tax_inclusive',
                'email_notifications', 'order_confirmations', 'low_stock_alerts',
                'store_hours'
            ])->toArray(),
            ['settings' => $settings]
        ));

        return back()->with('status', 'store-settings-updated');
    }
}
