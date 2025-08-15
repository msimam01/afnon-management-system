<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\ReturnVerification;
use App\Http\Controllers\Controller;
use App\Models\CollectionVerification;

class AdminVerificationController extends Controller
{
    public function collections()
    {
        $verifications = CollectionVerification::with(['application.farmer', 'agent'])->latest()->get();
        return view('admin.verifications.collections', compact('verifications'));
    }

    public function approveCollection($id)
    {
        $verification = CollectionVerification::findOrFail($id);
        $verification->update(['status' => 'approved']);
        return back()->with('success', 'Collection approved.');
    }

    public function rejectCollection($id)
    {
        $verification = CollectionVerification::findOrFail($id);
        $verification->update(['status' => 'rejected']);
        return back()->with('success', 'Collection rejected.');
    }

    public function returns()
    {
        $verifications = ReturnVerification::with(['application.farmer', 'agent'])->latest()->get();
        return view('admin.verifications.returns', compact('verifications'));
    }

    public function approveReturn($id)
    {
        $verification = ReturnVerification::findOrFail($id);
        $verification->update(['status' => 'approved']);
        return back()->with('success', 'Return approved.');
    }

    public function rejectReturn($id)
    {
        $verification = ReturnVerification::findOrFail($id);
        $verification->update(['status' => 'rejected']);
        return back()->with('success', 'Return rejected.');
    }
}
