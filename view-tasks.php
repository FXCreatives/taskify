<?php
session_start();
include 'includes/db.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

$query = "SELECT * FROM tasks WHERE 1=1";
$params = [];

if (!empty($search)) {
    $query .= " AND (title LIKE ? OR description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($filter !== 'all') {
    $query .= " AND status = ?";
    $params[] = $filter;
}

$query .= " ORDER BY created_at DESC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$tasks = $stmt->fetchAll();

$activePage = 'view';
$pageTitle = 'All Tasks';
include 'includes/header.php';
?>

<div class="page-header">
    <div>
        <h1 class="page-title">All Tasks</h1>
        <p class="page-subtitle">View and manage all your tasks</p>
    </div>
</div>

<div class="panel">
    <div class="panel-header">
        <div class="panel-title">Tasks <span class="count"><?php echo count($tasks); ?> total</span></div>
        <div class="toolbar-actions">
            <form method="GET" action="" id="filterForm" style="display:flex;gap:8px;flex-wrap:wrap;">
                <div class="search-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" id="searchInput" name="search" class="search-input" placeholder="Search tasks..." value="<?php echo htmlspecialchars($search); ?>">
                </div>
                <select id="filterSelect" name="filter" class="filter-select">
                    <option value="all" <?php echo $filter === 'all' ? 'selected' : ''; ?>>All Statuses</option>
                    <option value="Pending" <?php echo $filter === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                    <option value="In Progress" <?php echo $filter === 'In Progress' ? 'selected' : ''; ?>>In Progress</option>
                    <option value="Completed" <?php echo $filter === 'Completed' ? 'selected' : ''; ?>>Completed</option>
                </select>
            </form>
        </div>
    </div>
    <div class="table-wrap">
        <table id="taskTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Priority</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (count($tasks) > 0) {
                    foreach ($tasks as $task) {
                        $today = date('Y-m-d');
                        $dueClass = '';
                        if ($task['status'] !== 'Completed') {
                            if ($task['due_date'] < $today) {
                                $dueClass = 'overdue';
                            } elseif ($task['due_date'] == $today) {
                                $dueClass = 'due-soon';
                            }
                        }

                        echo "<tr data-title=\"" . htmlspecialchars($task['title']) . "\" data-description=\"" . htmlspecialchars($task['description']) . "\" data-status=\"" . htmlspecialchars($task['status']) . "\">";
                            echo "<td class='cell-id' data-label='ID'>#{$task['id']}</td>";
                            echo "<td class='cell-title' data-label='Title'>" . htmlspecialchars($task['title']) . "</td>";
                            echo "<td class='cell-desc' data-label='Description'>" . htmlspecialchars(substr($task['description'], 0, 50)) . (strlen($task['description']) > 50 ? '...' : '') . "</td>";
                            echo "<td data-label='Priority'><span class='badge badge-" . strtolower($task['priority']) . "'>{$task['priority']}</span></td>";
                            echo "<td class='cell-date $dueClass' data-label='Due Date'>{$task['due_date']}</td>";
                            echo "<td data-label='Status'><span class='badge badge-" . strtolower(str_replace(' ', '-', $task['status'])) . "'>{$task['status']}</span></td>";
                            echo "<td class='actions' data-label='Actions'>
                                    <a href='edit-task.php?id={$task['id']}' class='btn btn-ghost btn-sm'>Edit</a>
                                    <a href='delete-task.php?id={$task['id']}' class='btn btn-danger btn-sm btn-confirm-delete'>Delete</a>
                                  </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7' class='empty-state'>
                            <svg class='empty-state-icon' xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='currentColor' stroke-width='1.5'><path stroke-linecap='round' stroke-linejoin='round' d='M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z'/></svg>
                            <div class='empty-state-title'>No tasks found</div>
                            <p class='empty-state-text'>Adjust your search or add a new task</p>
                            <a href='add-task.php' class='btn btn-primary'>Add your first task</a>
                          </td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
