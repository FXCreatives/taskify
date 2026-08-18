document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchInput');
    const filterSelect = document.getElementById('filterSelect');
    const taskTable = document.getElementById('taskTable');
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileNav = document.getElementById('mobileNav');

    function filterTasks() {
        const searchTerm = searchInput.value.toLowerCase();
        const filterValue = filterSelect.value;
        const rows = taskTable.querySelectorAll('tbody tr');

        rows.forEach(row => {
            const title = row.cells[1].textContent.toLowerCase();
            const description = row.cells[2].textContent.toLowerCase();
            const status = row.cells[5].textContent.trim();

            const matchesSearch = title.includes(searchTerm) || description.includes(searchTerm);
            const matchesFilter = filterValue === 'all' || status === filterValue;

            row.style.display = matchesSearch && matchesFilter ? '' : 'none';
        });
    }

    if (searchInput) searchInput.addEventListener('input', filterTasks);
    if (filterSelect) filterSelect.addEventListener('change', filterTasks);

    if (mobileMenuBtn && mobileNav) {
        mobileMenuBtn.addEventListener('click', function () {
            mobileNav.classList.toggle('show');
        });

        document.addEventListener('click', function (e) {
            if (!mobileMenuBtn.contains(e.target) && !mobileNav.contains(e.target)) {
                mobileNav.classList.remove('show');
            }
        });
    }

    setTimeout(() => {
        const toasts = document.querySelectorAll('.toast');
        toasts.forEach(toast => {
            toast.classList.add('show');
            setTimeout(() => toast.classList.remove('show'), 3000);
        });
    }, 100);

    const confirmButtons = document.querySelectorAll('.btn-confirm-delete');
    confirmButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            if (!confirm('Are you sure you want to delete this task? This action cannot be undone.')) {
                e.preventDefault();
            }
        });
    });

    const themeToggle = document.getElementById('themeToggle');
    if (themeToggle) {
        themeToggle.addEventListener('click', function () {
            document.body.classList.toggle('dark-mode');
            const isDark = document.body.classList.contains('dark-mode');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        });

        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'dark') {
            document.body.classList.add('dark-mode');
        }
    }
});
