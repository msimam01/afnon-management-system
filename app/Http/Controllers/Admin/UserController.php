<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Admin\Role;
use Illuminate\Http\Request;
use App\Mail\UserCreatedMail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Permission;
use Devrabiul\ToastMagic\Facades\ToastMagic;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::all();

        $users = User::with('roles')->get()->transform(fn($user) => [
            'uuid' => $user->uuid ?? null,
            'name' => $user->name ?? null,
            'email' => $user->email ?? null,
            'role' => $user->roles->pluck('name')->implode(', ') ?: 'No Role Assigned',
            'status' => $user->status,
        ]);

        return view('admin.users.index', compact('roles', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'roles' => 'required|array',
        ]);

        // Generate default password
        $defaultPassword = 'password'; // or Str::random(8);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($defaultPassword),
        ]);

        $user->syncRoles($request->roles);

        if ($request->has('permissions')) {
            $user->syncPermissions($request->permissions);
        }

        // Send email with default password
        Mail::to($user->email)->send(new UserCreatedMail($user, $defaultPassword));

        ToastMagic::success('User created successfully and email sent');
        return redirect()->back();
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($uuid)
    {
        $user = User::whereUuid($uuid)->first();

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->roles->pluck('name'),
                'permissions' => $user->permissions->pluck('name'),
            ],
            'all_roles' => Role::all()->pluck('name'),
            'all_permissions' => Permission::all()->pluck('name'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $uuid)
    {
        $user = User::whereUuid($uuid)->first();

        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'roles' => 'required|array',
            'permissions' => 'nullable|array',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $user->syncRoles($request->roles);
        $user->syncPermissions($request->permissions ?? []);

        ToastMagic::success('User updated successfully');
        return redirect()->back();
    }


    public function toggleStatus($uuid)
    {
        $user = User::whereUuid($uuid)->firstOrFail();

        $user->status = $user->status === 'active' ? 'inactive' : 'active';
        $user->save();

        ToastMagic::success("User status updated to {$user->status}");
        return redirect()->back();
    }

    /**
     * 
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
