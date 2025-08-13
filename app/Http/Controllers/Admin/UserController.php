<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Admin\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
        'role' => $user->roles->pluck('name')->implode(', '), // show all roles
    ]);

    return view('admin.users.index', compact('roles', 'users'));
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt('password'),
        ])->assignRole($request->role);
        if ($user) {
           ToastMagic::success('User created successfully');
           return redirect()->back();
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
