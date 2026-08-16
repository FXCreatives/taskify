<?php
session_start();
include 'includes/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taskify - Dashboard</title>
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
                <button class="theme-toggle" id="themeToggle">🌓</button>
            </nav>
        </div>
    </header>

    <div class="container">
        <h1 class="page-title">📊 Dashboard</h1>

        <?php
        $total = $pdo->query("SELECT COUNT(*) FROM tasks")->fetchColumn();
        $completed = $pdo->query("SELECT COUNT(*) FROM tasks WHERE status = 'Completed'")->fetchColumn();
        $pending = $pdo->query("SELECT COUNT(*) FROM tasks WHERE status = 'Pending'")->fetchColumn();
        $highPriority = $pdo->query("SELECT COUNT(*) FROM tasks WHERE priority = 'High' AND status != 'Completed'")->fetchColumn();
        $progress = $total > 0 ? round(($completed / $total) * 100) : 0;
        ?>

        <div class="cards">
            <div class="card">
                <h3>Total Tasks</h3>
                <div class="number"><?php echo $total; ?></div>
            </div>
            <div class="card success">
                <h3>Completed</h3>
                <div class="number"><?php echo $completed; ?></div>
            </div>
            <div class="card warning">
                <h3>Pending</h3>
                <div class="number"><?php echo $pending; ?></div>
            </div>
            <div class="card danger">
                <h3>High Priority</h3>
                <div class="number"><?php echo $highPriority; ?></div>
            </div>
        </div>

        <div class="card" style="margin-bottom: 30px;">
            <h3>Overall Progress</h3>
            <div class="progress-bar">
                <div class="progress-fill" style="width: <?php echo $progress; ?>%"></div>
            </div>
            <p style="margin-top: 8px; color: var(--text-light);"><?php echo $progress; ?>% completed</p>
        </div>

        <div class="table-container">
            <div class="toolbar">
                <h3 style="margin-right: auto;">Recent Tasks</h3>
                <a href="add-task.php" class="btn btn-primary">+ Add New Task</a>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Priority</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $pdo->query("SELECT * FROM tasks ORDER BY created_at DESC LIMIT 10");
                    $tasks = $stmt->fetchAll();
                    if (count($tasks) > 0) {
                        foreach ($tasks as $task) {
                            echo "<tr>";
                            echo "<td>#{$task['id']}</td>";
                            echo "<td>" . htmlspecialchars($task['title']) . "</td>";
                            echo "<td><span class='badge badge-{$task['priority']}'>{$task['priority']}</span></td>";
                            echo "<td>{$task['due_date']}</td>";
                            echo "<td><span class='badge badge-" . strtolower(str_replace(' ', '-', $task['status'])) . "'>{$task['status']}</span></td>";
                            echo "<td class='actions'>
                                    <a href='edit-task.php?id={$task['id']}' class='btn btn-warning btn-sm'>Edit</a>
                                    <a href='delete-task.php?id={$task['id']}' class='btn btn-danger btn-sm btn-confirm-delete'>Delete</a>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' class='empty-state'>No tasks yet. <a href='add-task.php' class='btn btn-primary btn-sm'>Create your first task</a></td></tr>";
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