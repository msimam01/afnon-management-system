@extends('layouts.layout')

@section('content')
    <div class="p-6">
        <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">🌾 Seasons Overview</h2>
                <a href="{{ route('admin.seasons.create') }}"
                    class="bg-emerald-600 text-white px-5 py-2 rounded hover:bg-emerald-700 transition">
                    + New Season
                </a>
            </div>
            @if ($seasons->count())
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

                    @foreach ($seasons as $season)
                        <div
                            class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border border-gray-200 dark:border-gray-700">
                            <div class="flex justify-between items-center mb-2">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $season->name }}</h3>
                                <span
                                    class="px-2 py-1 text-xs rounded-full {{ $season->status === 'open' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ ucfirst($season->status) }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">📅 {{ $season->start_date }} →
                                {{ $season->end_date }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">📅 Collection Start/End Date:
                                {{ $season->collection_start_date }} → {{ $season->collection_end_date }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">🧭 Scenario:
                                {{ $season->loan_type === 'complete-loan' ? 'Complete Loan (commodity return)' : 'Co-funded (50% upfront)' }}</p>
                            @if ($season->loan_type === 'complete-loan')
                                <p class="text-sm text-gray-600 dark:text-gray-400">📌 Return Deadline:
                                    {{ $season->return_deadline }}</p>
                            @else
                                <p class="text-sm text-gray-600 dark:text-gray-400">📌 Return: Not required</p>
                            @endif
                            <p class="text-sm text-gray-600 dark:text-gray-400">💰 Budget:
                                ₦{{ number_format($season->budget) }}
                            </p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">🛡 Insurance: {{ $season->insurance_rate }}%
                            </p>

                            <div class="mt-4 flex justify-between items-center">
                                <a href="{{ route('admin.seasons.show', $season->uuid) }}"
                                    class="text-sm text-emerald-600 hover:underline">🔍 View Details</a>
                                <a href="{{ route('admin.seasons.edit', $season->uuid) }}"
                                    class="text-sm text-emerald-600 hover:underline">🔄
                                    Edit</a>
                                <!-- Close/Reopen Button -->
                                @if ($season->status === 'open')
                                    <form method="POST" action="{{ route('admin.seasons.close', $season->uuid) }}"
                                        onsubmit="return confirm('Are you sure you want to close this season?');">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit"
                                            class="text-xs text-red-600 hover:underline dark:text-red-400">🚫
                                            Close</button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('admin.seasons.reopen', $season->uuid) }}"
                                        onsubmit="return confirm('Reopen this season for allocations and distributions?');">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit"
                                            class="text-xs text-green-600 hover:underline dark:text-green-400">✅
                                            Reopen</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg text-center text-gray-600 dark:text-gray-400">
                        No seasons created yet. Click the button above to create your first one.
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
