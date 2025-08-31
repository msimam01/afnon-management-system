<?php

namespace App\Http\Controllers;

use App\Models\Tenant\User;
use App\Models\Agent;
use App\Models\Center;
use App\Models\Admin\Role;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Devrabiul\ToastMagic\Facades\ToastMagic;

class AgentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Agent::with('user', 'center');

        if ($request->filled('search')) {
            $query->whereHas('user', fn($q) => $q->where('name', 'like', '%' . $request->search . '%'));
        }

        $agents = $query->get();
        $users = User::role('agent')->doesntHave('agent')->get();
        $centers = Center::all();

        return view('admin.agents.index', compact('agents', 'users', 'centers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'center_id' => 'nullable|exists:centers,id',
            'status' => 'required|in:active,inactive',
        ]);

        Agent::create([
            'user_id' => $request->user_id,
            'center_id' => $request->center_id,
            'status' => $request->status,
            'uuid' => Str::uuid(),
        ]);

        ToastMagic::success('Agent created successfully');
        return redirect()->back();
    }



    public function edit($uuid)
    {
        $agent = Agent::with('user', 'center')->whereUuid($uuid)->firstOrFail();

        $users = User::role('agent')
            ->where(function($query) use ($agent) {
                $query->doesntHave('agent')
                      ->orWhere('id', $agent->user_id);
            })
            ->get();

        $centers = Center::all();

        return response()->json([
            'agent' => $agent->load('user', 'center'),
            'users' => $users,
            'centers' => $centers
        ]);
    }

    public function update(Request $request, $uuid)
    {
        try {
            $agent = Agent::whereUuid($uuid)->firstOrFail();

            $validated = $request->validate([
                'user_id' => 'required|exists:users,id|unique:agents,user_id,' . $agent->id,
                'center_id' => 'nullable|exists:centers,id',
                'status' => 'required|in:active,inactive',
            ]);

            $agent->update($validated);
            ToastMagic::success('Agent updated successfully');
            return redirect()->route('admin.agents.index');
        } catch (\Exception $e) {
            ToastMagic::error('Failed to update agent: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function destroy($uuid)
    {
        $agent = Agent::whereUuid($uuid)->firstOrFail();
        $agent->delete();

        ToastMagic::success('Agent deleted successfully');
        return redirect()->back();
    }
}
