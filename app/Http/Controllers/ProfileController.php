<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\StoreSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
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
        ]);

        if ($request->hasFile('logo')) {
            if ($storeSetting->logo) {
                Storage::disk('public')->delete($storeSetting->logo);
            }
            $validated['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $storeSetting->update($validated);

        return back()->with('status', 'store-settings-updated');
    }
}
