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
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="orgName"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Organization
                                    Name</label>
                                <input type="text" id="orgName" value="NECAS"
                                    class="mt-1 block w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600">
                            </div>
                            <div>
                                <label for="orgLogo"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Organization
                                    Logo</label>
                                <input type="file" id="orgLogo" accept="image/*"
                                    class="mt-1 block w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600">
                            </div>
                        </div>
                    </section>

                    <!-- Notification Settings -->
                    <section>
                        <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4">Notification
                            Preferences</h4>
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <input type="checkbox" id="smsEnabled"
                                    class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 rounded border-gray-300 dark:border-gray-600"
                                    checked>
                                <label for="smsEnabled" class="ml-2 block text-sm text-gray-900 dark:text-white">Enable SMS
                                    Notifications</label>
                            </div>
                            <div>
                                <label for="smsProvider"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">SMS
                                    Provider</label>
                                <select id="smsProvider"
                                    class="mt-1 block w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600">
                                    <option>Twilio</option>
                                    <option>Nexmo</option>
                                    <option>Local Provider</option>
                                </select>
                            </div>
                            <div>
                                <label for="senderId"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Default
                                    Sender ID</label>
                                <input type="text" id="senderId" value="NECAS"
                                    class="mt-1 block w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600">
                            </div>
                        </div>
                    </section>

                    <!-- Commodity Quota Settings -->
                    <section>
                        <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4">Commodity/Quota
                            Defaults</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="defaultCommodityUnit"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Default
                                    Commodity Unit</label>
                                <select id="defaultCommodityUnit"
                                    class="mt-1 block w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600">
                                    <option value="bags">Bags</option>
                                    <option value="kg">Kilograms</option>
                                    <option value="tonnes">Tonnes</option>
                                </select>
                            </div>
                            <div>
                                <label for="defaultCurrency"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Currency</label>
                                <select id="defaultCurrency"
                                    class="mt-1 block w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600">
                                    <option value="NGN">₦ NGN (Naira)</option>
                                    <option value="USD">$ USD</option>
                                </select>
                            </div>
                        </div>
                    </section>

                    <!-- Data Settings -->
                    <section>
                        <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4">Data & Access
                            Settings</h4>
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <input type="checkbox" id="autoTenantIsolation"
                                    class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 rounded border-gray-300 dark:border-gray-600"
                                    checked>
                                <label for="autoTenantIsolation" class="ml-2 block text-sm text-gray-900 dark:text-white">
                                    Enforce Tenant Data Isolation
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="multiAdmin"
                                    class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 rounded border-gray-300 dark:border-gray-600">
                                <label for="multiAdmin" class="ml-2 block text-sm text-gray-900 dark:text-white">
                                    Allow Multiple Admins Per Zone
                                </label>
                            </div>
                        </div>
                    </section>

                    <!-- Save Button -->
                    <div class="flex justify-end">
                        <button type="button"
                            class="bg-emerald-600 text-white px-6 py-2 rounded-md hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                            Save Settings
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
