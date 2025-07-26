<?php

namespace App\Http\Controllers\SuperAdmin;

use Log;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\SuperAdmin\Tenant;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class TenantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tenants = Tenant::all();
        return view('super-admin.tenants.index', compact('tenants'));
    }

    public function create()
    {
        return view('super-admin.tenants.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'id' => 'required|string|alpha_dash|unique:tenants,id',
            'name' => 'required|string',
            'domain' => 'required|url',
        ]);

        $host = parse_url($request->domain, PHP_URL_HOST);

        $tenant = Tenant::create([
            'id' => $request->id,
            'data' => [
                'name' => $request->name,
            ],
        ]);

        $tenant->domains()->create([
            'domain' => $host,
        ]);

        // 3. Switch context to this tenant
        tenancy()->initialize($tenant);

        // 4. Create default admin user for this tenant
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@' . $tenant->id . '.com',
            'password' => Hash::make('password'), // Or generate a random one
        ]);

        // 5. Create 'admin' role if not exists, then assign it
        if (!Role::where('name', 'admin')->exists()) {
            Role::create(['name' => 'admin']);
        }

        $admin->assignRole('admin');

        tenancy()->end(); // switch back to central

        return redirect()->route('superadmin.tenants')->with('success', 'Tenant created and admin seeded!');
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
