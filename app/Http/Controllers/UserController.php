<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\Auth\UpdateProfile;
use App\Services\Auth\DeleteProfile;
use App\Services\API\Admin\Accounts\AdminSearchAccountService;
use App\Services\API\Admin\Accounts\AdminDisplayAccountService;
use App\Services\API\Admin\Accounts\AdminDeleteAccountService;
use App\Services\API\Admin\Accounts\AdminUpdateAccountService;
use App\Services\API\Admin\Accounts\AdminCreateAccountService;
use App\Services\API\Manager\Accounts\ManagerCreateAccountService;
use Illuminate\View\View;
use App\Http\Requests\ProfileUpdateRequest;
use App\Services\API\Admin\GrdsList\UpdateGRDSList;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;


class UserController extends Controller
{
    protected $updateProfile;
    protected $deleteProfile;
    protected $adminSearchAccountService;
    protected $adminDisplayAccountService;
    protected $adminDeleteAccountService;
    protected $adminUpdateAccountService;
    protected $adminCreateAccountService;
    protected $managerCreateAccountService;


    public function __construct(
        UpdateProfile $updateProfile,
        DeleteProfile $deleteProfile,
        AdminSearchAccountService $adminSearchAccountService,
        AdminDisplayAccountService $adminDisplayAccountService,
        AdminDeleteAccountService $adminDeleteAccountService,
        AdminUpdateAccountService $adminUpdateAccountService,
        AdminCreateAccountService $adminCreateAccountService,
        ManagerCreateAccountService $managerCreateAccountService,
    ) {
        $this->updateProfile = $updateProfile;
        $this->deleteProfile = $deleteProfile;
        $this->adminSearchAccountService = $adminSearchAccountService;
        $this->adminDisplayAccountService = $adminDisplayAccountService;
        $this->adminDeleteAccountService = $adminDeleteAccountService;
        $this->adminUpdateAccountService = $adminUpdateAccountService;
        $this->adminCreateAccountService = $adminCreateAccountService;
        $this->managerCreateAccountService = $managerCreateAccountService;
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
        $users = $this->adminDisplayAccountService->display();
        return view('admin.manage-accounts', compact('users'));
    }

    // update loggedIn profile
    public function updateProfile(ProfileUpdateRequest $request): RedirectResponse
    {
        $this->updateProfile->update(
            $request->user(),
            $request->validated(),
            // FileUpload
            $request->file('signature'),

            // Canvas
            // $request->input('signature'),
        );

        
        return Redirect::route('profile.edit')
            ->with('status', 'profile-updated');
    }

    // delete loggedIn profile
    public function destroyProfile(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $success = $this->deleteProfile->delete($request->user(), $request->password);

        if (! $success) {
            return back()->withErrors([
                'password' => 'Invalid password.',
            ])->withInput();
        }

        return Redirect::to('/');
    }

    // register a manager
    public function registerManager(Request $request, AdminCreateAccountService $adminCreateAccountService)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|email|unique:users',
            'password' => ['required', 'confirmed', 'min:8', 'regex:/[A-Z]/', 'regex:/[0-9]/', 'regex:/[@$!%*?&_]/'],
            'office_id' => 'required|exists:offices,id',
            'signature' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $manager = $adminCreateAccountService->createAccount($validated);

        if (!$manager) {
            return back()->with('error', 'The name or email is already registered.');
        }

        return redirect()->route('admin.manage-accounts')->with('success', 'Cost Center Manager registered successfully!');
    }

    // register a user
    public function registerUser(Request $request, ManagerCreateAccountService $managerCreateAccountService)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|email|unique:users',
            'password' => ['required', 'confirmed', 'min:8', 'regex:/[A-Z]/', 'regex:/[0-9]/', 'regex:/[@$!%*?&_]/'],
        ]);

        $user = $managerCreateAccountService->createAccount($validated);

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

        $users = $this->adminSearchAccountService->findAccount($search);
        return view('admin.manage-accounts', compact('users', 'search'));
    }

    // update the users and managers
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            // 'signature' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $this->adminUpdateAccountService->update($user, $validated,$request->file('signature'));

        return redirect()->back()->with('success', 'User updated successfully.');
    }

    // delete the managers and users
    public function destroy(Request $request, User $user)
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required'],
        ]);

        $success = $this->adminDeleteAccountService->destroy($user, $request->password);

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
