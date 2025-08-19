// Dark mode functionality
const darkModeToggle = document.getElementById('darkModeToggle');
const html = document.documentElement;

// Check for saved theme preference
const savedTheme = localStorage.getItem('theme') || 'light';
if (savedTheme === 'dark') {
    html.classList.add('dark');
}

darkModeToggle.addEventListener('click', () => {
    html.classList.toggle('dark');
    const isDark = html.classList.contains('dark');
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
});

// Profile dropdown
const profileDropdown = document.getElementById('profileDropdown');
const profileMenu = document.getElementById('profileMenu');

profileDropdown.addEventListener('click', () => {
    profileMenu.classList.toggle('hidden');
});

// Close dropdown when clicking outside
document.addEventListener('click', (e) => {
    if (!profileDropdown.contains(e.target)) {
        profileMenu.classList.add('hidden');
    }
});

// Return modal functionality
const returnBtn = document.getElementById('returnBtn');
const returnModal = document.getElementById('returnModal');
const closeModal = document.getElementById('closeModal');
const cancelReturn = document.getElementById('cancelReturn');

returnBtn.addEventListener('click', () => {
    returnModal.classList.remove('hidden');
});

closeModal.addEventListener('click', () => {
    returnModal.classList.add('hidden');
});

cancelReturn.addEventListener('click', () => {
    returnModal.classList.add('hidden');
});

// Camera functionality (mock)
document.getElementById('cameraBtn').addEventListener('click', () => {
    alert('Camera functionality would be implemented here using navigator.mediaDevices.getUserMedia()');
});
function showReturnStatus(season) {
    // later populate modal fields dynamically using JS
    document.getElementById('returnInfoModal').classList.remove('hidden');
}
function closeReturnModal() {
    document.getElementById('returnInfoModal').classList.add('hidden');
}

// Sidebar toggle for mobile
document.getElementById('sidebarToggle').addEventListener('click', function () {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('hidden');
});

// Tab navigation
document.querySelectorAll('.sidebar-link').forEach(link => {
    link.addEventListener('click', function (e) {
        e.preventDefault();

        // Remove active class from all links
        document.querySelectorAll('.sidebar-link').forEach(l => {
            l.classList.remove('bg-emerald-50', 'dark:bg-emerald-900', 'text-emerald-700', 'dark:text-emerald-300');
            l.classList.add('text-gray-700', 'dark:text-gray-300', 'hover:bg-gray-50', 'dark:hover:bg-gray-700');
        });

        // Add active class to clicked link
        this.classList.add('bg-emerald-50', 'dark:bg-emerald-900', 'text-emerald-700', 'dark:text-emerald-300');
        this.classList.remove('text-gray-700', 'dark:text-gray-300', 'hover:bg-gray-50', 'dark:hover:bg-gray-700');

        // Hide all sections
        document.querySelectorAll('.section').forEach(section => {
            section.classList.add('hidden');
        });

        // Show selected section
        const target = this.getAttribute('href').substring(1);
        const section = document.getElementById(target + '-section');
        if (section) {
            section.classList.remove('hidden');
        }
    });
});


function toggleAllocationMode() {
    const mode = document.getElementById('allocationMode').value;
    document.getElementById('autoQtyDisplay').classList.toggle('hidden', mode !== 'auto');
    document.getElementById('manualQtyInput').classList.toggle('hidden', mode !== 'manual');
}

function approveApplication() {
    const mode = document.getElementById('allocationMode').value;
    let message = '';

    if (mode === 'auto') {
        message = 'Auto-approved with calculated quantity:\nMaize: 5 bags\nRice: 3 bags';
    } else {
        const maize = parseInt(document.getElementById('maizeQty').value || 0);
        const rice = parseInt(document.getElementById('riceQty').value || 0);

        if (maize < 0 || rice < 0) {
            alert("Please enter valid quantities.");
            return;
        }

        // Optionally check against stock
        if (maize > 12000 || rice > 8000) {
            alert("Insufficient stock for one or more commodities.");
            return;
        }

        message = `Manually approved:\nMaize: ${maize} bags\nRice: ${rice} bags`;
    }

    alert(message);
    closeApplicationModal();
}


// Simulated stock per commodity
const commodityStock = {
    "maize-seeds": 12000,
    "rice-seeds": 8000
};

// Simulated load on modal open
function openApplicationModal(appSlug) {
    document.getElementById('applicationApprovalModal').classList.remove('hidden');

    const select = document.getElementById('commoditySelect');
    select.innerHTML = `<option value="">-- Select a Commodity --</option>`;
    Object.keys(commodityStock).forEach(c => {
        const option = document.createElement('option');
        option.value = c;
        option.textContent = c.replace('-', ' ').toUpperCase();
        select.appendChild(option);
    });

    toggleAllocationMode();
}

function closeApplicationModal() {
    document.getElementById('applicationApprovalModal').classList.add('hidden');
}

// Simulated approval function
function approveApplication() {
    const commodity = document.getElementById('commoditySelect').value;
    const mode = document.getElementById('allocationMode').value;
    let quantity = 0;

    if (!commodity) {
        alert("Please select a commodity");
        return;
    }

    if (mode === 'auto') {
        quantity = Math.floor(5.2); // use actual farm size
    } else {
        quantity = parseInt(document.getElementById('manualQty').value || 0);
        if (quantity < 1) {
            alert("Please enter a valid quantity.");
            return;
        }
    }

    if (quantity > commodityStock[commodity]) {
        alert(`Insufficient stock. Only ${commodityStock[commodity]} bags available.`);
        return;
    }

    // Proceed with approval (replace with actual backend logic)
    alert(`Application approved for ${quantity} bag(s) of ${commodity.replace('-', ' ')}.`);
    closeApplicationModal(); u
}
function toggleReturnCenter() {
    const checkbox = document.getElementById('sameAsCollection');
    const returnSelect = document.getElementById('returnCenter');
    const collectionSelect = document.getElementById('collectionCenter');

    if (checkbox.checked) {
        returnSelect.value = collectionSelect.value;
        returnSelect.disabled = true;
    } else {
        returnSelect.disabled = false;
    }
}

// Close modal manually
document.getElementById('closeApplicationModal')?.addEventListener('click', () => {
    document.getElementById('applicationModal').classList.add('hidden');
});

document.getElementById('cancelApplication')?.addEventListener('click', () => {
    document.getElementById('applicationModal').classList.add('hidden');
});
function openSeasonModal(edit = false) {
    document.getElementById('seasonModal').classList.remove('hidden');
    document.getElementById('seasonModalTitle').innerText = edit ? 'Edit Season' : 'Create New Season';
    // Prefill logic can go here later
}

function closeSeasonModal() {
    document.getElementById('seasonModal').classList.add('hidden');
}

function toggleSeasonStatus(button) {
    const label = button.innerText.trim();
    if (label === 'Close') {
        button.innerText = 'Open';
        button.closest('.border').querySelector('span').innerText = 'Closed';
        button.closest('.border').querySelector('span').className = 'inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-100';
    } else {
        button.innerText = 'Close';
        button.closest('.border').querySelector('span').innerText = 'Open';
        button.closest('.border').querySelector('span').className = 'inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200';
    }
}

function openCommodityModal() {
    document.getElementById('commodityModal').classList.remove('hidden');
}

function closeCommodityModal() {
    document.getElementById('commodityModal').classList.add('hidden');
}

// Optional: Save allocation (simulate)
document.getElementById('commodityForm')?.addEventListener('submit', function (e) {
    e.preventDefault();
    alert('Commodity allocation saved!');
    closeCommodityModal();
});
function openNewCommodityModal() {
    document.getElementById('newCommodityModal').classList.remove('hidden');
}
function closeNewCommodityModal() {
    document.getElementById('newCommodityModal').classList.add('hidden');
}
document.getElementById('commodityTypeForm')?.addEventListener('submit', function (e) {
    e.preventDefault();
    alert('Commodity type saved!');
    closeNewCommodityModal();
});
const farmerList = new List('farmerList', {
    valueNames: ['name', 'phone', 'cluster']
});

function exportFarmersToCSV() {
    alert("This will export visible farmer data (mock for now).");
}

function viewFarmerProfile(slug) {
    alert("Open modal to view farmer profile: " + slug);
}

function confirmDeleteFarmer(slug) {
    if (confirm("Are you sure you want to delete this farmer?")) {
        alert("Deleted (mock): " + slug);
    }
}

function viewFarmerProfile(slug) {
    console.log("Loading farmer data for:", slug); // Replace with fetch() later
    document.getElementById('farmerProfileModal').classList.remove('hidden');
}

function closeFarmerModal() {
    document.getElementById('farmerProfileModal').classList.add('hidden');
}

function openAddAgentModal() {
    document.getElementById('addAgentModal').classList.remove('hidden');
}
function closeAddAgentModal() {
    document.getElementById('addAgentModal').classList.add('hidden');
}

document.getElementById('agentForm')?.addEventListener('submit', function (e) {
    e.preventDefault();
    alert('Agent added successfully (mock)');
    closeAddAgentModal();
});
new List(document.querySelector('.list')?.parentElement, {
    valueNames: ['name'],
});

function viewAgentProfile(slug) {
    document.getElementById('agentProfileModal').classList.remove('hidden');
    // Fetch agent data via slug if backend is ready
}
function closeAgentProfile() {
    document.getElementById('agentProfileModal').classList.add('hidden');
}
function openAssignCenterModal() {
    document.getElementById('assignCenterModal').classList.remove('hidden');
}
function closeAssignCenterModal() {
    document.getElementById('assignCenterModal').classList.add('hidden');
}
document.getElementById('assignCenterForm')?.addEventListener('submit', function (e) {
    e.preventDefault();
    alert("Centers assigned to agent (mock)");
    closeAssignCenterModal();
});
function viewReturnDetails(slug) {
    console.log("Viewing return for:", slug);
    document.getElementById('returnModal').classList.remove('hidden');
}

function closeReturnModal() {
    document.getElementById('returnModal').classList.add('hidden');
}

function approveReturn() {
    alert("Return Approved!");
    closeReturnModal();
}

function rejectReturn() {
    const note = document.getElementById('rejectionNote').value;
    if (!confirm("Are you sure you want to reject this return?")) return;
    alert("Return Rejected!\nReason: " + note);
    closeReturnModal();
}
document.getElementById('exportBtn').addEventListener('click', () => {
    const table = document.querySelector('table');
    let csv = [];
    for (let row of table.rows) {
        const rowData = Array.from(row.cells).map(cell => `"${cell.textContent.trim()}"`);
        csv.push(rowData.join(','));
    }
    const csvContent = "data:text/csv;charset=utf-8," + csv.join('\n');
    const link = document.createElement('a');
    link.setAttribute('href', encodeURI(csvContent));
    link.setAttribute('download', 'reports.csv');
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
});
function openUserModal() {
    document.getElementById('userModal').classList.remove('hidden');
}

function closeUserModal() {
    document.getElementById('userModal').classList.add('hidden');
}
function openZoneModal() {
    document.getElementById('zoneModal').classList.remove('hidden');
}

function closeZoneModal() {
    document.getElementById('zoneModal').classList.add('hidden');
}
function openCommodityModal() {
    document.getElementById('addCommodityModal').classList.remove('hidden');
}

function closeCommodityModal() {
    document.getElementById('addCommodityModal').classList.add('hidden');
}

function toggleReturnMode() {
    const mode = document.getElementById('returnMode').value;
    document.getElementById('globalReturnDateGroup').classList.toggle('hidden', mode !== 'global');
    document.getElementById('perCommodityDates').classList.toggle('hidden', mode !== 'per-commodity');
}


function openEditSeasonModal() {
    document.getElementById('editSeasonModal').classList.remove('hidden');
    document.getElementById('seasonModalTitle').innerText = 'Edit Season';
    toggleReturnMode(); // initialize return mode visibility
}

function closeEditSeasonModal() {
    document.getElementById('editSeasonModal').classList.add('hidden');
}

function adjustQuota(zoneId) {
    document.getElementById('quotaModal').classList.remove('hidden');
    document.getElementById('tenantName').innerText = zoneId.replace('-', ' ').toUpperCase();
}

function closeQuotaModal() {
    document.getElementById('quotaModal').classList.add('hidden');
}

document.getElementById('adjustQuotaForm').addEventListener('submit', function (e) {
    e.preventDefault();
    // save quota to backend
    alert("Quota updated!");
    closeQuotaModal();
});
const availableCommodities = {
    maize: { name: "Maize Seeds", stock: 12000 },
    rice: { name: "Rice Seeds", stock: 8000 }
};

const tenants = [
    "North Central", "North East", "North West", "South South", "South East", "South West"
];

let allocationMap = {}; // { commodity: remainingStock }

function openSeasonModal() {
    document.getElementById('seasonModal').classList.remove('hidden');
    goToStep(1);

    const commoditySelect = document.getElementById('commodities');
    commoditySelect.innerHTML = "";
    for (const key in availableCommodities) {
        const opt = document.createElement("option");
        opt.value = key;
        opt.textContent = availableCommodities[key].name;
        commoditySelect.appendChild(opt);
    }
}

function closeSeasonModal() {
    document.getElementById('seasonModal').classList.add('hidden');
}

function goToStep(step) {
    document.getElementById('step1').classList.toggle('hidden', step !== 1);
    document.getElementById('step2').classList.toggle('hidden', step !== 2);
    document.getElementById('backBtn').classList.toggle('hidden', step !== 2);
    document.getElementById('nextBtn').classList.toggle('hidden', step !== 1);
    document.getElementById('submitBtn').classList.toggle('hidden', step !== 2);
}

function handleNextStep() {
    const selectedCommodities = Array.from(document.getElementById('commodities').selectedOptions).map(opt => opt.value);
    if (!selectedCommodities.length) {
        alert("Please select at least one commodity.");
        return;
    }

    allocationMap = {};
    selectedCommodities.forEach(c => allocationMap[c] = availableCommodities[c].stock);

    const container = document.getElementById("allocationContainer");
    container.innerHTML = "";

    tenants.forEach(tenant => {
        const block = document.createElement("div");
        block.className = "border-b border-gray-200 dark:border-gray-700 pb-4";

        const title = `<h5 class="text-sm font-semibold text-gray-800 dark:text-white mb-2">${tenant}</h5>`;
        const rows = selectedCommodities.map(commodityKey => {
            const commodity = availableCommodities[commodityKey];
            return `
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-2 items-center">
          <div><span class="text-sm text-gray-700 dark:text-gray-300">${commodity.name}</span></div>
          <div class="text-xs text-gray-500 dark:text-gray-400">Stock: <span id="stock-${commodityKey}" class="font-medium">${allocationMap[commodityKey]}</span> bags</div>
          <div>
            <input type="number" min="0" value="0"
              data-tenant="${tenant}" data-commodity="${commodityKey}"
              class="allocationInput w-full px-2 py-1 rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
              oninput="updateStock(this)">
          </div>
        </div>
      `;
        }).join("");

        block.innerHTML = title + rows;
        container.appendChild(block);
    });

    goToStep(2);
}

function updateStock(input) {
    const commodity = input.dataset.commodity;
    const allInputs = document.querySelectorAll(`input[data-commodity="${commodity}"]`);
    let totalAllocated = 0;

    allInputs.forEach(inp => {
        totalAllocated += parseInt(inp.value || "0");
    });

    const remaining = Math.max(0, availableCommodities[commodity].stock - totalAllocated);
    allocationMap[commodity] = remaining;
    document.getElementById(`stock-${commodity}`).textContent = remaining;
}

function openRoleModal() {
    document.getElementById("roleModal").classList.remove("hidden");
    document.getElementById("roleModalTitle").textContent = "Add New Role";
    document.getElementById("roleForm").reset();
}

function closeRoleModal() {
    document.getElementById("roleModal").classList.add("hidden");
}
// ========== Farmer Search ==========
const searchBtn = document.getElementById("searchBtn");
if (searchBtn) {
    searchBtn.addEventListener("click", function () {
        const id = document.getElementById("farmerSearch").value.trim().toUpperCase();
        const validId = "NEC001234";

        if (id === validId) {
            document.getElementById("searchResults").classList.remove("hidden");
            document.getElementById("notFound").classList.add("hidden");
        } else {
            document.getElementById("searchResults").classList.add("hidden");
            document.getElementById("notFound").classList.remove("hidden");
        }
    });
}
// ============= Manage center modal ============
function openCenterModal() {
      document.getElementById('centerModal').classList.remove('hidden');
    }

    function closeCenterModal() {
      document.getElementById('centerModal').classList.add('hidden');
    }

    function openAssignModal() {
      document.getElementById('assignModal').classList.remove('hidden');
    }

    function closeAssignModal() {
      document.getElementById('assignModal').classList.add('hidden');
    }

    document.getElementById('centerForm')?.addEventListener('submit', function (e) {
      e.preventDefault();
      alert("Center saved successfully!");
      closeCenterModal();
    });

    document.getElementById('assignForm')?.addEventListener('submit', function (e) {
      e.preventDefault();
      alert("Agents assigned to center!");
      closeAssignModal();
    });
