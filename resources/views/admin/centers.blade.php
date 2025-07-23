@extends('layouts.layout')

@section('content')
    <div id="returns-section" class="w-full min-h-screen px-4 py-6 bg-gray-50 dark:bg-gray-900">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex justify-between items-center mb-6">
                            <h1 class="text-2xl dark:text-white font-bold">Collection & Returning Centers</h1>
                            <button onclick="openCenterModal()"
                                class="bg-emerald-600 text-white px-4 py-2 rounded-md hover:bg-emerald-700">
                                + Add Center
                            </button>
                        </div>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <div class="max-w-6xl mx-auto p-6">
                        <!-- Centers Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- Example center -->
                            <div
                                class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-lg p-4 shadow-sm space-y-3">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Ikeja Main Center</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Type: <span
                                        class="font-medium">Collection + Return</span></p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Zone: North Central</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Address: 12 Airport Road, Ikeja, Lagos
                                </p>
                                <div class="text-sm mt-2">
                                    <p class="text-gray-600 dark:text-gray-400 mb-1"><strong>Assigned Agents:</strong></p>
                                    <ul class="ml-4 list-disc space-y-1 text-gray-700 dark:text-gray-300">
                                        <li>Abdul Musa</li>
                                        <li>Mary Okon</li>
                                    </ul>
                                    <p class="mt-2 text-gray-600 dark:text-gray-400 mb-1"><strong>Assigned Farmers:</strong>
                                    </p>
                                    <ul class="ml-4 list-disc space-y-1 text-gray-700 dark:text-gray-300">
                                        <li>Suleman J.</li>
                                        <li>Grace A.</li>
                                    </ul>
                                </div>
                                <div class="flex flex-wrap gap-3 mt-4 text-sm">
                                    <button class="text-emerald-600 dark:text-emerald-400 hover:underline"
                                        onclick="openAssignModal()">Assign Agent</button>
                                    <button class="text-blue-600 dark:text-blue-400 hover:underline">Edit</button>
                                    <button class="text-red-600 dark:text-red-400 hover:underline">Delete</button>
                                </div>
                            </div>
                            <!-- Repeat for other centers dynamically -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  <!-- Add/Edit Center Modal -->
  <div id="centerModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white dark:bg-gray-800 w-full max-w-md rounded-lg shadow-lg p-6">
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold dark:text-white">Add New Center</h2>
        <button onclick="closeCenterModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
          ✕
        </button>
      </div>
      <form id="centerForm" class="space-y-4">
        <div>
          <label for="centerName" class="block text-sm font-medium dark:text-gray-300">Center Name</label>
          <input type="text" id="centerName" name="centerName" required
            class="mt-1 w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" />
        </div>

        <div>
          <label for="centerType" class="block text-sm font-medium dark:text-gray-300">Center Type</label>
          <select id="centerType" name="centerType" required
            class="mt-1 w-full px-3 py-2 rounded-md border dark:text-gray-300 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
            <option value="">Select type</option>
            <option value="collection">Collection Only</option>
            <option value="return">Return Only</option>
            <option value="both">Collection + Return</option>
          </select>
        </div>

        <div>
          <label for="zone" class="block text-sm font-medium dark:text-gray-300">State</label>
          <select id="zone" name="zone" required
            class="mt-1 w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
            <option value="">Select state</option>
            <option value="north-central">Gombe</option>
            <option value="north-east">Bauchi</option>
            <option value="north-west">Adamawa</option>
            <option value="south-east">Kano</option>
            <option value="south-south">Taraba</option>
            <option value="south-west">Yobe</option>
          </select>
        </div>

        <div>
          <label for="address" class="block text-sm font-medium dark:text-gray-300">Address</label>
          <textarea id="address" name="address" rows="2"
            class="mt-1 w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"></textarea>
        </div>

        <div class="flex justify-end">
          <button type="button" onclick="closeCenterModal()"
            class="px-4 py-2 bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-white rounded-md hover:bg-gray-300 dark:hover:bg-gray-500 mr-2">Cancel</button>
          <button type="submit"
            class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700">Save Center</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Assign Agent Modal -->
  <div id="assignModal" class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white dark:bg-gray-800 w-full max-w-md rounded-lg shadow-lg p-6">
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-bold dark:text-gray-300">Assign Agents to Center</h2>
        <button onclick="closeAssignModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
          ✕
        </button>
      </div>
      <form id="assignForm" class="space-y-4">
        <div>
          <label for="agents" class="block text-sm font-medium dark:text-gray-300">Select Agent</label>
          <select id="agents" multiple
            class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
            <option value="abdul">Abdul Musa</option>
            <option value="mary">Mary Okon</option>
            <option value="fatima">Fatima Bello</option>
          </select>
        </div>
        <div class="flex justify-end">
          <button type="button" onclick="closeAssignModal()"
            class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-white rounded-md hover:bg-gray-400 dark:hover:bg-gray-500 mr-2">Cancel</button>
          <button type="submit"
            class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700">Assign</button>
        </div>
      </form>
    </div>
  </div>
@endsection
