<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\UsersServices;
use Illuminate\View\View;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;


class UserController extends Controller
{
    protected $usersServices;

    public function __construct(UsersServices $usersServices)
    {
        $this->usersServices = $usersServices;
    }

    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }
    //display all users with their roles
    public function display()
    {
        $users = $this->usersServices->usersRoles();
        return view('admin.manage-accounts', compact('users'));
    }
    // update loggedIn profile
    public function updateProfile(ProfileUpdateRequest $request): RedirectResponse
    {
        $this->usersServices->updateProfile($request->user(), $request->validated());

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    // delete loggedIn profile
    public function destroyProfile(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $success = $this->usersServices->deleteAccount($request->user(), $request->password);

        if (! $success) {
            return back()->withErrors([
                'password' => 'Invalid password.',
            ])->withInput();
        }

        return Redirect::to('/');
    }

    // register a manager
    public function registerManager(Request $request, UsersServices $userServices)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|email|unique:users',
            'password' => ['required', 'confirmed', 'min:8', 'regex:/[A-Z]/', 'regex:/[0-9]/', 'regex:/[@$!%*?&_]/'],
            'office_id' => 'required|exists:offices,id',
        ]);

        $manager = $userServices->registerManager($validated);

        if (!$manager) {
            return back()->with('error', 'The name or email is already registered.');
        }

        return redirect()->route('admin.manage-accounts')->with('success', 'Cost Center Manager registered successfully!');
    }

    // register a user
    public function registerUser(Request $request, UsersServices $userServices)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|email|unique:users',
            'password' => ['required', 'confirmed', 'min:8', 'regex:/[A-Z]/', 'regex:/[0-9]/', 'regex:/[@$!%*?&_]/'],
        ]);

        $user = $userServices->registerUser($validated);

        if (!$user) {
            return back()->with('error', 'The name or email is already registered.');
        }

        return back()->with('success', 'User registered successfully!');
    }

    // search manager's and user's
    public function search(Request $request)
    {
        $search = $request->input('search');

        if (!$search) {
            return redirect()->route('admin.manage-accounts');
        }

        $users = $this->usersServices->searchUsers($search);
        return view('admin.manage-accounts', compact('users', 'search'));
    }

    // update the users and managers
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
        ]);

        $this->usersServices->updateUser($user, $validated);

        return redirect()->back()->with('success', 'User updated successfully.');
    }

    // delete the managers and users
    public function destroy(Request $request, User $user)
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required'],
        ]);

        $success = $this->usersServices->deleteUser($user, $request->password);

        if (!$success) {
            return back()->withErrors([
                'password' => 'Password does not match your current password.',
            ])->withInput();
        }

        return redirect()->route('admin.manage-accounts')->with('success', 'User deleted successfully!');
    }

    //unlock profile
    public function unlock(User $user)
    {
        $user->update([
            'is_locked' => false,
            'login_attempts' => 0,
        ]);

        return back()->with('success', 'Account unlocked successfully!');
    }
}
