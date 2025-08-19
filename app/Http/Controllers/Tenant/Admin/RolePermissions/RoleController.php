<?php

namespace App\Http\Controllers\Tenant\Admin\RolePermissions;

use App\Models\Admin\Role;
use Illuminate\Http\Request;
use App\Models\Admin\Permission;
use App\Http\Controllers\Controller;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // App\Http\Controllers\Tenant\Admin\RolePermissions\RoleController.php


    public function index()
    {
        $permissions = Permission::where('tenant_id', tenant('id'))
            ->orderBy('name')
            ->get(['id', 'name']);

        $roles = Role::where('tenant_id', tenant('id'))
            ->with('permissions:id,name') // eager-load
            ->orderBy('name')
            ->get(['id', 'name', 'tenant_id']);

        // roleId => [permissionId, ...]
        $rolePermissions = [];
        foreach ($roles as $role) {
            $rolePermissions[$role->id] = $role->permissions->pluck('id')->toArray();
        }

        return view('admin.roles.index', compact('roles', 'permissions', 'rolePermissions'));
    }

    public function togglePermission(Request $request, Role $role)
    {
        $this->authorizeRole($role);

        $request->validate([
            'permission_id' => [
                'required',
                'integer',
                Rule::exists('permissions', 'id')->where(
                    fn($q) =>
                    $q->where('tenant_id', tenant('id'))->where('guard_name', 'web')
                ),
            ],
            'grant' => ['required', 'boolean'],
        ]);

        // Always resolve the permission within the current tenant
        $permission = Permission::where('id', $request->permission_id)
            ->where('tenant_id', tenant('id'))
            ->firstOrFail();

        if ($request->boolean('grant')) {
            $role->givePermissionTo($permission);   // pass model instance
        } else {
            $role->revokePermissionTo($permission); // pass model instance
        }

        return response()->json([
            'success' => true,
            'role_id' => $role->id,
            'permission_id' => (int) $permission->id,
            'granted' => $request->boolean('grant'),
        ]);
    }



    public function create()
    {
        $permissions = Permission::where('tenant_id', tenant('id'))->get();
        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'array'
        ]);

        $role = Role::create([
            'name' => $request->name,
            'guard_name' => 'web',
            'tenant_id' => tenant('id')
        ]);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Role created successfully.',
                'role' => $role
            ]);
        }
        ToastMagic::success('Role created successfully.');
        return redirect()->route('admin.roles.index');
    }

    public function update(Request $request, Role $role)
    {
        $this->authorizeRole($role);

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'array'
        ]);

        $role->update([
            'name' => $request->name
        ]);

        $role->syncPermissions($request->permissions ?? []);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Role updated successfully.',
                'role' => $role
            ]);
        }
        ToastMagic::success('Role updated successfully.');
        return redirect()->route('admin.roles.index');
    }

    public function destroy(Request $request, Role $role)
    {
        $this->authorizeRole($role);

        $role->delete();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Role deleted successfully.'
            ]);
        }
        ToastMagic::success('Role deleted successfully.');
        return redirect()->route('admin.roles.index');
    }

    private function authorizeRole(Role $role)
    {
        if ($role->tenant_id !== tenant('id')) {
            abort(403, 'Unauthorized action.');
        }
    }
}
