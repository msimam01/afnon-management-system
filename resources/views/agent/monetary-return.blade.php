@extends('layouts.layout')

@section('content')
    <div class="w-full px-4 py-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-200 dark:border-gray-700 p-6">
            <!-- Header -->
            <div class="mb-8 flex items-center space-x-4">
                <div
                    class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Collected Applications</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Generate invoices for collected applications</p>
                </div>
            </div>

            <!-- Filters -->
            <div
                class="bg-gray-50 dark:bg-gray-700 rounded-xl p-6 mb-6 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <!-- Filters -->
                <form method="GET" action="{{ route('agent.monetary-return') }}"
                    class="bg-gray-50 dark:bg-gray-700 rounded-xl p-6 mb-6 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                    <div class="flex gap-4 flex-wrap">
                        <!-- Search -->
                        <input type="text" name="filter" placeholder="Search Farmer Name, ID, App Ref, or Transaction Ref"
                            value="{{ request('filter') }}"
                            class="px-4 py-3 border rounded-lg dark:bg-gray-800 dark:border-gray-600 text-gray-900 dark:text-white" />

                        <!-- Season Filter -->
                        <select name="season" onchange="this.form.submit()"
                            class="px-4 py-3 border rounded-lg dark:bg-gray-800 dark:border-gray-600 text-gray-900 dark:text-white">
                            <option value="">All Seasons</option>
                            @foreach ($seasons as $season)
                                <option value="{{ $season->slug }}"
                                    {{ request('season') == $season->slug ? 'selected' : '' }}>
                                    {{ $season->name }}
                                </option>
                            @endforeach
                        </select>

                        <!-- Status Filter -->
                        <select name="status" onchange="this.form.submit()"
                            class="px-4 py-3 border rounded-lg dark:bg-gray-800 dark:border-gray-600 text-gray-900 dark:text-white">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="invoiced" {{ request('status') == 'invoiced' ? 'selected' : '' }}>Invoiced
                            </option>
                            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        </select>

                        <!-- Submit Button (for text search) -->
                        <button type="submit" class="px-4 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                            Apply
                        </button>
                    </div>
                </form>

            </div>

            <!-- Applications Table -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gradient-to-r from-indigo-500 to-indigo-600 text-white">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Farmer Details
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Application</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Commodities</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($applications as $app)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $app->farmer->full_name }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $app->farmer->registration_number }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 dark:text-white">{{ $app->season->name ?? 'N/A' }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        <span class="font-medium">App Ref: </span>{{ $app->reference_number }}
                                    </div>
                                    @if($app->monetaryReturn && $app->monetaryReturn->tx_ref)
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            <span class="font-medium">Tx Ref: </span>{{ $app->monetaryReturn->tx_ref }}
                                        </div>
                                    @endif
                                    <div class="text-sm font-semibold text-indigo-600 dark:text-indigo-400">
                                        ₦{{ number_format($app->total_loan) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @foreach ($app->commodity_allocations as $c)
                                        <div class="text-sm">{{ $c->commodity_name }} ({{ $c->allocated_quantity }})</div>
                                    @endforeach
                                </td>
                                <td class="px-6 py-4">
                                    @if ($app->payment_status === 'paid')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
        bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                            Paid
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
        bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                            {{ ucfirst($app->payment_status) }}
                                        </span>
                                    @endif

                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $monetaryReturn = $app->monetaryReturn;
                                    @endphp

                                    <div class="flex space-x-2">
                                        @if ($monetaryReturn)
                                            <a href="{{ route('agent.monetary-returns.show', $monetaryReturn->uuid) }}"
                                               class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @endif

                                        @if ($app->payment_status !== 'paid')
                                            @if ($monetaryReturn && $app->payment_status === 'pending')
                                                <a href="{{ $monetaryReturn->payment_link }}" target="_blank"
                                                    class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                                    Continue Payment
                                                </a>
                                            @else
                                                <form id="initiate-payment-{{ $app->id }}"
                                                    action="{{ route('agent.generatePayment', $app->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit"
                                                        class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                                        Pay Now
                                                    </button>
                                                </form>
                                            @endif
                                        @elseif ($monetaryReturn)
                                            <a href="{{ route('agent.monetary-returns.receipt', $monetaryReturn->uuid) }}"
                                               class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">
                                                <i class="fas fa-receipt"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>





                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">No
                                    collected applications found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $applications->links() }}
            </div>
        </div>
    </div>
@endsection
