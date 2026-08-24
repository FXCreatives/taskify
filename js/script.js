document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileCloseBtn = document.getElementById('mobileCloseBtn');
    const themeToggle = document.getElementById('themeToggle');
    const themeIcon = document.getElementById('themeIcon');

    // ---------- Mobile sidebar toggle ----------
    function openSidebar() {
        if (!sidebar) return;
        sidebar.classList.add('open');
        if (sidebarOverlay) sidebarOverlay.classList.add('show');
        if (mobileCloseBtn) mobileCloseBtn.style.display = 'inline-flex';
    }

    function closeSidebar() {
        if (!sidebar) return;
        sidebar.classList.remove('open');
        if (sidebarOverlay) sidebarOverlay.classList.remove('show');
        if (mobileCloseBtn) mobileCloseBtn.style.display = 'none';
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

    if (mobileCloseBtn) {
        mobileCloseBtn.addEventListener('click', closeSidebar);
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
    function updateThemeIcon() {
        if (!themeIcon) return;
        const isDark = document.documentElement.classList.contains('dark');
        if (isDark) {
            themeIcon.innerHTML = '<circle cx="12" cy="12" r="5"/><path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/>';
        } else {
            themeIcon.innerHTML = '<path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>';
        }
    }

    updateThemeIcon();
    if (themeToggle) themeToggle.addEventListener('click', function () {
        document.documentElement.classList.toggle('dark');
        localStorage.setItem('taskify.theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
        updateThemeIcon();
    });

    // ---------- Task filtering (client-side, view-tasks page) ----------
    const filterForm = document.getElementById('filterForm');
    const filterSelect = document.getElementById('filterSelect');
    const searchInput = document.getElementById('searchInput');
    const taskTable = document.getElementById('taskTable');
    const priorityFilter = document.getElementById('priorityFilter');

    function filterTasks() {
        if (!taskTable) return;
        const searchTerm = (searchInput ? searchInput.value : '').toLowerCase();
        const filterValue = filterSelect ? filterSelect.value : 'all';
        const priorityValue = priorityFilter ? priorityFilter.value : 'all';
        const rows = taskTable.querySelectorAll('tbody tr[data-title]');

        rows.forEach(row => {
            const title = (row.dataset.title || '').toLowerCase();
            const description = (row.dataset.description || '').toLowerCase();
            const status = (row.dataset.status || '').trim();
            const priority = (row.dataset.priority || '').trim();

            const matchesSearch = title.includes(searchTerm) || description.includes(searchTerm);
            const matchesFilter = filterValue === 'all' || status === filterValue;
            const matchesPriority = priorityValue === 'all' || priority === priorityValue;

            row.style.display = matchesSearch && matchesFilter && matchesPriority ? '' : 'none';
        });
    }

    if (searchInput) searchInput.addEventListener('input', filterTasks);
    if (filterSelect) filterSelect.addEventListener('change', filterTasks);
    if (priorityFilter) priorityFilter.addEventListener('change', filterTasks);
    if (searchInput || filterSelect) filterTasks();
});
