<?php

namespace App\Http\Controllers\SuperAdmin;

use Log;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\SuperAdmin\Tenant;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use App\Jobs\CreateTenantJob;

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

        try {
            $tenant = Tenant::create([
                'id' => $request->id,
                'data' => [
                    'name' => $request->name,
                ],
            ]);

            $tenant->domains()->create([
                'domain' => $host,
            ]);

            // 🚀 Dispatch background job
            CreateTenantJob::dispatch($tenant);
            ToastMagic::info('Tenant creation started in background. You will be notified when ready.');
            return redirect()->route('superadmin.tenants.index');
        } catch (\Throwable $e) {
            \Log::error('Tenant creation error: ' . $e->getMessage());
            ToastMagic::error('Something went wrong while creating the tenant. Please check logs.');
            return redirect()->back()->withInput();
        }
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
