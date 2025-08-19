<?php

namespace App\Http\Controllers\SuperAdmin;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Devrabiul\ToastMagic\Facades\ToastMagic;

class RoleController extends Controller
{
    
    public function index()
    {
        $permissions = Permission::orderBy('name')
            ->get(['id', 'name']);

        $roles = Role::with('permissions:id,name') // eager-load
            ->orderBy('name')
            ->get(['id', 'name']);

        // roleId => [permissionId, ...]
        $rolePermissions = [];
        foreach ($roles as $role) {
            $rolePermissions[$role->id] = $role->permissions->pluck('id')->toArray();
        }

        return view('super-admin.roles.index', compact('roles', 'permissions', 'rolePermissions'));
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
                    $q->where('guard_name', 'web')
                ),
            ],
            'grant' => ['required', 'boolean'],
        ]);

        // Always resolve the permission within the current tenant
        $permission = Permission::where('id', $request->permission_id)
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
        $permissions = Permission::all();
        return view('super-admin.roles.create', compact('permissions'));
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
        return redirect()->route('superadmin.roles.index');
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
        return redirect()->route('superadmin.roles.index');
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
