@extends('layouts.layout')

@section('content')
    <!-- Applications Section -->
    <div id="applications-section" class="w-full min-h-screen px-4 py-6 bg-gray-50 dark:bg-gray-900">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Application Management</h3>
            </div>
            <div class="p-6">
                <!-- Filters -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Season</label>
                        <select
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <option>All Seasons</option>
                            <option>2024 Dry Season</option>
                            <option>2024 Wet Season</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                        <select
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <option>All Status</option>
                            <option>Pending</option>
                            <option>Approved</option>
                            <option>Distributed</option>
                            <option>Rejected</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search</label>
                        <input type="text" placeholder="Search farmer..."
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    </div>
                </div>

                <!-- Applications Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Farmer Details</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Application</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Farm Info</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($applications as $app)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                                                    @php($initials = collect(explode(' ', $app->farmer->full_name ?? ''))
                                                        ->map(fn($s) => strtoupper(substr($s,0,1)))
                                                        ->take(2)->implode(''))
                                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $initials }}</span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $app->farmer->full_name ?? '—' }}</div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $app->farmer->phone ?? '—' }}</div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">BVN: {{ $app->farmer->bvn ?? '—' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white">{{ $app->season->name ?? '—' }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            @php($summary = ($app->commodities ?? collect())->map(function($c){
                                                $qty = $c->pivot->quantity ?? $c->quantity ?? null;
                                                return trim($c->name . ($qty ? ' ('.$qty.')' : ''));
                                            })->take(2)->implode(', '))
                                            {{ $summary ?: '—' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white">{{ optional($app->farm)->size ? number_format($app->farm->size, 2) . ' hectares' : '—' }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $app->farmer->address ?? '—' }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $app->farmer->cluster ?? '—' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php($statusColors = [
                                            'pending' => 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200',
                                            'approved' => 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200',
                                            'rejected' => 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200',
                                        ])
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[strtolower($app->status ?? 'pending')] ?? 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200' }}">
                                            {{ ucfirst($app->status ?? 'pending') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        <a href="{{ route('admin.applications.show', $app->uuid) }}" class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-900 dark:hover:text-emerald-300 mr-3">View Full Info</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">No applications found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $applications->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Updated Application Approval Modal -->
    <div id="applicationApprovalModal"
        class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center px-4 sm:px-0">
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-4xl p-6 sm:p-8 overflow-y-auto max-h-[90vh]">

            <!-- Header -->
            <div class="flex justify-between items-center mb-6 border-b pb-4 border-gray-200 dark:border-gray-600">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Approve Application</h3>
                <button onclick="closeApplicationModal()"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Farmer & Application Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border">
                    <h4 class="font-semibold text-gray-800 dark:text-white mb-2">Farmer Info</h4>
                    <ul class="text-sm space-y-1 text-gray-700 dark:text-gray-300" id="farmer-info">
                        <!-- Populated via JS -->
                    </ul>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border">
                    <h4 class="font-semibold text-gray-800 dark:text-white mb-2">Application Info</h4>
                    <ul class="text-sm space-y-1 text-gray-700 dark:text-gray-300" id="application-info">
                        <!-- Populated via JS -->
                    </ul>
                </div>
            </div>

            <!-- Commodity Breakdown -->
            <div class="mb-6">
                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Commodity Breakdown</h4>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm border border-gray-200 dark:border-gray-600 rounded-lg overflow-hidden">
                        <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-white">
                            <tr>
                                <th class="px-4 py-2 text-left">Commodity</th>
                                <th class="px-4 py-2 text-left">Quantity</th>
                                <th class="px-4 py-2 text-left">Unit Price</th>
                                <th class="px-4 py-2 text-left">Total</th>
                            </tr>
                        </thead>
                        <tbody id="commodity-breakdown" class="bg-white dark:bg-gray-800 text-gray-800 dark:text-white">
                            <!-- Injected by JS -->
                        </tbody>
                        <tfoot class="bg-gray-50 dark:bg-gray-700 font-medium text-gray-900 dark:text-white">
                            <tr>
                                <td colspan="3" class="px-4 py-2">Insurance (1%)</td>
                                <td id="breakdown-insurance" class="px-4 py-2"></td>
                            </tr>
                            <tr>
                                <td colspan="3" class="px-4 py-2">Total Loan</td>
                                <td id="breakdown-total" class="px-4 py-2"></td>
                            </tr>
                            <tr>
                                <td colspan="3" class="px-4 py-2">Equity Held</td>
                                <td id="breakdown-equity" class="px-4 py-2"></td>
                            </tr>
                            <tr>
                                <td colspan="3" class="px-4 py-2">Disbursed Amount</td>
                                <td id="breakdown-disbursed" class="px-4 py-2"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Collection & Return Center -->
            <div class="mb-6">
                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Assign Centers</h4>

                <label for="collectionCenter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Collection Center *
                </label>
                <select id="collectionCenter"
                    class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white mb-3">
                    <option value="">-- Select Collection Center --</option>
                    <!-- Filled dynamically -->
                </select>

                <div class="flex items-center mb-2">
                    <input type="checkbox" id="sameAsCollection" onclick="toggleReturnCenter()"
                        class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                    <label for="sameAsCollection" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                        Use same center for return
                    </label>
                </div>

                <label for="returnCenter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Return Center *
                </label>
                <select id="returnCenter"
                    class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    <option value="">-- Select Return Center --</option>
                    <!-- Filled dynamically -->
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                <button onclick="closeApplicationModal()"
                    class="px-5 py-2 bg-gray-100 dark:bg-gray-600 text-gray-800 dark:text-white rounded-md hover:bg-gray-200 dark:hover:bg-gray-500 transition">
                    Cancel
                </button>
                <button onclick="approveApplication()"
                    class="px-5 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 transition">
                    Approve
                </button>
            </div>
        </div>
    </div>
@endsection
