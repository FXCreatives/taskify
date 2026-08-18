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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taskify - View Tasks</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <div class="header-content">
            <a href="index.php" class="logo">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                Taskify
            </a>
            <nav>
                <a href="index.php">Dashboard</a>
                <a href="add-task.php">Add Task</a>
                <a href="view-tasks.php" class="active">View Tasks</a>
                <button class="theme-toggle" id="themeToggle" aria-label="Toggle theme">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </button>
            </nav>
            <button class="mobile-menu-btn" id="mobileMenuBtn" aria-label="Toggle menu">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
        <div class="mobile-nav" id="mobileNav">
            <a href="index.php">Dashboard</a>
            <a href="add-task.php">Add Task</a>
            <a href="view-tasks.php" class="active">View Tasks</a>
        </div>
    </header>

    <div class="container">
        <div class="page-header">
            <div>
                <h1 class="page-title">All Tasks</h1>
                <p class="page-subtitle">View and manage all your tasks</p>
            </div>
            <a href="add-task.php" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Add Task
            </a>
        </div>

        <div class="table-container">
            <div class="toolbar">
                <div class="toolbar-actions">
                    <input type="text" id="searchInput" placeholder="Search tasks by title or description..." value="<?php echo htmlspecialchars($search); ?>">
                    <select id="filterSelect">
                        <option value="all" <?php echo $filter === 'all' ? 'selected' : ''; ?>>All Statuses</option>
                        <option value="Pending" <?php echo $filter === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="In Progress" <?php echo $filter === 'In Progress' ? 'selected' : ''; ?>>In Progress</option>
                        <option value="Completed" <?php echo $filter === 'Completed' ? 'selected' : ''; ?>>Completed</option>
                    </select>
                </div>
            </div>

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

                            echo "<tr>";
                            echo "<td>#{$task['id']}</td>";
                            echo "<td>" . htmlspecialchars($task['title']) . "</td>";
                            echo "<td>" . htmlspecialchars(substr($task['description'], 0, 50)) . (strlen($task['description']) > 50 ? '...' : '') . "</td>";
                            echo "<td><span class='badge badge-" . strtolower($task['priority']) . "'>{$task['priority']}</span></td>";
                            echo "<td class='$dueClass'>{$task['due_date']}</td>";
                            echo "<td><span class='badge badge-" . strtolower(str_replace(' ', '-', $task['status'])) . "'>{$task['status']}</span></td>";
                            echo "<td class='actions'>
                                    <a href='edit-task.php?id={$task['id']}' class='btn btn-warning btn-sm'>Edit</a>
                                    <a href='delete-task.php?id={$task['id']}' class='btn btn-danger btn-sm btn-confirm-delete'>Delete</a>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7' class='empty-state'>
                                <svg class='empty-state-icon' xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='currentColor' stroke-width='1.5'>
                                    <path stroke-linecap='round' stroke-linejoin='round' d='M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2' />
                                </svg>
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

    <div class="toast" id="toast"></div>
    <script src="js/script.js"></script>
</body>
</html>
