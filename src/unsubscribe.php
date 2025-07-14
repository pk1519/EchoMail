<?php
$subscribersFile = 'subscribers.txt';

if (!isset($_GET['email'])) {
    exit("Invalid unsubscribe link.");
}

$email = base64_decode(urldecode($_GET['email']));

$subscribers = [];
if (file_exists($subscribersFile)) {
    $subscribers = json_decode(file_get_contents($subscribersFile), true) ?? [];
}

if (($key = array_search($email, $subscribers)) !== false) {
    unset($subscribers[$key]);
    $subscribers = array_values($subscribers);
    file_put_contents($subscribersFile, json_encode($subscribers, JSON_PRETTY_PRINT));
    echo "You have been unsubscribed.";
} else {
    echo "You are not subscribed.";
}
?>
