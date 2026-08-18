<?php
session_start();
include 'includes/db.php';

$error = '';
$success = '';

if (!isset($_GET['id'])) {
    header('Location: view-tasks.php');
    exit();
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = ?");
$stmt->execute([$id]);
$task = $stmt->fetch();

if (!$task) {
    header('Location: view-tasks.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $priority = $_POST['priority'];
    $status = $_POST['status'];
    $due_date = $_POST['due_date'];

    if (empty($title)) {
        $error = "Task title is required";
    } elseif (empty($due_date)) {
        $error = "Due date is required";
    } else {
        $stmt = $pdo->prepare("UPDATE tasks SET title = ?, description = ?, priority = ?, status = ?, due_date = ? WHERE id = ?");
        $stmt->execute([$title, $description, $priority, $status, $due_date, $id]);
        $success = "Task updated successfully!";
        $task = array_merge($task, $_POST);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taskify - Edit Task</title>
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
                <a href="view-tasks.php">View Tasks</a>
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
            <a href="view-tasks.php">View Tasks</a>
        </div>
    </header>

    <div class="container">
        <div class="page-header">
            <div>
                <h1 class="page-title">Edit Task</h1>
                <p class="page-subtitle">Update task details and status</p>
            </div>
        </div>

        <?php if (!empty($error)) echo "<div class='alert alert-error'>$error</div>"; ?>
        <?php if (!empty($success)) echo "<div class='alert alert-success'>$success</div>"; ?>

        <div class="form-container">
            <form method="POST" action="">
                <div class="form-group">
                    <label for="title">Task Title <span class="required">*</span></label>
                    <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($task['title']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description"><?php echo htmlspecialchars($task['description']); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="priority">Priority</label>
                    <select id="priority" name="priority">
                        <?php
                        foreach (['Low', 'Medium', 'High'] as $p) {
                            echo "<option value='$p'" . ($task['priority'] === $p ? ' selected' : '') . ">$p</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <?php
                        foreach (['Pending', 'In Progress', 'Completed'] as $s) {
                            echo "<option value='$s'" . ($task['status'] === $s ? ' selected' : '') . ">$s</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="due_date">Due Date <span class="required">*</span></label>
                    <input type="date" id="due_date" name="due_date" value="<?php echo $task['due_date']; ?>" required>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Update Task</button>
                    <a href="view-tasks.php" class="btn btn-ghost">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <div class="toast" id="toast"></div>
    <script src="js/script.js"></script>
</body>
</html>
