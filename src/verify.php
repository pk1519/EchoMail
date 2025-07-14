<?php
$pendingFile = 'pending_subscriptions.txt';
$subscribersFile = 'subscribers.txt';

if (!isset($_GET['email']) || !isset($_GET['code'])) {
    exit("Invalid verification link.");
}

$email = base64_decode(urldecode($_GET['email']));
$code = $_GET['code'];

$pending = file($pendingFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$newPending = [];
$found = false;

foreach ($pending as $line) {
    list($e, $c) = explode('|', $line);
    if ($e === $email && $c === $code) {
        $found = true;
    } else {
        $newPending[] = $line;
    }
}
file_put_contents($pendingFile, implode("\n", $newPending));

if ($found) {
    $subscribers = [];
    if (file_exists($subscribersFile)) {
        $subscribers = json_decode(file_get_contents($subscribersFile), true) ?? [];
    }

    if (!in_array($email, $subscribers)) {
        $subscribers[] = $email;
        file_put_contents($subscribersFile, json_encode($subscribers, JSON_PRETTY_PRINT));
    }

    echo "Subscription successfully verified!";
} else {
    echo "Verification failed.";
}
?>
