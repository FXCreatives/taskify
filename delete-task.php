<?php
session_start();
include 'includes/db.php';

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
                <h1 class="page-title">Delete Task</h1>
                <p class="page-subtitle">This action cannot be undone</p>
            </div>
        </div>

        <div class="form-container delete-card">
            <div class="delete-card-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </div>
            <h2><?php echo htmlspecialchars($task['title']); ?></h2>
            <p>Are you sure you want to delete this task? This action cannot be undone.</p>

            <form method="POST" action="">
                <div class="form-actions">
                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                    <a href="view-tasks.php" class="btn btn-ghost">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script src="js/script.js"></script>
</body>
</html>
