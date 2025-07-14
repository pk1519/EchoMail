<?php
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['task-name'])) {
        addTask(trim($_POST['task-name']));
    }
    if (isset($_POST['email'])) {
        subscribeEmail(trim($_POST['email']));
    }
    if (isset($_POST['task-id']) && isset($_POST['completed'])) {
        markTaskAsCompleted($_POST['task-id'], $_POST['completed']);
    }
    if (isset($_POST['delete-task-id'])) {
        deleteTask($_POST['delete-task-id']);
    }
}

$tasks = getAllTasks();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Task Scheduler</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>📝 Task Manager</h2>
    <form method="POST">
        <input type="text" name="task-name" id="task-name" placeholder="Enter new task" required>
        <button type="submit" id="add-task">Add Task</button>
    </form>

    <ul class="tasks-list">
        <?php foreach ($tasks as $task): ?>
            <li class="task-item <?= $task['completed'] ? 'completed' : '' ?>">
                <div class="task-left">
                    <form method="POST">
                        <input type="checkbox" class="task-status" onchange="this.form.submit()" <?= $task['completed'] ? 'checked' : '' ?>>
                        <input type="hidden" name="task-id" value="<?= $task['id'] ?>">
                        <input type="hidden" name="completed" value="<?= $task['completed'] ? 0 : 1 ?>">
                    </form>
                    <?= htmlspecialchars($task['name']) ?>
                </div>
                <form method="POST">
                    <input type="hidden" name="delete-task-id" value="<?= $task['id'] ?>">
                    <button class="delete-task" type="submit">Delete</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>

    <h2>📧 Subscribe to Email Reminders</h2>
    <form method="POST">
        <input type="email" name="email" placeholder="Enter your email" required>
        <button id="submit-email" type="submit">Submit</button>
    </form>
</div>
</body>
</html>

