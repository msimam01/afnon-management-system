<?php

namespace App\Http\Controllers;

use App\Models\MonetaryReturn;
use Illuminate\Http\Request;

class MonetaryReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $returns = MonetaryReturn::with('application.farmer')->where('status', 'paid')->latest()->get();
        return view('admin.reciepts.index', compact('returns'));
    }

    public function show($id)
    {
        $return = MonetaryReturn::with(['application.farmer', 'application.commodities.commodity'])->findOrFail($id);
        return view('admin.receipts.show', compact('return'));
    }

    public function verify($id)
    {
        $return = MonetaryReturn::findOrFail($id);
        $return->update([
            'status' => 'verified',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        return redirect()->route('admin.receipts')->with('success', 'Return verified successfully.');
    }

    public function reject($id)
    {
        $return = MonetaryReturn::findOrFail($id);
        $return->update(['status' => 'rejected']);

        return redirect()->route('admin.receipts')->with('error', 'Return marked as rejected.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MonetaryReturn $monetaryReturn)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MonetaryReturn $monetaryReturn)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MonetaryReturn $monetaryReturn)
    {
        //
    }
}
