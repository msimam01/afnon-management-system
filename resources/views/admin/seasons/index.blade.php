@extends('layouts.layout')

@section('content')
    <div class="p-6">
        <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-6">
            <h2 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">🌾 Active Seasons</h2>

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
                        <p class="text-sm text-gray-600 dark:text-gray-400">📌 Return Deadline:
                            {{ $season->return_deadline }}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">💰 Budget: ₦{{ number_format($season->budget) }}
                        </p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">🛡 Insurance: {{ $season->insurance_rate }}%</p>

                        <div class="mt-4 flex justify-between items-center">
                            <a href="{{ route('admin.seasons.edit', $season->uuid) }}"
                                class="text-sm text-emerald-600 hover:underline">🔍 View Details</a>

                            {{-- <form method="POST" action="{{ route('admin.seasons.update', $season->uuid) }}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status"
                                    value="{{ $season->status === 'open' ? 'closed' : 'open' }}">
                                <button type="submit" class="text-sm text-blue-600 hover:underline">
                                    {{ $season->status === 'open' ? '🔒 Close' : '🔓 Reopen' }}
                                </button>
                            </form> --}}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
