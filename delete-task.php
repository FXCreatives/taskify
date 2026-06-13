<?php
session_start();
include '../includes/db.php';

if (!isset($_GET['id'])) {
    header('Location: view-tasks.php');
    exit();
}

$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ?");
        $stmt->execute([$id]);
    } catch (PDOException $e) {
        die("Error deleting task: " . $e->getMessage());
    }
    header('Location: view-tasks.php');
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = ?");
$stmt->execute([$id]);
$task = $stmt->fetch();

if (!$task) {
    header('Location: view-tasks.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taskify - Delete Task</title>
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
        <h1 class="page-title">🗑️ Delete Task</h1>

        <div class="form-container" style="text-align: center;">
            <p style="margin-bottom: 10px; font-size: 1.1rem;">Are you sure you want to delete this task?</p>
            <h2 style="color: var(--danger); margin-bottom: 5px;"><?php echo htmlspecialchars($task['title']); ?></h2>
            <p style="color: var(--text-light); margin-bottom: 24px;">This action cannot be undone.</p>

            <form method="POST" action="">
                <div style="display: flex; gap: 10px; justify-content: center;">
                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                    <a href="view-tasks.php" class="btn" style="background-color: var(--bg); border: 1px solid var(--border);">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script src="js/script.js"></script>
</body>
</html>