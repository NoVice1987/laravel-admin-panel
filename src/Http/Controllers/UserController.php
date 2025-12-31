<?php

namespace StatisticLv\AdminPanel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use StatisticLv\AdminPanel\Models\AdminUser;

class UserController extends Controller
{
    /**
     * Display a listing of admin users.
     *
     * @return \Illuminate\View\View
     */
    public function index(): \Illuminate\View\View
    {
        $users = AdminUser::withTrashed()
            ->orderBy('created_at', 'desc')
            ->paginate(config('admin-panel.per_page', 15));

        return view('admin-panel::users.index', compact('users'));
    }

    /**
     * Show the form for creating a new admin user.
     *
     * @return \Illuminate\View\View
     */
    public function create(): \Illuminate\View\View
    {
        return view('admin-panel::users.create');
    }

    /**
     * Store a newly created admin user in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:admin_users,email',
            'password' => 'required|min:8|regex:/[A-Z]/|regex:/[a-z]/|regex:/[0-9]/',
            'role' => 'required|in:admin,super_admin',
            'is_active' => 'boolean',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = $request->has('is_active');

        $user = AdminUser::create($validated);

        Log::info('Admin user created by super admin', [
            'created_user_id' => $user->id,
            'created_user_email' => $user->email,
            'created_by' => auth()->guard('admin')->id(),
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Admin user created successfully.');
    }

    /**
     * Display the specified admin user.
     *
     * @param \StatisticLv\AdminPanel\Models\AdminUser $user
     * @return \Illuminate\View\View
     */
    public function show(AdminUser $user): \Illuminate\View\View
    {
        $user->load(['news' => function ($query) {
            $query->withTrashed()->orderBy('created_at', 'desc');
        }]);

        return view('admin-panel::users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified admin user.
     *
     * @param \StatisticLv\AdminPanel\Models\AdminUser $user
     * @return \Illuminate\View\View
     */
    public function edit(AdminUser $user): \Illuminate\View\View
    {
        return view('admin-panel::users.edit', compact('user'));
    }

    /**
     * Update the specified admin user in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \StatisticLv\AdminPanel\Models\AdminUser $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, AdminUser $user): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:admin_users,email,' . $user->id,
            'password' => 'nullable|min:8|regex:/[A-Z]/|regex:/[a-z]/|regex:/[0-9]/',
            'role' => 'required|in:admin,super_admin',
            'is_active' => 'boolean',
        ]);

        // Prevent super admin from changing their own role or deactivating themselves
        if ($user->id === auth()->guard('admin')->id()) {
            if ($user->role !== $validated['role']) {
                return redirect()
                    ->route('admin.users.edit', $user)
                    ->with('error', 'You cannot change your own role.');
            }

            if (!$request->has('is_active') && $user->is_active) {
                return redirect()
                    ->route('admin.users.edit', $user)
                    ->with('error', 'You cannot deactivate your own account.');
            }
        }

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['is_active'] = $request->has('is_active');

        $user->update($validated);

        Log::info('Admin user updated by super admin', [
            'updated_user_id' => $user->id,
            'updated_user_email' => $user->email,
            'updated_by' => auth()->guard('admin')->id(),
        ]);

        return redirect()
            ->route('admin.users.edit', $user)
            ->with('success', 'Admin user updated successfully.');
    }

    /**
     * Remove the specified admin user from storage.
     *
     * @param \StatisticLv\AdminPanel\Models\AdminUser $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(AdminUser $user): \Illuminate\Http\RedirectResponse
    {
        // Prevent super admin from deleting themselves
        if ($user->id === auth()->guard('admin')->id()) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        Log::info('Admin user deleted by super admin', [
            'deleted_user_id' => $user->id,
            'deleted_user_email' => $user->email,
            'deleted_by' => auth()->guard('admin')->id(),
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Admin user deleted successfully.');
    }

    /**
     * Restore a soft deleted admin user.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore(int $id): \Illuminate\Http\RedirectResponse
    {
        $user = AdminUser::withTrashed()->findOrFail($id);
        $user->restore();

        Log::info('Admin user restored by super admin', [
            'restored_user_id' => $user->id,
            'restored_user_email' => $user->email,
            'restored_by' => auth()->guard('admin')->id(),
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Admin user restored successfully.');
    }

    /**
     * Permanently delete a soft deleted admin user.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function forceDelete(int $id): \Illuminate\Http\RedirectResponse
    {
        $user = AdminUser::withTrashed()->findOrFail($id);

        // Prevent super admin from permanently deleting themselves
        if ($user->id === auth()->guard('admin')->id()) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'You cannot permanently delete your own account.');
        }

        $user->forceDelete();

        Log::info('Admin user permanently deleted by super admin', [
            'deleted_user_id' => $user->id,
            'deleted_user_email' => $user->email,
            'deleted_by' => auth()->guard('admin')->id(),
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Admin user permanently deleted successfully.');
    }
}
