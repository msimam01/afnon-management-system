@extends('layouts.layout')

@section('content')
<div class="max-w-5xl mx-auto mt-10 p-6 bg-white dark:bg-gray-800 shadow rounded-lg">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white">🌍 All Tenants</h2>
        <a href="{{ route('superadmin.tenants.create') }}"
            class="bg-emerald-600 text-white px-4 py-2 rounded hover:bg-emerald-700">➕ New Tenant</a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left border border-gray-200 dark:border-gray-700">
            <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-white">
                <tr>
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Domain</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Created At</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white divide-y">
                @forelse ($tenants as $tenant)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-4 py-2 font-mono text-xs">{{ $tenant->id }}</td>
                        <td class="px-4 py-2 font-medium">{{ $tenant->name }}</td>
                        <td class="px-4 py-2">
                            <a href="http://{{ $tenant->domain }}:8000" target="_blank"
                               class="text-blue-600 hover:text-blue-800 hover:underline">
                                {{ $tenant->domain }}
                            </a>
                        </td>
                        <td class="px-4 py-2">
                            @switch($tenant->status)
                                @case('active')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <svg class="w-2 h-2 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3"/>
                                        </svg>
                                        Active
                                    </span>
                                    @break
                                @case('pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <svg class="w-2 h-2 mr-1 animate-spin" fill="currentColor" viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3"/>
                                        </svg>
                                        Pending
                                    </span>
                                    @break
                                @case('inactive')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <svg class="w-2 h-2 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3"/>
                                        </svg>
                                        Inactive
                                    </span>
                                    @break
                                @case('suspended')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                        <svg class="w-2 h-2 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3"/>
                                        </svg>
                                        Suspended
                                    </span>
                                    @break
                                @case('failed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <svg class="w-2 h-2 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3"/>
                                        </svg>
                                        Failed
                                    </span>
                                    @break
                            @endswitch
                        </td>
                        <td class="px-4 py-2 text-gray-500">
                            {{ $tenant->created_at->format('M j, Y') }}
                        </td>
                        <td class="px-4 py-2">
                            <div class="flex items-center space-x-2">
                                @if($tenant->isActive())
                                    <button onclick="openDeactivateModal('{{ $tenant->id }}', '{{ $tenant->name }}')"
                                            class="text-red-600 hover:text-red-800 text-xs font-medium">
                                        Deactivate
                                    </button>
                                @elseif($tenant->isInactive() || $tenant->isSuspended())
                                    <form action="{{ route('superadmin.tenants.toggle-status', $tenant) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                class="text-green-600 hover:text-green-800 text-xs font-medium"
                                                onclick="return confirm('Are you sure you want to activate this tenant?')">
                                            Activate
                                        </button>
                                    </form>
                                @endif

                                @if($tenant->isActive())
                                    <button onclick="openSuspendModal('{{ $tenant->id }}', '{{ $tenant->name }}')"
                                            class="text-orange-600 hover:text-orange-800 text-xs font-medium">
                                        Suspend
                                    </button>
                                @endif

                                <a href="{{ route('superadmin.tenants.show', $tenant) }}"
                                   class="text-blue-600 hover:text-blue-800 text-xs font-medium">
                                    View
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                            No tenants found. <a href="{{ route('superadmin.tenants.create') }}" class="text-emerald-600 hover:text-emerald-800">Create your first tenant</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($tenants->hasPages())
        <div class="mt-6">
            {{ $tenants->links() }}
        </div>
    @endif

    <!-- Deactivate Modal -->
    <div id="deactivateModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Deactivate Tenant</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                        Are you sure you want to deactivate <span id="deactivateTenantName" class="font-medium"></span>?
                        This will make the tenant inaccessible to all users.
                    </p>
                    <form id="deactivateForm" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="mb-4">
                            <label for="deactivateReason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Reason for deactivation (optional)
                            </label>
                            <textarea id="deactivateReason" name="reason" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 dark:bg-gray-700 dark:text-white"
                                      placeholder="Enter reason for deactivation..."></textarea>
                        </div>
                        <div class="flex justify-end space-x-3">
                            <button type="button" onclick="closeDeactivateModal()"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-md">
                                Deactivate
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Suspend Modal -->
    <div id="suspendModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Suspend Tenant</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                        Are you sure you want to suspend <span id="suspendTenantName" class="font-medium"></span>?
                        This will temporarily make the tenant inaccessible.
                    </p>
                    <form id="suspendForm" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="suspendReason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Reason for suspension <span class="text-red-500">*</span>
                            </label>
                            <textarea id="suspendReason" name="reason" rows="3" required
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 dark:bg-gray-700 dark:text-white"
                                      placeholder="Enter reason for suspension..."></textarea>
                        </div>
                        <div class="flex justify-end space-x-3">
                            <button type="button" onclick="closeSuspendModal()"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="px-4 py-2 text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 rounded-md">
                                Suspend
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function openDeactivateModal(tenantId, tenantName) {
    document.getElementById('deactivateTenantName').textContent = tenantName;
    document.getElementById('deactivateForm').action = `/super-admin/tenants/${tenantId}/toggle-status`;
    document.getElementById('deactivateModal').classList.remove('hidden');
}

function closeDeactivateModal() {
    document.getElementById('deactivateModal').classList.add('hidden');
    document.getElementById('deactivateReason').value = '';
}

function openSuspendModal(tenantId, tenantName) {
    document.getElementById('suspendTenantName').textContent = tenantName;
    document.getElementById('suspendForm').action = `/super-admin/tenants/${tenantId}/suspend`;
    document.getElementById('suspendModal').classList.remove('hidden');
}

function closeSuspendModal() {
    document.getElementById('suspendModal').classList.add('hidden');
    document.getElementById('suspendReason').value = '';
}

// Close modals when clicking outside
document.getElementById('deactivateModal').addEventListener('click', function(e) {
    if (e.target === this) closeDeactivateModal();
});

document.getElementById('suspendModal').addEventListener('click', function(e) {
    if (e.target === this) closeSuspendModal();
});
</script>

@endsection
