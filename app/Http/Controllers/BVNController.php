<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class BVNController extends Controller
{
    public function verifyBVN(Request $request)
    {
        $request->validate([
            'bvn' => 'required|string|size:11'
        ]);

        try {
            $response = Http::withHeaders([
                'Token' => env('YOUVERIFY_API_KEY'),
                'Accept' => 'application/json',
            ])
                ->withoutVerifying() // Remove this in production
                ->post('https://api.sandbox.youverify.co/v2/api/identity/ng/bvn', [
                    "id" => $request->bvn,
                    "isSubjectConsent" => true
                ]);

            Log::info('YouVerify raw response: ' . $response->body());

            if ($response->failed()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Verification request failed.',
                    'error' => $response->json()
                ], $response->status());
            }

            $data = $response->json();

            if (empty($data['success']) || $data['success'] !== true) {
                return response()->json([
                    'status' => false,
                    'message' => $data['message'] ?? 'BVN not found or invalid.'
                ], 422);
            }
            
            // If API says success but no name or details found, treat as invalid
            if (empty($data['data']['firstName']) || empty($data['data']['lastName'])) {
                return response()->json([
                    'status' => false,
                    'message' => 'BVN not found in records.'
                ], 422);
            }
            

            return response()->json([
                'status' => true,
                'message' => 'BVN verified successfully ✅',
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            Log::error('BVN Verification Exception: ' . $th->getMessage(), [
                'trace' => $th->getTraceAsString()
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Server error: ' . $th->getMessage()
            ], 500);
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
