@extends('layouts.layout')

@section('content')
<div class="p-6">
    <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-white">Manage Seasons</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($seasons as $season)
        <div class="bg-white dark:bg-gray-800 border p-4 rounded">
            <h3 class="font-semibold text-lg text-gray-800 dark:text-white">{{ $season->name }}</h3>
            <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">Start: {{ $season->start_date }} → {{ $season->end_date }}</p>
            <p class="text-sm text-gray-600 dark:text-gray-300">Return Deadline: {{ $season->return_deadline }}</p>
            <p class="text-sm text-gray-600 dark:text-gray-300">Insurance Rate: {{ $season->insurance_rate }}%</p>

            <p class="mt-2 text-sm">Status:
                <span class="font-medium {{ $season->status === 'open' ? 'text-green-600' : 'text-red-600' }}">
                    {{ ucfirst($season->status) }}
                </span>
            </p>

            <div class="mt-3 flex gap-3">
                <a href="{{ route('admin.seasons.edit', $season->uuid) }}"
                   class="text-sm text-blue-600 hover:underline">✏️ Edit</a>

                <form method="POST" action="{{ route('admin.seasons.update', $season->uuid) }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="{{ $season->status === 'open' ? 'closed' : 'open' }}">
                    <button type="submit" class="text-sm text-red-600 hover:underline">
                        {{ $season->status === 'open' ? '🔒 Close' : '🔓 Reopen' }}
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
