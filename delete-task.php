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

$activePage = 'view';
$pageTitle = 'Delete Task';
include 'includes/header.php';
?>

<div class="page-header">
    <div>
        <h1 class="page-title">Delete Task</h1>
        <p class="page-subtitle">This action cannot be undone</p>
    </div>
</div>

<div class="form-container delete-card">
    <div class="delete-card-icon empty-state-icon-container">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
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

<?php include 'includes/footer.php'; ?>
