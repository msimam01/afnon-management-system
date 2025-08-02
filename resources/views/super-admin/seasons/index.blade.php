@extends('layouts.layout')

@section('content')
    <div class="p-6">
        <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">📅 Seasons Overview</h2>
                <a href="{{ route('superadmin.seasons.create') }}"
                    class="bg-emerald-600 text-white px-5 py-2 rounded hover:bg-emerald-700 transition">
                    + New Season
                </a>
            </div>

            @if ($seasons->count())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($seasons as $season)
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md border dark:border-gray-700">
                            <div class="p-4 border-b dark:border-gray-600 flex justify-between items-center">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $season->name }}
                                </h3>
                                <span
                                    class="text-xs font-medium px-2 py-1 rounded-full
                            {{ $season->status === 'open'
                                ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300'
                                : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                                    {{ ucfirst($season->status) }}
                                </span>
                            </div>
                            <div class="p-4 text-sm space-y-2 text-gray-700 dark:text-gray-300">
                                <p><strong>📆 Start:</strong> {{ $season->start_date }}</p>
                                <p><strong>📆 End:</strong> {{ $season->end_date }}</p>
                                <p><strong>💰 Budget:</strong> ₦{{ number_format($season->budget, 0) }}</p>
                                <p><strong>📦 Commodities:</strong>
                                    @if ($season->commodities)
                                        {{ implode(', ', json_decode($season->commodities, true)) }}
                                    @else
                                        <span class="italic text-gray-400">None</span>
                                    @endif
                                </p>
                            </div>
                            <div class="p-4 border-t dark:border-gray-600 flex justify-between items-center">
                                <form method="POST" action="{{ route('superadmin.seasons.sync', $season->id) }}">
                                    @csrf
                                    <button type="submit"
                                        class="text-xs text-blue-600 hover:underline dark:text-blue-400">🔄
                                        Sync</button>
                                </form>
                                <!-- Close/Reopen Button -->
                                @if ($season->status === 'open')
                                    <form method="POST" action="{{ route('superadmin.seasons.close', $season->id) }}"
                                        onsubmit="return confirm('Are you sure you want to close this season?');">
                                        @csrf
                                        <button type="submit"
                                            class="text-xs text-red-600 hover:underline dark:text-red-400">🚫
                                            Close</button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('superadmin.seasons.reopen', $season->id) }}"
                                        onsubmit="return confirm('Reopen this season for allocations and distributions?');">
                                        @csrf
                                        <button type="submit"
                                            class="text-xs text-green-600 hover:underline dark:text-green-400">✅
                                            Reopen</button>
                                    </form>
                                @endif
                                <a href="{{ route('superadmin.seasons.quotas.create', $season->id) }}"
                                    class="text-xs text-emerald-600 hover:underline dark:text-emerald-400">📦 Manage
                                    Quotas</a>
                            </div>

                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg text-center text-gray-600 dark:text-gray-400">
                    No seasons created yet. Click the button above to create your first one.
                </div>
            @endif
        </div>
    </div>
@endsection
