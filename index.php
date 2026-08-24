<?php
session_start();
include 'includes/db.php';
$activePage = 'dashboard';
$pageTitle = 'Dashboard';
include 'includes/header.php';
?>

<div class="page-header">
    <div>
        <h1 class="page-title"><?php
            $h = date('H');
            if ($h < 12) echo "Good morning, let's get things done.";
            elseif ($h < 18) echo "Good afternoon, let's get things done.";
            else echo "Good evening, let's get things done.";
        ?></h1>
        <p class="page-subtitle">Track your tasks, stay organized, and keep making progress.</p>
    </div>
    <div class="page-header-action">
        <a href="add-task.php" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            New Task
        </a>
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
    <div class="stat-card">
        <div class="stat-card-top">
            <div>
                <div class="stat-card-label">Total Tasks</div>
            </div>
            <div class="stat-card-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
        </div>
        <div class="stat-card-value"><?php echo $total; ?></div>
        <div class="stat-card-trend">Across your workspace</div>
    </div>
    <div class="stat-card success">
        <div class="stat-card-top">
            <div>
                <div class="stat-card-label">Completed</div>
            </div>
            <div class="stat-card-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            </div>
        </div>
        <div class="stat-card-value"><?php echo $completed; ?></div>
        <div class="stat-card-trend"><?php echo $progress; ?>% of all tasks</div>
    </div>
    <div class="stat-card warning">
        <div class="stat-card-top">
            <div>
                <div class="stat-card-label">Pending</div>
            </div>
            <div class="stat-card-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
        <div class="stat-card-value"><?php echo $pending; ?></div>
        <div class="stat-card-trend"><?php echo $inProgress; ?> in progress</div>
    </div>
    <div class="stat-card danger">
        <div class="stat-card-top">
            <div>
                <div class="stat-card-label">High Priority</div>
            </div>
            <div class="stat-card-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
            </div>
        </div>
        <div class="stat-card-value"><?php echo $highPriority; ?></div>
        <div class="stat-card-trend">Open and urgent</div>
    </div>
</div>

<div class="progress-section">
    <div class="progress-header">
        <div class="progress-title-group">
            <div>
                <div class="progress-title">Overall Progress</div>
                <div class="progress-subtitle">Completion across all tasks</div>
            </div>
        </div>
        <div class="progress-percentage"><?php echo $progress; ?>%</div>
    </div>
    <div class="progress-bar">
        <div class="progress-fill" style="width: <?php echo $progress; ?>%"></div>
    </div>
    <div class="progress-stats">
        <div class="progress-stat">
            <span class="progress-stat-dot" style="background:var(--success);"></span>
            <span class="progress-stat-label">Completed</span>
            <span class="progress-stat-value"><?php echo $completed; ?></span>
        </div>
        <div class="progress-stat">
            <span class="progress-stat-dot" style="background:var(--primary);"></span>
            <span class="progress-stat-label">In Progress</span>
            <span class="progress-stat-value"><?php echo $inProgress; ?></span>
        </div>
        <div class="progress-stat">
            <span class="progress-stat-dot" style="background:var(--warning);"></span>
            <span class="progress-stat-label">Pending</span>
            <span class="progress-stat-value"><?php echo $pending; ?></span>
        </div>
    </div>
</div>

<div class="panel">
    <div class="panel-header">
        <div class="panel-title-group">
            <div class="panel-title">Recent Tasks</div>
            <div class="panel-subtitle">Your five most recently created tasks</div>
        </div>
        <div class="panel-action">
            <a href="view-tasks.php" class="panel-link">View All Tasks
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Task</th>
                    <th>Priority</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $pdo->query("SELECT * FROM tasks ORDER BY created_at DESC LIMIT 5");
                $tasks = $stmt->fetchAll();
                    if (count($tasks) > 0) {
                        foreach ($tasks as $task) {
                            echo "<tr>";
                            echo "<td class='cell-title' data-label='Task'>" . htmlspecialchars($task['title']) . "</td>";
                            echo "<td data-label='Priority'><span class='badge badge-" . strtolower($task['priority']) . "'>{$task['priority']}</span></td>";
                            echo "<td class='cell-date' data-label='Due Date'>{$task['due_date']}</td>";
                            echo "<td data-label='Status'><span class='badge badge-" . strtolower(str_replace(' ', '-', $task['status'])) . "'>{$task['status']}</span></td>";
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
                    echo "<tr><td colspan='5' class='empty-state'>
                            <div class='empty-state-icon-container'>
                                <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' fill='none' viewBox='0 0 24 24' stroke='currentColor' stroke-width='1.5'><path stroke-linecap='round' stroke-linejoin='round' d='M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'/></svg>
                            </div>
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
