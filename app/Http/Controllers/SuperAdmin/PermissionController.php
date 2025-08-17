<?php

namespace App\Http\Controllers\SuperAdmin;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Devrabiul\ToastMagic\Facades\ToastMagic;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::orderBy('name')
            ->get(['id', 'name']);
        return view('super-admin.permissions.index', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('permissions', 'name')
            ]
        ]);

        $permission = Permission::create([
            'name'       => $request->name,
            'guard_name' => 'web',
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success'    => true,
                'message'    => 'Permission created successfully.',
                'permission' => $permission
            ]);
        }
        ToastMagic::success('Permission created successfully.');
        return redirect()->route('superadmin.permissions.index');
    }

    public function update(Request $request, Permission $permission)
    {
        $this->authorizePermission($permission);

        $request->validate([
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('permissions', 'name')
                    ->ignore($permission->id)
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
        return redirect()->route('superadmin.permissions.index');
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
        return redirect()->route('superadmin.permissions.index');
    }

    private function authorizePermission(Permission $permission)
    {
        if ($permission->tenant_id !== tenant('id')) {
            abort(403, 'Unauthorized action.');
        }
    }
}
