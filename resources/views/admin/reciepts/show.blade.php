@extends('layouts.layout')

@section('content')
<div class="p-6 max-w-3xl mx-auto">
    <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-4">🧾 Invoice: {{ $return->invoice_number ?? 'INV-'.$return->id }}</h2>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 space-y-4 border border-gray-200 dark:border-gray-600">
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div><strong class="text-gray-600 dark:text-gray-300">Farmer:</strong> {{ $return->application->farmer->full_name }}</div>
            <div><strong class="text-gray-600 dark:text-gray-300">Phone:</strong> {{ $return->application->farmer->phone }}</div>
            <div><strong class="text-gray-600 dark:text-gray-300">Season:</strong> {{ $return->application->season->name }}</div>
            <div><strong class="text-gray-600 dark:text-gray-300">Amount:</strong> N{{ number_format($return->amount, 2) }}</div>
        </div>

        <hr class="border-gray-200 dark:border-gray-600">

        <div>
            <h4 class="font-semibold text-gray-700 dark:text-white mb-2">📎 Payment Receipt:</h4>
            @if($return->payment_proof)
                <a href="{{ asset('storage/'.$return->payment_proof) }}" target="_blank"
                   class="text-blue-600 underline">📂 View Receipt</a>
            @else
                <span class="text-red-500">No receipt uploaded.</span>
            @endif
        </div>

        <div class="flex gap-4 pt-4 border-t border-gray-200 dark:border-gray-600">
            <form method="POST" action="{{ route('admin.receipts.verify', $return->id) }}">
                @csrf
                <button type="submit" class="bg-emerald-600 text-white px-4 py-2 rounded hover:bg-emerald-700">✅ Approve</button>
            </form>

            <form method="POST" action="{{ route('admin.receipts.reject', $return->id) }}">
                @csrf
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">❌ Reject</button>
            </form>
        </div>
    </div>
</div>
@endsection
