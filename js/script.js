document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');

    // ---------- Mobile sidebar toggle ----------
    function openSidebar() {
        if (!sidebar) return;
        sidebar.classList.add('open');
        if (sidebarOverlay) sidebarOverlay.classList.add('show');
    }

    function closeSidebar() {
        if (!sidebar) return;
        sidebar.classList.remove('open');
        if (sidebarOverlay) sidebarOverlay.classList.remove('show');
    }

    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', function () {
            if (sidebar && sidebar.classList.contains('open')) {
                closeSidebar();
            } else {
                openSidebar();
            }
        });
    }

    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', closeSidebar);
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeSidebar();
    });

    // ---------- Toast notifications ----------
    const toast = document.getElementById('toast');
    if (toast) {
        const successAlert = document.querySelector('.alert-success');
        const errorAlert = document.querySelector('.alert-error');

        if (successAlert) {
            toast.className = 'toast success show';
            toast.textContent = successAlert.textContent.trim();
            setTimeout(() => toast.classList.remove('show'), 3000);
        } else if (errorAlert) {
            toast.className = 'toast error show';
            toast.textContent = errorAlert.textContent.trim();
            setTimeout(() => toast.classList.remove('show'), 3000);
        }
    }

    // ---------- Delete confirmation ----------
    const confirmButtons = document.querySelectorAll('.btn-confirm-delete');
    confirmButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            if (!confirm('Are you sure you want to delete this task? This action cannot be undone.')) {
                e.preventDefault();
            }
        });
    });

    // ---------- Theme toggle ----------
    const themeToggle = document.getElementById('themeToggle');
    const themeLabel = document.getElementById('themeLabel');
    const themeIcon = document.getElementById('themeIcon');

    function getStoredTheme() {
        return localStorage.getItem('taskify.theme') || localStorage.getItem('theme');
    }

    function setStoredTheme(value) {
        localStorage.setItem('taskify.theme', value);
        localStorage.removeItem('theme');
    }

    function updateThemeLabel() {
        const isDark = document.body.classList.contains('dark-mode');
        if (themeLabel) themeLabel.textContent = isDark ? 'Light mode' : 'Dark mode';
        if (themeIcon) {
            themeIcon.innerHTML = isDark
                ? '<path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>'
                : '<path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9 0 0012 21a9.003 9 0 008.354-5.646z"/>';
        }
    }

    if (themeToggle) {
        const savedTheme = getStoredTheme();
        if (savedTheme === 'dark') {
            document.body.classList.add('dark-mode');
        }
        updateThemeLabel();

        themeToggle.addEventListener('click', function () {
            document.body.classList.toggle('dark-mode');
            const isDark = document.body.classList.contains('dark-mode');
            setStoredTheme(isDark ? 'dark' : 'light');
            updateThemeLabel();
        });
    } else {
        const savedTheme = getStoredTheme();
        if (savedTheme === 'dark') {
            document.body.classList.add('dark-mode');
        }
    }

    // ---------- Task filtering (client-side, view-tasks page) ----------
    const filterForm = document.getElementById('filterForm');
    const filterSelect = document.getElementById('filterSelect');
    const searchInput = document.getElementById('searchInput');
    const taskTable = document.getElementById('taskTable');

    function filterTasks() {
        if (!taskTable) return;
        const searchTerm = (searchInput ? searchInput.value : '').toLowerCase();
        const filterValue = filterSelect ? filterSelect.value : 'all';
        const rows = taskTable.querySelectorAll('tbody tr[data-title]');

        rows.forEach(row => {
            const title = (row.dataset.title || '').toLowerCase();
            const description = (row.dataset.description || '').toLowerCase();
            const status = (row.dataset.status || '').trim();

            const matchesSearch = title.includes(searchTerm) || description.includes(searchTerm);
            const matchesFilter = filterValue === 'all' || status === filterValue;

            row.style.display = matchesSearch && matchesFilter ? '' : 'none';
        });
    }

    if (searchInput) searchInput.addEventListener('input', filterTasks);
    if (filterSelect) filterSelect.addEventListener('change', filterTasks);
    if (searchInput || filterSelect) filterTasks();
});
