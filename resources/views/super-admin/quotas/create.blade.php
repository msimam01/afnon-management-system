@extends('layouts.layout')

@section('content')
<div class="p-6">
    <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-white">
        📦 Allocate Commodities for: <span class="text-emerald-600">{{ $season->name }}</span>
    </h2>

    <form action="{{ route('superadmin.seasons.quotas.store', $season->id) }}" method="POST" id="allocationForm">
        @csrf

        <div class="mb-4 flex items-center gap-4">
            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                <input type="checkbox" id="distributeEqual" class="mr-2">
                Distribute equally to all tenants
            </label>
        </div>

        @foreach ($commodities as $commodity)
            <div class="mb-6 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm">
                <div class="bg-gray-100 dark:bg-gray-700 px-4 py-2 font-semibold text-gray-800 dark:text-white flex justify-between items-center">
                    <span>{{ $commodity->name }}</span>
                    <span class="text-sm text-gray-600 dark:text-gray-300">
                        Available: <span id="stock-{{ $commodity->id }}">{{ $commodity->stock }}</span> units
                    </span>
                </div>

                <div class="bg-white dark:bg-gray-800 p-4">
                    <div class="grid grid-cols-1 md:grid-cols-{{ count($tenants) }} gap-4">
                        @foreach ($tenants as $tenant)
                            @php
                                $key = "{$tenant}_{$commodity->id}";
                                $existing = $allocations->where('tenant', $tenant)->where('commodity_id', $commodity->id)->first();
                                $initial = old("allocations.$key.quantity", $existing->allocated_quantity ?? 0);
                            @endphp
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ ucfirst($tenant) }}
                                </label>
                                <input type="hidden" name="allocations[{{ $key }}][tenant]" value="{{ $tenant }}">
                                <input type="hidden" name="allocations[{{ $key }}][commodity_id]" value="{{ $commodity->id }}">
                                <input
                                    type="number"
                                    step="1"
                                    min="0"
                                    class="allocation-input w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white"
                                    data-commodity="{{ $commodity->id }}"
                                    name="allocations[{{ $key }}][quantity]"
                                    value="{{ $initial }}"
                                >
                            </div>
                        @endforeach
                    </div>
                    <p class="text-sm text-right text-gray-500 mt-2 dark:text-gray-400">
                        Remaining: <span id="remaining-{{ $commodity->id }}">{{ $commodity->stock - $allocations->where('commodity_id', $commodity->id)->sum('allocated_quantity') }}</span> units
                    </p>
                </div>
            </div>
        @endforeach

        <div class="mt-6">
            <button type="submit"
                class="bg-emerald-600 text-white px-6 py-2 rounded hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-500">
                Save All Allocations
            </button>
        </div>
    </form>
</div>

<script>
    const distributeEqualCheckbox = document.getElementById('distributeEqual');

    distributeEqualCheckbox?.addEventListener('change', () => {
        if (!distributeEqualCheckbox.checked) return;

        const commodities = [...document.querySelectorAll('.allocation-input')]
            .reduce((acc, input) => {
                const commodityId = input.dataset.commodity;
                acc[commodityId] = acc[commodityId] || [];
                acc[commodityId].push(input);
                return acc;
            }, {});

        for (let commodityId in commodities) {
            const stock = parseInt(document.getElementById(`stock-${commodityId}`).innerText);
            const inputs = commodities[commodityId];
            const share = Math.floor(stock / inputs.length);

            inputs.forEach(input => {
                input.value = share;
            });

            updateRemaining(commodityId);
        }
    });

    document.querySelectorAll('.allocation-input').forEach(input => {
        input.addEventListener('input', () => {
            const commodityId = input.dataset.commodity;
            updateRemaining(commodityId);
        });
    });

    function updateRemaining(commodityId) {
        const stock = parseInt(document.getElementById(`stock-${commodityId}`).innerText);
        let total = 0;

        document.querySelectorAll(`[data-commodity="${commodityId}"]`).forEach(input => {
            const val = parseInt(input.value) || 0;
            total += val;
        });

        const remaining = Math.max(stock - total, 0);
        document.getElementById(`remaining-${commodityId}`).innerText = remaining;

        // Optional: highlight if overallocated
        const remainingDisplay = document.getElementById(`remaining-${commodityId}`);
        if (remaining < 0) {
            remainingDisplay.classList.add('text-red-500');
        } else {
            remainingDisplay.classList.remove('text-red-500');
        }
    }
</script>
@endsection
