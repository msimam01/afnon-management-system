// Core global functionality - preventing function redeclaration conflicts
// All page-specific functions moved to individual templates

// Profile dropdown functionality
document.addEventListener('DOMContentLoaded', function() {
    const profileDropdown = document.getElementById('profileDropdown');
    const profileMenu = document.getElementById('profileMenu');

    if (profileDropdown && profileMenu) {
        profileDropdown.addEventListener('click', function(e) {
            e.stopPropagation();
            profileMenu.classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!profileDropdown.contains(e.target)) {
                profileMenu.classList.add('hidden');
            }
        });
    }

    // Sidebar toggle for mobile
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');

    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('hidden');
        });
    }

    // CSV export functionality (global)
    const exportBtn = document.getElementById('exportBtn');
    if (exportBtn) {
        exportBtn.addEventListener('click', () => {
            const table = document.querySelector('table');
            if (table) {
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
            }
        });
    }
});
