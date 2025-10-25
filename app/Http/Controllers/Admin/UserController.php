<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Filter by role
        if ($request->filled('role')) {
            $query->role($request->role);
        }

        // Search by name
        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $users = $query->latest()->paginate(20)->appends($request->query());

        // Get all roles for dropdown
        $roles = \Spatie\Permission\Models\Role::pluck('name')->toArray();

        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'user_avatar_id' => 'nullable|exists:documents,id',
            'new_user_avatar' => 'nullable|image|max:2048', // Max 2MB
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // 🔹 Attach selected user avatar
        if (!empty($validated['user_avatar_id'])) {
            Document::where('id', $validated['user_avatar_id'])
                ->update([
                    'documentable_id'   => $user->id,
                    'documentable_type' => User::class,
                    'document_type'     => 'user_avatar',
                ]);
        }

        // 🔹 Upload new user avatar
        if ($request->hasFile('new_user_avatar')) {
            $file = $request->file('new_user_avatar');
            $path = $file->store('documents', 'public');

            $user->userAvatar()->updateOrCreate([], [
                'name'          => $file->getClientOriginalName(),
                'file_path'     => $path,
                'file_type'     => $file->getClientOriginalExtension(),
                'mime_type' => $file->getMimeType(),
                'size'     => $file->getSize(),
                'document_type' => 'user_avatar',
            ]);
        }

        // Assign roles
        $roles = Role::whereIn('id', $validated['roles'])->get();
        $user->syncRoles($roles);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load('roles');
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        $user->load('roles');
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'user_avatar_id' => 'nullable|exists:documents,id',
            'new_user_avatar' => 'nullable|image|max:2048', // Max 2MB
            'remove_user_avatar' => 'nullable|boolean',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        // Update password if provided
        if (!empty($validated['password'])) {
            $user->update([
                'password' => Hash::make($validated['password']),
            ]);
        }

        // ---- PROFILE PICTURE ----

        // Remove existing
        if (!empty($validated['remove_user_avatar']) && $user->userAvatar) {
            Document::where('id', $user->userAvatar->id)->update([
                'documentable_id' => null,
                'documentable_type' => null,
            ]);
        }

        // Attach selected existing
        if (!empty($validated['user_avatar_id'])) {
            Document::where('id', $validated['user_avatar_id'])->update([
                'documentable_id' => $user->id,
                'documentable_type' => User::class,
                'document_type' => 'user_avatar',
            ]);
        }

        // Upload new user avatar (overrides selection)
        if ($request->hasFile('new_user_avatar')) {
            $file = $request->file('new_user_avatar');
            $path = $file->store('documents', 'public');

            $documentData = [
                'name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_type' => $file->getClientOriginalExtension(),
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'document_type' => 'user_avatar',
            ];

            if ($user->userAvatar) {
                $user->userAvatar->update($documentData);
            } else {
                $user->userAvatar()->create($documentData);
            }
        }

        // Sync roles
        $roles = Role::whereIn('id', $validated['roles'])->get();
        $user->syncRoles($roles);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Prevent deletion of current user
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}