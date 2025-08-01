@extends('layouts.layout')

@section('content')
    <div id="returns-section" class="w-full min-h-screen px-4 py-6 bg-gray-50 dark:bg-gray-900">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Collection & Return Centers</h1>
            <button onclick="openCenterModal()"
                class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-md text-sm font-medium transition">
                + Add Center
            </button>
        </div>

        <!-- Body -->
        <div class="p-6">
            <div class="overflow-x-auto">
                <div class="max-w-6xl mx-auto">
                    <!-- Dynamic Centers Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Example Center Card -->
                        <div
                            class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-5 shadow-sm space-y-4 transition hover:shadow-md">
                            <div class="space-y-1">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Ikeja Main Center</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400"><strong>Type:</strong>
                                    Collection + Return</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400"><strong>State:</strong> Lagos</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400"><strong>Address:</strong> 12 Airport
                                    Road, Ikeja</p>
                            </div>

                            <!-- Assigned Users -->
                            <div class="text-sm space-y-2">
                                <div>
                                    <p class="font-semibold text-gray-700 dark:text-gray-300">Assigned Agents:</p>
                                    <ul class="ml-4 list-disc space-y-1 text-gray-700 dark:text-gray-300">
                                        <li>Abdul Musa</li>
                                        <li>Mary Okon</li>
                                    </ul>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-700 dark:text-gray-300">Assigned Farmers:</p>
                                    <ul class="ml-4 list-disc space-y-1 text-gray-700 dark:text-gray-300">
                                        <li>Suleman J.</li>
                                        <li>Grace A.</li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex flex-wrap gap-3 pt-2 text-sm">
                                <button class="text-emerald-600 dark:text-emerald-400 hover:underline"
                                    onclick="openAssignModal()">Assign Agent</button>
                                <button class="text-blue-600 dark:text-blue-400 hover:underline">Edit</button>
                                <button class="text-red-600 dark:text-red-400 hover:underline">Delete</button>
                            </div>
                        </div>
                        <!-- Repeat Center Cards Dynamically -->
                    </div>

                    <!-- If no centers -->
                    <!-- <p class="text-center text-gray-500 dark:text-gray-400 mt-10">No centers found. Add a new one to get started.</p> -->
                </div>
            </div>
        </div>
    </div>
</div>

  <!-- Add/Edit Center Modal -->
<div id="centerModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center px-4">
  <div class="bg-white dark:bg-gray-800 w-full max-w-lg rounded-lg shadow-lg p-6">
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-xl font-bold text-gray-900 dark:text-white">Add New Center</h2>
      <button onclick="closeCenterModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
        ✕
      </button>
    </div>

    <form id="centerForm" class="space-y-5">
      <div>
        <label for="centerName" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Center Name</label>
        <input type="text" id="centerName" name="centerName" required
          class="mt-1 w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-emerald-500 focus:border-emerald-500">
      </div>

      <div>
        <label for="centerType" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Center Type</label>
        <select id="centerType" name="centerType" required
          class="mt-1 w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-emerald-500 focus:border-emerald-500">
          <option value="">Select type</option>
          <option value="collection">Collection Only</option>
          <option value="return">Return Only</option>
          <option value="both">Collection + Return</option>
        </select>
      </div>

      <div>
        <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Address</label>
        <textarea id="address" name="address" rows="2"
          class="mt-1 w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-emerald-500 focus:border-emerald-500"
          placeholder="Full address..."></textarea>
      </div>

      <div class="flex justify-end space-x-3 pt-2">
        <button type="button" onclick="closeCenterModal()"
          class="px-4 py-2 bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-white rounded-md hover:bg-gray-300 dark:hover:bg-gray-500">Cancel</button>
        <button type="submit"
          class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700">Save Center</button>
      </div>
    </form>
  </div>
</div>


  <!-- Assign Agent Modal -->
<div id="assignModal" class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center px-4">
  <div class="bg-white dark:bg-gray-800 w-full max-w-lg rounded-lg shadow-lg p-6">
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-xl font-bold text-gray-900 dark:text-white">Assign Agents to Center</h2>
      <button onclick="closeAssignModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">✕</button>
    </div>

    <form id="assignForm" class="space-y-5">
      <div>
        <label for="agents" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Select Agent(s)</label>
        <select id="agents" name="agents[]" multiple
          class="mt-1 w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-emerald-500 focus:border-emerald-500">
          <option value="abdul">Abdul Musa</option>
          <option value="mary">Mary Okon</option>
          <option value="fatima">Fatima Bello</option>
          <option value="ibrahim">Ibrahim Lawal</option>
        </select>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Hold Ctrl (Cmd on Mac) to select multiple agents.</p>
      </div>

      <div class="flex justify-end space-x-3">
        <button type="button" onclick="closeAssignModal()"
          class="px-4 py-2 bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-white rounded-md hover:bg-gray-300 dark:hover:bg-gray-500">Cancel</button>
        <button type="submit"
          class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700">Assign</button>
      </div>
    </form>
  </div>
</div>

@endsection
