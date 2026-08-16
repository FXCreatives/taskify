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
            <div class="logo">⚡ Taskify</div>
            <nav>
                <a href="index.php">Dashboard</a>
                <a href="add-task.php">Add Task</a>
                <a href="view-tasks.php">View Tasks</a>
            </nav>
        </div>
    </header>

    <div class="container">
        <h1 class="page-title">✏️ Edit Task</h1>

        <?php if (!empty($error)) echo "<div class='alert alert-error'>$error</div>"; ?>
        <?php if (!empty($success)) echo "<div class='alert alert-success'>$success</div>"; ?>

        <div class="form-container">
            <form method="POST" action="">
                <div class="form-group">
                    <label for="title">Task Title *</label>
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
                    <label for="due_date">Due Date *</label>
                    <input type="date" id="due_date" name="due_date" value="<?php echo $task['due_date']; ?>" required>
                </div>

                <div style="display: flex; gap: 10px;">
                    <button type="submit" class="btn btn-primary">Update Task</button>
                    <a href="view-tasks.php" class="btn btn-danger">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <div class="toast" id="toast"></div>
    <script src="js/script.js"></script>
</body>
</html>