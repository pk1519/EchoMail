<?php
require_once 'utils.php';

file_put_contents(__DIR__ . "/cron_debug.log", "[" . date("Y-m-d H:i:s") . "] CRON triggered\n", FILE_APPEND);

$subscribersFile = 'subscribers.txt';
$tasksFile = 'tasks.txt';

if (!file_exists($subscribersFile) || !file_exists($tasksFile)) {
    file_put_contents("cron_debug.log", "Missing required files.\n", FILE_APPEND);
    exit("Missing required files.\n");
}

file_put_contents("cron_debug.log", "Reading subscribers.txt...\n", FILE_APPEND);
$subscribers = json_decode(file_get_contents($subscribersFile), true) ?? [];
file_put_contents("cron_debug.log", "Loaded " . count($subscribers) . " subscribers\n", FILE_APPEND);

file_put_contents("cron_debug.log", "Reading tasks.txt...\n", FILE_APPEND);
$tasks = json_decode(file_get_contents($tasksFile), true);
$incompleteTasks = [];

foreach ($tasks as $task) {
    if (!$task['completed']) {
        $incompleteTasks[] = $task['name'];
    }
}
file_put_contents("cron_debug.log", "Found " . count($incompleteTasks) . " incomplete tasks\n", FILE_APPEND);

if (empty($incompleteTasks)) {
    $incompleteTasks[] = "[No tasks found]";
    file_put_contents("cron_debug.log", "No real tasks, sending placeholder.\n", FILE_APPEND);
}

foreach ($subscribers as $email) {
    file_put_contents("cron_debug.log", "Processing: $email\n", FILE_APPEND);

    $encoded = urlencode(base64_encode($email));
    $unsubscribeLink = "http://localhost:8000/unsubscribe.php?email=$encoded";

    $subject = "Task Reminder: You have pending tasks!";
    $message = "
        <html>
        <body>
            <h3>Pending Tasks:</h3>
            <ul>" . implode('', array_map(fn($t) => "<li>$t</li>", $incompleteTasks)) . "</ul>
            <p><a href='$unsubscribeLink'>Unsubscribe</a></p>
        </body>
        </html>
    ";

    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8\r\n";
    $headers .= "From: reminders@task-scheduler.com\r\n";

    $result = mail($email, $subject, $message, $headers);
    file_put_contents("cron_debug.log", ($result ? "True" : "False") . " Sent to $email\n", FILE_APPEND);
}
?>
