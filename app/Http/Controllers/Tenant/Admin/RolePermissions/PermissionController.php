<?php

namespace App\Http\Controllers\Tenant\Admin\RolePermissions;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Admin\Permission;
use App\Http\Controllers\Controller;
use Devrabiul\ToastMagic\Facades\ToastMagic;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = Permission::where('tenant_id', tenant('id'))
            ->orderBy('name')
            ->get(['id', 'name']);
        return view('admin.permissions.index', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('permissions', 'name')->where(fn($q) => 
                    $q->where('tenant_id', tenant('id'))
                )
            ]
        ]);

        $permission = Permission::create([
            'name'       => $request->name,
            'guard_name' => 'web',
            'tenant_id'  => tenant('id')
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success'    => true,
                'message'    => 'Permission created successfully.',
                'permission' => $permission
            ]);
        }
        ToastMagic::success('Permission created successfully.');
        return redirect()->route('admin.permissions.index');
    }

    public function update(Request $request, Permission $permission)
    {
        $this->authorizePermission($permission);

        $request->validate([
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('permissions', 'name')
                    ->ignore($permission->id)
                    ->where(fn($q) => $q->where('tenant_id', tenant('id')))
            ]
        ]);

        $permission->update([
            'name' => $request->name
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success'    => true,
                'message'    => 'Permission updated successfully.',
                'permission' => $permission
            ]);
        }
        ToastMagic::success('Permission updated successfully.');
        return redirect()->route('admin.permissions.index');
    }

    public function destroy(Request $request, Permission $permission)
    {
        $this->authorizePermission($permission);
        $permission->delete();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Permission deleted successfully.'
            ]);
        }
        ToastMagic::success('Permission deleted successfully.');
        return redirect()->route('admin.permissions.index');
    }

    private function authorizePermission(Permission $permission)
    {
        if ($permission->tenant_id !== tenant('id')) {
            abort(403, 'Unauthorized action.');
        }
    }
}
