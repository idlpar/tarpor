<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of users (Admin only).
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);
        $users = User::query()
            ->when(request('search'), function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%'.request('search').'%')
                        ->orWhere('email', 'like', '%'.request('search').'%');
                });
            })
            ->when(request('role'), function ($query) {
                $query->where('role', request('role'));
            })
            ->when(request('sort'), function ($query) {
                switch (request('sort')) {
                    case 'name_asc':
                        $query->orderBy('name', 'asc');
                        break;
                    case 'name_desc':
                        $query->orderBy('name', 'desc');
                        break;
                    case 'newest':
                        $query->orderBy('created_at', 'desc');
                        break;
                    case 'oldest':
                        $query->orderBy('created_at', 'asc');
                        break;
                }
            }, function ($query) {
                $query->orderBy('id', 'desc'); // Default sort: newest user ID first
            })
            ->paginate(10)
            ->withQueryString();

        $links = [
            'Users' => route('users.index')
        ];
        if ($request->ajax()) {
            return response()->json([
                'users' => $users,
            ]);
        }

        return view('users.index', compact('users', 'links'));
    }

    /**
     * Show the form for creating a new user (Admin only).
     */
    public function create()
    {
        $this->authorize('create', User::class);
        $links = [
            'Users' => route('users.index'),
            'Add New' => null
        ];
        return view('users.create', compact('links'));
    }

    /**
     * Store a newly created user (Admin only).
     */
    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|in:admin,staff,user',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'is_verified' => true, // Admin-created users are verified
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    /**
     * Show the form for editing a user (Admin only).
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);
        $links = [
            'Users' => route('users.index'),
            'Edit' => null
        ];
        return view('users.edit', compact('user', 'links'));
    }

    /**
     * Update a user (Admin only).
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,staff,user',
            'password' => 'nullable|min:8|confirmed',
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'User updated successfully.')->with('highlight_user_id', $user->id);
    }

    /**
     * Delete a user (Admin only).
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    /**
     * Display the specified user (Admin only).
     */
    public function show(User $user)
    {
        $this->authorize('view', $user);
        return view('users.show', compact('user'));
    }

    /**
     * Show the user's profile (All roles).
     */
    public function showProfile()
    {
        $this->authorize('view', Auth::user());
        return view('profile.show', ['user' => Auth::user()]);
    }

    /**
     * Update the user's profile (All roles).
     */
    public function updateProfile(Request $request)
    {
        $this->authorize('update', Auth::user());

        $user = Auth::user();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|regex:/^01[0-9]{9}$/|unique:users,phone,' . $user->id,
        ]);

        $user->update($validated);
        return redirect()->route('profile.show')->with('success', 'Profile updated successfully.');
    }

    /**
     * Update the user's avatar (Verified users only).
     */
    public function updateAvatar(Request $request)
    {
        $this->authorize('updateAvatar', Auth::user());

        $validated = $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = Auth::user();

        // Delete existing avatar if present in database and file exists
        if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
            Storage::disk('public')->delete($user->profile_photo);
        }

        if ($request->hasFile('avatar')) {
            // Get the original filename
            $file = $request->file('avatar');
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $filenameWithoutExtension = pathinfo($originalName, PATHINFO_FILENAME);

            $newFilename = $originalName;
            $counter = 1;

            // Check database for existing filenames
            while (User::where('profile_photo', 'avatars/' . $newFilename)->exists()) {
                $newFilename = $filenameWithoutExtension . '_' . $counter . '.' . $extension;
                $counter++;
            }

            // Store the file with the determined filename
            $path = $file->storeAs('avatars', $newFilename, 'public');
            $user->update(['profile_photo' => $path]);
        }

        return redirect()->route('profile.show')->with('success', 'Avatar updated successfully.');
    }

    /**
     * Destroy the user's avatar (Verified users only).
     */
    public function deleteAvatar(Request $request)
    {
        $this->authorize('deleteAvatar', Auth::user());

        $user = Auth::user();
        // Check database for avatar path and delete file if it exists
        if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
            Storage::disk('public')->delete($user->profile_photo);
        }

        // Update user to remove avatar reference
        $user->update(['profile_photo' => null]);
        return redirect()->route('profile.show')->with('success', 'Avatar deleted successfully.');
    }

    /**
     * Update the user's address (Verified users only).
     */
    public function updateAddress(Request $request)
    {
        $this->authorize('updateAddress', Auth::user());

        $validated = $request->validate([
            'division' => 'required|string|max:255',
            'district' => 'required|string|max:100',
            'upazila' => 'required|string|max:100',
            'union' => 'nullable|string|max:100',
            'street_address' => 'nullable|string|max:100',
            'postal_code' => 'required|string|max:20',
        ]);

        $user = Auth::user();
        $user->update([
            'division' => $validated['division'],
            'district' => $validated['district'],
            'upazila' => $validated['upazila'],
            'union' => $validated['union'],
            'street_address' => $validated['street_address'],
            'postal_code' => $validated['postal_code'],
        ]);

        return redirect()->route('profile.show')->with('success', 'Address updated successfully.');
    }
}
