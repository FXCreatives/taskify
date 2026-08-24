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

$activePage = 'edit';
$pageTitle = 'Edit Task';
include 'includes/header.php';
?>

<div class="page-header">
    <div>
        <h1 class="page-title">Edit Task</h1>
        <p class="page-subtitle">Update the work, its priority, and its deadline.</p>
    </div>
</div>

<?php if (!empty($error)) echo "<div class='alert alert-error'>$error</div>"; ?>
<?php if (!empty($success)) echo "<div class='alert alert-success'>$success</div>"; ?>

<div class="form-container">
    <form method="POST" action="">
        <div class="form-group">
            <label for="title">Task Title <span class="required">*</span></label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($task['title']); ?>" required>
            <p class="form-hint">Keep it short and action oriented.</p>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description"><?php echo htmlspecialchars($task['description']); ?></textarea>
            <p class="form-hint">Optional. Supports plain text.</p>
        </div>

        <div class="form-row">
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
        </div>

        <div class="form-group">
            <label for="due_date">Due Date <span class="required">*</span></label>
            <input type="date" id="due_date" name="due_date" value="<?php echo $task['due_date']; ?>" required>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="view-tasks.php" class="btn btn-ghost">Cancel</a>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
