<?php
session_start();
include 'includes/db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $priority = $_POST['priority'];
    $due_date = $_POST['due_date'];

    if (empty($title)) {
        $error = "Task title is required";
    } elseif (empty($due_date)) {
        $error = "Due date is required";
    } else {
        $stmt = $pdo->prepare("INSERT INTO tasks (title, description, priority, due_date) VALUES (?, ?, ?, ?)");
        $stmt->execute([$title, $description, $priority, $due_date]);
        $success = "Task added successfully!";
    }
}

$activePage = 'add';
$pageTitle = 'Add Task';
include 'includes/header.php';
?>

<div class="page-header">
    <div>
        <h1 class="page-title">Add New Task</h1>
        <p class="page-subtitle">Create a new task to track</p>
    </div>
</div>

<?php if (!empty($error)) echo "<div class='alert alert-error'>$error</div>"; ?>
<?php if (!empty($success)) echo "<div class='alert alert-success'>$success</div>"; ?>

<div class="form-container">
    <form method="POST" action="">
        <div class="form-group">
            <label for="title">Task Title <span class="required">*</span></label>
            <input type="text" id="title" name="title" placeholder="e.g. Prepare quarterly report" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" placeholder="Add details about this task (optional)"></textarea>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="priority">Priority</label>
                <select id="priority" name="priority">
                    <option value="Low">Low</option>
                    <option value="Medium" selected>Medium</option>
                    <option value="High">High</option>
                </select>
            </div>

            <div class="form-group">
                <label for="due_date">Due Date <span class="required">*</span></label>
                <input type="date" id="due_date" name="due_date" required>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save Task</button>
            <a href="view-tasks.php" class="btn btn-ghost">Cancel</a>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
