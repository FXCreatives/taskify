<?php
session_start();
include 'includes/db.php';
$activePage = 'dashboard';
$pageTitle = 'Dashboard';
include 'includes/header.php';
?>

<div class="page-header">
    <div>
        <h1 class="page-title">Dashboard</h1>
        <p class="page-subtitle">Track and manage your tasks efficiently</p>
    </div>
</div>

<?php
$total = $pdo->query("SELECT COUNT(*) FROM tasks")->fetchColumn();
$completed = $pdo->query("SELECT COUNT(*) FROM tasks WHERE status = 'Completed'")->fetchColumn();
$pending = $pdo->query("SELECT COUNT(*) FROM tasks WHERE status = 'Pending'")->fetchColumn();
$highPriority = $pdo->query("SELECT COUNT(*) FROM tasks WHERE priority = 'High' AND status != 'Completed'")->fetchColumn();
$progress = $total > 0 ? round(($completed / $total) * 100) : 0;
$inProgress = $pdo->query("SELECT COUNT(*) FROM tasks WHERE status = 'In Progress'")->fetchColumn();
?>

<div class="cards">
    <div class="stat-card primary">
        <div class="stat-card-top">
            <div>
                <div class="stat-card-label">Total Tasks</div>
                <div class="stat-card-value"><?php echo $total; ?></div>
            </div>
            <div class="stat-card-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
        </div>
    </div>
    <div class="stat-card success">
        <div class="stat-card-top">
            <div>
                <div class="stat-card-label">Completed</div>
                <div class="stat-card-value"><?php echo $completed; ?></div>
            </div>
            <div class="stat-card-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            </div>
        </div>
    </div>
    <div class="stat-card warning">
        <div class="stat-card-top">
            <div>
                <div class="stat-card-label">Pending</div>
                <div class="stat-card-value"><?php echo $pending; ?></div>
            </div>
            <div class="stat-card-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
    </div>
    <div class="stat-card danger">
        <div class="stat-card-top">
            <div>
                <div class="stat-card-label">High Priority</div>
                <div class="stat-card-value"><?php echo $highPriority; ?></div>
            </div>
            <div class="stat-card-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
            </div>
        </div>
    </div>
</div>

<div class="progress-section">
    <div class="progress-header">
        <div class="progress-title-group">
            <div>
                <div class="progress-title">Overall Progress</div>
                <div class="progress-subtitle"><?php echo $completed; ?> of <?php echo $total; ?> tasks completed</div>
            </div>
        </div>
        <div class="progress-percentage"><?php echo $progress; ?>%</div>
    </div>
    <div class="progress-bar">
        <div class="progress-fill" style="width: <?php echo $progress; ?>%"></div>
    </div>
    <div class="progress-stats">
        <div class="progress-stat">
            <span class="progress-stat-dot done"></span>
            <span class="progress-stat-label">Completed</span>
            <span class="progress-stat-value"><?php echo $completed; ?></span>
        </div>
        <div class="progress-stat">
            <span class="progress-stat-dot pending"></span>
            <span class="progress-stat-label">In Progress</span>
            <span class="progress-stat-value"><?php echo $inProgress; ?></span>
        </div>
        <div class="progress-stat">
            <span class="progress-stat-dot pending"></span>
            <span class="progress-stat-label">Pending</span>
            <span class="progress-stat-value"><?php echo $pending; ?></span>
        </div>
    </div>
</div>

<div class="panel">
    <div class="panel-header">
        <div class="panel-title">Recent Tasks <span class="count">Last 10</span></div>
        <div class="toolbar-actions">
            <a href="add-task.php" class="btn btn-primary btn-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Add New Task
            </a>
        </div>
    </div>
    <div class="table-wrap">
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
                            echo "<td class='cell-id' data-label='ID'>#{$task['id']}</td>";
                            echo "<td class='cell-title' data-label='Title'>" . htmlspecialchars($task['title']) . "</td>";
                            echo "<td data-label='Priority'><span class='badge badge-" . strtolower($task['priority']) . "'>{$task['priority']}</span></td>";
                            echo "<td class='cell-date' data-label='Due Date'>{$task['due_date']}</td>";
                            echo "<td data-label='Status'><span class='badge badge-" . strtolower(str_replace(' ', '-', $task['status'])) . "'>{$task['status']}</span></td>";
                            echo "<td class='actions' data-label='Actions'>
                                    <a href='edit-task.php?id={$task['id']}' class='btn btn-ghost btn-sm'>Edit</a>
                                    <a href='delete-task.php?id={$task['id']}' class='btn btn-danger btn-sm btn-confirm-delete'>Delete</a>
                                  </td>";
                            echo "</tr>";
                        }
                } else {
                    echo "<tr><td colspan='6' class='empty-state'>
                            <svg class='empty-state-icon' xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='currentColor' stroke-width='1.5'><path stroke-linecap='round' stroke-linejoin='round' d='M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'/></svg>
                            <div class='empty-state-title'>No tasks yet</div>
                            <p class='empty-state-text'>Create your first task to get started</p>
                            <a href='add-task.php' class='btn btn-primary'>Create your first task</a>
                          </td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
