<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Devrabiul\ToastMagic\Facades\ToastMagic;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::first(); // only one record needed
        return view('admin.settings.index', compact('setting'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'org_name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('', 'tenant_logos');
        }

        Setting::updateOrCreate(['id' => 1], $data);
        ToastMagic::success('Settings saved successfully.');
        return redirect()->back()->with('success', 'Settings saved successfully.');
    }
}
// 