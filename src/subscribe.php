<?php
require_once 'utils.php';

$pendingFile = 'pending_subscriptions.txt';
$subscribersFile = 'subscribers.txt';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = trim($_POST['email']);

    // Load existing subscribers
    $subscribers = [];
    if (file_exists($subscribersFile)) {
        $subscribers = json_decode(file_get_contents($subscribersFile), true) ?? [];
    }

    if (in_array($email, $subscribers)) {
        echo "You are already subscribed.";
        exit;
    }

    $code = rand(100000, 999999);
    file_put_contents($pendingFile, "$email|$code\n", FILE_APPEND);

    if (send_verification_email($email, $code)) {
        echo "Verification email sent to $email. Please check your inbox.";
    } else {
        echo "Failed to send verification email.";
    }

    exit;
}
?>

<!DOCTYPE html>
<html>
<head><title>Subscribe</title></head>
<body>
    <h2>Email Subscription</h2>
    <form method="POST" action="subscribe.php">
        <label for="email">Enter your email:</label>
        <input type="email" name="email" id="email" required>
        <button type="submit" id="subscribe-button">Subscribe</button>
    </form>
</body>
</html>
