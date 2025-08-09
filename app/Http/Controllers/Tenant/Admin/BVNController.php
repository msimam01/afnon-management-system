<?php

namespace App\Http\Controllers\Tenant\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class BVNController extends Controller
{
    public function verifyBVN(Request $request)
    {
        $request->validate([
            'bvn' => 'required|digits:11'
        ]);

        try {
            $response = Http::withHeaders([
                'mono-sec-key' => env('MONO_SECRET_KEY'), // put your key in .env
                'Accept'       => 'application/json'
            ])->post('https://api.withmono.com/v2/lookup/bvn/initiate', [
                'bvn' => $request->bvn
            ]);

            if ($response->failed()) {
                return back()->withErrors(['bvn' => 'BVN verification failed. Please try again.']);
            }

            $data = $response->json();

            // Check if valid
            if (!isset($data['data'])) {
                return back()->withErrors(['bvn' => 'Invalid BVN or not found in database.']);
            }

            // Example: return the details
            return view('bvn.result', [
                'details' => $data['data']
            ]);

        } catch (\Exception $e) {
            return back()->withErrors(['bvn' => 'Error: ' . $e->getMessage()]);
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        //
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
