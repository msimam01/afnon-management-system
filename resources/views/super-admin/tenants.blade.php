@extends('layouts.layout')

@section('content')
    <div id="zones-section" class="w-full min-h-screen px-4 py-6 bg-gray-50 dark:bg-gray-900">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">States & Zones Management</h3>
                <button onclick="openZoneModal()"
                    class="bg-emerald-600 text-white px-4 py-2 rounded-md hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    + Add New Zone
                </button>
            </div>

            <!-- Search Field -->
            <div class="p-4">
                <input type="text" placeholder="Search zones or states..." id="zoneSearchInput"
                    class="w-full px-4 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-emerald-500 focus:border-emerald-500">
            </div>

            <!-- Zones -->
            <div class="mt-8 bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Tenants (States/Zones)</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                    Each zone operates as a separate tenant with isolated data. Use this panel to manage access
                    and tenant settings.
                </p>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="zoneList">
                    <!-- Tenant Card Example -->
                    <div
                        class="tenant-card border border-gray-200 dark:border-gray-600 rounded-lg p-4 bg-white dark:bg-gray-800">
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">North Central</h3>
                            <span
                                class="text-xs px-2 py-1 rounded-full bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300">Active</span>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Tenant ID:
                            <code>north-central</code>
                        </p>
                        <p class="text-xs mt-2 text-gray-500 dark:text-gray-400">States: FCT, Benue, Kogi,
                            Kwara, Nasarawa, Niger, Plateau</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Farmers: 2,450 • Agents: 12</p>

                        <div class="mt-3 flex justify-between items-center text-sm">
                            <button onclick="switchTenant('north-central')"
                                class="text-emerald-600 hover:underline">Switch</button>
                            <button onclick="editTenant('north-central')"
                                class="text-blue-500 hover:underline">Edit</button>
                            <button onclick="deleteTenant('north-central')"
                                class="text-red-500 hover:underline">Delete</button>
                        </div>
                    </div>

                    <!-- Repeat tenant-card for each zone -->
                </div>
            </div>
        </div>


    </div>
        <!-- Add Zone Modal -->
    <div id="zoneModal"
        class="hidden fixed inset-0 z-50 flex items-start justify-center bg-black bg-opacity-50 overflow-y-auto">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-2xl mt-20 mx-4 p-6 relative">

            <!-- Modal Header -->
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Add New Zone</h3>
                <button onclick="closeZoneModal()"
                    class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 text-lg">✕</button>
            </div>

            <!-- Modal Form -->
            <form id="zoneForm" class="space-y-6">
                <!-- Zone Name -->
                <div>
                    <label for="zoneName" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Zone Name
                        *</label>
                    <input type="text" id="zoneName" name="zoneName" required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>

                <!-- Tenant Slug -->
                <div>
                    <label for="zoneSlug" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tenant Slug
                        *</label>
                    <input type="text" id="zoneSlug" name="zoneSlug" required placeholder="e.g. north-central"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>

                <!-- States Under This Zone -->
                <div>
                    <label for="zoneStates" class="block text-sm font-medium text-gray-700 dark:text-gray-300">States
                        Under Zone *</label>
                    <textarea id="zoneStates" name="zoneStates" rows="3" required
                        placeholder="Separate states with commas"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white"></textarea>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit"
                        class="bg-emerald-600 text-white py-2 px-6 rounded-md hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-500 font-medium transition-colors">
                        Add Zone
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection
