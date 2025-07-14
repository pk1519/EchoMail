<?php

function getAllTasks() {
    return file_exists('tasks.txt') ? json_decode(file_get_contents('tasks.txt'), true) : [];
}

function saveTasks($tasks) {
    file_put_contents('tasks.txt', json_encode($tasks, JSON_PRETTY_PRINT));
}

function addTask($task_name) {
    $tasks = getAllTasks();
    foreach ($tasks as $task) {
        if (strcasecmp($task['name'], $task_name) === 0) return;
    }
    $tasks[] = ['id' => uniqid(), 'name' => $task_name, 'completed' => false];
    saveTasks($tasks);
}

function markTaskAsCompleted($task_id, $is_completed) {
    $tasks = getAllTasks();
    foreach ($tasks as &$task) {
        if ($task['id'] === $task_id) {
            $task['completed'] = $is_completed == 1 ? true : false;
        }
    }
    saveTasks($tasks);
}


function deleteTask($task_id) {
    $tasks = array_filter(getAllTasks(), fn($t) => $t['id'] !== $task_id);
    saveTasks(array_values($tasks));
}

function generateVerificationCode() {
    return str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
}

function subscribeEmail($email) {
    $pending = file_exists('pending_subscriptions.txt') ? json_decode(file_get_contents('pending_subscriptions.txt'), true) : [];
    $code = generateVerificationCode();
    $pending[$email] = ['code' => $code, 'timestamp' => time()];
    file_put_contents('pending_subscriptions.txt', json_encode($pending, JSON_PRETTY_PRINT));

    $link = "http://localhost:8000/src/verify.php?email=" . urlencode($email) . "&code=$code";
    $subject = "Verify subscription to Task Planner";
    $body = "<p>Click the link below to verify your subscription to Task Planner:</p><p><a id='verification-link' href='$link'>Verify Subscription</a></p>";
    $headers = "From: no-reply@example.com\r\nContent-type: text/html\r\n";

    mail($email, $subject, $body, $headers);
}

function verifySubscription($email, $code) {
    $pending = file_exists('pending_subscriptions.txt') ? json_decode(file_get_contents('pending_subscriptions.txt'), true) : [];
    if (!isset($pending[$email]) || $pending[$email]['code'] !== $code) return false;

    unset($pending[$email]);
    file_put_contents('pending_subscriptions.txt', json_encode($pending, JSON_PRETTY_PRINT));

    $subscribers = file_exists('subscribers.txt') ? json_decode(file_get_contents('subscribers.txt'), true) : [];
    if (!in_array($email, $subscribers)) {
        $subscribers[] = $email;
    }
    file_put_contents('subscribers.txt', json_encode($subscribers, JSON_PRETTY_PRINT));
    return true;
}

function unsubscribeEmail($email) {
    $subscribers = file_exists('subscribers.txt') ? json_decode(file_get_contents('subscribers.txt'), true) : [];
    $subscribers = array_filter($subscribers, fn($e) => $e !== $email);
    file_put_contents('subscribers.txt', json_encode(array_values($subscribers), JSON_PRETTY_PRINT));
}

function sendTaskReminders() {
    $subscribers = file_exists('subscribers.txt') ? json_decode(file_get_contents('subscribers.txt'), true) : [];
    $pendingTasks = array_filter(getAllTasks(), fn($task) => !$task['completed']);
    $taskNames = array_map(fn($task) => $task['name'], $pendingTasks);
    foreach ($subscribers as $email) {
        sendTaskEmail($email, $taskNames);
    }
}

function sendTaskEmail($email, $pending_tasks) {
    $list = "<ul>" . implode('', array_map(fn($t) => "<li>$t</li>", $pending_tasks)) . "</ul>";
    $unsubscribe = "http://localhost:8000/src/unsubscribe.php?email=" . urlencode($email);
    $body = "<h2>Pending Tasks Reminder</h2><p>Here are the current pending tasks:</p>$list<p><a id='unsubscribe-link' href='$unsubscribe'>Unsubscribe from notifications</a></p>";
    $subject = "Task Planner – Pending Tasks Reminder";
    $headers = "From: no-reply@example.com\r\nContent-type: text/html\r\n";

    mail($email, $subject, $body, $headers);
}

