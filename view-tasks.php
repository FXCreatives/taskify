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
            <div class="logo">⚡ Taskify</div>
            <nav>
                <a href="index.php">Dashboard</a>
                <a href="add-task.php">Add Task</a>
                <a href="view-tasks.php">View Tasks</a>
            </nav>
        </div>
    </header>

    <div class="container">
        <h1 class="page-title">📋 All Tasks</h1>

        <div class="table-container">
            <div class="toolbar">
                <input type="text" id="searchInput" placeholder="🔍 Search tasks by title or description..." value="<?php echo htmlspecialchars($search); ?>">
                <select id="filterSelect">
                    <option value="all" <?php echo $filter === 'all' ? 'selected' : ''; ?>>All Statuses</option>
                    <option value="Pending" <?php echo $filter === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                    <option value="In Progress" <?php echo $filter === 'In Progress' ? 'selected' : ''; ?>>In Progress</option>
                    <option value="Completed" <?php echo $filter === 'Completed' ? 'selected' : ''; ?>>Completed</option>
                </select>
                <a href="add-task.php" class="btn btn-primary">+ Add Task</a>
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
                                <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='currentColor' style='margin: 0 auto 16px;'>
                                    <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2' />
                                </svg>
                                <p>No tasks found. <a href='add-task.php' class='btn btn-primary btn-sm'>Add your first task</a></p>
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