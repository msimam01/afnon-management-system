@extends('layouts.layout')

@section('content')
<div class="p-6">
    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">📥 Receipt Verification</h2>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 text-red-800 p-3 rounded mb-4">{{ session('error') }}</div>
    @endif

    <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
            <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-200">
                <tr>
                    <th class="px-4 py-2 text-left">Invoice</th>
                    <th class="px-4 py-2 text-left">Farmer</th>
                    <th class="px-4 py-2 text-left">Amount</th>
                    <th class="px-4 py-2 text-left">Status</th>
                    <th class="px-4 py-2">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                @forelse($returns as $return)
                <tr class="border-t border-gray-200 dark:border-gray-700">
                    <td class="px-4 py-2">{{ $return->invoice_number ?? 'INV-'.$return->id }}</td>
                    <td class="px-4 py-2">{{ $return->application->farmer->full_name }}</td>
                    <td class="px-4 py-2">₦{{ number_format($return->amount, 2) }}</td>
                    <td class="px-4 py-2 capitalize">{{ $return->status }}</td>
                    <td class="px-4 py-2">
                        <a href="{{ route('admin.receipts.show', $return->id) }}"
                           class="text-emerald-600 hover:underline">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-4 text-center text-gray-500 dark:text-gray-400">No pending receipts</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
