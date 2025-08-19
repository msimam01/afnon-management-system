@extends('layouts.layout')

@section('content')
    <div id="settings-section" class="w-full min-h-screen px-4 py-6 bg-gray-50 dark:bg-gray-900">
        <!-- System Settings -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">System Settings</h3>
            </div>

            <div class="p-6">
                <div class="space-y-8">

                    <!-- Organization Details -->
                    <section>
                        <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4">Organization
                            Information</h4>
                            <form action="{{ route('superadmin.settings.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block dark:text-gray-300 mb-2 text-gray-900">Organization Name</label>
                                        <input type="text" name="org_name" value="{{ $setting->org_name ?? '' }}" class="w-full border rounded p-2">
                                    </div>
                            
                                    <div>
                                        <label class="block dark:text-gray-300 mb-2 text-gray-900">Email</label>
                                        <input type="email" name="email" value="{{ $setting->email ?? '' }}" class="w-full border rounded p-2">
                                    </div>
                            
                                    <div>
                                        <label class="block dark:text-gray-300 mb-2 text-gray-900">Phone</label>
                                        <input type="text" name="phone" value="{{ $setting->phone ?? '' }}" class="w-full border rounded p-2">
                                    </div>
                            
                                    <div>
                                        <label class="block dark:text-gray-300 mb-2 text-gray-900">Address</label>
                                        <input type="text" name="address" value="{{ $setting->address ?? '' }}" class="w-full border rounded p-2">
                                    </div>
                            
                                    <div>
                                        <label class="block dark:text-gray-300 mb-2 text-gray-900">Logo</label>
                                        <input type="file" name="logo" class="w-full border rounded p-2">
                                        @if(!empty($setting->logo))
                                            <img src="{{ asset('storage/' . $setting->logo) }}" alt="Logo" class="h-16 mt-2">
                                        @endif
                                    </div>
                                </div>
                            
                                <div class="mt-6">
                                    <button type="submit" class="bg-emerald-600 text-white px-6 py-2 rounded-md">
                                        Save Settings
                                    </button>
                                </div>
                            </form>
                            
                    </section>
                </div>
            </div>
        </div>
    </div>
@endsection
