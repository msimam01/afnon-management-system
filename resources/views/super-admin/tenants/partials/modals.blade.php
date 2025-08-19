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
