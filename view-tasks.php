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
                    <th>Task</th>
                    <th class="hidden lg:table-cell">Description</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Due Date</th>
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
                            echo "<td class='cell-title' data-label='Task'>" . htmlspecialchars($task['title']) . "</td>";
                            echo "<td class='cell-desc hidden lg:table-cell' data-label='Description'>" . htmlspecialchars(substr($task['description'], 0, 50)) . (strlen($task['description']) > 50 ? '...' : '') . "</td>";
                            echo "<td data-label='Priority'><span class='badge badge-" . strtolower($task['priority']) . "'>{$task['priority']}</span></td>";
                            echo "<td data-label='Status'><span class='badge badge-" . strtolower(str_replace(' ', '-', $task['status'])) . "'>{$task['status']}</span></td>";
                            echo "<td class='cell-date $dueClass' data-label='Due Date'>{$task['due_date']}</td>";
                            echo "<td class='actions' data-label='Actions'>
                                    <a href='edit-task.php?id={$task['id']}' class='btn-icon' aria-label='Edit'>
                                        <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='none' viewBox='0 0 24 24' stroke='currentColor' stroke-width='2'><path stroke-linecap='round' stroke-linejoin='round' d='M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10'/></svg>
                                    </a>
                                    <a href='delete-task.php?id={$task['id']}' class='btn-icon btn-confirm-delete' aria-label='Delete'>
                                        <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='none' viewBox='0 0 24 24' stroke='currentColor' stroke-width='2'><path stroke-linecap='round' stroke-linejoin='round' d='M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.061-.94-1.75-1.816-1.618l-3.04.397a1.125 1.125 0 01-1.064-1.064l.397-3.04c.132-.875.557-1.618 1.618-1.816z'/></svg>
                                    </a>
                                  </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='empty-state'>
                            <div class='empty-state-icon-container'>
                                <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' fill='none' viewBox='0 0 24 24' stroke='currentColor' stroke-width='1.5'><path stroke-linecap='round' stroke-linejoin='round' d='M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z'/></svg>
                            </div>
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
